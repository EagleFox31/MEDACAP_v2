<?php
// create_technician_brand_scores.php

// 1) Chargement de l’autoloader et du Logger
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Logger.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

// 2) Démarrage du batch
Logger::info("=== Lancement du batch create_technician_brand_scores ===");

try {
    // 3) Connexion
    $client = new Client('mongodb://127.0.0.1:27017');
    $db     = $client->selectDatabase('academy_test');
    Logger::info("Connexion MongoDB réussie (127.0.0.1:27017/academy)");

    // 4) Collections
    $usersCol            = $db->users;
    $technicianScoresCol = $db->technicianScores;
    $nonSupportedCol     = $db->nonSupportedGroupsByBrandLevel;
    $brandScoresCol      = $db->technicianBrandScores;

    // 5) Helpers
    function getBrandsByLevel(array $user): array {
        return [
            'Junior' => $user['brandJunior'] ?? [],
            'Senior' => $user['brandSenior'] ?? [],
            'Expert' => $user['brandExpert'] ?? [],
        ];
    }

    function getSupportedGroups($nonSupportedCol, string $brand, string $level): array {
        $brandDoc = $nonSupportedCol->findOne(['brand' => $brand]);
        if (!$brandDoc || !isset($brandDoc['levels']) || !is_array($brandDoc['levels'])) {
            return [];
        }
        foreach ($brandDoc['levels'] as $lvl) {
            if (($lvl['level'] ?? '') === $level) {
                return $lvl['supportedGroups'] ?? [];
            }
        }
        return [];
    }

    // 6) Récupération des techniciens
    $cursor = $usersCol->find(['profile' => 'Technicien']);
    $count  = $cursor->count();
    Logger::info("Techniciens récupérés: {$count}");

    // 7) Parcours des techniciens
    foreach ($cursor as $user) {
        $userId       = $user['_id'];
        $firstName    = $user['firstName'] ?? '';
        $lastName     = $user['lastName']  ?? '';
        $levelsBrands = getBrandsByLevel($user);

        $scores = [];

        foreach ($levelsBrands as $level => $brands) {
            if (empty($brands)) {
                continue;
            }
            $scores[$level] = [];

            foreach ($brands as $brand) {
                // 7.1) Récupérer les groupes supportés
                $supported = getSupportedGroups($nonSupportedCol, $brand, $level);
                if (empty($supported)) {
                    continue;
                }

                // 7.2) Récupérer technicianScores
                $techScoreDoc = $technicianScoresCol->findOne(['userId' => $userId]);
                if (!$techScoreDoc
                    || !isset($techScoreDoc['levels'][$level]['specialities'])
                    || !is_array($techScoreDoc['levels'][$level]['specialities'])
                ) {
                    continue;
                }
                $levelSpecs = $techScoreDoc['levels'][$level]['specialities'];

                // 7.3) Calcul des moyennes
                $factuelSum    = 0; $factuelCount    = 0;
                $declaratifSum = 0; $declaratifCount = 0;

                foreach ($supported as $spec) {
                    if (!isset($levelSpecs[$spec])) {
                        continue;
                    }
                    $data = $levelSpecs[$spec];
                    if (isset($data['factuel']) && is_numeric($data['factuel'])) {
                        $factuelSum    += $data['factuel'];
                        $factuelCount++;
                    }
                    if (isset($data['declaratif']) && is_numeric($data['declaratif'])) {
                        $declaratifSum    += $data['declaratif'];
                        $declaratifCount++;
                    }
                }

                $avgFactuel    = $factuelCount    ? $factuelSum    / $factuelCount    : 0;
                $avgDeclaratif = $declaratifCount ? $declaratifSum / $declaratifCount : 0;
                $avgTotal      = ($avgFactuel + $avgDeclaratif) / 2;

                $scores[$level][$brand] = [
                    'averageFactuel'    => round($avgFactuel,    2),
                    'averageDeclaratif' => round($avgDeclaratif,2),
                    'averageTotal'      => round($avgTotal,     2),
                ];
            }
        }

        Logger::debug("Scores pour {$firstName} {$lastName} : " . json_encode($scores, JSON_UNESCAPED_UNICODE));

        // 8) Upsert dans technicianBrandScores
        $doc = [
            'userId'    => $userId,
            'firstName' => $firstName,
            'lastName'  => $lastName,
            'scores'    => $scores,
            'updatedAt' => new UTCDateTime(),
        ];

        $brandScoresCol->updateOne(
            ['userId' => $userId],
            ['$set' => $doc],
            ['upsert' => true]
        );

        Logger::info("Upsert technicianBrandScores pour {$firstName} {$lastName}");
        echo "✅ Scores calculés pour {$firstName} {$lastName}\n";
    }

    Logger::info("=== Fin du batch create_technician_brand_scores ===");
    echo "Batch terminé avec succès.\n";

} catch (\Throwable $e) {
    Logger::error("Erreur dans create_technician_brand_scores: " . $e->getMessage());
    echo "Erreur : consultez les logs pour plus de détails.\n";
}
