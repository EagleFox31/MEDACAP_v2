<?php
// watcher_oplog.php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Logger.php';

use MongoDB\Client;
use MongoDB\Driver\Cursor;
use MongoDB\BSON\Timestamp;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

// 1) Connexion au replica-set et récupération des collections
Logger::info("Démarrage du watcher_oplog");
$client  = new Client('mongodb://10.68.0.7:27017,localhost:27017/?replicaSet=rs0');
$local   = $client->selectDatabase('local');
$oplog   = $local->selectCollection('oplog.rs');
$academy_test = $client->selectDatabase('academy_test');
$tp      = $academy_test->testProgress;
$tbs     = $academy_test->technicianBrandScore;

// 2) Se positionner juste après la dernière entrée de l’oplog
$lastEntry = $oplog->find([], [
    'sort'  => ['$natural' => -1],
    'limit' => 1,
])->toArray()[0] ?? null;

$ts = $lastEntry
    ? $lastEntry['ts']
    : new Timestamp(time(), 1);

// 3) Filtre pour ne capter que nos deux collections
$filter = [
    'ns' => ['$in' => ['academy_test.allocations', 'academy_test.results']],
    'ts' => ['$gt' => $ts],
];

// 4) Cursor tailable
$options = [
    'cursorType' => Cursor::TAILABLE_AWAIT,
    'awaitData'  => true,
    'batchSize'  => 1,
];

$cursor = $oplog->find($filter, $options);
Logger::info("Lecture de l’oplog démarrée");

// 5) Boucle infinie sur l’oplog
foreach ($cursor as $entry) {
    // mise à jour du pointeur
    $ts = $entry['ts'];

    // ne traiter que les insertions / updates
    if (!in_array($entry['op'], ['i','u'], true)) {
        continue;
    }

    // full document dans $o pour 'i', ou modifications partielles dans 'o'/'o2' pour 'u'
    $doc     = $entry['op'] === 'i' ? (array)$entry['o'] : (array)$entry['o2'];
    $updates = $entry['op'] === 'u' && isset($entry['o']['$set'])
             ? (array)$entry['o']['$set']
             : [];

    $namespace = $entry['ns']; // e.g. "academy_test.results" ou "academy_test.allocations"
    handleChange($doc, $updates, $namespace, $tp, $tbs, $academy_test);
}

/**
 * Reprend votre logique de phases.
 */
function handleChange(array $doc, array $updates, string $ns, $tp, $tbs, $academy_testDB): void
{
    $now     = new UTCDateTime();
    $userId  = $doc['user']   ?? $doc['userId'] ?? null;
    $testType= $doc['type']   ?? null;

    if (!$userId || !$testType) {
        Logger::debug("Ignoré (pas de user/type) dans l’événement Oplog");
        return;
    }

    $base = [
        'userId'   => new ObjectId($userId),
        'testType' => $testType,
        'date'     => $now,
        'details'  => new \stdClass(),
    ];

    // Phase 1 : factuel terminé
    if (isset($updates['active']) && $updates['active'] === false
        && $testType === 'Factuel'
    ) {
        Logger::info("→ factuel.done pour {$userId}");
        $tp->insertOne($base + ['phase'=>'factuel.done']);
        updatePhase($tbs, $academy_testDB, $userId, 'factuel');
    }

    // Phase 2 : déclaratif tech terminé
    if (isset($updates['activeManager']) && $updates['activeManager'] === true
        && $testType === 'Declaratif'
    ) {
        Logger::info("→ declaratif.done pour {$userId}");
        $tp->insertOne($base + ['phase'=>'declaratif.done']);
        updatePhase($tbs, $academy_testDB, $userId, 'declaratif');
    }

    // Phase 3 : validation manager
    if (
        (isset($updates['validated'])     && $updates['validated'] === true) ||
        (isset($updates['activeManager']) && $updates['activeManager'] === false)
    ) {
        if ($testType === 'Declaratif') {
            Logger::info("→ validation.done pour {$userId}");
            $tp->insertOne($base + ['phase'=>'validation.done']);
            updatePhase($tbs, $academy_testDB, $userId, 'validation');
        }
    }
}

/**
 * Met à jour les sous-documents d’une phase dans technicianBrandScore
 */
function updatePhase($tbs, $db, string $userId, string $phaseName): void
{
    $now = new UTCDateTime();
    $matchTypeR = [
        'factuel'    => 'Technicien',
        'declaratif' => 'Technicien – Manager',
        'validation' => 'Technicien – Manager',
    ][$phaseName] ?? null;

    if (!$matchTypeR) {
        Logger::error("Phase inconnue : {$phaseName}");
        return;
    }

    Logger::debug("Aggregation {$phaseName} pour user {$userId}");

    $pipeline = [
        ['$match' => [
            'user'      => new ObjectId($userId),
            'typeR'     => $matchTypeR,
            'validated' => $phaseName==='validation' ? true : ['$exists'=>false],
        ]],
        ['$group' => [
            '_id'   => '$brand',
            'score' => ['$sum'=>'$score'],
            'total' => ['$sum'=>'$total'],
        ]],
    ];

    $agg = $db->results->aggregate($pipeline);
    $brands = [];
    foreach ($agg as $r) {
        $brands[] = ['brand'=>$r->_id,'score'=>$r->score,'total'=>$r->total];
    }

    $tbs->updateOne(
        ['userId'=>new ObjectId($userId)],
        ['$set'=>[
            "$phaseName.done"   => true,
            "$phaseName.date"   => $now,
            "$phaseName.brands" => $brands,
            'updatedAt'         => $now,
        ]],
        ['upsert'=>true]
    );

    Logger::info("⇒ {$phaseName} enregistré pour {$userId}");
}
