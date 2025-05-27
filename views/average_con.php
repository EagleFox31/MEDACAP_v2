<?php

session_start();
include_once "language.php";
include_once "getValidatedResults3.php"; // Inclusion du fichier contenant la logique des résultats
include_once "userFilters.php"; // Inclusion du fichier contenant les fonctions de filtrage

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION["profile"])) {
    header("Location: ../");
    exit();
} else{

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

    // Récupérer les questions factuelles actives pour le niveau sélectionné
    $questionDeclaCursor = $questionsCollection->find([
        '$and' => [
            ["type" => "Factuelle"],
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

    // Récupérer les résultats validés par technicien et par question
    $tableauResultats = getTechnicianResults3($selectedLevel);

    // Initialiser des tableaux pour compter les maîtrises et les questions évaluées
    $technicianMasteryCounts = [];
    $technicianQuestionCounts = [];

    foreach ($technicians as $technician) {
        $techId = (string)$technician['_id'];
        $technicianMasteryCounts[$techId] = 0;
        $technicianQuestionCounts[$techId] = isset($tableauResultats[$techId]) ? count($tableauResultats[$techId]) : 0;
    }

    // Boucles pour calculer les pourcentages de maîtrise par niveau
    $levels = ['Junior', 'Senior', 'Expert'];
    $technicianPercentagesByLevel = [];
    $technicianCountsByLevel = [];

    foreach ($levels as $level) {
        // Récupérer les techniciens par niveau
        $technicians = filterUsersByProfile($academy, $profile, $selectedCountry, $level, $selectedAgency);
        $technicianPercentagesByLevel[$level] = 0;
        $technicianCountsByLevel[$level] = 0;

        // Récupérer les résultats des techniciens pour ce niveau
        $tableauResultats = getTechnicianResults3($level);

        // Calculer les pourcentages de maîtrise pour chaque technicien
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
        $averageMasteryByLevel[$level] = $technicianCountsByLevel[$level] > 0 ? 
            round($technicianPercentagesByLevel[$level] / $technicianCountsByLevel[$level]) : 0;
    }
    $averageMasteryByLevel['Total'] = round(array_sum($averageMasteryByLevel) / count($levels));

?>
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card h5 { font-size: 1.2rem; margin-top: 1rem; }
        .chart-container { position: relative; width: 100%; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Moyenne de Maîtrise de Connaissance par Niveau</h1>
        <div class="row" id="chartFact"></div>
    </div>

    <script>
    var averageMasteryData = <?php echo json_encode($averageMasteryByLevel); ?>;
    console.log("Fact")    
    console.log(averageMasteryData);
    document.addEventListener('DOMContentLoaded', function() {
        function getFactColor(percentage) {
            if (percentage <= 60) {
                return ['#F9945E', '#D3D3D3']; // Orange et Gris clair
            } else if (percentage <= 80) {
                return ['#FFFC36', '#D3D3D3']; // Jaune et Gris clair
            } else {
                return ['#6CF95E', '#D3D3D3']; // Vert et Gris clair
            }
        }

        var levels = ['Junior', 'Senior', 'Expert', 'Total'];
        var containerM = document.getElementById('chartFact');

        // Boucle pour créer et ajouter les graphiques
        levels.forEach(function(level, index) {
            console.log("In level foreach");
            var completedPercentage = averageMasteryData[level] || 0;
            var colors = getFactColor(completedPercentage);
            var title = level === 'Total' ? 'Total : 3 Niveaux' : 'Résultat Niveau ' + level;
            var cardHtml = `
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="chart-container">
                                <canvas id="doughnutFactChart${index}" width="200" height="200"></canvas>
                            </div>
                            <h5 class="mt-4">${title}</h5>
                        </div>
                    </div>
                </div>
            `;

            containerM.insertAdjacentHTML('beforeend', cardHtml);

            // Initialiser le graphique doughnut avec Chart.js
            new Chart(document.getElementById(`doughnutFactChart${index}`).getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: [`${completedPercentage}% des compétences acquises`, `${100 - completedPercentage}% des compétences à acquérir`],
                    datasets: [{
                        data: [completedPercentage, 100 - completedPercentage],
                        backgroundColor: colors,
                        borderColor: ['#ffffff', '#ffffff'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { size: 12 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    var percentage = tooltipItem.parsed;
                                    return tooltipItem.label + ': ' + percentage + '%';
                               }
                            }
                        }
                    },
                    cutout: '45%',
                }
            });
        });
    });


    </script>

    <!-- Inclure Bootstrap JS (facultatif) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
<?php include_once "partials/footer.php"; ?>
<?php } ?>
