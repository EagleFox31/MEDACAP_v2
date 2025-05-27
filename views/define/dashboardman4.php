<?php
session_start();

// ----------------------------------------------------------
// 1) Vérifier session / profil, puis connexion MongoDB
// ----------------------------------------------------------
if (!isset($_SESSION["profile"])) {
    header("Location: /");
    exit();
} else {
    // Allow access if the user is a Manager or Super Admin
    if ($_SESSION["profile"] !== 'Manager' && $_SESSION["profile"] !== 'Super Admin') {
        echo "Accès refusé.";
        exit();
    }
    if ($_SESSION["profile"] === 'Super Admin') {
        // Super Admin can specify managerId via GET
        if (isset($_GET['managerId'])) {
            $managerId = $_GET['managerId'];
        } else {
            echo "Paramètre managerId requis pour les Super Admin.";
            exit();
        }
    } else {
        // Manager can only view their own dashboard
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
    // 2) Charger la config GF (groupes fonctionnels) et ScoreCalculator
    // ----------------------------------------------------------
    $config = require __DIR__ . "/configGF.php";
    require_once __DIR__ . "/ScoreFunctions.php";

    // ----------------------------------------------------------
    // 3) Récupérer les données des managers et techniciens
    // ----------------------------------------------------------
    require_once __DIR__ . "/getPureManagersAndTechScores.php"; // Supposé exister
    require_once __DIR__ . "/getTotalTrainingsForTeam.php"; // Supposé exister

    $allManagersData = getPureManagersAndScores($academy);

    // Filtrer les données pour le manager actuel
    $filtered = array_filter($allManagersData, function ($m) use ($managerId) {
        return (string)$m['managerId'] === (string)$managerId;
    });

    if (empty($filtered)) {
        echo "Aucun manager pur ne correspond à cet ID. (managerId=$managerId)";
        exit();
    }

    // Correction de l'erreur PHP : assigner à une variable avant d'accéder à [0]
    $filteredValues = array_values($filtered);
    if (isset($filteredValues[0])) {
        $managerScores = $filteredValues[0];
    } else {
        echo "Aucun manager pur ne correspond à cet ID. (managerId=$managerId)";
        exit();
    }

    $managerName   = htmlspecialchars($managerScores['managerName'] ?? "Manager Inconnu");
    $technicians   = $managerScores['technicians'] ?? [];

    // ----------------------------------------------------------
    // 4) Filtres : level, brand, technician
    // ----------------------------------------------------------
    $filterLevel      = isset($_GET['level'])        ? trim($_GET['level']) : 'all';
    $filterBrand      = isset($_GET['brand'])        ? trim($_GET['brand']) : 'all';
    $filterTechnician = isset($_GET['technicianId']) ? trim($_GET['technicianId']) : 'all';

    function matchesMarque($tech, $filterBrand)
    {
        if ($filterBrand === 'all') return true;
        $allBrands = array_merge(
            $tech['brandJunior'] ?? [],
            $tech['brandSenior'] ?? [],
            $tech['brandExpert'] ?? []
        );
        foreach ($allBrands as $b) {
            if (strcasecmp($b, $filterBrand) === 0) {
                return true;
            }
        }
        return false;
    }

    // Appliquer les filtres en PHP
    $filteredTechs = [];
    foreach ($technicians as $t) {
        $tid = $t['technicianId'];

        // Filtre technicianId
        if ($filterTechnician !== 'all' && $tid !== $filterTechnician) {
            continue;
        }
        // Filtre brand
        if (!matchesMarque($t, $filterBrand)) {
            continue;
        }
        // Filtre level
        if ($filterLevel !== 'all') {
            $hasLevel = false;
            foreach ($t['scores'] as $lvlBlock) {
                if (strcasecmp($lvlBlock['level'], $filterLevel) === 0 && !empty($lvlBlock['specialities'])) {
                    $hasLevel = true;
                    break;
                }
            }
            if (!$hasLevel) {
                continue;
            }
        }

        $filteredTechs[] = $t;
    }
    $technicians = $filteredTechs;

    // ----------------------------------------------------------
    // 5) Extraire la liste des marques (teamBrands)
    // ----------------------------------------------------------
    $teamBrands = [];
    foreach ($technicians as $t) {
        foreach (['brandJunior', 'brandSenior', 'brandExpert'] as $bf) {
            if (!empty($t[$bf]) && is_array($t[$bf])) {
                foreach ($t[$bf] as $b) {
                    $bTrim = trim($b);
                    if ($bTrim !== '' && !in_array($bTrim, $teamBrands)) {
                        $teamBrands[] = $bTrim;
                    }
                }
            }
        }
    }
    sort($teamBrands);

    // ----------------------------------------------------------
    // 6) Calculer la Moy. Globale Factuel+Déclaratif par Marque
    // ----------------------------------------------------------
    function getSupportedGroupsForBrand($brand, $level, $config)
    {
        // On part de functionalGroupsByLevel[$level], puis on retire 
        // tout ce qui est "nonSupportedGroupsByBrand[$brand]"
        $all = $config['functionalGroupsByLevel'][$level] ?? [];

        $nonSupp = $config['nonSupportedGroupsByBrand'][$brand][$level] ?? [];

        return array_values(array_diff($all, $nonSupp));
    }

    function getAverageScoresByBrand(array $technicians, array $config, array $teamBrands, array $levels)
    {
        $acc = [];
        foreach ($teamBrands as $b) {
            $acc[$b] = ['sum' => 0, 'count' => 0];
        }
        foreach ($technicians as $tech) {
            foreach ($tech['scores'] as $lvlBlock) {
                $level = $lvlBlock['level'];
                if (!in_array($level, $levels)) continue; // Utiliser les niveaux définis
                $brandField = 'brand' . ucfirst(strtolower($level));
                if (empty($tech[$brandField])) continue;
                foreach ($tech[$brandField] as $brand) {
                    if (!in_array($brand, $teamBrands)) continue;
                    $supported = getSupportedGroupsForBrand($brand, $level, $config);
                    foreach ($lvlBlock['specialities'] as $spec) {
                        $spName = $spec['speciality'];
                        if (!in_array($spName, $supported)) continue;
                        $f = $spec['factuelScore']    ?? null;
                        $d = $spec['declaratifScore'] ?? null;
                        if (is_numeric($f) && is_numeric($d)) {
                            $acc[$brand]['sum']   += ($f + $d) / 2.0;
                            $acc[$brand]['count'] += 1;
                        } elseif (is_numeric($f)) {
                            $acc[$brand]['sum']   += $f;
                            $acc[$brand]['count'] += 1;
                        } elseif (is_numeric($d)) {
                            $acc[$brand]['sum']   += $d;
                            $acc[$brand]['count'] += 1;
                        }
                    }
                }
            }
        }

        $brandScores = [];
        foreach ($acc as $b => $val) {
            if ($val['count'] > 0) {
                $moy = round($val['sum'] / $val['count']);
            } else {
                $moy = null;
            }
            $c = '#6c757d'; // Gris par défaut
            if ($moy !== null) {
                if ($moy >= 80) {
                    $c = '#198754'; // Vert
                } elseif ($moy >= 60) {
                    $c = '#ffc107'; // Jaune
                } else {
                    $c = '#dc3545'; // Rouge
                }
            }
            $brandScores[] = [
                'x'         => $b,
                'y'         => $moy,
                'fillColor' => $c
            ];
        }
        return $brandScores;
    }
    // Définir les niveaux à inclure
    if ($filterLevel === 'all') {
        $levels = ['Junior', 'Senior', 'Expert']; // Adaptez selon vos besoins
    } else {
        $levels = [$filterLevel];
    }
    $brandScores = getAverageScoresByBrand($technicians, $config, $teamBrands, $levels);

    // ----------------------------------------------------
    // 7) Nombre de Formations par Marque
    // ----------------------------------------------------
    $techIdsObj = [];
    foreach ($technicians as $t) {
        try {
            $techIdsObj[] = new MongoDB\BSON\ObjectId($t['technicianId']);
        } catch (\Exception $e) {
            // Gérer l'exception si nécessaire
        }
    }

    $trainingsCounts = [];
    if (!empty($techIdsObj)) {
        $trainingsColl = $academy->trainings;
        $pipeline = [
            [
                '$match' => [
                    'active' => true,
                    'users'  => ['$in' => $techIdsObj],
                    'brand'  => ['$ne' => '']
                ]
            ],
            [
                '$group' => [
                    '_id'   => '$brand',
                    'count' => ['$sum' => 1]
                ]
            ]
        ];
        try {
            $results = $trainingsColl->aggregate($pipeline);
            foreach ($results as $doc) {
                $b = (string)$doc->_id;
                $trainingsCounts[$b] = (int)$doc->count;
            }
        } catch (MongoDB\Exception\Exception $e) {
            echo "Erreur lors de l'agrégation des formations par marque : " . htmlspecialchars($e->getMessage());
            exit();
        }
    }

    // ----------------------------------------------------
    // 7bis) Calculer le Nombre Total de Formations Recommandées pour l'Équipe
    // ----------------------------------------------------

    // Définir une fonction pour compter les formations recommandées pour l'équipe
    function countRecommendedTrainingsTeam($trainingsColl, array $technicianIds, array $levels) {
        $query = [
            'active' => true,
            'level' => ['$in' => $levels],
            'users' => ['$in' => $technicianIds],
            'brand' => ['$ne' => ''] // Exclure les marques vides
        ];
        return $trainingsColl->count($query);
    }

    // Définir les niveaux à inclure (ajustez selon vos besoins)
    // Déjà défini précédemment comme $levels

    // Calculer le nombre total de formations recommandées pour l'équipe
    $numRecommendedTeam = 0;
    if (!empty($techIdsObj)) {
        try {
            $numRecommendedTeam = countRecommendedTrainingsTeam($trainingsColl, $techIdsObj, $levels);
        } catch (MongoDB\Exception\Exception $e) {
            echo "Erreur lors du calcul des formations recommandées : " . htmlspecialchars($e->getMessage());
            exit();
        }
    }

    // ----------------------------------------------------
    // 8) Compter les Formations par Technicien et Totaliser
    // ----------------------------------------------------
    $totalTrainingsForTeam = 0;
    $trainingsPerTechnician = [];

    if (!empty($techIdsObj)) {
        $countResults = getTotalTrainingsForTeam($trainingsColl, $techIdsObj);
        if ($countResults !== false) {
            $totalTrainingsForTeam = $countResults['totalTrainings'];
            $trainingsPerTechnician = $countResults['trainingsPerTechnician'];
        } else {
            echo "Erreur lors du comptage des formations par technicien.";
            exit();
        }
    }

    // ----------------------------------------------------
    // 9) Logos
    // ----------------------------------------------------
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
        // Ajoutez d'autres marques si nécessaire
    ];

    // ----------------------------------------------------
    // 10) AFFICHAGE HTML
    // ----------------------------------------------------

    // Ajouter les fonctions PHP pour les statistiques
    function calculateTotalAverage($scores)
    {
        $total = 0;
        $count = 0;
        foreach ($scores as $s) {
            if ($s['y'] !== null) {
                $total += $s['y'];
                $count += 1;
            }
        }
        return $count > 0 ? round($total / $count) : 'N/A';
    }

    function calculateTotalTrainings($trainings)
    {
        $total = 0;
        foreach ($trainings as $count) {
            $total += $count;
        }
        return $total;
    }

    function calculateTotalHours($trainings)
    {
        // Supposons que chaque formation représente un jour de 8 heures
        $totalJours = calculateTotalTrainings($trainings);
        $totalHeures = $totalJours * 8; // 1 jour = 8 heures
        return $totalHeures;
    }
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
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
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
        #trainingsScatterCanvas-logo-container {
            z-index: 10;
        }

        /* Ajustement des logos */
        #chart-container-logo-container img,
        #trainingsScatterCanvas-logo-container img {
            transition: transform 0.2s;
        }

        #chart-container-logo-container img:hover,
        #trainingsScatterCanvas-logo-container img:hover {
            transform: scale(1.1);
        }

        /* Canvas des graphiques */
        canvas {
            /* width: 100% !important;
        height: auto !important; */
        }

        /* Centrage des graphiques */
        .chart-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        /* Style pour les boutons de collapse */
        .toggle-info {
            margin-right: 0.5rem;
            background: none;
            border: none;
            color: #000;
        }

        .toggle-info:focus {
            outline: none;
        }

        /* Style spécifique pour les cartes rétractables */
        .collapse-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
            /* Hauteur fixe pour le bouton */
        }

        /* Position de l'icône (i) */
        .collapse-card button {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
        }

        /* Suppression des bordures internes */
        .card-body {
            border: none;
            padding: 1rem;
        }

        /* Style des cartes de statistiques */
        .stat-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .stat-card h5 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #6c757d;
        }

        .stat-card p {
            font-size: 1.25rem;
            font-weight: bold;
        }

        /* Conteneur de graphique avec défilement horizontal */
        .scrollable-chart-container {
            overflow-x: auto;
            white-space: nowrap;
            width: 100%;
            padding-bottom: 25px;
            /* Pour éviter que le scrollbar ne chevauche le graphique */
        }

        .scrollable-chart-container canvas {
            width: 100% !important;
            min-width: 800px;
            /* Largeur minimale pour garantir la lisibilité */
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
                            Tableau de Bord du Manager: <i class="fas fa-user-circle text-success"></i> <?php echo $managerName; ?>
                        </h1>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
                <div class="container-xxl">
                    <!-- Filtres -->
                    <div class="row mb-4 justify-content-center">
                        <!-- Filtre de Niveau Dynamique -->
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-filter me-2 text-warning"></i>
                            <label class="form-label">Filtrer par Niveau</label>
                            <select id="level-filter" class="form-select">
                                <option value="all" <?php if ($filterLevel === 'all') echo 'selected'; ?>>Tous</option>
                                <?php
                                // Extraire les niveaux disponibles parmi les techniciens filtrés
                                $availableLevels = [];
                                foreach ($technicians as $t) {
                                    foreach ($t['scores'] as $lvlBlock) {
                                        $lvl = $lvlBlock['level'];
                                        if (!in_array($lvl, $availableLevels)) {
                                            $availableLevels[] = $lvl;
                                        }
                                    }
                                }
                                sort($availableLevels); // Trier les niveaux par ordre alphabétique ou selon une logique spécifique
                                foreach ($availableLevels as $lvl) {
                                    $selected = ($filterLevel === $lvl) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($lvl) . "' $selected>" . htmlspecialchars($lvl) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Filtre de Marque -->
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-filter me-2 text-warning"></i>
                            <label class="form-label">Filtrer par Marque</label>
                            <select id="brand-filter" class="form-select">
                                <option value="all" <?php if ($filterBrand === 'all') echo 'selected'; ?>>Toutes</option>
                                <?php foreach ($teamBrands as $b): ?>
                                    <option value="<?php echo htmlspecialchars($b); ?>"
                                        <?php if ($filterBrand === $b) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($b); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Filtre de Technicien -->
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-filter me-2 text-warning"></i>
                            <label class="form-label">Filtrer par Technicien</label>
                            <select id="technician-filter" class="form-select">
                                <option value="all" <?php if ($filterTechnician === 'all') echo 'selected'; ?>>
                                    Tous
                                </option>
                                <?php foreach ($technicians as $t): ?>
                                    <option value="<?php echo htmlspecialchars($t['technicianId']); ?>"
                                        <?php if ($filterTechnician === $t['technicianId']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($t['technicianName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <script>
                        function applyFilters() {
                            const url = new URL(window.location.href);
                            url.searchParams.set('managerId', '<?php echo htmlspecialchars($managerId); ?>');

                            const lvl = document.getElementById('level-filter').value;
                            if (lvl === 'all') {
                                url.searchParams.delete('level');
                            } else {
                                url.searchParams.set('level', lvl);
                            }

                            const br = document.getElementById('brand-filter').value;
                            if (br === 'all') {
                                url.searchParams.delete('brand');
                            } else {
                                url.searchParams.set('brand', br);
                            }

                            const tch = document.getElementById('technician-filter').value;
                            if (tch === 'all') {
                                url.searchParams.delete('technicianId');
                            } else {
                                url.searchParams.set('technicianId', tch);
                            }

                            window.location.href = url.toString();
                        }
                        document.getElementById('level-filter').addEventListener('change', applyFilters);
                        document.getElementById('brand-filter').addEventListener('change', applyFilters);
                        document.getElementById('technician-filter').addEventListener('change', applyFilters);
                    </script>

                    <hr>

                    <!-- Marques (logos) -->
                    <h5 class="text-center mb-4">Marques Présentes dans l'Équipe (Filtrée)</h5>
                    <div class="row mb-4 justify-content-center">
                        <?php if (!empty($teamBrands)): ?>
                            <?php foreach ($teamBrands as $b): ?>
                                <?php
                                $logoSrc = isset($brandLogos[$b]) ? 'brands/' . $brandLogos[$b] : 'brands/default.png';
                                ?>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                                    <div class="card custom-card h-100">
                                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                            <img src="<?php echo $logoSrc; ?>" alt="Logo <?php echo htmlspecialchars($b); ?>"
                                                class="img-fluid brand-logo" aria-label="Logo <?php echo htmlspecialchars($b); ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Aucune marque trouvée.</p>
                        <?php endif; ?>
                    </div>
                    <hr>

                    <div class="chart-dashboard-container">

                        <!-- Graphique 1: Résultats aux Tests par Marque -->
                        <div class="row align-items-center mb-4">
                            <!-- Carte d'explication à gauche -->
                            <div class="col-lg-3 mb-4 mb-lg-0">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="chart-title">
                                            <i class="fas fa-chart-bar"></i>
                                            <span>Résultats aux Tests</span>
                                        </div>
                                        <p class="card-text">Visualisez les scores moyens par marque pour votre équipe.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Graphique à droite -->
                            <div class="col-lg-9">
                                <div class="card custom-card h-100">
                                    <div class="card-body">
                                        <div class="row mb-3 justify-content-center">
                                            <div id="chart-container" class="w-100 mb-4">
                                                <h3 class="text-center mb-4">1. Résultats aux Tests par Marque</h3>

                                                <!-- Conteneur du graphique avec défilement horizontal -->
                                                <div class="scrollable-chart-container">
                                                    <canvas id="scoreScatterCanvas" aria-label="Graphique des Résultats aux Tests par Marque" role="img" height="500"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Graphique 2: Plans de Formations de l'équipe par Marque -->
                        <div class="row align-items-center mb-4">
                            <!-- Carte d'explication à gauche -->
                            <div class="col-lg-3 mb-4 mb-lg-0">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="chart-title">
                                            <i class="fas fa-chart-line"></i>
                                            <span>Plan de Formation</span>
                                        </div>
                                        <p class="card-text">Visualisez le nombre de formations et les heures de formation par marque.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Graphique à droite -->
                            <div class="col-lg-9">
                                <div class="card custom-card h-100">
                                    <div class="card-body">
                                        <div class="row mb-3 justify-content-center">
                                            <div id="chart-container" class="w-100 mb-4">
                                                <h3 class="text-center mb-4">2. Plans de Formations de l'équipe par Marque</h3>
                                                <div class="row justify-content-center">
                                                    <!-- Carte Total Formations de l'Équipe -->
                                                    <div class="row mb-4 justify-content-center">
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card shadow h-100 custom-card text-center">
                                                                <div class="card-body">
                                                                    <i class="fas fa-tasks fa-2x text-info mb-2"></i>
                                                                    <p class="fs-3 fw-bold"><?php echo htmlspecialchars($numRecommendedTeam); ?></p>
                                                                    <h5 class="card-title">Modules de Formation</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card shadow h-100 custom-card text-center">
                                                                <div class="card-body">
                                                                    <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                                                                    <p class="fs-3 fw-bold"><?php echo htmlspecialchars(calculateTotalHours($trainingsCounts)); ?></p>
                                                                    <h5 class="card-title">Jours de Formation</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Conteneur du graphique avec défilement horizontal -->
                                                <div class="scrollable-chart-container">
                                                    <canvas id="trainingsScatterCanvas" height="500" aria-label="Scatter Formations Équipe" role="img"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>

    </div><!-- .chart-dashboard-container -->

    <hr>

    <!-- (10) Tableau COMMENTÉ -->
    <!-- 
                <h3 class="mb-4">Détails Scores (Factuel / Déclaratif) par Technicien</h3>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped text-center">
                    <thead><tr><th>Technicien</th><th>Niveau</th><th>Marque</th><th>Factuel</th><th>Déclaratif</th></tr></thead>
                    <tbody>
                    <?php /*
                    foreach($technicians as $tech) {
                      // ... votre code ...
                    }
                    */ ?>
                    </tbody>
                  </table>
                </div>
                -->
    </div><!-- .container-xxl -->
    </div><!-- .post -->
<?php } ?>
</div><!-- .content -->

<?php
include "./partials/footer.php";
?>

<!-- Scripts JavaScript -->
<script>
    // Enregistrer les plugins Chart.js Datalabels et Zoom
    Chart.register(ChartDataLabels, ChartZoom);
</script>

<!-- Passer les variables PHP au JavaScript -->
<script>
    const variablesPHP = {
        brandScores: <?php echo json_encode($brandScores, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        trainingsCounts: <?php echo json_encode($trainingsCounts, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        brandLogos: <?php echo json_encode($brandLogos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        teamBrands: <?php echo json_encode($teamBrands, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        numRecommendedTeam: <?php echo json_encode($numRecommendedTeam); ?>,
        totalTrainingsForTeam: <?php echo json_encode(calculateTotalHours($trainingsCounts)); ?>,
        trainingsPerTechnician: <?php echo json_encode($trainingsPerTechnician, JSON_HEX_APOS | JSON_HEX_QUOT); ?>
    };

    // Afficher les variables dans la console du navigateur
    console.log("Variables PHP dans JS:", variablesPHP);
</script>

<script>
    // Fonction pour dessiner les logos sur les graphiques
    function drawLogos(chart, containerId, specificLabels) {
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

        const shiftRight = 25;

        // Boucler sur les labels spécifiques pour placer les logos
        specificLabels.forEach((label, index) => {
            const xPos = xScale.getPixelForValue(index);
            let yPos;

            if (containerId === 'scoreScatterCanvas') {
                yPos = chartArea.bottom + 80; // Ajuster selon les besoins
            } else if (containerId === 'trainingsScatterCanvas') {
                yPos = chartArea.bottom + 240; // Ajuster selon les besoins
            } else {
                yPos = chartArea.bottom + 10; // Valeur par défaut
            }

            // Créer l'élément image
            const img = document.createElement('img');
            img.src = variablesPHP.brandLogos[label] ? `brands/${variablesPHP.brandLogos[label]}` : `brands/default.png`;
            img.style.position = 'absolute';
            img.style.left = (xPos - 22 + shiftRight) + 'px'; // Centrer l'image (ajusté pour 60px de largeur)
            img.style.top = yPos + 'px';
            img.style.width = '60px';
            img.style.height = '35px';
            img.onerror = function() {
                console.error(`Erreur de chargement de l'image : ${img.src}`);
                img.src = 'brands/default.png';
            };

            // Ajouter l'image au conteneur
            logoContainer.appendChild(img);
        });

        // Ajouter le conteneur au parent
        const chartContainer = document.getElementById(containerId).parentElement;
        chartContainer.appendChild(logoContainer);
    }

    const imagePluginCombined = {
        id: 'imagePluginCombined',
        afterRender: (chart) => {
            let labels = [];
            let containerId = '';

            // Identifier le graphique en fonction de l'ID du canvas
            switch (chart.canvas.id) {
                case 'scoreScatterCanvas':
                    labels = variablesPHP.brandScores.map(obj => obj.x);
                    containerId = 'scoreScatterCanvas';
                    break;
                case 'trainingsScatterCanvas':
                    labels = Object.keys(variablesPHP.trainingsCounts);
                    containerId = 'trainingsScatterCanvas';
                    break;
                default:
                    // Si d'autres graphiques utilisent ce plugin, gérer ici
                    return;
            }

            drawLogos(chart, containerId, labels);
        },
        afterResize: (chart) => {
            let containerId = '';

            // Identifier le graphique en fonction de l'ID du canvas
            switch (chart.canvas.id) {
                case 'scoreScatterCanvas':
                    containerId = 'scoreScatterCanvas';
                    break;
                case 'trainingsScatterCanvas':
                    containerId = 'trainingsScatterCanvas';
                    break;
                default:
                    // Si d'autres graphiques utilisent ce plugin, gérer ici
                    return;
            }

            // Supprimer l'ancien conteneur de logos
            const logoContainer = document.getElementById(`${containerId}-logo-container`);
            if (logoContainer) logoContainer.remove();

            // Récupérer les labels en fonction du graphique
            let labels = [];
            if (containerId === 'scoreScatterCanvas') {
                labels = variablesPHP.brandScores.map(obj => obj.x);
            } else if (containerId === 'trainingsScatterCanvas') {
                labels = Object.keys(variablesPHP.trainingsCounts);
            }

            // Ajouter les logos
            drawLogos(chart, containerId, labels);
        }
    };
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sélectionnez tous les boutons de toggle
        const toggleButtons = document.querySelectorAll('.toggle-info');

        toggleButtons.forEach(btn => {
            const target = document.querySelector(btn.getAttribute('data-bs-target'));
            const icon = btn.querySelector('i');

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
        const brandScoresData = variablesPHP.brandScores;
        const trainingsCounts = variablesPHP.trainingsCounts;
        const brandLogos = variablesPHP.brandLogos;
        const teamBrands = variablesPHP.teamBrands;
        const numRecommendedTeam = variablesPHP.numRecommendedTeam;
        const totalTrainingsForTeam = variablesPHP.totalTrainingsForTeam;
        const trainingsPerTechnician = variablesPHP.trainingsPerTechnician;

        // 1) Préparer l'axe X : brandLabels pour le premier graphique
        const brandLabelsScores = brandScoresData.map((obj, index) => obj.x);

        // 2) Préparer les données pour le Scatter Chart "Résultats aux Tests (Équipe)"
        const scatterScores = brandScoresData.map((obj, index) => {
            return {
                x: index,
                y: (obj.y === null ? 0 : obj.y),
                fillColor: obj.fillColor,
                pointBorderColor: obj.fillColor,
                labelText: obj.y !== null ? obj.y + '%' : 'N/A'
            };
        });

        // 3) Scatter Chart #1 => Résultats aux Tests (Équipe)
        const ctx1 = document.getElementById('scoreScatterCanvas').getContext('2d');
        const scoreScatter = new Chart(ctx1, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: "Résultats aux Tests (Équipe)",
                    data: scatterScores.map(d => ({ x: d.x, y: d.y })),
                    backgroundColor: scatterScores.map(d => d.fillColor),
                    borderColor: scatterScores.map(d => d.fillColor),
                    pointRadius: 35,
                    pointHoverRadius: 40,
                    pointStyle: 'circle'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    datalabels: {
                        color: '#000',
                        align: 'center',
                        anchor: 'center',
                        usePointStyle: true,
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        formatter: (value) => `${value.y}%`
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const brand = brandLabelsScores[index];
                                const score = scatterScores[index].y !== 0 ? scatterScores[index].y + "%" : "N/A";
                                return [
                                    `Marque: ${brand}`,
                                    `Score: ${score}`
                                ];
                            }
                        }
                    },
                    zoom: {
                        pan: {
                            enabled: true,
                            mode: 'x'
                        },
                        zoom: {
                            enabled: false
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        min: -0.5,
                        max: scatterScores.length - 0.5,
                        grid: {
                            color: '#ccc'
                        },
                        ticks: {
                            display: false // Masquer les labels textuels
                        }
                    },
                    y: {
                        type: 'linear',
                        min: 0,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Score (%)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: '#ccc'
                        },
                        ticks: {
                            stepSize: 10,
                            padding: 10,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels, imagePluginCombined]
        });

        // 4) Préparer les données pour le Scatter Chart "Plan de Formation (Équipe)"
        const scatterTrainings = teamBrands.map((b, index) => {
            return {
                x: index,
                y: trainingsCounts[b] || 0,
                fillColor: '#ffc107', // Couleur orange par défaut
                labelText: `${trainingsCounts[b] || 0} Modules`
            };
        });

        // Définir un plugin personnalisé pour ajouter des labels sans afficher les axes X et Y
        const customLabelPlugin = {
            id: 'customLabelPlugin',
            afterDraw: (chart) => {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, datasetIndex) => {
                    const meta = chart.getDatasetMeta(datasetIndex);
                    meta.data.forEach((element, index) => {
                        const data = dataset.data[index];
                        const position = element.getCenterPoint();

                        // Dessiner le chiffre
                        ctx.font = 'bold 20px Arial';
                        ctx.fillStyle = '#000';
                        ctx.textAlign = 'center';
                        ctx.fillText(data.y, position.x, position.y - 15);

                        // Dessiner "Module(s)"
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';
                        ctx.textAlign = 'center';
                        ctx.fillText('Module(s)', position.x, position.y + 15);
                    });
                });
            }
        };

        // 5) Scatter Chart #2 => Plan de Formation (Équipe)
        const ctx2 = document.getElementById('trainingsScatterCanvas').getContext('2d');
        const trainingsScatter = new Chart(ctx2, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: "Plan de Formation (Équipe)",
                    data: scatterTrainings.map(d => ({ x: d.x, y: d.y })),
                    backgroundColor: scatterTrainings.map(d => d.fillColor),
                    borderColor: scatterTrainings.map(d => d.fillColor),
                    pointRadius: 35,
                    pointHoverRadius: 40,
                    pointStyle: 'circle'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    datalabels: {
                        display: false // Masquer les labels de données
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const brand = teamBrands[index];
                                const count = scatterTrainings[index].y;
                                return `Marque: ${brand} | Formations: ${count}`;
                            }
                        }
                    },
                    zoom: {
                        pan: {
                            enabled: true,
                            mode: 'x'
                        },
                        zoom: {
                            enabled: false
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        min: -0.5,
                        max: scatterTrainings.length - 0.5,
                        grid: {
                            color: '#ccc'
                        },
                        ticks: {
                            display: false // Masquer les labels textuels
                        }
                    },
                    y: {
                        type: 'linear',
                        min: 0,
                        max: 100,
                        title: {
                            display: true // Afficher le titre de l'axe Y
                        },
                        grid: {
                            color: '#ccc'
                        },
                        ticks: {
                            stepSize: 10,
                            padding: 10,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            },
            plugins: [customLabelPlugin, imagePluginCombined]
        });
    });
</script>

</body>

</html>
<?php } ?>
