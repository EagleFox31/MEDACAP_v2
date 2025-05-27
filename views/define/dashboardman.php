<?php
// dashboardManager.php

require_once "../../vendor/autoload.php";
use MongoDB\Client;
use MongoDB\BSON\ObjectId;

// Démarrer la session
session_start();

// Activer le mode débogage (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier l'authentification et les droits d'accès
if (!isset($_SESSION["profile"]) || ($_SESSION["profile"] !== 'Manager' && $_SESSION["profile"] !== 'Super Admin')) {
    header("Location: /");
    exit();
}

try {
    // Connexion à MongoDB
    $mongo   = new Client("mongodb://localhost:27017");
    $academy = $mongo->academy;

    $usersColl     = $academy->users;
    $trainingsColl = $academy->trainings;
} catch (MongoDB\Exception\Exception $e) {
    echo "Erreur de connexion à MongoDB : " . htmlspecialchars($e->getMessage());
    exit();
}

// Charger la configuration et les fonctions nécessaires
$config = require __DIR__ . "/configGF.php";
require_once __DIR__ . "/ScoreFunctions.php";       // Classe ScoreCalculator
require_once __DIR__ . "/trainingFunctions.php";    // Fonctions de formation
require_once __DIR__ . "/technicianFunctions.php";  // Fonctions des techniciens (à créer ou inclure)

// Déterminer si l'utilisateur est un Super Admin avec un manager spécifique
$managerId = null;
if ($_SESSION["profile"] === 'Super Admin' && isset($_GET['manager'])) {
    $managerId = $_GET['manager'];
} elseif ($_SESSION["profile"] === 'Manager') {
    $managerId = $_SESSION["id"];
} else {
    echo "Accès refusé.";
    exit();
}

// Récupérer les techniciens de l'équipe
try {
    $filter = [
        'profile' => 'Technicien',
        'active'  => true,
        'manager' => new ObjectId($managerId)
    ];

    // Appliquer des filtres supplémentaires si présents
    $selectedLevel    = $_GET['level'] ?? 'all';
    $selectedBrand    = $_GET['brand'] ?? 'all';

    if ($selectedLevel !== 'all') {
        $filter['level'] = $selectedLevel;
    }
    if ($selectedBrand !== 'all') {
        $filter['$or'] = [
            ['brandJunior' => $selectedBrand],
            ['brandSenior' => $selectedBrand],
            ['brandExpert' => $selectedBrand]
        ];
    }

    $techniciansCursor = $usersColl->find($filter);
    $filteredTechnicians = iterator_to_array($techniciansCursor);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des techniciens : " . htmlspecialchars($e->getMessage());
    exit();
}

// S'assurer que brandJunior, brandSenior, et brandExpert sont des tableaux indexés numériquement
foreach ($filteredTechnicians as &$tech) {
    $tech['brandJunior'] = (isset($tech['brandJunior']) && is_array($tech['brandJunior'])) ? array_values($tech['brandJunior']) : [];
    $tech['brandSenior'] = (isset($tech['brandSenior']) && is_array($tech['brandSenior'])) ? array_values($tech['brandSenior']) : [];
    $tech['brandExpert'] = (isset($tech['brandExpert']) && is_array($tech['brandExpert'])) ? array_values($tech['brandExpert']) : [];
}
unset($tech); // Pour éviter les références inattendues

// Instancier ScoreCalculator
$scoreCalc = new ScoreCalculator($academy);

// Définir les niveaux cibles
$levels = ['Junior', 'Senior', 'Expert'];

// Récupérer toutes les spécialités via ScoreCalculator
$allSpecialities = [];
foreach ($config['functionalGroupsByLevel'] as $lvl => $groups) {
    foreach ($groups as $g) {
        if (!in_array($g, $allSpecialities)) {
            $allSpecialities[] = $g;
        }
    }
}

// Créer la map technicien => manager
$technicianManagerMap = [];
foreach ($filteredTechnicians as $t) {
    $tid  = (string)$t['_id'];
    $mid  = isset($t['manager']) ? (string)$t['manager'] : null;
    if ($mid) {
        $technicianManagerMap[$tid] = $mid;
    }
}

// Calculer tous les scores pour les techniciens
$allScores = $scoreCalc->getAllScoresForTechnicians(
    $academy,
    $technicianManagerMap,
    $levels,
    $allSpecialities,
    $optionalParam = [] // Variable pour le débogage
);

// Calculer les formations recommandées et réalisées pour l'équipe
$numRecommendedTotal = 0;
$numCompletedTotal = 0;
$brandFormationsMap = [];
$brandHoursMap = [];

