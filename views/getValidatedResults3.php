<?php
// getTechnicianResults.php

require_once "../vendor/autoload.php"; // Charger les dépendances nécessaires

use MongoDB\Client;

/**
 * Récupère les questions et les réponses des techniciens pour un niveau spécifique.
 *
 * @param string $selectedLevel Le niveau sélectionné (Junior, Senior, Expert).
 * @return array Un tableau associatif où chaque clé est l'ID du technicien et chaque valeur est un tableau associatif de questions et leurs statuts (1, 0, -).
 */
function getTechnicianResults3($selectedLevel = "Junior") {
    // Connexion à MongoDB
    try {
        $conn = new Client("mongodb://localhost:27017");
    } catch (Exception $e) {
        die("Erreur de connexion à MongoDB: " . $e->getMessage());
    }

    $academy = $conn->academy;
    $resultsCollection = $academy->results;

    // Requête MongoDB pour récupérer les techniciens de type "Techniciens"
    $cursor = $resultsCollection->find([
        'typeR' => 'Technicien',
        'type' => 'Factuel',
        'level' => $selectedLevel,
        'active' => true
    ]);

    $tableauResultats = [];

    foreach ($cursor as $result) {
        // Vérifier l'existence du champ 'user'
        if (!isset($result['user'])) {
            echo "Champ 'user' manquant dans un document.\n";
            continue; // Passer au document suivant si 'user' n'est pas défini
        }

        $techId = (string)$result['user']; // ID du technicien

        // Initialiser le tableau pour le technicien s'il n'existe pas encore
        if (!isset($tableauResultats[$techId])) {
            $tableauResultats[$techId] = [];
        }

        //echo "Avant le test \n";

        // Récupérer les questions et les réponses du technicien
        if (isset($result['questions'])&& isset($result['answers'])) {
        //    echo " Après le test Pour : \n";
        //     echo $techId;

            // Convertir les champs en tableaux PHP
            $questionsArray = (array) $result['questions'];
            //echo $questionsArray; // Questions du technicien (tableau de chaînes)
            $answersArray = (array) $result['answers']; // Réponses du technicien (tableau de 'oui'/'non')

            // Vérifier que les deux tableaux ont le même nombre d'éléments
            $totalQuestions = count($questionsArray);
            // echo " nombre de questions : \n ";
            // echo $totalQuestions;
            $totalAnswers = count($answersArray);
            // echo " nombre de réponses : \n ";
            // echo $totalAnswers;

            if ($totalQuestions !== $totalAnswers) {
                echo "Mismatch entre questions et réponses pour le technicien: $techId\n";
                continue; // Ignorer ce résultat s'il y a un problème d'alignement
            }

            // Parcourir les questions et réponses ensemble avec leur index
            foreach ($questionsArray as $index => $questionId) {
                $questionIdStr = (string)$questionId; // ID de la question (chaîne)
                // echo " Question : \n ";
                // echo $questionIdStr;
                // Obtenir la réponse correspondante
                $answerRaw = isset($answersArray[$index]) ? $answersArray[$index] : '';
                $answer = strtolower(trim($answerRaw));

                // Déterminer le statut
                if ($answer === 'maitrisé') {
                    $status = 1; // Maîtrisé
                } elseif ($answer === 'non maitrisé') {
                    $status = 0; // Non maîtrisé
                } else {
                    $status = '-'; // Réponse non reconnue ou autre
                }

                // Assigner le statut dans le tableau des résultats en utilisant l'ID de la question
                $tableauResultats[$techId][$questionIdStr] = $status;
            }
        } else {
            echo "Pas de questions ou de réponses valides pour ce technicien: $techId\n";
        }
    }

    return $tableauResultats;
}
?>
