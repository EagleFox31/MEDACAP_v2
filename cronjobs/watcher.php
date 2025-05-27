<?php
// watcher.php

// 1) Chargement de l’autoloader Composer
require __DIR__ . '/../vendor/autoload.php';

// 2) Chargement du logger
require __DIR__ . '/Logger.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

Logger::info("Initialisation du watcher");

try {
    // 3) Connexion MongoDB on-premise (replicaSet rs0, nœud unique)
    $client  = new Client('mongodb://10.68.0.7:27017,localhost:27017/?replicaSet=rs0');
    $db      = $client->selectDatabase('academy_test');
    $tp      = $db->testProgress;
    $tbs     = $db->technicianBrandScore;
    Logger::info("Connexion MongoDB établie sur academy_test");
} catch (\Throwable $e) {
    Logger::error("Erreur de connexion MongoDB : " . $e->getMessage());
    exit(1);
}

// 4) Ouvre un change stream sur toute la base
try {
    $changeStream = $db->watch(
        [], 
        ['fullDocument' => 'updateLookup']
    );
    Logger::info("Change stream sur la base ouvert");
} catch (\Throwable $e) {
    Logger::error("Impossible d'ouvrir le change stream : " . $e->getMessage());
    exit(1);
}

// 5) Boucle d’écoute
foreach ($changeStream as $change) {
    // On ne traite que allocations et results
    $coll = $change->ns['coll'] ?? '';
    if (!in_array($coll, ['allocations','results'], true)) {
        continue;
    }

    Logger::debug("ChangeEvent sur {$coll}: " . json_encode($change->toArray()));

    try {
        handleChange($change, $db, $tp, $tbs);
    } catch (\Throwable $e) {
        Logger::error("Exception dans handleChange : " . $e->getMessage());
    }
}

/**
 * Traite un changeEvent MongoDB pour testProgress & technicianBrandScore.
 */
function handleChange($change, $db, $tp, $tbs): void
{
    $doc     = (array)($change->fullDocument   ?? []);
    $updates = (array)($change->updateDescription->updatedFields ?? []);
    $now     = new UTCDateTime();

    $userId   = $doc['user']   ?? $doc['userId'] ?? null;
    $testType = $doc['type']   ?? null;

    if (!$userId || !$testType) {
        Logger::debug("Ignoré : pas de userId ou de testType");
        return;
    }

    $base = [
        'userId'   => $userId,
        'testType' => $testType,
        'date'     => $now,
        'details'  => new stdClass(),
    ];

    // Phase 1 : Factuel terminé
    if (isset($updates['active']) && $updates['active'] === false
        && $testType === 'Factuel'
    ) {
        Logger::info("factuel.done pour user {$userId}");
        $tp->insertOne(array_merge($base, ['phase'=>'factuel.done']));
        updatePhase($tbs, $db, $userId, 'factuel');
    }

    // Phase 2 : Déclaratif (tech) terminé
    if (isset($updates['activeManager']) && $updates['activeManager'] === true
        && $testType === 'Declaratif'
    ) {
        Logger::info("declaratif.done pour user {$userId}");
        $tp->insertOne(array_merge($base, ['phase'=>'declaratif.done']));
        updatePhase($tbs, $db, $userId, 'declaratif');
    }

    // Phase 3 : Validation manager terminé
    if (
        (isset($updates['validated'])     && $updates['validated']===true) ||
        (isset($updates['activeManager']) && $updates['activeManager']===false)
    ) {
        if ($testType==='Declaratif') {
            Logger::info("validation.done pour user {$userId}");
            $tp->insertOne(array_merge($base, ['phase'=>'validation.done']));
            updatePhase($tbs, $db, $userId, 'validation');
        }
    }
}

/**
 * Met à jour la phase dans technicianBrandScore.
 */
function updatePhase($tbs, $db, $userId, string $phaseName): void
{
    $now = new UTCDateTime();

    $matchTypeR = [
        'factuel'    => 'Technicien',
        'declaratif' => 'Technicien – Manager',
        'validation' => 'Technicien – Manager',
    ][$phaseName] ?? null;

    if (!$matchTypeR) {
        Logger::error("Phase inconnue passée à updatePhase : {$phaseName}");
        return;
    }
    Logger::debug("updatePhase start user={$userId}, phase={$phaseName}, typeR={$matchTypeR}");

    $pipeline = [
        ['$match'=>[
            'user'      => new ObjectId($userId),
            'typeR'     => $matchTypeR,
            'validated' => $phaseName==='validation' ? true : ['$exists'=>false],
        ]],
        ['$group'=>[
            '_id'=>'$brand',
            'score'=>['$sum'=>'$score'],
            'total'=>['$sum'=>'$total'],
        ]]
    ];

    try {
        $agg = $db->results->aggregate($pipeline);
    } catch (\Throwable $e) {
        Logger::error("Aggregation failed for {$phaseName}/{$userId}: ".$e->getMessage());
        return;
    }

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

    Logger::info("updatePhase complete {$phaseName} pour user {$userId}");
}