foreach ($filteredTechnicians as $tech) {
    $techId = new ObjectId($tech['_id']);

    // Recommandées
    $numRecommended = $trainingsColl->countDocuments([
        'active'   => true,
        'level'    => ['$in' => $levels],
        'users'    => $techId,
        'brand'    => ['$ne' => '']
    ]);
    $numRecommendedTotal += $numRecommended;

    // Réalisées
    $numCompleted = $trainingsColl->countDocuments([
        'active'   => true,
        'level'    => ['$in' => $levels],
        'endDate'  => ['$exists' => true, '$ne' => null],
        'users'    => $techId,
        'brand'    => ['$ne' => '']
    ]);
    $numCompletedTotal += $numCompleted;

    // Formations par marque
    $pipeline = [
        [
            '$match' => [
                'active' => true,
                'level'  => ['$in' => $levels],
                'users'  => $techId,
                'brand'  => ['$ne' => '']
            ]
        ],
        [
            '$group' => [
                '_id'   => '$brand',
                'count' => ['$sum' => 1]
            ]
        ],
    ];

    $results = $trainingsColl->aggregate($pipeline);
    foreach ($results as $doc) {
        $brand = (string)$doc->_id;
        if (isset($brandFormationsMap[$brand])) {
            $brandFormationsMap[$brand] += $doc->count;
        } else {
            $brandFormationsMap[$brand] = $doc->count;
        }
    }

    // Heures de formation par marque
    $pipelineHours = [
        [
            '$match' => [
                'active'       => true,
                'level'        => ['$in' => $levels],
                'users'        => $techId,
                'brand'        => ['$ne' => ''],
                'duree_jours'  => ['$exists' => true, '$ne' => null]
            ]
        ],
        [
            '$group' => [
                '_id'             => '$brand',
                'totalDureeJours' => ['$sum' => '$duree_jours']
            ]
        ]
    ];

    $resultsHours = $trainingsColl->aggregate($pipelineHours);
    foreach ($resultsHours as $doc) {
        $brand = (string)$doc->_id;
        $totalHours = $doc->totalDureeJours * 8; // Convertir les jours en heures (1 jour = 8 heures)
        if (isset($brandHoursMap[$brand])) {
            $brandHoursMap[$brand] += $totalHours;
        } else {
            $brandHoursMap[$brand] = $totalHours;
        }
    }
}

// Calculer totalDurationTotal
$totalDurationTotal = [
    'jours'  => 0,
    'heures' => 0
];

$cursorTrainings = $trainingsColl->find([
    'active' => true,
    'users'  => ['$in' => array_map(fn($t) => new ObjectId($t['_id']), $filteredTechnicians)],
    'level'  => ['$in' => $levels],
    'brand'  => ['$ne' => ''],
]);

$daysSumTotal = 0;
foreach ($cursorTrainings as $trainingDoc) {
    if (isset($trainingDoc['duree_jours']) && $trainingDoc['duree_jours'] > 0) {
        $daysSumTotal += (float)$trainingDoc['duree_jours'];
    }
}

$fullDaysTotal = floor($daysSumTotal);
$decimalPartTotal = $daysSumTotal - $fullDaysTotal;
$hoursTotal = $decimalPartTotal * 8;

$totalDurationTotal['jours']  = (int) $fullDaysTotal;
$totalDurationTotal['heures'] = (int) $hoursTotal;

// Définir les logos des marques
$brandLogos = [
    'RENAULT TRUCK'   => 'renaultTrucks.png',
    'HINO'            => 'Hino_logo.png',
    'TOYOTA BT'       => 'bt.png',
    'SINOTRUK'        => 'sinotruk.png',
    'JCB'             => 'jcb.png',
    'MERCEDES TRUCK'  => 'mercedestruck.png',
    'TOYOTA FORKLIFT' => 'forklift.png',
    'FUSO'            => 'fuso.png',
    'LOVOL'           => 'lovol.png',
    'KING LONG'       => 'kl2.png',
];

// Calcul de brandScores
function getSupportedGroupsForBrand($brand, $level, $config)
{
    // On part de functionalGroupsByLevel[$level], puis on retire 
    // tout ce qui est "nonSupportedGroupsByBrand[$brand]"  
    $all = $config['functionalGroupsByLevel'][$level] ?? [];
    $nonSupp = $config['nonSupportedGroupsByBrand'][$brand] ?? [];
    return array_values(array_diff($all, $nonSupp));
}

$brandsToShow = []; // Liste dynamique des marques utilisées par l'équipe

// Identifier les marques utilisées par l'équipe
foreach ($filteredTechnicians as $tech) {
    foreach (['brandJunior', 'brandSenior', 'brandExpert'] as $brandField) {
        if (isset($tech[$brandField]) && is_array($tech[$brandField])) {
            foreach ($tech[$brandField] as $brand) {
                if (!in_array($brand, $brandsToShow)) {
                    $brandsToShow[] = $brand;
                }
            }
        }
    }
}

// Calcul de brandScores
$brandScores = [];  // Structure : [ ['x'=>'Marque', 'y'=>score, 'fillColor'=>'color', 'labelText'=>['text1', 'text2'], 'url'=>'url'], ... ]

