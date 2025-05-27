<?php
session_start();

// ----------------------------------------------------------
// 1) Vérifier session / profil, puis connexion MongoDB
// ----------------------------------------------------------
if (!isset($_SESSION["profile"])) {
    header("Location: /");
    exit();
} else {
    // Autoriser l'accès si l'utilisateur est Manager ou Super Admin
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
        // Manager peut uniquement voir son propre tableau de bord
        $managerId = $_SESSION["id"];
    }

    require_once "../../vendor/autoload.php";
    require_once "dataCollection.php"; // Inclure la classe DataCollection

    // Connexion
    try {
        $mongo = new MongoDB\Client("mongodb://localhost:27017");
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
    // 3) Utiliser DataCollection pour récupérer les données
    // ----------------------------------------------------------
    try {
        $dataCollection = new DataCollection();
        $fullData = $dataCollection->getFullData();
    } catch (Exception $e) {
        echo "Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage());
        exit();
    }

    // ----------------------------------------------------------
    // 4) Filtrer les données pour le manager actuel
    // ----------------------------------------------------------
    // Parcourir les données pour trouver le manager correspondant
    $managerScores = null;
    $managerName = "Manager Inconnu";
    $technicians = [];

    foreach ($fullData as $subsidiary => $subData) {
        if (!isset($subData['agencies'])) {
            continue; // Passer à la prochaine itération si 'agencies' n'est pas défini
        }
        foreach ($subData['agencies'] as $agencyName => $agencyData) {
            if (!isset($agencyData['managers'])) {
                continue; // Passer à la prochaine agence si 'managers' n'est pas défini
            }
            foreach ($agencyData['managers'] as $manager) {
                // Vérifiez si 'id' est défini pour le manager
                if (isset($manager['id']) && (string)$manager['id'] === (string)$managerId) {
                    $managerScores = isset($manager['averages']) ? $manager['averages'] : [];
                    $managerName = isset($manager['name']) ? htmlspecialchars($manager['name']) : 'Manager Inconnu';
                    $technicians = isset($manager['technicians']) ? $manager['technicians'] : [];
                    break 3; // Sortir de toutes les boucles
                }
            }
        }
    }

    if ($managerScores === null) {
        echo "Aucun manager correspondant à l'ID spécifié. (managerId=$managerId)";
        exit();
    }

    // ----------------------------------------------------------
    // 5) Appliquer les filtres : level, brand, technician
    // ----------------------------------------------------------
    $filterLevel      = isset($_GET['level'])        ? trim($_GET['level']) : 'all';
    $filterBrand      = isset($_GET['brand'])        ? trim($_GET['brand']) : 'all';
    $filterTechnician = isset($_GET['technicianId']) ? trim($_GET['technicianId']) : 'all';
    // Initialiser les tableaux pour les statistiques
    $trainingsByLevel = [];         // Nombre de formations par niveau
    $trainingsByBrand = [];         // Nombre de formations par marque
    $trainingsByTechnician = [];    // Nombre de formations par technicien

    function matchesMarque($tech, $filterBrand)
    {
        if ($filterBrand === 'all') return true;
        $allBrands = array_merge(
            $tech['brands'] ?? []
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
        $tid = isset($t['id']) ? $t['id'] : 'Unknown ID';

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
            foreach ($t['scoresLevels'] as $level => $brandScores) {
                if (strcasecmp($level, $filterLevel) === 0 && !empty($brandScores)) { // Ajusté pour vérifier brandScores
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
    // 6) Extraire la liste des marques (teamBrands)
    // ----------------------------------------------------------
    $teamBrands = [];
    foreach ($technicians as $t) {
        if ($filterLevel === 'all') {
            $levelsToConsider = ['Junior', 'Senior', 'Expert'];
        } else {
            $levelsToConsider = [$filterLevel];
        }
        foreach ($levelsToConsider as $level) {
            if (isset($t['scoresLevels'][$level])) {
                foreach ($t['scoresLevels'][$level] as $brand => $score) {
                    $bTrim = trim($brand);
                    if ($bTrim !== '' && !in_array($bTrim, $teamBrands)) {
                        $teamBrands[] = $bTrim;
                    }
                }
            }
        }
    }
    sort($teamBrands);

    // ----------------------------------------------------------
    // 7) Calculer la Moy. Globale Factuel+Déclaratif par Marque
    // ----------------------------------------------------------
    function getSupportedGroupsForBrand($brand, $level, $config)
    {
        // On part de functionalGroupsByLevel[$level], puis on retire 
        // tout ce qui est "nonSupportedGroupsByBrand[$brand]"
        $all = isset($config['functionalGroupsByLevel'][$level]) ? $config['functionalGroupsByLevel'][$level] : [];

        $nonSupp = isset($config['nonSupportedGroupsByBrand'][$brand][$level]) ? $config['nonSupportedGroupsByBrand'][$brand][$level] : [];

        return array_values(array_diff($all, $nonSupp));
    }

    function getAverageScoresByBrand(array $technicians, array $config, array $teamBrands, array $levels)
    {
        $acc = [];
        foreach ($teamBrands as $b) {
            $acc[$b] = ['sum' => 0, 'count' => 0];
        }
        foreach ($technicians as $tech) {
            foreach ($tech['scoresLevels'] as $level => $brandScores) {
                if (!in_array($level, $levels)) continue; // Utiliser les niveaux définis
                foreach ($brandScores as $brand => $avgTotal) {
                    if (!in_array($brand, $teamBrands)) continue;
                    $supported = getSupportedGroupsForBrand($brand, $level, $config);
                    // Supposons que les spécialités sont déjà prises en compte dans l'avgTotal
                    // Sinon, ajuster en fonction de la structure des données
                    $acc[$brand]['sum']   += $avgTotal;
                    $acc[$brand]['count'] += 1;
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

    // ----------------------------------------------------------
    // 8) Calculer le Nombre Total de Formations Recommandées pour l'Équipe
    // ----------------------------------------------------------
    // Vous pouvez réutiliser le pipeline d'agrégation existant ou ajuster selon les nouvelles données
    // Pour cet exemple, nous utiliserons une logique simplifiée

    $totalTrainings = 0;
    $trainingsCounts = []; // Nombre de formations par marque

    foreach ($technicians as $tech) {
        foreach ($tech['scoresLevels'] as $level => $brandScores) {
            foreach ($brandScores as $brand => $avgTotal) {
                if (!isset($trainingsCounts[$brand])) {
                    $trainingsCounts[$brand] = 0;
                }
                // Supposons que chaque score représente une formation recommandée
                $trainingsCounts[$brand] += 1;
                $totalTrainings += 1;
            }
        }
    }

    // Calculer le nombre de jours (par exemple, 5 jours par formation)
    $numDays = $totalTrainings * 5;

    // ----------------------------------------------------------
    // 9) Logos
    // ----------------------------------------------------------
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

    // Récupération de la filiale depuis la session
    $subsidiary = isset($_SESSION["subsidiary"]) ? $_SESSION["subsidiary"] : null;

    // Initialisation du tableau pour les comptes de formations
    $trainingsCountsFinal = [];

    foreach ($trainingsCounts as $brand => $count) {
        $trainingsCountsFinal[$brand] = $count;
    }

    // Préparation des données pour le Scatter Plot des formations recommandées
    $formationsRecommandeesLabels = [];
    $formationsRecommandeesData = [];
    $formationsRecommandeesLogos = [];

    foreach ($trainingsCountsFinal as $brand => $count) {
        $formationsRecommandeesLabels[] = ucfirst($brand);
        $formationsRecommandeesData[] = $count;
        $formationsRecommandeesLogos[] = isset($brandLogos[$brand]) ? "brands/" . $brandLogos[$brand] : "brands/default.png";
    }

    // Déterminer les formations par marque pour le Graphique 2
    $trainingsCountsForGraph2 = [];
    if ($filterLevel === 'all') {
        // On prend simplement trainingsCountsFinal pour chaque marque
        foreach ($trainingsCountsFinal as $brand => $count) {
            $trainingsCountsForGraph2[$brand] = $count;
        }
    } else {
        // Filtrer par niveau si nécessaire (à ajuster selon la structure des données)
        foreach ($trainingsCountsFinal as $brand => $count) {
            // Ici, nous n'avons pas de détails par niveau, donc on garde le même nombre
            $trainingsCountsForGraph2[$brand] = $count;
        }
    }

    // ----------------------------------------------------
    // 10) AFFICHAGE HTML
    // ----------------------------------------------------

    // Ajouter les fonctions PHP pour les statistiques
    function calculateTotalAverage($scores)
    {
        $total = 0;
        $count = 0;
        foreach ($scores as $s) {
            if (isset($s['y']) && $s['y'] !== null) {
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
        $totalJours  = calculateTotalTrainings($trainings);
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
        #scoreScatterCanvas-logo-container,
        #trainingsScatterCanvas-logo-container {
            z-index: 10;
        }

        /* Ajustement des logos */
        #scoreScatterCanvas-logo-container img,
        #trainingsScatterCanvas-logo-container img {
            transition: transform 0.2s;
        }

        #scoreScatterCanvas-logo-container img:hover,
        #trainingsScatterCanvas-logo-container img:hover {
            transform: scale(1.1);
        }

        /* Canvas des graphiques */
        canvas {
            width: 100% !important;
            /* height: auto !important; */
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
                                    foreach ($t['scoresLevels'] as $level => $brandScores) {
                                        if (!in_array($level, $availableLevels)) {
                                            $availableLevels[] = $level;
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
                                    <option value="<?php echo htmlspecialchars($t['id']); ?>"
                                        <?php if ($filterTechnician === $t['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($t['name']); ?>
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
                    <h5 class="text-center mb-4">Marques Présentes dans l'Équipe</h5>
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
                            <p class="text-muted">Aucune marque trouvée pour le niveau sélectionné.</p>
                        <?php endif; ?>
                    </div>

                    <hr>

                    <div class="chart-dashboard-container">

                        <!-- Graphique 1: Résultats aux Tests par Marque -->
                        <div class="row align-items-center mb-4">
                            <div class="col-12">
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
                            <div class="col-12x">
                                <div class="card custom-card h-100">
                                    <div class="card-body">
                                        <div class="row mb-3 justify-content-center">
                                            <div id="chart-container-2" class="w-100 mb-4">
                                                <h3 class="text-center mb-4">2. Plans de Formations de l'équipe par Marque</h3>
                                                <div class="row mb-4 justify-content-center">
                                                    <!-- Carte Modules de Formation -->
                                                    <div class="col-md-4 mb-3">
                                                        <div class="card shadow h-100 custom-card text-center">
                                                            <div class="card-body">
                                                                <i class="fas fa-tasks fa-2x text-info mb-2"></i>
                                                                <p class="fs-3 fw-bold"><?php echo htmlspecialchars($totalTrainings); ?></p>
                                                                <h5 class="card-title">
                                                                    Modules de Formation
                                                                    <?php if ($filterLevel !== 'all') {
                                                                        echo " - " . htmlspecialchars($filterLevel);
                                                                    } ?>

                                                                </h5>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Carte Jours de Formation -->
                                                    <div class="col-md-4 mb-3">
                                                        <div class="card shadow h-100 custom-card text-center">
                                                            <div class="card-body">
                                                                <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                                                                <p class="fs-3 fw-bold"><?php echo htmlspecialchars($numDays); ?></p>
                                                                <h5 class="card-title">
                                                                    Jours de Formation
                                                                    <?php if ($filterLevel !== 'all') {
                                                                        echo " - " . htmlspecialchars($filterLevel);
                                                                    } ?>
                                                                </h5>

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

        </div><!-- .chart-dashboard-container -->

        <hr>

        <!-- (10) Tableau COMMENTÉ -->
        <!-- 
                <h3 class="mb-4">Détails Scores (Factuel / Déclaratif) par Technicien</h3>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>Technicien</th>
                            <th>Niveau</th>
                            <th>Marque</th>
                            <th>Factuel</th>
                            <th>Déclaratif</th>
                        </tr>
                    </thead>
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
        trainingsCounts: <?php echo json_encode($trainingsCountsForGraph2, JSON_HEX_APOS | JSON_HEX_QUOT); ?>, // Utiliser trainingsCountsForGraph2
        brandLogos: <?php echo json_encode($brandLogos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        teamBrands: <?php echo json_encode($teamBrands, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        numRecommendedTeam: <?php echo json_encode($totalTrainings); ?>,
        totalTrainingsForTeam: <?php echo json_encode(calculateTotalHours($trainingsCountsForGraph2)); ?>,
        trainingsPerTechnician: <?php echo json_encode([], JSON_HEX_APOS | JSON_HEX_QUOT); ?>, // À adapter si nécessaire
        trainingsByBrandAndLevel: <?php echo json_encode([], JSON_HEX_APOS | JSON_HEX_QUOT); ?>, // À adapter si nécessaire
        trainingByLevel: <?php echo json_encode([], JSON_HEX_APOS | JSON_HEX_QUOT); ?>, // À adapter si nécessaire
        numTrainings: <?php echo json_encode($totalTrainings); ?>,
        numDays: <?php echo json_encode($numDays); ?>,
        formationsRecommendedLabels: <?php echo json_encode($formationsRecommandeesLabels, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        formationsRecommendedData: <?php echo json_encode($formationsRecommandeesData, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        formationsRecommendedLogos: <?php echo json_encode($formationsRecommandeesLogos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
        trainingsCountsForGraph2: <?php echo json_encode($trainingsCountsForGraph2, JSON_HEX_APOS | JSON_HEX_QUOT); ?>
    };

    // Optionally, log to verify
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
                    labels = variablesPHP.formationsRecommendedLabels;
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
                labels = variablesPHP.formationsRecommendedLabels;
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

        const recommendedTrainingsLabels = variablesPHP.formationsRecommendedLabels;
        const recommendedTrainingsData = variablesPHP.formationsRecommendedData;
        const recommendedTrainingsLogos = variablesPHP.formationsRecommendedLogos;

        // Récupérer les données PHP
        const brandScoresData = variablesPHP.brandScores;
        const trainingsCounts = variablesPHP.trainingsCountsForGraph2; // Maintenant, trainingsCounts contient formationsRecommendedData
        const brandLogos = variablesPHP.brandLogos;
        const teamBrands = variablesPHP.teamBrands;
        const numRecommendedTeam = variablesPHP.numRecommendedTeam;
        // const totalTrainingsForTeam = variablesPHP.totalTrainingsForTeam; // Optionnellement, peut-être redondant
        const trainingsPerTechnician = variablesPHP.trainingsPerTechnician;

        // 1) Préparer l'axe X : brandLabelsScores pour le premier graphique
        const brandLabelsScores = brandScoresData.map((obj, index) => obj.x);

        // Ensure teamBrandsOrdered matches brandLabelsScores
        const teamBrandsOrdered = variablesPHP.teamBrands;

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
                    data: scatterScores.map(d => ({
                        x: d.x,
                        y: d.y
                    })),
                    backgroundColor: '#aaaaa7',
                    borderColor: '#aaaaa7',
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
        const brandScoreMap = {};
        brandScoresData.forEach(obj => {
            brandScoreMap[obj.x] = isset(obj.y) ? obj.y : 0;
        });

        const scatterTrainings = teamBrandsOrdered.map((b, index) => {
            const count = trainingsCounts[b] || 0;
            return {
                x: index,
                y: brandScoreMap[b],
                fillColor: '#ffc107',
                labelText: `${count} Modules`
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

                        // Dessiner le chiffre (training counts)
                        const count = trainingsCounts[teamBrandsOrdered[index]] || 0;
                        ctx.font = 'bold 20px Arial';
                        ctx.fillStyle = '#000';
                        ctx.textAlign = 'center';
                        ctx.fillText(count, position.x, position.y - 15);

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
                    data: scatterTrainings.map(d => ({
                        x: d.x,
                        y: d.y
                    })),
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
                                const brand = teamBrandsOrdered[index];
                                const count = trainingsCounts[brand] || 0;
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
                            display: true,
                            text: 'Modules',
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
            plugins: [customLabelPlugin, imagePluginCombined]
        });
    });
</script>


</body>

</html>
<?php } ?>
