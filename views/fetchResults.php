<?php
require_once "../vendor/autoload.php"; // Charger les dépendances nécessaires

function getValidatedResults() {
    // Connexion à la base de données MongoDB
    try {
        $conn = new MongoDB\Client("mongodb://localhost:27017");
    } catch (Exception $e) {
        die(json_encode(["error" => "Erreur de connexion à MongoDB: " . $e->getMessage()]));
    }

    $academy = $conn->academy;

    // Connexion aux collections
    $results = $academy->results;
    $questions = $academy->questions;

    // Code pour récupérer les résultats, techniciens et questions
    // Convertir le résultat final en JSON
    $tableauResultats = []; // Remplir ce tableau avec les données finales

    // Par exemple :
    // $tableauResultats["technicianId"]["questionId"] = "Maîtrisé" or "Non maîtrisé";

    echo json_encode($tableauResultats);
}

getValidatedResults();
?>