// Calcul de la moyenne des scores par marque
foreach ($brandsToShow as $oneBrand) {
    $sumAll   = 0.0;
    $countAll = 0;

    foreach ($levels as $lvl) {
        // Récupérer les groupes supportés pour cette marque et ce niveau
        $supportedGroups = getSupportedGroupsForBrand($oneBrand, $lvl, $config);

        // Parcourir chaque groupe et chaque technicien pour accumuler les scores
        foreach ($supportedGroups as $grp) {
            foreach ($filteredTechnicians as $tech) { // Itérer sur chaque technicien
                $technicianId = (string)$tech['_id'];
                if (isset($allScores[$technicianId][$lvl][$grp])) {
                    $fact = $allScores[$technicianId][$lvl][$grp]['Factuel']    ?? null;
                    $decl = $allScores[$technicianId][$lvl][$grp]['Declaratif'] ?? null;
                    if ($fact !== null && $decl !== null) {
                        // Moyenne fact + decl
                        $grpScore = ($fact + $decl) / 2;
                        $sumAll += $grpScore;
                        $countAll++;
                    } elseif ($fact !== null) {
                        $sumAll += $fact;
                        $countAll++;
                    } elseif ($decl !== null) {
                        $sumAll += $decl;
                        $countAll++;
                    }
                    // Si aucun, on n'ajoute rien
                }
            }
        }
    }

    if ($countAll > 0) {
        $finalScore = round($sumAll / $countAll);
    } else {
        // Pas de score disponible
        $finalScore = null;
    }

    // Définir le texte basé sur la couleur avec nombre de modules
    if ($finalScore !== null && $finalScore >= 80) {
        $modulesCount = $brandFormationsMap[$oneBrand] ?? 0;
        $labelText = [$modulesCount, 'Modules de Formations'];
    } elseif ($finalScore !== null) {
        $labelText = ['Accès', 'Formations'];
    } else {
        $labelText = ['Accès', 'Tests'];
    }

    // Définir la couleur en fonction du score
    if ($finalScore !== null && $finalScore >= 80) {
        $fillColor = '#198754'; // Vert
    } elseif ($finalScore !== null) {
        $fillColor = '#ffc107'; // Jaune
    } else {
        $fillColor = '#6c757d'; // Gris
    }

    // Ajouter à brandScores
    $brandScores[] = [
        'x' => $oneBrand,
        'y' => $finalScore,
        'fillColor' => $fillColor,
        'labelText' => $labelText,
        'url' => '#' // URL réelle à définir si nécessaire
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Manager | CFAO Mobility Academy</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Inclure Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmD/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Inclure les CDNs des bibliothèques de graphiques -->
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js Datalabels Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <style>
        /* Styles personnalisés */
        .card-custom {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .card-custom .card-body {
            position: relative;
        }

        .card-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            color: #6c757d;
        }

        .brand-logo {
            width: 55px;
            height: 30px;
            margin-bottom: 0.5rem;
        }

        .brand-name {
            font-size: 0.9rem;
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .chart-dashboard-container {
                height: 600px;
                width: 100%;
            }

            .brand-logo {
                width: 40px;
                height: 25px;
            }
        }

        /* Bouton personnalisé */
        .btn-objectives {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include "./partials/header.php"; ?>

    <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
        <?php if ($_SESSION["profile"] == "Manager" || $_SESSION["profile"] == "Super Admin") { ?>
            <!-- Toolbar -->
            <div class="toolbar" id="kt_toolbar">
                <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
                    <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                        <h1 class="text-dark fw-bold my-1 fs-2">Dashboard Manager</h1>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
                    <div class="container-xxl">
                        <!-- Filtres pour le Manager -->
                        <div class="row mb-4 justify-content-center">
                            <!-- Filtre Niveau -->
                            <div class="col-md-3">
                                <label for="level-filter" class="form-label d-flex align-items-center">
                                    <i class="bi bi-bar-chart-line-fill me-2 text-warning"></i> Niveau
                                </label>
                                <select id="level-filter" name="level" class="form-select">
                                    <option value="all" selected>Tous les niveaux</option>
                                    <option value="Junior">Junior</option>
                                    <option value="Senior">Senior</option>
                                    <option value="Expert">Expert</option>
                                </select>
                            </div>
                            <!-- Filtre Marque -->
                            <div class="col-md-3">
                                <label for="brand-filter" class="form-label d-flex align-items-center">
                                    <i class="bi bi-tags-fill me-2 text-primary"></i> Marque
                                </label>
                                <select id="brand-filter" name="brand" class="form-select">
                                    <option value="all" selected>Toutes les marques</option>
                                    <?php
                                    // Liste des marques disponibles
                                    foreach ($brandsToShow as $brand) {
                                        echo "<option value='" . htmlspecialchars($brand) . "'>" . htmlspecialchars($brand) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Filtre Techniciens -->
                            <div class="col-md-3">
                                <label for="technician-filter" class="form-label d-flex align-items-center">
                                    <i class="bi bi-person-fill me-2 text-info"></i> Techniciens
                                </label>
                                <select id="technician-filter" name="technician" class="form-select">
                                    <option value="all" selected>Tous les techniciens</option>
                                    <?php
                                    foreach ($filteredTechnicians as $tech) {
                                        $techId = (string)$tech['_id'];
                                        $techName = htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']);
                                        echo "<option value='" . htmlspecialchars($techId) . "'>" . $techName . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    
                        <hr>

                        <!-- Statistiques d'Équipe -->
                        <div class="row mb-4">
                            <!-- Card: Modules de Formation Recommandées Totales -->
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="card-custom card h-100">
                                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                                        <i class="fas fa-book-open fa-2x mb-3 text-primary card-icon"></i>
                                        <div class="fs-2 fw-bold text-gray-800">
                                            <span id="countup-recommended">0</span>
                                        </div>
                                        <div class="fs-5 fw-bold mb-2">
                                            Modules de Formation Recommandées Totales
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Modules de Formation Réalisées Totales -->
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="card-custom card h-100">
                                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                                        <i class="fas fa-check-circle fa-2x mb-3 text-success card-icon"></i>
                                        <div class="fs-2 fw-bold text-gray-800">
                                            <span id="countup-completed">0</span>
                                        </div>
                                        <div class="fs-5 fw-bold mb-2">
                                            Modules de Formation Réalisées Totales
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Volume de Formation Total -->
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="card-custom card h-100">
                                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                                        <i class="fas fa-hourglass-half fa-2x mb-3 text-warning card-icon"></i>
                                        <div class="fs-2 fw-bold text-gray-800">
                                            <span id="countup-days">0</span> jours / 
                                            <span id="countup-hours">0</span> heures
                                        </div>
                                        <div class="fs-5 fw-bold mb-2">
                                            Volume de Formation Total
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vous pouvez ajouter plus de cards pour d'autres statistiques si nécessaire -->
                        </div>
                        <hr>

                        <!-- Marques de l'Équipe -->
                        <div class="text-center mb-4">
                            <h5 class="mb-3">Marques utilisées par l'équipe :</h5>
                            <div class="row justify-content-center">
                                <?php
                                if (!empty($brandsToShow)) {
                                    foreach ($brandsToShow as $brand) {
                                        $logoSrc = isset($brandLogos[$brand]) ? "brands/" . $brandLogos[$brand] : "brands/default.png";
                                        echo "<div class='col-6 col-sm-4 col-md-3 col-lg-2 mb-4'>";
                                        echo "<div class='card custom-card h-100'>";
                                        echo "<div class='card-body d-flex flex-column justify-content-center align-items-center'>";
                                        echo "<img src='$logoSrc' alt='$brand Logo' class='img-fluid brand-logo' aria-label='Logo $brand'>";
                                        // Afficher le nom de la marque
                                        echo "<h6 class='card-title text-center'>$brand</h6>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<span class='badge bg-secondary me-2 mb-2'>Aucune marque</span>";
                                }
                                ?>
                            </div>
                        </div>

                        <hr><br>

                        <!-- Conteneur des Graphiques -->
                        <div class="chart-dashboard-container">
                            <!-- Graphique 1: Résultats des Tests de l'Équipe -->
                            <div class="row align-items-center mb-4">
                                <!-- Carte d'explication à gauche avec collapse -->
                                <div class="col-lg-3 mb-4 mb-lg-0">
                                    <div class="card custom-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <!-- Bouton de toggle -->
                                            <button class="btn btn-sm btn-outline-secondary toggle-info" type="button" data-bs-toggle="collapse" data-bs-target="#infoMesure" aria-expanded="true" aria-controls="infoMesure">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <div id="infoMesure" class="collapse show">
                                            <div class="chart-title">
                                                <i class="fas fa-chart-bar"></i>
                                                <span>Résultats des Tests de l'Équipe</span>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Visualisez les performances de l'équipe sur différents tests par marque.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Graphique à droite -->
                                <div class="col-lg-9">
                                    <div class="card custom-card h-100">
                                        <div class="card-body">
                                            <div id="mesure-container" class="d-flex flex-column align-items-center mb-5">
                                                <h3 class="text-center mb-4">1. Résultats des Tests de l'Équipe</h3>
                                                <canvas id="myChartMesure" aria-label="Graphique des Résultats des Tests" role="img"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Graphique 2: Plans de Formations de l'Équipe -->
                            <div class="row align-items-center mb-4">
                                <!-- Carte d'explication à gauche avec collapse -->
                                <div class="col-lg-3 mb-4 mb-lg-0">
                                    <div class="card custom-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <!-- Bouton de toggle -->
                                            <button class="btn btn-sm btn-outline-secondary toggle-info" type="button" data-bs-toggle="collapse" data-bs-target="#infoFormation" aria-expanded="true" aria-controls="infoFormation">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <div id="infoFormation" class="collapse show">
                                            <div class="chart-title">
                                                <i class="fas fa-chart-line"></i>
                                                <span>Plans de Formations de l'Équipe</span>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Suivez les modules et le volume de formation recommandés par marque.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Graphique à droite -->
                                <div class="col-lg-9">
                                    <div class="card custom-card h-100">
                                        <div class="card-body">
                                            <div id="chart-container" class="d-flex flex-column align-items-center mb-4">
                                                <h3 class="text-center mb-4">2. Plans de Formations de l'Équipe</h3>
                                                <canvas id="chartjs-container" aria-label="Graphique des Plans de Formations" role="img"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php include "./partials/footer.php"; ?>

        <!-- Scripts JavaScript -->
        <script>
            // Enregistrer le plugin Chart.js Datalabels
            Chart.register(ChartDataLabels);
        </script>

        <!-- Passer les variables PHP au JavaScript -->
        <script>
        const variablesPHP = {
            numRecommendedTotal: <?php echo json_encode($numRecommendedTotal, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            numCompletedTotal: <?php echo json_encode($numCompletedTotal, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            totalDurationTotal: <?php echo json_encode($totalDurationTotal, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            brandScores: <?php echo json_encode($brandScores, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            brandFormationsMap: <?php echo json_encode($brandFormationsMap, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            brandHoursMap: <?php echo json_encode($brandHoursMap, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            brandLogos: <?php echo json_encode($brandLogos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            technicians: <?php echo json_encode($filteredTechnicians, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            scores: <?php echo json_encode($allScores, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            config: <?php echo json_encode($config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?> // Inclure la configuration pour les fonctions JS
        };

        // Afficher les variables dans la console du navigateur
        console.log("Variables PHP dans JS:", variablesPHP);

        // Fonction pour obtenir les groupes supportés pour une marque et un niveau (répliqué en JS)
        function getSupportedGroupsForBrand(brand, level, config) {
            const allGroups = config.functionalGroupsByLevel[level] || [];
            const nonSupported = config.nonSupportedGroupsByBrand[brand] || [];
            return allGroups.filter(group => !nonSupported.includes(group));
        }
        </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des filtres et récupération des techniciens
        const technicianFilter = document.getElementById('technician-filter');
        const levelFilter = document.getElementById('level-filter');
        const brandFilter  = document.getElementById('brand-filter');

        // Fonction pour mettre à jour les graphiques et les statistiques
        function updateDashboard() {
            const selectedTechnician = technicianFilter.value;
            const selectedLevel = levelFilter.value;
            const selectedBrand = brandFilter.value;

            // Filtrer les techniciens selon les sélections
            let filteredTechnicians = variablesPHP.technicians;

            if (selectedTechnician !== 'all') {
                filteredTechnicians = filteredTechnicians.filter(tech => tech._id === selectedTechnician);
            }

            if (selectedLevel !== 'all') {
                filteredTechnicians = filteredTechnicians.filter(tech => tech.level === selectedLevel);
            }

            if (selectedBrand !== 'all') {
                filteredTechnicians = filteredTechnicians.filter(tech => {
                    return ['brandJunior', 'brandSenior', 'brandExpert'].some(field => tech[field] && tech[field].includes(selectedBrand));
                });
            }

            // Recalculer les statistiques basées sur les techniciens filtrés
            // Calcul de la moyenne des scores par marque et somme des formations
            const brandScoresData = [];
            const uniqueBrands = new Set();

            // Identifier les marques utilisées par les techniciens filtrés
            filteredTechnicians.forEach(tech => {
                ['brandJunior', 'brandSenior', 'brandExpert'].forEach(field => {
                    if (Array.isArray(tech[field])) {
                        tech[field].forEach(brand => uniqueBrands.add(brand));
                    }
                });
            });

            const brandsToShowDynamic = Array.from(uniqueBrands);

            // Recalculer les scores par marque
            brandsToShowDynamic.forEach(oneBrand => {
                let sumAll = 0.0;
                let countAll = 0;

                const levels = ['Junior', 'Senior', 'Expert']; // Définir les niveaux cibles

                levels.forEach(lvl => {
                    const supportedGroups = getSupportedGroupsForBrand(oneBrand, lvl, variablesPHP.config);

                    supportedGroups.forEach(grp => {
                        filteredTechnicians.forEach(tech => {
                            const technicianId = tech._id;
                            if (variablesPHP.scores && variablesPHP.scores[technicianId] && variablesPHP.scores[technicianId][lvl] && variablesPHP.scores[technicianId][lvl][grp]) {
                                const fact = variablesPHP.scores[technicianId][lvl][grp]['Factuel'] || null;
                                const decl = variablesPHP.scores[technicianId][lvl][grp]['Declaratif'] || null;
                                if (fact !== null && decl !== null) {
                                    const grpScore = (fact + decl) / 2;
                                    sumAll += grpScore;
                                    countAll++;
                                } else if (fact !== null) {
                                    sumAll += fact;
                                    countAll++;
                                } else if (decl !== null) {
                                    sumAll += decl;
                                    countAll++;
                                }
                                // Si aucun, on n'ajoute rien
                            }
                        });
                    });
                });

                let finalScore = null;
                if (countAll > 0) {
                    finalScore = Math.round(sumAll / countAll);
                }

                let labelText = ['Accès', 'Tests'];
                let fillColor = '#6c757d'; // Gris par défaut

                if (finalScore !== null && finalScore >= 80) {
                    const modulesCount = variablesPHP.brandFormationsMap[oneBrand] || 0;
                    labelText = [modulesCount, 'Modules de Formations'];
                    fillColor = '#198754'; // Vert
                } else if (finalScore !== null) {
                    labelText = ['Accès', 'Formations'];
                    fillColor = '#ffc107'; // Jaune
                }

                brandScoresData.push({
                    x: oneBrand,
                    y: finalScore,
                    fillColor: fillColor,
                    labelText: labelText,
                    url: '#' // URL réelle à définir si nécessaire
                });
            });

            // Recalculer les formations par marque
            const brandFormationsMapDynamic = {};
            const brandHoursMapDynamic = {};

            filteredTechnicians.forEach(tech => {
                const techId = tech._id;
                // Formations Recommandées
                const numRecommended = variablesPHP.brandFormationsMap ? Object.values(variablesPHP.brandFormationsMap).reduce((a, b) => a + b, 0) : 0;
                // Formations Réalisées
                const numCompleted = variablesPHP.numCompletedTotal || 0;

                // Formations par marque
                Object.keys(variablesPHP.brandFormationsMap).forEach(brand => {
                    if (brandsToShowDynamic.includes(brand)) {
                        brandFormationsMapDynamic[brand] = (brandFormationsMapDynamic[brand] || 0) + (variablesPHP.brandFormationsMap[brand] || 0);
                    }
                });

                // Heures de formation par marque
                Object.keys(variablesPHP.brandHoursMap).forEach(brand => {
                    if (brandsToShowDynamic.includes(brand)) {
                        brandHoursMapDynamic[brand] = (brandHoursMapDynamic[brand] || 0) + (variablesPHP.brandHoursMap[brand] || 0);
                    }
                });
            });

            // Mettre à jour les statistiques clés
            document.getElementById('countup-recommended').innerText = brandFormationsMapDynamic ? Object.values(brandFormationsMapDynamic).reduce((a, b) => a + b, 0) : 0;
            document.getElementById('countup-completed').innerText = numCompletedTotal;
            document.getElementById('countup-days').innerText = variablesPHP.totalDurationTotal.jours;
            document.getElementById('countup-hours').innerText = variablesPHP.totalDurationTotal.heures;

            // Mettre à jour les graphiques avec les nouvelles données
            updateCharts(brandScoresData, brandFormationsMapDynamic, brandHoursMapDynamic, brandsToShowDynamic);
        }

        // Fonction pour obtenir les groupes supportés pour une marque et un niveau
        function getSupportedGroupsForBrand(brand, level, config) {
            const allGroups = config.functionalGroupsByLevel[level] || [];
            const nonSupported = config.nonSupportedGroupsByBrand[brand] || [];
            return allGroups.filter(group => !nonSupported.includes(group));
        }

        // Fonction pour mettre à jour les graphiques
        function updateCharts(brandScoresData, brandFormationsMap, brandHoursMap, brandsToShowDynamic) {
            // Mettre à jour le Scatter Chart "Plans de Formations"
            scatterChart.data.datasets[0].data = brandScoresData.map((brand, index) => ({
                x: index,
                y: brand.y !== null ? brand.y : 0,
                backgroundColor: brand.fillColor,
                labelText: brand.labelText
            }));
            scatterChart.update();

            // Mettre à jour le Bubble Chart "Résultats des Tests"
            bubbleChart.data.datasets[0].data = brandScoresData.map((brand, index) => {
                let borderColor = '#6c757d'; // Gris par défaut
                if (brand.y !== null && brand.y >= 80) {
                    borderColor = '#198754'; // Vert
                } else if (brand.y !== null) {
                    borderColor = '#ffc107'; // Jaune
                }

                return {
                    x: index,
                    y: brand.y !== null ? brand.y : 0,
                    r: brand.y !== null ? (brand.y / 100) * 20 + 5 : 5,
                    backgroundColor: '#ffffff', // Blanc
                    borderColor: borderColor,
                    borderWidth: 2
                };
            });
            bubbleChart.update();
        }

        // Initialisation des Graphiques
        const brandLogos = variablesPHP.brandLogos;
        const brandFormationsMap = variablesPHP.brandFormationsMap;
        const brandHoursMap = variablesPHP.brandHoursMap;

        // Initialiser les graphiques avec les données initiales
        function initializeCharts() {
            const brandScoresData = variablesPHP.brandScores;
            const labels = brandScoresData.map(d => d.x);
            const dataValues = brandScoresData.map(d => d.y);
            const colors = brandScoresData.map(d => d.fillColor);
            const urls = brandScoresData.map(d => d.url || '#');

            // Définir la fonction pour dessiner les logos sur les graphiques
            function drawLogos(chart, containerId) {
                // Supprimer les anciens conteneurs de logos
                const oldDiv = document.getElementById(containerId + '-logo-container');
                if (oldDiv) oldDiv.remove();

                // Créer un conteneur DIV pour les logos
                const logoContainer = document.createElement('div');
                logoContainer.id = containerId + '-logo-container';
                logoContainer.style.position = 'absolute';
                logoContainer.style.top = '0';
                logoContainer.style.left = '0';
                logoContainer.style.width = '100%';
                logoContainer.style.height = '100%';
                logoContainer.style.pointerEvents = 'none'; // Permettre les événements de souris à travers

                // Obtenir les échelles du graphique
                const xScale = chart.scales.x;
                const chartArea = chart.chartArea;

                // Boucler sur les labels pour placer les logos
                labels.forEach((label, index) => {
                    const xPos = xScale.getPixelForValue(index);
                    const yPos = chartArea.bottom + 30; // Ajuster selon les besoins

                    // Créer l'élément image
                    const img = document.createElement('img');
                    img.src = brandLogos[label] ? `brands/${brandLogos[label]}` : `brands/default.png`;
                    img.style.position = 'absolute';
                    img.style.left = (xPos - 25) + 'px'; // Centrer l'image (ajusté pour 50px de largeur)
                    img.style.top = yPos + 'px';
                    img.style.width = '50px';
                    img.style.height = '30px';
                    img.onerror = function() {
                        console.error(`Erreur de chargement de l'image : ${img.src}`);
                        img.src = 'brands/default.png';
                    };

                    // Ajouter l'image au conteneur
                    logoContainer.appendChild(img);
                });

                // Ajouter le conteneur au parent
                const chartContainer = document.getElementById(containerId);
                chartContainer.appendChild(logoContainer);
            }

            // Plugin pour le Scatter Chart
            const imagePluginScatter = {
                id: 'imagePluginScatter',
                afterRender: (chart) => drawLogos(chart, 'chartjs-container'),
                afterResize: (chart) => {
                    const logoContainer = document.getElementById('chartjs-container-logo-container');
                    if (logoContainer) {
                        logoContainer.remove();
                    }
                    drawLogos(chart, 'chartjs-container');
                }
            };

            // Plugin pour le Bubble Chart
            const imagePluginMesure = {
                id: 'imagePluginMesure',
                afterRender: (chart) => drawLogos(chart, 'mesure-container'),
                afterResize: (chart) => {
                    const logoContainer = document.getElementById('mesure-container-logo-container');
                    if (logoContainer) {
                        logoContainer.remove();
                    }
                    drawLogos(chart, 'mesure-container');
                }
            };

            // Initialisation du Scatter Chart "Plans de Formations"
            const ctxScatter = document.getElementById('chartjs-container').getContext('2d');
            const scatterChart = new Chart(ctxScatter, {
                type: 'scatter',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de Modules de Formations par Marque',
                        data: labels.map((brand, i) => ({
                            x: i,
                            y: dataValues[i] !== null ? dataValues[i] : 0
                        })),
                        backgroundColor: colors,
                        pointRadius: 55, // Taille des points
                        pointHoverRadius: 65,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        datalabels: {
                            anchor: 'center',
                            align: 'center',
                            color: '#fff',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                // Récupérer le nom de la marque
                                const brand = labels[context.dataIndex];
                                // Récupérer modulesCount à partir de brandFormationsMap
                                const modulesCount = brandFormationsMap[brand] !== undefined ? brandFormationsMap[brand] : '0';
                                return ` ${modulesCount}\nModules\nde Formations`;
                            },
                            textAlign: 'center' // Centrer le texte
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    const i = context.dataIndex;
                                    const brand = labels[i];
                                    const score = dataValues[i] !== null ? dataValues[i] : 'N/A';
                                    const modulesCount = brandFormationsMap[brand] !== undefined ? brandFormationsMap[brand] : '0';
                                    const hours = brandHoursMap[brand] !== undefined ? brandHoursMap[brand] : '0';
                                    return [
                                        `Marque: ${brand}`,
                                        `Score: ${score}%`,
                                        `Modules de Formations: ${modulesCount}`,
                                        `Heures de Formation: ${hours}h`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            ticks: {
                                display: false // Masquer les labels textuels
                            },
                            grid: {
                                display: true,
                                color: '#ccc'
                            },
                            min: -0.5,
                            max: labels.length - 0.5
                        },
                        y: {
                            type: 'linear',
                            beginAtZero: true,
                            min: 0,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Score (%)',
                                padding: 30
                            },
                            grid: {
                                display: true,
                                color: '#ccc'
                            },
                            ticks: {
                                stepSize: 10,
                                padding: 10
                            }
                        }
                    },
                    onClick: (evt, activeElements) => {
                        if (activeElements.length > 0) {
                            const index = activeElements[0].index;
                            if (colors[index] === '#ffc107') { // Couleur warning (Jaune)
                                window.open(urls[index], '_blank');
                            }
                        }
                    }
                },
                plugins: [imagePluginScatter, ChartDataLabels]
            });

            // Initialisation du Bubble Chart "Résultats des Tests"
            const ctxMesure = document.getElementById('myChartMesure').getContext('2d');

            // Préparer les données pour le Bubble Chart avec couleurs conditionnelles
            const bubbleMesureData = brandScoresData.map((item, index) => {
                let borderColor = '#6c757d'; // Gris par défaut
                if (item.y !== null && item.y >= 80) {
                    borderColor = '#198754'; // Vert pour >=80
                } else if (item.y !== null) {
                    borderColor = '#ffc107'; // Jaune pour <80
                }

                return {
                    x: index,
                    y: item.y !== null ? item.y : 0,
                    r: item.y !== null ? (item.y / 100) * 20 + 5 : 5, // Rayon basé sur le score
                    backgroundColor: '#ffffff', // Blanc
                    borderColor: borderColor,
                    borderWidth: 2
                };
            });

            const bubbleChart = new Chart(ctxMesure, {
                type: 'bubble',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Résultats des Tests (%)',
                        data: bubbleMesureData,
                        backgroundColor: bubbleMesureData.map(d => d.backgroundColor),
                        borderColor: bubbleMesureData.map(d => d.borderColor),
                        borderWidth: bubbleMesureData.map(d => d.borderWidth)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        datalabels: {
                            color: function(context) {
                                const score = context.dataset.data[context.dataIndex].y;
                                if (score >= 80) {
                                    return '#198754'; // Vert
                                } else if (score >= 0 && score < 80) {
                                    return '#ffc107'; // Jaune
                                } else {
                                    return '#000000'; // Noir pour les autres cas
                                }
                            },
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            align: 'center',
                            anchor: 'center',
                            formatter: function(value) {
                                return (value.y === 0) ? '0%' : value.y + '%';
                            }
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            min: -0.5,
                            max: labels.length - 0.5,
                            grid: {
                                color: '#ccc'
                            },
                            ticks: {
                                display: false
                            }
                        },
                        y: {
                            type: 'linear',
                            min: 0,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Score (%)'
                            },
                            grid: {
                                color: '#ccc'
                            },
                            ticks: {
                                stepSize: 10,
                                padding: 10
                            }
                        }
                    },
                    onClick: (evt, activeElements) => {
                        if (activeElements.length > 0) {
                            const index = activeElements[0].index;
                            if (bubbleMesureData[index].borderColor === '#ffc107') { // Couleur warning (Jaune)
                                window.open(brandScoresData[index].url, '_blank');
                            }
                        }
                    }
                },
                plugins: [imagePluginMesure, ChartDataLabels]
            });

            // Fonction pour dessiner les logos sur les graphiques (réutilisée)
            function drawLogos(chart, containerId) {
                // Supprimer les anciens conteneurs de logos
                const oldDiv = document.getElementById(containerId + '-logo-container');
                if (oldDiv) oldDiv.remove();

                // Créer un conteneur DIV pour les logos
                const logoContainer = document.createElement('div');
                logoContainer.id = containerId + '-logo-container';
                logoContainer.style.position = 'absolute';
                logoContainer.style.top = '0';
                logoContainer.style.left = '0';
                logoContainer.style.width = '100%';
                logoContainer.style.height = '100%';
                logoContainer.style.pointerEvents = 'none'; // Permettre les événements de souris à travers

                // Obtenir les échelles du graphique
                const xScale = chart.scales.x;
                const chartArea = chart.chartArea;

                // Boucler sur les labels pour placer les logos
                labels.forEach((label, index) => {
                    const xPos = xScale.getPixelForValue(index);
                    const yPos = chartArea.bottom + 30; // Ajuster selon les besoins

                    // Créer l'élément image
                    const img = document.createElement('img');
                    img.src = brandLogos[label] ? `brands/${brandLogos[label]}` : `brands/default.png`;
                    img.style.position = 'absolute';
                    img.style.left = (xPos - 25) + 'px'; // Centrer l'image (ajusté pour 50px de largeur)
                    img.style.top = yPos + 'px';
                    img.style.width = '50px';
                    img.style.height = '30px';
                    img.onerror = function() {
                        console.error(`Erreur de chargement de l'image : ${img.src}`);
                        img.src = 'brands/default.png';
                    };

                    // Ajouter l'image au conteneur
                    logoContainer.appendChild(img);
                });

                // Ajouter le conteneur au parent
                const chartContainer = document.getElementById(containerId);
                chartContainer.appendChild(logoContainer);
            }
        }

        // Initialiser les Graphiques
        initializeCharts();

        // Événement de changement des filtres
        technicianFilter.addEventListener('change', updateDashboard);
        levelFilter.addEventListener('change', updateDashboard);
        brandFilter.addEventListener('change', updateDashboard);
    });
    </script>

</body>
</html>
