<?php
require_once "../vendor/autoload.php"; // Charger les dépendances nécessaires

/**
 * Récupère les scores et le total des techniciens pour un niveau spécifique.
 *
 * @param string $selectedLevel Le niveau sélectionné (Junior, Senior, Expert).
 * @return array Un tableau associatif où chaque clé est l'ID du technicien et chaque valeur est un tableau avec 'score' et 'total'.
 */
function getTechnicianScores($selectedLevel) {
    // Connexion MongoDB
    try {
        $conn = new MongoDB\Client("mongodb://localhost:27017");
    } catch (Exception $e) {
        die("Erreur de connexion à MongoDB: " . $e->getMessage());
    }

    $academy = $conn->academy;
    $resultsCollection = $academy->results;

    // Requête MongoDB pour récupérer les scores des techniciens de niveau spécifié
    $cursor = $resultsCollection->find([
        'level' => $selectedLevel,
        'typeR' => 'Technicien - Manager', // Filtrer pour les scores
        'active' => true
    ]);

    $tableauScores = [];

    foreach ($cursor as $result) {
        // Conversion des ObjectId en chaînes de caractères pour l'utilisateur
        if (!isset($result['user'])) {
            echo "Champ 'user' manquant dans un document de scores.\n";
            continue; // Passer au document suivant si 'user' n'est pas défini
        }
        $techId = (string)$result['user'];  // ID du technicien

        // Initialiser le tableau pour le technicien s'il n'existe pas encore
        if (!isset($tableauScores[$techId])) {
            $tableauScores[$techId] = [
                'score' => 0,
                'total' => 0
            ];
        }

        // Vérifier l'existence des champs 'score' et 'total'
        if (isset($result['score']) && isset($result['total'])) {
            $tableauScores[$techId]['score'] += $result['score'];
            $tableauScores[$techId]['total'] += $result['total'];
        } else {
            echo "Pas de 'score' ou 'total' pour ce technicien: $techId\n";
        }
    }

    return $tableauScores;
}
?>
