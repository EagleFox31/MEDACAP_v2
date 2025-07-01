<?php
/**
 * ---------------------------------------------------------------------------
 *  stats_tests.php  –  Avancement des QCM (Factuel + Déclaratif validé manager)
 * ---------------------------------------------------------------------------
 *  Retourne un tableau :
 *
 *      [
 *          'Junior' => ['done' => 18, 'total' => 27],
 *          'Senior' => ['done' => 11, 'total' => 23],
 *          'Expert' => ['done' =>  4, 'total' => 12],
 *          'Total'  => ['done' => 33, 'total' => 62], // ⇠ personnes UNIQUES
 *      ]
 * ---------------------------------------------------------------------------
 */

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;

require_once __DIR__ . '/../../../vendor/autoload.php';

/* ════════════════════ 1. Connexion DB ═════════════════════════════════════ */
$client   = new Client('mongodb://localhost:27017');
$academy  = $client->academy;
$usersCol = $academy->users;
$allocCol = $academy->allocations;

/* ════════════════════ 2. Filtres venant du contrôleur ═════════════════════ */
$filters     = $GLOBALS['filtersForStats'] ?? [];
$wantedLevel = $filters['level'] ?? 'all';   // 'Junior' | 'Senior' | 'Expert' | 'all'

/* ════════════════════ 3. Outils ═══════════════════════════════════════════ */
function getTechnicians(Collection $users, array $f): array
{
    $q = [
        'active' => true,
        '$or'    => [
            ['profile' => 'Technicien'],
            ['profile' => 'Manager', 'test' => true],
        ],
    ];

    foreach (['subsidiary', 'agency'] as $k) {
        if (($f[$k] ?? 'all') !== 'all') $q[$k] = $f[$k];
    }
    if (($f['managerId'] ?? 'all')    !== 'all') $q['manager'] = $f['managerId'];
    if (($f['technicianId'] ?? 'all') !== 'all') $q['_id']     = new ObjectId($f['technicianId']);

    /* Niveau hiérarchique */
    if (($f['level'] ?? 'all') !== 'all') {
        switch ($f['level']) {
            case 'Junior':
                $q['level'] = ['$in' => ['Junior', 'Senior', 'Expert']];
                break;
            case 'Senior':
                $q['level'] = ['$in' => ['Senior', 'Expert']];
                break;
            case 'Expert':
                $q['level'] = 'Expert';
                break;
        }
    }

    /* Marque */
    if (($f['brand'] ?? 'all') !== 'all') {
        $brand    = $f['brand'];
        $q['$or'] = [
            ['brandJunior' => $brand],
            ['brandSenior' => $brand],
            ['brandExpert' => $brand],
        ];
    }

    /* On récupère aussi le niveau pour éviter une requête plus loin */
    $ids = [];
    foreach ($users->find($q, ['projection' => ['_id' => 1, 'level' => 1]]) as $doc) {
        $ids[(string) $doc->_id] = $doc['level'] ?? 'Junior';
    }
    return $ids; // [ '656a…' => 'Senior', … ]
}

function getAllocation(
    Collection $c,
    ObjectId $uId,
    string $level,
    string $type,
    bool $needMgr = false
): ?array {
    $q = ['user' => $uId, 'level' => $level, 'type' => $type];
    if ($needMgr) $q['activeManager'] = true;

    $doc = $c->findOne($q);
    return $doc ? $doc->getArrayCopy() : null;
}

function hasBothAlloc(Collection $c, ObjectId $uId, string $lvl): bool
{
    return getAllocation($c, $uId, $lvl, 'Factuel')    !== null
        && getAllocation($c, $uId, $lvl, 'Declaratif') !== null;
}

function hasBothActive(Collection $c, ObjectId $uId, string $lvl): bool
{
    $fact = getAllocation($c, $uId, $lvl, 'Factuel');
    $decl = getAllocation($c, $uId, $lvl, 'Declaratif', true);

    return $fact && $decl &&
           ($fact['active']        ?? false) &&
           ($decl['active']        ?? false) &&
           ($decl['activeManager'] ?? false);
}

/* ════════════════════ 4. Population concernée ═════════════════════════════ */
$techLevels = getTechnicians($usersCol, $filters); // id => level

/* ════════════════════ 5. Calcul par niveau ════════════════════════════════ */
$levels = ['Junior', 'Senior', 'Expert'];

/* --- Pré-initialisation pour éviter les “Undefined index” ----------------- */
$statsTests = [
    'Junior' => ['done' => 0, 'total' => 0],
    'Senior' => ['done' => 0, 'total' => 0],
    'Expert' => ['done' => 0, 'total' => 0],
];
/* ------------------------------------------------------------------------- */

$doneSet  = []; // personnes uniques ayant terminé
$totalSet = []; // personnes uniques devant passer

foreach ($levels as $lvl) {
    /* Si un filtre de niveau est appliqué, on peut ignorer les autres niveaux.
       Les valeurs pré-initialisées resteront donc à 0. */
    if ($wantedLevel !== 'all' && $lvl !== $wantedLevel) {
        continue;
    }

    $done = $total = 0;

    foreach ($techLevels as $id => $userLevel) {
        if ($userLevel !== $lvl) continue; // ne compte que son vrai niveau

        $oid = new ObjectId($id);

        if (hasBothAlloc($allocCol, $oid, $lvl)) {
            $total++;
            $totalSet[$id] = true;

            if (hasBothActive($allocCol, $oid, $lvl)) {
                $done++;
                $doneSet[$id] = true;
            }
        }
    }

    /* Mise à jour du tableau pré-initialisé */
    $statsTests[$lvl]['done']  = $done;
    $statsTests[$lvl]['total'] = $total;
}

/* -------------------- COLONNE TOTAL (personnes uniques) ------------------ */
$statsTests['Total'] = [
    'done'  => count($doneSet),
    'total' => count($totalSet),
];

/* ════════════════════ 6. Retour ═══════════════════════════════════════════ */
return $statsTests;

/* ════════════════════ 7. Fin du script ═══════════════════════════════════ */
?>
