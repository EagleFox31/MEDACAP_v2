<?php
require_once "../vendor/autoload.php"; // Charger les dépendances nécessaires

function getValidatedResultsOld($level = "Junior") {
    // Définir le nom du fichier de cache basé sur le niveau
    $cacheFile = __DIR__ . "/../cache/results_cache_" . strtolower($level) . ".json";
    $cacheDuration = 3600; // Durée de validité du cache en secondes (ex : 1 heure)

    // Étape 1: Vérifier si le fichier de cache existe et est encore valide
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheDuration) {
        // Charger les résultats à partir du fichier de cache
        $cachedData = file_get_contents($cacheFile);
        return json_decode($cachedData, true);
    }

    // Étape 2: Sinon, exécuter la requête à MongoDB
    try {
        $conn = new MongoDB\Client("mongodb://localhost:27017");
    } catch (Exception $e) {
        die("Erreur de connexion à MongoDB: " . $e->getMessage());
    }

    $academy = $conn->academy; // Connexion à la base de données

    // Connexion aux collections
    $results = $academy->results;
    $questions = $academy->questions;

    // Étape 3: Récupérer les techniciens actifs du niveau spécifié qui ont fait un test actif
    $technicians = $results->find([
        '$and' => [
            ["typeR" => "Techniciens"],
            ["level" => $level], // Niveau dynamique
            ["active" => true]
        ]
    ])->toArray();

    if (empty($technicians)) {
        die("Aucun technicien trouvé pour le niveau $level.");
    }

    // Étape 4: Récupérer les questions "Declarative" du niveau spécifié
    $questionDecla = $questions->find([
        '$and' => [
            ["type" => "Declarative"],
            ["level" => $level], // Niveau dynamique
            ["active" => true]
        ],
    ])->toArray();

    if (empty($questionDecla)) {
        die("Aucune question trouvée pour le niveau $level.");
    }

    // Étape 5: Créer un tableau associatif contenant les résultats pour chaque technicien et chaque question
    $tableauResultats = [];

    foreach ($technicians as $technician) {
        $technicianId = $technician['user']; // Utiliser l'identifiant du technicien

        // Vérifier si ce technicien a été évalué par un manager
        $managerEvaluation = $results->findOne([
            '$and' => [
                ["typeR" => "Managers"],
                ["level" => $level], // Niveau dynamique
                ["user" => $technicianId], // Rechercher le même technicien
                ["active" => true]
            ]
        ]);

        if (!$managerEvaluation) {
            // Si le technicien n'a pas été évalué par un manager, passer au technicien suivant
            continue;
        }

        foreach ($questionDecla as $question) {
            $questionId = (string) $question['_id']; // Convertir l'ObjectId de la question en chaîne

            // Récupérer les réponses du technicien pour cette question
            $resultTech = $results->findOne([
                '$and' => [
                    ["user" => $technicianId],
                    ["typeR" => "Techniciens"],
                    ["level" => $level], // Niveau dynamique
                    ["active" => true]
                ]
            ]);

            if (!$resultTech) {
                continue; // Passer à la prochaine question si aucune réponse n'est trouvée
            }

            // Assurer que les champs 'questions' sont des tableaux PHP
            $questionsArray = (array) $resultTech['questions']; // Conversion en tableau PHP si nécessaire
            $managerQuestionsArray = (array) $managerEvaluation['questions']; // Conversion en tableau PHP si nécessaire

            // Déterminer si la compétence est maîtrisée
            $isMastered = false;

            // Vérifier si la question est présente dans les réponses du technicien et du manager
            $techIndex = array_search((string)$questionId, array_map('strval', $questionsArray));
            $manIndex = array_search((string)$questionId, array_map('strval', $managerQuestionsArray));

            if ($techIndex !== false && $manIndex !== false &&
                isset($resultTech['answers'][$techIndex]) && isset($managerEvaluation['answers'][$manIndex]) &&
                $resultTech['answers'][$techIndex] == 'Oui' &&
                $managerEvaluation['answers'][$manIndex] == 'Oui') {
                $isMastered = true;
            }

            // Ajouter au tableau des résultats
            $tableauResultats[(string)$technicianId][(string)$questionId] = $isMastered ? "Maîtrisé" : "Non maîtrisé";
        }
    }

    // Étape 6: Stocker les résultats dans le fichier de cache
    file_put_contents($cacheFile, json_encode($tableauResultats));

    // Retourner les résultats calculés
    return $tableauResultats;
}

// Exemple d'appel de la fonction avec un niveau dynamique
$results = getValidatedResultsOld("Senior"); // Vous pouvez changer "Senior" par "Expert", "Junior", etc.
?>
