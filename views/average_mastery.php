<?php
session_start();
include_once "language.php";
include_once "getValidatedResults2.php"; // Inclusion du fichier contenant la logique des résultats
include_once "userFilters.php"; // Inclusion du fichier contenant les fonctions de filtrage

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION["profile"])) {
    header("Location: ../");
    exit();
} else {

   // Récupérer les paramètres depuis l'URL
    $selectedLevel = isset($_GET['level']) ? $_GET['level'] : 'Junior';
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;
    $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null;
    $selectedUser = isset($_GET['user']) ? $_GET['user'] : null;

    // Créer une connexion
    $conn = new MongoDB\Client("mongodb://localhost:27017");
    $academy = $conn->academy;

    // Connexion à la collection des questions
    $questionsCollection = $academy->questions;

    // Récupérer les questions déclaratives actives pour le niveau sélectionné
    $questionDeclaCursor = $questionsCollection->find([
        '$and' => [
            ["type" => "Declarative"],
            ["level" => $selectedLevel],
            ["active" => true]
        ],
    ]);

    $questionDecla = iterator_to_array($questionDeclaCursor);

    // Récupérer toutes les questions pour créer une liste d'ID de questions
    $allQuestionIds = [];
    foreach ($questionDecla as $question) {
        $allQuestionIds[] = (string)$question['_id'];
    }

    // Récupérer le profil utilisateur de la session
    $profile = $_SESSION['profile'];
    $userCountry = isset($_SESSION['country']) ? $_SESSION['country'] : null;

    // Filtrer pour obtenir uniquement les techniciens selon le profil de l'utilisateur
    $technicians = filterUsersByProfile($academy, $profile, $selectedCountry, $selectedLevel, $selectedAgency);
    
    // Définir le titre en fonction du niveau sélectionné
    $taux_de_couverture = "";
    switch ($selectedLevel) {
        case 'Junior':
            $taux_de_couverture = $taux_de_couverture_ju;
            break;
        case 'Senior':
            $taux_de_couverture = $taux_de_couverture_se;
            break;
        case 'Expert':
            $taux_de_couverture = $taux_de_couverture_ex;
            break;
        default:
            $taux_de_couverture = $taux_de_couverture_ju;
            break;
    }

    // Fonction pour obtenir la classe Bootstrap en fonction du pourcentage de non-maîtrise
    function getBootstrapClass($pourcentage)
    {
        if ($pourcentage <= 60) {
            return 'text-danger'; // Rouge pour plus de 50% non maîtrisé
        } elseif ($pourcentage <= 80) {
            return 'text-warning'; // Orange pour 10% à 49% non maîtrisé
        } else {
            return 'text-success'; // Vert pour moins de 10% non maîtrisé
        }
    }

    // Connexion aux collections nécessaires
    $resultsCollection = $academy->results;
    //$testsCollection = $academy->tests;

    // Récupérer les résultats validés par technicien et par question
    $tableauResultats = getTechnicianResults($selectedLevel);

    // Initialiser des tableaux pour compter les maîtrises et les questions évaluées
    $technicianMasteryCounts = [];
    $technicianQuestionCounts = [];

    foreach ($technicians as $technician) {
        $techId = (string)$technician['_id'];
        $technicianMasteryCounts[$techId] = 0;
        $technicianQuestionCounts[$techId] = isset($tableauResultats[$techId]) ? count($tableauResultats[$techId]) : 0;
    }

    $agencies = [
        "Burkina Faso" => ["Ouaga"],
        "Cameroun" => ["Bafoussam","Bertoua", "Douala", "Garoua", "Ngaoundere", "Yaoundé"],
        "Cote d'Ivoire" => ["Vridi - Equip"],
        "Gabon" => ["Libreville"],
        "Mali" => ["Bamako"],
        "RCA" => ["Bangui"],
        "RDC" => ["Kinshasa", "Kolwezi", "Lubumbashi"],
        "Senegal" => ["Dakar"],
        // Add more countries and their agencies here
    ];
    $countries = array_keys($agencies);  // Extraction des pays

    $levels = ['Junior', 'Senior', 'Expert'];

 // Stocker les résultats de chaque niveau
    $technicianPercentagesByLevel = [];
    $technicianCountsByLevel = [];

    // Boucles pour chaque niveau
    foreach ($levels as $level) {
        // 1. Récupérer les techniciens par niveau
        $technicians = filterUsersByProfile($academy, "Directeur Groupe", null, $level, null);
        $technicianPercentagesByLevel[$level] = 0;
        $technicianCountsByLevel[$level] = 0;

        // 2. Récupérer les résultats des techniciens pour ce niveau
        $tableauResultats = getTechnicianResults($level);  // Filtrer par niveau

        // 3. Calculer les pourcentages de maîtrise
        foreach ($technicians as $technician) {
            $techId = (string)$technician['_id'];
            $results = isset($tableauResultats[$techId]) ? $tableauResultats[$techId] : [];
            $questionsEvaluated = count($results);

            if ($questionsEvaluated > 0) {
                $masteryCount = 0;
                foreach ($results as $status) {
                    if ($status == 1) {
                        $masteryCount++;
                    }
                }
                $percentageMastery = ($masteryCount / $questionsEvaluated) * 100;
                $technicianPercentagesByLevel[$level] += $percentageMastery;
                $technicianCountsByLevel[$level]++;
            }
        }
    }

    // Calculer les moyennes par niveau
    $averageMasteryByLevel = [];
    foreach ($levels as $level) {
        if ($technicianCountsByLevel[$level] > 0) {
            $averageMasteryByLevel[$level] = round($technicianPercentagesByLevel[$level] / $technicianCountsByLevel[$level]);
        } else {
            $averageMasteryByLevel[$level] = 0;
        }
    }
    $averageMasteryByLevel['Total'] = round(array_sum($averageMasteryByLevel) / count($levels));

?>
<head>
    <!-- Inclure les méta-tags et feuilles de style nécessaires -->
    <meta charset="UTF-8">
    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Inclure Bootstrap CSS pour le style (facultatif) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card h5 {
            font-size: 1.2rem;
            margin-top: 1rem;
        }
        .chart-container {
            position: relative;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Moyenne de Maîtrise de T.P par Niveau</h1>
        <div class="row" id="chartMoyen"></div>
    </div>

    <div class="container mt-5">
        <h1 class="text-center mb-5">Moyenne de Maîtrise de Connaissance par Niveau</h1>
        <div class="row" id="chartFact"></div>
    </div>

    <!-- Inclure Bootstrap JS (facultatif) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
