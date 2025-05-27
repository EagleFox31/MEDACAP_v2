<?php
session_start();

// ----------------------------------------------------------
// 1) Vérifier session / profil, puis connexion MongoDB
// ----------------------------------------------------------
if (!isset($_SESSION["profile"])) {
    header("Location: /");
    exit();
} else {
    // Autoriser l'accès si l'utilisateur est un Manager ou Super Admin
    if ($_SESSION["profile"] !== 'Manager' && $_SESSION["profile"] !== 'Super Admin') {
        echo "Accès refusé.";
        exit();
    }
    if ($_SESSION["profile"] === 'Super Admin') {
        // Super Admin peut spécifier managerId via GET
        if (isset($_GET['managerId'])) {
            $managerId = $_GET['managerId'];
        } else {
            echo "Paramètre managerId requis pour les Super Admin.";
            exit();
        }
    } else {
        // Manager peut uniquement voir son propre dashboard
        $managerId = $_SESSION["id"];
    }

    require_once "../../vendor/autoload.php";

    // Connexion
    try {
        $mongo   = new MongoDB\Client("mongodb://localhost:27017");
        $academy = $mongo->academy;  // base "academy"

        // Collections
        $usersColl     = $academy->users;
        $trainingsColl = $academy->trainings;
    } catch (MongoDB\Exception\Exception $e) {
        echo "Erreur de connexion à MongoDB : " . htmlspecialchars($e->getMessage());
        exit();
    }

    // ----------------------------------------------------------
    // 2) Charger la config GF (groupes fonctionnels) et les fonctions
    // ----------------------------------------------------------
    $config = require __DIR__ . "/configGF.php";
    require_once __DIR__ . "/ScoreFunctions.php";
    require_once __DIR__ . "/technicianFunctions.php"; // Assurez-vous que ce fichier contient les fonctions nécessaires

    // Convertir $managerId en ObjectId
    try {
        $managerObjId = new MongoDB\BSON\ObjectId($managerId);
    } catch (\Exception $e) {
        echo "Identifiant manager invalide.";
        exit();
    }

    // ----------------------------------------------------------
    // 3) Récupérer les techniciens sous la responsabilité du manager avec filtres
    // ----------------------------------------------------------
    // Lire les paramètres de filtre depuis l'URL
    $filterLevel = isset($_GET['level']) ? htmlspecialchars($_GET['level']) : 'all';
    $filterBrand = isset($_GET['brand']) ? htmlspecialchars($_GET['brand']) : 'all';
    $filterTechnician = isset($_GET['technicianId']) ? htmlspecialchars($_GET['technicianId']) : 'all';

    // Récupérer les techniciens avec filtres
    $profile = 'Technicien';
    $technicians = getAllTechnicians($academy, $profile, $managerObjId, $filterLevel, $filterBrand, $filterTechnician);

    // Mapping Tech => Manager (si nécessaire)
    $technicianManagerMap = [];
    foreach ($technicians as $t) {
        $tid  = (string)$t['_id'];
        $mid  = isset($t['manager']) ? (string)$t['manager'] : null;
        if ($mid) {
            $technicianManagerMap[$tid] = $mid;
        }
    }

    // ----------------------------------------------------------
    // 4) Récupérer et filtrer les recommandations de formation
    // ----------------------------------------------------------
    // Inclure processRecommendations.php pour obtenir les recommandations
    $recommendationData = include "processRecommendations.php";

    // Vérifier si $recommendationData est bien défini et contient les clés nécessaires
    if (!is_array($recommendationData)) {
        echo "Erreur lors de l'inclusion de processRecommendations.php.";
        exit();
    }

    // Extraire les données pour l'affichage
    $filteredTechnicians    = $recommendationData['technicians'];
    $allScores              = $recommendationData['scores'];
    $trainings              = $recommendationData['trainings'];
    $missingGroups          = $recommendationData['missingGroups'];
    $debug                  = $recommendationData['debug'];
    $totalDays              = $recommendationData['totalDays'] ?? 0;

    // ----------------------------------------------------------
    // 5) Préparer les données pour les graphiques par marque
    // ----------------------------------------------------------
    // Récupérer les logos des marques depuis le tableau $brandLogos
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
        // Ajoutez d'autres marques et leurs logos ici
    ];

    // Définir l'association Spécialités ↔ Marques
    $specialityToBrandMap = [
        'Electricité et Electronique' => ['RENAULT TRUCK', 'TOYOTA BT'],
        'Moteur Diesel' => ['HINO', 'TOYOTA BT', 'SINOTRUK'],
        'Direction' => ['RENAULT TRUCK'],
        // Ajoutez d'autres spécialités et marques ici
    ];

    // Inclure la fonction d'agrégation
    /**
     * Agrège les scores Factuel et Déclaratif par niveau et par marque pour un technicien.
     *
     * @param string $technicianId
     * @param array $technician
     * @param array $scores
     * @param array $specialityToBrandMap
     * @return array
     */
    function aggregateScoresByLevelAndBrand($technicianId, $technician, $scores, $specialityToBrandMap) {
        $aggregatedScores = [];
        
        // Définir les niveaux
        $levels = ['Junior', 'Senior', 'Expert'];
        
        foreach ($levels as $level) {
            // Récupérer les marques pour ce niveau
            $brandField = 'brand' . ucfirst($level); // e.g., brandJunior
            if (!isset($technician[$brandField])) {
                continue;
            }
            // Convertir BSONArray en array si nécessaire
            $brands = is_array($technician[$brandField]) ? $technician[$brandField] : $technician[$brandField]->getArrayCopy();
            
            foreach ($brands as $brand) {
                if (!isset($aggregatedScores[$level])) {
                    $aggregatedScores[$level] = [];
                }
                if (!isset($aggregatedScores[$level][$brand])) {
                    $aggregatedScores[$level][$brand] = [
                        'Factuel' => [],
                        'Declaratif' => []
                    ];
                }
                
                // Parcourir les spécialités et agréger les scores
                if (isset($scores[$technicianId][$level])) {
                    foreach ($scores[$technicianId][$level] as $speciality => $scoreData) {
                        // Vérifier si la spécialité est liée à la marque
                        if (isset($specialityToBrandMap[$speciality]) && in_array($brand, $specialityToBrandMap[$speciality])) {
                            if (isset($scoreData['Factuel'])) {
                                $aggregatedScores[$level][$brand]['Factuel'][] = $scoreData['Factuel'];
                            }
                            if (isset($scoreData['Declaratif'])) {
                                $aggregatedScores[$level][$brand]['Declaratif'][] = $scoreData['Declaratif'];
                            }
                        }
                    }
                }
            }
        }
        
        // Calculer la moyenne des scores par niveau et par marque
        foreach ($aggregatedScores as $level => &$brandsData) {
            foreach ($brandsData as $brand => &$scoresData) {
                // Factuel
                if (!empty($scoresData['Factuel'])) {
                    $scoresData['Factuel'] = round(array_sum($scoresData['Factuel']) / count($scoresData['Factuel']), 2);
                } else {
                    $scoresData['Factuel'] = 'N/A';
                }
                
                // Declaratif
                if (!empty($scoresData['Declaratif'])) {
                    $scoresData['Declaratif'] = round(array_sum($scoresData['Declaratif']) / count($scoresData['Declaratif']), 2);
                } else {
                    $scoresData['Declaratif'] = 'N/A';
                }
            }
        }
        
        return $aggregatedScores;
    }

    // Préparer les données agrégées par technicien, par niveau et par marque
    $aggregatedScoresByTechnician = [];
    
    foreach ($filteredTechnicians as $technician) {
        $techId = (string)$technician['_id'];
        $aggregatedScoresByTechnician[$techId] = aggregateScoresByLevelAndBrand($techId, $technician, $allScores, $specialityToBrandMap);
    }

    // Récupérer le nom du manager
    $managerDoc = $usersColl->findOne([
        '_id' => $managerObjId,
        'profile' => 'Manager'
    ]);

    if (!$managerDoc) {
        echo "Manager introuvable.";
        exit();
    }

    $firstName = htmlspecialchars($managerDoc['firstName'] ?? '');
    $lastName  = htmlspecialchars($managerDoc['lastName'] ?? '');

    // ----------------------------------------------------------
    // 6) Préparer les données pour le Frontend
    // ----------------------------------------------------------
    // Identifier les marques utilisées par l'équipe
    $brandsToShow = []; // Liste dynamique des marques utilisées par l'équipe

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

    // Définir les couleurs pour les niveaux
    $levelColors = [
        'Junior' => 'primary',   // Bleu
        'Senior' => 'warning',   // Orange
        'Expert' => 'success',   // Vert
        'N/A'    => 'secondary'  // Gris
    ];

    /**
     * Détermine le niveau principal du technicien basé sur les niveaux disponibles.
     *
     * @param array $technician
     * @return string
     */
    function getPrimaryLevel($technician) {
        $levels = ['Expert', 'Senior', 'Junior']; // Ordre décroissant de priorité
        foreach ($levels as $level) {
            $brandField = 'brand' . $level;
            if (isset($technician[$brandField]) && !empty($technician[$brandField])) {
                return $level;
            }
        }
        return 'N/A';
    }

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
            $modulesCount = $trainings[$oneBrand] ?? 0;
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

    // Calculer les formations et les heures par marque
    $brandFormationsMap = [];
    $brandHoursMap = [];

    // Initialiser les variables pour éviter les erreurs
    $numRecommendedTotal = 0;
    $numCompletedTotal = 0;

    foreach ($filteredTechnicians as $tech) {
        $techId = new MongoDB\BSON\ObjectId($tech['_id']);

        // Recommandées
        $numRecommended = $trainingsColl->count([
            'active'   => true,
            'level'    => ['$in' => $levels],
            'users'    => $techId,
            'brand'    => ['$ne' => '']
        ]);
        $numRecommendedTotal += $numRecommended;

        // Réalisées
        $numCompleted = $trainingsColl->count([
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
        'users'  => ['$in' => array_map(fn($t) => new MongoDB\BSON\ObjectId($t['_id']), $filteredTechnicians)],
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

    // ----------------------------------------------------------
    // 7) Passer les variables PHP au JavaScript
    // ----------------------------------------------------------
    // Préparer les données pour les graphiques
    $brandLabels = array_map(function($b) { return $b['x']; }, $brandScores);
    $averageScores = array_map(function($b) { return $b['y']; }, $brandScores);
    $trainingsCounts = array_map(function($b) { return $b['y']; }, $brandScores); // Ajuster si nécessaire

    // Calculer la somme des formations recommandées
    $sumTrainings = array_sum($trainingsCounts);

    // Assigner les données nécessaires
    $brandLogosArray = $brandLogos;

    // ----------------------------------------------------------
    // 8) Générer le HTML avec les graphiques et statistiques
    // ----------------------------------------------------------
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Manager | CFAO Mobility Academy</title>
        <!-- Inclure Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Inclure Font Awesome pour les icônes (si nécessaire) -->
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
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.0"></script>

        <!-- DataTables CSS (Optionnel pour fonctionnalités avancées) -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <style>
            /* Styles personnalisés */

            /* Style général des cartes */
            .custom-card {
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s, box-shadow 0.2s;
                cursor: pointer;
                border: none;
                /* Enlever les bordures existantes */
            }

            .custom-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            }

            /* Logos des marques */
            .brand-logo {
                width: 60px;
                height: 35px;

                margin-bottom: 0.5rem;
            }

            /* Conteneur des graphiques */
            .chart-dashboard-container {
                position: relative;
                padding: 20px;
                width: 100%;
                /* Utiliser la pleine largeur */
                box-sizing: border-box;
                margin: 0 auto;
            }

            /* Titre des graphiques avec icône */
            .chart-title {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
                font-size: 1.25rem;
                font-weight: bold;
            }

            .chart-title i {
                margin-right: 0.5rem;
                color: #198754;
                /* Couleur verte pour les icônes */
            }

            /* Responsive ajustements */
            @media (max-width: 768px) {
                .chart-title {
                    font-size: 1rem;
                }

                .brand-logo {
                    width: 40px;
                    height: 25px;
                }
            }

            /* Conteneur des logos */
            #chart-container-logo-container,
            #measure-container-logo-container {
                z-index: 10;
            }

            /* Ajustement des logos */
            #chart-container-logo-container img,
            #measure-container-logo-container img {
                transition: transform 0.2s;
            }

            #chart-container-logo-container img:hover,
            #measure-container-logo-container img:hover {
                transform: scale(1.1);
            }

            /* Canvas des graphiques */
            canvas {
                /* width: 100% !important;
    height: auto !important; */
            }

            /* Styles pour les graphiques avec scroll horizontal */
            .chart-wrapper {
                overflow-x: auto;
                overflow-y: hidden;
                white-space: nowrap;
                max-width: 100%;
                padding-bottom: 1rem;
            }

            .chart-container {
                display: inline-block;
                vertical-align: top;
                width: 800px;
                /* Ajustez cette largeur selon le nombre de marques */
            }

            /* Styles pour les statistiques */
            .stats-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 2rem;
            }

            .stats-item {
                background-color: #f8f9fa;
                padding: 1rem;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                width: 48%;
                text-align: center;
            }

            .stats-item h5 {
                margin-bottom: 0.5rem;
            }

            /* Styles conditionnels pour les badges de score */
            .badge-success {
                background-color: #28a745 !important;
            }

            .badge-warning {
                background-color: #ffc107 !important;
                color: #212529 !important;
            }

            .badge-danger {
                background-color: #dc3545 !important;
            }

            /* Styles pour les badges des niveaux */
            .badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                /* width: 10px;
                height: 10px; */
                border-radius: 50%;
                font-size: 0.75rem;
                padding: 0;
                margin-left: 5px;
            }

            /* Style du tableau */
            #scoresByLevelAndBrandTable th,
            #scoresByLevelAndBrandTable td {
                text-align: center;
                vertical-align: middle;
            }

            /* Responsive ajustements pour le tableau */
            @media (max-width: 768px) {
                #scoresByLevelAndBrandTable th,
                #scoresByLevelAndBrandTable td {
                    font-size: 0.9rem;
                    padding: 0.5rem;
                }
            }
        </style>
    </head>

    <body>
        <?php include "./partials/header.php"; ?>

        <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
            <?php if ($_SESSION["profile"] == "Manager" || $_SESSION["profile"] == "Super Admin") { ?>
                <div class="toolbar" id="kt_toolbar">
                    <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                Tableau de Bord du Manager: <i class="fas fa-user-circle text-success"></i> <?php echo "$firstName $lastName"; ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <!-- Main Content -->
                <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
                    <div class="container-xxl">
                        <!-- Filtres -->
                        <div class="row mb-4 justify-content-center">
                            <div class="col-md-4 mb-3">
                                <i class="fas fa-filter me-2 text-warning"></i>
                                <label for="level-filter" class="form-label">Filtrer par Niveau</label>
                                <select id="level-filter" class="form-select" onchange="applyFilters()">
                                    <option value="all">Tous les niveaux</option>
                                    <option value="Junior">Junior</option>
                                    <option value="Senior">Senior</option>
                                    <option value="Expert">Expert</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <i class="fas fa-filter me-2 text-warning"></i>
                                <label for="brand-filter" class="form-label">Filtrer par Marque</label>
                                <select id="brand-filter" class="form-select" onchange="applyFilters()">
                                    <option value="all">Toutes les marques</option>
                                    <?php
                                    // Récupérer toutes les marques supervisées
                                    $supervisedBrands = [];
                                    foreach ($filteredTechnicians as $tech) {
                                        // Convertir BSONArray en array
                                        $brandsJunior = is_array($tech['brandJunior']) ? $tech['brandJunior'] : $tech['brandJunior']->getArrayCopy();
                                        $brandsSenior = is_array($tech['brandSenior']) ? $tech['brandSenior'] : $tech['brandSenior']->getArrayCopy();
                                        $brandsExpert = is_array($tech['brandExpert']) ? $tech['brandExpert'] : $tech['brandExpert']->getArrayCopy();

                                        $brands = array_merge($brandsJunior, $brandsSenior, $brandsExpert);
                                        foreach ($brands as $brand) {
                                            $brand = trim($brand);
                                            if ($brand !== '' && !in_array($brand, $supervisedBrands)) {
                                                $supervisedBrands[] = $brand;
                                            }
                                        }
                                    }
                                    $uniqueBrands = array_unique($supervisedBrands);
                                    sort($uniqueBrands);
                                    foreach ($uniqueBrands as $brand) {
                                        $brandEscaped = htmlspecialchars($brand);
                                        echo "<option value='$brandEscaped'>$brandEscaped</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <i class="fas fa-filter me-2 text-warning"></i>
                                <label for="technician-filter" class="form-label">Filtrer par Technicien</label>
                                <select id="technician-filter" class="form-select" onchange="applyFilters()">
                                    <option value="all">Tous les techniciens</option>
                                    <?php
                                    foreach ($filteredTechnicians as $tech) {
                                        $techId = (string)$tech['_id'];
                                        $techName = htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']);
                                        echo "<option value='$techId'>$techName</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>

                        <!-- Marques du Manager (Affichage des logos des marques supervisées) -->
                        <div class="text-center mb-4">
                            <h5 class="mb-3">Marques Supervisées par Votre Équipe :</h5>
                            <div class="row justify-content-center">
                                <?php
                                // Récupérer toutes les marques supervisées par l'équipe sans doublons
                                $supervisedBrands = [];
                                foreach ($filteredTechnicians as $tech) {
                                    // Convertir BSONArray en array
                                    $brandsJunior = is_array($tech['brandJunior']) ? $tech['brandJunior'] : $tech['brandJunior']->getArrayCopy();
                                    $brandsSenior = is_array($tech['brandSenior']) ? $tech['brandSenior'] : $tech['brandSenior']->getArrayCopy();
                                    $brandsExpert = is_array($tech['brandExpert']) ? $tech['brandExpert'] : $tech['brandExpert']->getArrayCopy();

                                    $brands = array_merge($brandsJunior, $brandsSenior, $brandsExpert);
                                    foreach ($brands as $brand) {
                                        $brand = trim($brand);
                                        if ($brand !== '' && !in_array($brand, $supervisedBrands)) {
                                            $supervisedBrands[] = $brand;
                                        }
                                    }
                                }
                                sort($supervisedBrands); // Trier les marques si nécessaire

                                // Afficher les logos
                                if (!empty($supervisedBrands)) {
                                    foreach ($supervisedBrands as $brand) {
                                        $logoSrc = isset($brandLogos[$brand]) ? "brands/" . $brandLogos[$brand] : "brands/default.png";
                                        echo "<div class='col-6 col-sm-4 col-md-3 col-lg-2 mb-4'>";
                                        echo "<div class='card custom-card h-100'>";
                                        echo "<div class='card-body d-flex flex-column justify-content-center align-items-center'>";
                                        echo "<img src='$logoSrc' alt='$brand Logo' class='img-fluid brand-logo' aria-label='Logo $brand'>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<span class='badge bg-secondary me-2 mb-2'>Aucune marque supervisée</span>";
                                }
                                ?>
                            </div>
                        </div>

                        <hr><br>

                        <!-- Section Graphique -->
                        <div class="chart-dashboard-container">
                            <!-- Graphique 1: Moyenne des Scores de l'Équipe par Marque -->
                            <div class="row align-items-center mb-4">
                                <!-- Carte d'explication à gauche avec collapse -->
                                <div class="col-lg-3 mb-4 mb-lg-0">
                                    <div class="card custom-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <!-- Bouton de toggle -->
                                            <button class="btn btn-sm btn-outline-secondary toggle-info" type="button" data-bs-toggle="collapse" data-bs-target="#infoAverageScores" aria-expanded="true" aria-controls="infoAverageScores">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <div id="infoAverageScores" class="collapse show">
                                            <div class="chart-title">
                                                <i class="fas fa-chart-line"></i>
                                                <span>Moyenne des Scores de l'Équipe par Marque</span>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Visualisez la moyenne des scores de votre équipe de techniciens pour chaque marque supervisée.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Graphique à droite -->
                                <div class="col-lg-9">
                                    <div class="card custom-card h-100">
                                        <div class="card-body">
                                            <div class="chart-wrapper">
                                                <canvas id="averageScoresChart" aria-label="Graphique des Moyennes des Scores par Marque" role="img"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Graphique 2: Nombre de Formations Recommandées par Marque -->
                            <div class="row align-items-center mb-4">
                                <!-- Carte d'explication à gauche avec collapse -->
                                <div class="col-lg-3 mb-4 mb-lg-0">
                                    <div class="card custom-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <!-- Bouton de toggle -->
                                            <button class="btn btn-sm btn-outline-secondary toggle-info" type="button" data-bs-toggle="collapse" data-bs-target="#infoTrainingsRecommended" aria-expanded="true" aria-controls="infoTrainingsRecommended">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <div id="infoTrainingsRecommended" class="collapse show">
                                            <div class="chart-title">
                                                <i class="fas fa-chart-bar"></i>
                                                <span>Nombre de Formations Recommandées par Marque</span>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">Visualisez le nombre total de formations recommandées pour chaque marque supervisée par votre équipe.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Graphique à droite -->
                                <div class="col-lg-9">
                                    <div class="card custom-card h-100">
                                        <div class="card-body">
                                            <div class="chart-wrapper">
                                                <canvas id="trainingsRecommendedChart" aria-label="Graphique des Formations Recommandées par Marque" role="img"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistiques Globales -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="stats-container">
                                        <div class="stats-item">
                                            <h5>Somme des Formations Recommandées</h5>
                                            <p id="sumTrainings">0</p>
                                        </div>
                                        <div class="stats-item">
                                            <h5>Volume Journalier Correspondant</h5>
                                            <p id="dailyVolume">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Tableau des Scores Factuel et Déclaratif par Niveau et par Marque -->
                        <div class="container my-5">
                            <h3 class="mb-4">Scores Factuel et Déclaratif par Niveau et par Marque</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="scoresByLevelAndBrandTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Technicien</th>
                                            <th>Niveau</th>
                                            <th>Marque</th>
                                            <th>Factuel (%)</th>
                                            <th>Déclaratif (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($aggregatedScoresByTechnician as $techId => $levelsScores): ?>
                                            <?php
                                                // Récupérer les informations du technicien
                                                $technician = null;
                                                foreach ($filteredTechnicians as $t) {
                                                    if ((string)$t['_id'] === $techId) {
                                                        $technician = $t;
                                                        break;
                                                    }
                                                }
                                                if (!$technician) continue;
                                                $techName = htmlspecialchars($technician['firstName'] . ' ' . $technician['lastName']);
                                                
                                                // Obtenir le niveau principal
                                                $primaryLevel = getPrimaryLevel($technician);
                                                $levelColorClass = isset($levelColors[$primaryLevel]) ? "bg-{$levelColors[$primaryLevel]}" : 'bg-secondary';
                                                
                                                // Récupérer le profil et le rôle
                                                $profile = htmlspecialchars($technician['profile'] ?? 'N/A');
                                                $role = htmlspecialchars($technician['role'] ?? 'N/A');
                                                
                                                // Compter le nombre de lignes pour le rowspan
                                                $totalRows = 0;
                                                foreach ($levelsScores as $level => $brandsScores) {
                                                    $totalRows += count($brandsScores);
                                                }
                                                $currentRow = 0;
                                            ?>
                                            <?php foreach ($levelsScores as $level => $brandsScores): ?>
                                                <?php foreach ($brandsScores as $brand => $scoreData): ?>
                                                    <tr>
                                                        <?php if ($currentRow === 0): ?>
                                                            <td rowspan="<?php echo $totalRows; ?>" class="align-middle">
                                                                <?php echo $techName; ?>
                                                                <!-- Puce colorée représentant le niveau -->
                                                                <span class="badge <?php echo $levelColorClass; ?> ms-2" style="width: 10px; height: 10px; border-radius: 50%; display: inline-block;"></span>
                                                                <!-- Informations supplémentaires -->
                                                                <?php echo " $primaryLevel | Profile: $profile | Role: $role"; ?>
                                                            </td>
                                                        <?php endif; ?>
                                                        <td><?php echo htmlspecialchars($level); ?></td>
                                                        <td><?php echo htmlspecialchars($brand); ?></td>
                                                        <td>
                                                            <?php 
                                                                $factuel = is_numeric($scoreData['Factuel']) ? $scoreData['Factuel'] : null;
                                                                if ($factuel !== null) {
                                                                    if ($factuel >= 80) {
                                                                        echo "<span class='badge bg-success'>{$factuel}%</span>";
                                                                    } elseif ($factuel >= 60) {
                                                                        echo "<span class='badge bg-warning text-dark'>{$factuel}%</span>";
                                                                    } else {
                                                                        echo "<span class='badge bg-danger'>{$factuel}%</span>";
                                                                    }
                                                                } else {
                                                                    echo 'N/A';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                $declaratif = is_numeric($scoreData['Declaratif']) ? $scoreData['Declaratif'] : null;
                                                                if ($declaratif !== null) {
                                                                    if ($declaratif >= 80) {
                                                                        echo "<span class='badge bg-success'>{$declaratif}%</span>";
                                                                    } elseif ($declaratif >= 60) {
                                                                        echo "<span class='badge bg-warning text-dark'>{$declaratif}%</span>";
                                                                    } else {
                                                                        echo "<span class='badge bg-danger'>{$declaratif}%</span>";
                                                                    }
                                                                } else {
                                                                    echo 'N/A';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php $currentRow++; ?>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Section de Débogage (Optionnelle) -->
                        <?php if (!empty($debug)) { ?>
                            <div class="row">
                                <div class="col-12">
                                    <h4>Informations de Débogage</h4>
                                    <div class="alert alert-warning" role="alert">
                                        <?php
                                        foreach ($debug as $message) {
                                            echo htmlspecialchars($message) . "<br>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php include "./partials/footer.php"; ?>

        <!-- Scripts JavaScript -->
        <script>
            // Enregistrer les plugins Chart.js
            Chart.register(ChartDataLabels, Chart.Zoom);
        </script>

        <!-- Passer les variables PHP au JavaScript -->
        <script>
            const variablesPHP = <?php 
                echo json_encode([
                    'brandLabels' => $brandLabels ?? [],
                    'averageScores' => $averageScores ?? [],
                    'trainingsCounts' => $trainingsCounts ?? [],
                    'brandLogos' => $brandLogos,
                    'sumTrainings' => isset($sumTrainings) ? $sumTrainings : 0,
                    'dailyVolume' => isset($totalDays) ? $totalDays : 0
                ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); 
            ?>;
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Sélectionnez tous les boutons de toggle
                const toggleButtons = document.querySelectorAll('.toggle-info');

                toggleButtons.forEach(button => {
                    const target = document.querySelector(button.getAttribute('data-bs-target'));
                    const icon = button.querySelector('i');

                    // Écouter les événements de collapse
                    target.addEventListener('hidden.bs.collapse', () => {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-info-circle');
                    });

                    target.addEventListener('shown.bs.collapse', () => {
                        icon.classList.remove('fa-info-circle');
                        icon.classList.add('fa-minus');
                    });
                });

                // Récupérer les données PHP
                const brandLabels = variablesPHP.brandLabels;
                const averageScores = variablesPHP.averageScores;
                const trainingsCounts = variablesPHP.trainingsCounts;
                const brandLogos = variablesPHP.brandLogos;
                const sumTrainings = variablesPHP.sumTrainings;
                const dailyVolume = variablesPHP.dailyVolume;

                // Vérifiez si variablesPHP est correctement défini
                if (!variablesPHP || !brandLabels || !averageScores || !trainingsCounts || !brandLogos) {
                    console.error("Les variablesPHP ne sont pas correctement définies.");
                    return;
                }

                // Préparer les données pour le Graphique 1: Moyenne des Scores de l'Équipe par Marque
                const ctxAverageScores = document.getElementById('averageScoresChart').getContext('2d');
                const averageScoresChart = new Chart(ctxAverageScores, {
                    type: 'scatter', // Utiliser Scatter pour positionner les cercles
                    data: {
                        datasets: [{
                            label: 'Moyenne des Scores',
                            data: brandLabels.map((brand, index) => ({
                                x: index + 1, // Position sur l'axe X
                                y: averageScores[index]
                            })),
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            pointRadius: 10,
                            pointHoverRadius: 12
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const index = context.dataIndex;
                                        const count = variablesPHP.trainingsCounts[index];
                                        return `Moyenne: ${context.parsed.y.toFixed(2)} | Formations: ${count}`;
                                    }
                                }
                            },
                            legend: {
                                display: false
                            },
                            datalabels: {
                                display: false
                            },
                            zoom: {
                                pan: {
                                    enabled: true,
                                    mode: 'x'
                                },
                                zoom: {
                                    wheel: {
                                        enabled: true
                                    },
                                    mode: 'x'
                                }
                            }
                        },
                        scales: {
                            x: {
                                type: 'linear',
                                position: 'bottom',
                                ticks: {
                                    stepSize: 1,
                                    callback: function (value, index, values) {
                                        if (value >= 1 && value <= brandLabels.length) {
                                            return brandLabels[value - 1];
                                        }
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Marques'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Moyenne des Scores'
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Préparer les données pour le Graphique 2: Nombre de Formations Recommandées par Marque
                const ctxTrainingsRecommended = document.getElementById('trainingsRecommendedChart').getContext('2d');
                const trainingsRecommendedChart = new Chart(ctxTrainingsRecommended, {
                    type: 'bar',
                    data: {
                        labels: brandLabels,
                        datasets: [{
                            label: 'Formations Recommandées',
                            data: trainingsCounts,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                color: '#000',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: function (value) {
                                    return value;
                                }
                            },
                            tooltip: {
                                enabled: true
                            },
                            legend: {
                                display: false
                            },
                            zoom: {
                                pan: {
                                    enabled: true,
                                    mode: 'x'
                                },
                                zoom: {
                                    wheel: {
                                        enabled: true
                                    },
                                    mode: 'x'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Nombre de Formations'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Marques'
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Mettre à jour les statistiques
                document.getElementById('sumTrainings').innerText = sumTrainings;
                document.getElementById('dailyVolume').innerText = dailyVolume;
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialiser les tooltips (si vous utilisez des tooltips dans les badges)
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            });
        </script>

        <script>
            // Fonction pour appliquer les filtres et mettre à jour l'URL
            function applyFilters() {
                const level = document.getElementById('level-filter').value;
                const brand = document.getElementById('brand-filter').value;
                const technician = document.getElementById('technician-filter').value;

                // Construire l'URL avec les paramètres de filtre
                let url = `dashboardman2.php?managerId=<?php echo htmlspecialchars($managerId); ?>`;

                if (level !== 'all') {
                    url += `&level=${encodeURIComponent(level)}`;
                }
                if (brand !== 'all') {
                    url += `&brand=${encodeURIComponent(brand)}`;
                }
                if (technician !== 'all') {
                    url += `&technicianId=${encodeURIComponent(technician)}`;
                }

                // Rediriger vers l'URL filtrée
                window.location.href = url;
            }
        </script>
    </body>

    </html>
    <?php
}
?>