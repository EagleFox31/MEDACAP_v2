<?php
// create_technician_scores.php

// 1) Chargement de l’autoloader et du Logger
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Logger.php';

use MongoDB\Client;

// 2) Script principal
Logger::info("=== Lancement du batch create_technician_scores ===");

try {
    // Connexion à MongoDB
    $mongoUri     = "mongodb://localhost:27017";
    $databaseName = "academy_test";
    $client       = new Client($mongoUri);
    $academy      = $client->selectDatabase($databaseName);
    Logger::info("Connexion à MongoDB réussie ({$mongoUri}/{$databaseName})");

    // Instanciation de ScoreCalculator
    require __DIR__ . '/ScoreFunctions.php';
    $scoreCalculator = new ScoreCalculator($academy);
    Logger::info("Instanciation de ScoreCalculator réussie");

    // Récupération automatique de tous les techniciens
    require __DIR__ . '/technicianFunctions.php';
    $profile     = 'Technicien';
    $technicians = getAlltechnicians($academy, $profile);
    $countTech   = count($technicians);
    Logger::info("Techniciens récupérés: {$countTech}");

    // Construction automatique du mapping Tech => Manager
    $technicianManagerMap = [];
    foreach ($technicians as $tech) {
        $techId = (string)$tech['_id'];
        if (!empty($tech['manager'])) {
            $managerId = (string)$tech['manager'];
            $technicianManagerMap[$techId] = $managerId;
        }
    }
    Logger::debug("Mapping Technicien→Manager: " . json_encode($technicianManagerMap, JSON_UNESCAPED_UNICODE));

    // Définir les niveaux et spécialités
    $levels       = ["Junior", "Senior", "Expert"];
    $specialities = $scoreCalculator->getAllSpecialities();
    Logger::info("Spécialités récupérées: " . json_encode($specialities));

    // Calculer tous les scores (Factuel + Déclaratif)
    $debug   = [];
    $allScores = $scoreCalculator->getAllScoresForTechnicians(
        $academy,
        $technicianManagerMap,
        $levels,
        $specialities,
        $debug
    );
    $calculatedCount = count($allScores);
    Logger::info("Scores calculés pour {$calculatedCount} technicien(s)");

    // Afficher les logs de débogage détaillés
    foreach ($debug as $line) {
        Logger::debug($line);
    }

    // Sauvegarder les scores dans technicianScores
    $scoreCalculator->saveScores($academy, $allScores);
    Logger::info("Scores sauvegardés dans la collection technicianScores");

    Logger::info("=== Fin du batch create_technician_scores ===\n");
    echo "Batch terminé avec succès.\n";

} catch (\Throwable $e) {
    Logger::error("Erreur dans create_technician_scores: " . $e->getMessage());
    echo "Erreur : consultez les logs pour plus de détails.\n";
}
