<?php
session_start();
include_once "language.php";
include_once "getValidatedResults.php";
include_once "userFilters.php";

if (!isset($_SESSION["profile"])) {
    header("Location: ../");
    exit();
} else {
    // Liste des pays disponibles pour la sélection
    $countries = [
        "Burkina Faso",
        "Cameroun",
        "Cote d'Ivoire",
        "Gabon",
        "Mali",
        "RCA",
        "RDC",
        "Senegal"
    ];
    $agencies = [
        "Burkina Faso" => ["Ouaga"],
        "Cameroun" => ["Bafoussam", "Bertoua", "Douala", "Garoua", "Ngaoundere", "Yaoundé"],
        "Cote d'Ivoire" => ["Vridi - Equip"],
        "Gabon" => ["Libreville"],
        "Mali" => ["Bamako"],
        "RCA" => ["Bangui"],
        "RDC" => ["Kinshasa", "Kolwezi", "Lubumbashi"],
        "Senegal" => ["Dakar"],
        
    ];

    // Récupérer les paramètres depuis l'URL
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;
    $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null;

    // Connexion à MongoDB
    $conn = new MongoDB\Client("mongodb://localhost:27017");
    $academy = $conn->academy;

    // Récupérer les agences du pays sélectionné si le profil est Directeur de Filiale ou Super Admin
    if ($_SESSION['profile'] == 'Directeur de Filiale' || $_SESSION['profile'] == 'Super Admin') {
        // Si aucun pays n'est sélectionné, utiliser le pays de l'utilisateur
        if (!$selectedCountry) {
            $selectedCountry = $_SESSION['country'];
        }

        // Récupérer les agences du pays sélectionné
        $agencies = $academy->agencies->find([
            'country' => $selectedCountry
        ])->toArray();
    }

    // Fonction pour calculer le pourcentage de non-maîtrise
    function calculateQuestionMasteryStats($academy, $level, $country, $tableauResultats, $agency = null)
    {
        // Récupérer les questions "Déclaratives" actives pour le niveau donné
        $questions = $academy->questions->find([
            "type" => "Declarative",
            "level" => $level,
            "active" => true
        ])->toArray();

        // Filtrer les techniciens en fonction du profil, du pays, du niveau et de l'agence
        $technicians = filterUsersByProfile($academy, $_SESSION['profile'], $country, $level, $agency);
        $totalQuestions = count($questions);
        $totalNonMaitriseQuestions = 0;
        $totalSingleMaitriseQuestions = 0;
        $totalDoubleMaitriseQuestions = 0; // Nouveau compteur pour totalMaitrise == 2

        foreach ($questions as $question) {
            $totalMaitrise = 0;
            $questionId = (string)$question['_id'];

            foreach ($technicians as $technician) {
                $techId = (string)$technician['_id'];

                // Vérifier si le résultat est disponible dans le tableau des résultats
                $status = isset($tableauResultats[$techId][$questionId]) ? $tableauResultats[$techId][$questionId] : 'Non maîtrisé';

                // Compter le nombre de "Maîtrisé"
                if ($status == "Maîtrisé") {
                    $totalMaitrise++;
                }
            }

            // Compter les questions en fonction du nombre de techniciens qui les maîtrisent
            if ($totalMaitrise == 0) {
                $totalNonMaitriseQuestions++;
            } elseif ($totalMaitrise == 1) {
                $totalSingleMaitriseQuestions++;
            } elseif ($totalMaitrise == 2) {
                $totalDoubleMaitriseQuestions++; // Incrémenter le compteur pour 2 techniciens
            }
        }

        $numTechnicians = count($technicians);
        
        if ($numTechnicians < 3) {
            return [
                'totalQuestions' => $totalQuestions,
                'nonMaitrise' => $totalNonMaitriseQuestions,
                'singleMaitrise' => $totalSingleMaitriseQuestions,
                'doubleMaitrise' => 0,  // Pas de double maîtrise si moins de 3 techniciens
                'othersCount' => 0,      // Pas de "plus de 3 maîtrises" possible
                'insufficientSample' => true // Marqueur pour échantillon insuffisant
            ];
        }


        // Retourner les statistiques
        return [
            'totalQuestions' => $totalQuestions,
            'nonMaitrise' => $totalNonMaitriseQuestions,
            'singleMaitrise' => $totalSingleMaitriseQuestions,
            'doubleMaitrise' => $totalDoubleMaitriseQuestions,
            'othersCount' => $totalQuestions - ($totalNonMaitriseQuestions + $totalSingleMaitriseQuestions + $totalDoubleMaitriseQuestions),
            'insufficientSample' => false 
        ];
    }

    // Récupérer les résultats validés pour chaque niveau
    $junior = 'Junior';
    $senior = 'Senior';
    $expert = 'Expert';
    $total = 'Total';

    $tableauResultatsJunior = getValidatedResults($junior);
    $tableauResultatsSenior = getValidatedResults($senior);
    $tableauResultatsExpert = getValidatedResults($expert);

    // Calcul des statistiques pour chaque niveau
    $statsJunior = calculateQuestionMasteryStats($academy, $junior, $selectedCountry, $tableauResultatsJunior, $selectedAgency);
    $statsSenior = calculateQuestionMasteryStats($academy, $senior, $selectedCountry, $tableauResultatsSenior, $selectedAgency);
    $statsExpert = calculateQuestionMasteryStats($academy, $expert, $selectedCountry, $tableauResultatsExpert, $selectedAgency);

    // Calcul des pourcentages pour chaque niveau
    function calculatePercentages($stats)
    {
        $totalQuestions = $stats['totalQuestions'];
        if (isset($stats['insufficientSample']) && $stats['insufficientSample']) {
            // Simplification pour moins de 3 techniciens
            return [
                'nonMaitrise' => ($totalQuestions > 0) ? round(($stats['nonMaitrise'] / $totalQuestions) * 100) : 0,
                'singleMaitrise' => ($totalQuestions > 0) ? round(($stats['singleMaitrise'] / $totalQuestions) * 100) : 0,
                'doubleMaitrise' => 0,  // Pas de double maîtrise
                'others' => 100 - (($totalQuestions > 0) ? round(($stats['nonMaitrise'] / $totalQuestions) * 100) + round(($stats['singleMaitrise'] / $totalQuestions) * 100) : 0)
            ];
        }
        $percentages = [
            'nonMaitrise' => ($totalQuestions > 0) ? round(($stats['nonMaitrise'] / $totalQuestions) * 100) : 0,
            'singleMaitrise' => ($totalQuestions > 0) ? round(($stats['singleMaitrise'] / $totalQuestions) * 100) : 0,
            'doubleMaitrise' => ($totalQuestions > 0) ? round(($stats['doubleMaitrise'] / $totalQuestions) * 100) : 0,
            // Calculer le pourcentage restant pour les autres tâches
            'others' => 100 - (($totalQuestions > 0) ? round(($stats['nonMaitrise'] / $totalQuestions) * 100) + round(($stats['singleMaitrise'] / $totalQuestions) * 100) + round(($stats['doubleMaitrise'] / $totalQuestions) * 100) : 0)
        ];
        
        return $percentages;
    }

    // Calcul des pourcentages pour chaque niveau
    $percentagesJunior = calculatePercentages($statsJunior);
    $percentagesSenior = calculatePercentages($statsSenior);
    $percentagesExpert = calculatePercentages($statsExpert);

    // Calcul du total pour tous les niveaux
    $totalQuestionsAllLevels = $statsJunior['totalQuestions'] + $statsSenior['totalQuestions'] + $statsExpert['totalQuestions'];
    $totalNonMaitriseAllLevels = $statsJunior['nonMaitrise'] + $statsSenior['nonMaitrise'] + $statsExpert['nonMaitrise'];
    $totalSingleMaitriseAllLevels = $statsJunior['singleMaitrise'] + $statsSenior['singleMaitrise'] + $statsExpert['singleMaitrise'];
    $totalDoubleMaitriseAllLevels = $statsJunior['doubleMaitrise'] + $statsSenior['doubleMaitrise'] + $statsExpert['doubleMaitrise'];

    // Récupérer les techniciens pour chaque niveau
    $techniciansJunior = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Junior', $selectedAgency);
    $numberOfTechniciansJunior = count($techniciansJunior);

    $techniciansSenior = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Senior', $selectedAgency);
    $numberOfTechniciansSenior = count($techniciansSenior);

    $techniciansExpert = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Expert', $selectedAgency);
    $numberOfTechniciansExpert = count($techniciansExpert);
    
    // Calculer le nombre total de techniciens uniques
    $allTechnicians = array_merge($techniciansJunior, $techniciansSenior, $techniciansExpert);

    // Extraire les IDs des techniciens
    $technicianIds = array();
    foreach ($allTechnicians as $technician) {
        $technicianIds[] = (string)$technician['_id'];
    }

    // Obtenir les IDs uniques
    $uniqueTechnicianIds = array_unique($technicianIds);
    $numberOfTechniciansTotal = count($uniqueTechnicianIds);

    $statsTotal = [
        'totalQuestions' => $totalQuestionsAllLevels,
        'nonMaitrise' => $totalNonMaitriseAllLevels,
        'singleMaitrise' => $totalSingleMaitriseAllLevels,
        'doubleMaitrise' => $totalDoubleMaitriseAllLevels,
        'insufficientSample' => $numberOfTechniciansTotal <= 3 
    ];
    $statsTotal['insufficientSample'] = $statsTotal['insufficientSample'] ?? false;


    $statsJunior['othersCount'] = $statsJunior['totalQuestions'] - ($statsJunior['nonMaitrise'] + $statsJunior['singleMaitrise'] + $statsJunior['doubleMaitrise']);
    $statsSenior['othersCount'] = $statsSenior['totalQuestions'] - ($statsSenior['nonMaitrise'] + $statsSenior['singleMaitrise'] + $statsSenior['doubleMaitrise']);
    $statsExpert['othersCount'] = $statsExpert['totalQuestions'] - ($statsExpert['nonMaitrise'] + $statsExpert['singleMaitrise'] + $statsExpert['doubleMaitrise']);
    $statsTotal['othersCount'] = $statsTotal['totalQuestions'] - ($statsTotal['nonMaitrise'] + $statsTotal['singleMaitrise'] + $statsTotal['doubleMaitrise']);

    $statsJunior['numTechnicians'] = $numberOfTechniciansJunior;
    $statsSenior['numTechnicians'] = $numberOfTechniciansSenior;
    $statsExpert['numTechnicians'] = $numberOfTechniciansExpert;
    $statsTotal['numTechnicians'] = $numberOfTechniciansTotal;

    $percentagesTotal = calculatePercentages($statsTotal);

    // Nombre total de tâches pour chaque niveau
    $numberOfTasksJunior = $statsJunior['totalQuestions'];
    $numberOfTasksSenior = $statsSenior['totalQuestions'];
    $numberOfTasksExpert = $statsExpert['totalQuestions'];
    $numberOfTasksTotal = $statsTotal['totalQuestions'];

    function getLegendMessage($numTechnicians) {
        if ($numTechnicians == 1) {
            return [
                'nonMaitrise' => "Aucun technicien maîtrise",
                'singleMaitrise' => "Le technicien maîtrise"
            ];
        } elseif ($numTechnicians == 2) {
            return [
                'nonMaitrise' => "Aucun technicien maîtrise",
                'singleMaitrise' => "1 technicien maîtrise",
                'allMaitrise' => "Les 2 techniciens maîtrisent"
            ];
        } elseif ($numTechnicians == 3) {
            return [
                'nonMaitrise' => "Aucun technicien maîtrise",
                'singleMaitrise' => "1 seul technicien maîtrise",
                'doubleMaitrise' => "Seuls 2 techniciens maîtrisent",
                'allMaitrise' => "Les 3 techniciens maîtrisent"
            ];
        } else { // Cas pour plus de 3 techniciens
            return [
                'nonMaitrise' => "Aucun technicien maîtrise",
                'singleMaitrise' => "1 seul technicien maîtrise",
                'doubleMaitrise' => "Seuls 2 techniciens maîtrisent",
                'moreMaitrise' => "Plus de 3 techniciens maîtrisent"
            ];
        }
    }

    // Utilisation pour chaque niveau
    $legendsJunior = getLegendMessage($numberOfTechniciansJunior);
    $legendsSenior = getLegendMessage($numberOfTechniciansSenior);
    $legendsExpert = getLegendMessage($numberOfTechniciansExpert);
    $legendsTotal = getLegendMessage($numberOfTechniciansTotal);


    // Messages pour les tooltips
    $Tasks_Professional_Singular = "tâche";
    $Tasks_Professional_Plural = "tâches";
    $Maitrise_technicien = "Maîtrise des techniciens";
    $nbreTechie = "Nombre de techniciens";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $Maitrise_techniciens; ?></title>
    <!-- Intégrer Bootstrap via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Intégrer Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
    <style>
        /* Ajustement de la taille des graphes et alignement */
        .chart-box {
            max-width: 400px;
            max-height: 500px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Ajout d'une ombre douce */
            border-radius: 10px;
            /* Coins arrondis */
            background-color: #f9f9f9;
            /* Fond clair */
            padding: 20px;
            /* Ajout de padding */
            text-align: relative;
        }

        .card-title {
            font-size: 0.9em;
            font-weight: bold;
            margin-bottom: 0.2rem;
            color: #333;
        }

        .card-text {
            font-size: 1.2em;
            color: #000;
            font-weight: bold;
        }

        .chart-box p {
            margin-top: 10px;
            font-size: 1em;
            text-align: center;
        }

        /* Style pour le conteneur de la chart box */
        .chart-colors {
            --danger-color: #800020;
            /* Bordeaux */
            --gray: #d3d3d3;
            /* Gris clair */
            background-color: #fff;
            /* Fond blanc */
            padding: 10px;
            border-radius: 8px;
        }

        /* Améliorer l'aspect général de la carte */
        .card {
            border: none;
            /* Enlever la bordure */
            background-color: transparent;
            text-align: center;
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 5px;
        }



        /* Style pour la zone de filtre */
        .filter-box {
            max-width: 400px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Ajout d'une ombre douce */
            border-radius: 10px;
            /* Coins arrondis */
            background-color: transparent;
            /* Fond clair */
            padding: 20px;
            /* Ajout de padding */
            text-align: center;
        }

        .filter-box select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1.1em;
            margin-top: 10px;
        }

        .filter-box label {
            font-weight: bold;
            font-size: 1.2em;
            color: #333;
        }

        /* Style pour le cadre en bas des charts */
        .stats-frame {
            border-top: 1px solid #ccc;
            /* Trait fin pour séparer les informations */
            padding-top: 10px;
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
        }

        .stats-frame p {
            margin: 0;
            font-size: 1.2em;
            color: #000;
            font-weight: bold;
        }

        .stats-frame span {
            display: block;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>

<body class="chart-colors">
    <div class="container">
        <h1 class="text-center my-4"><?php echo $Maitrise_techniciens; ?></h1>

        <!-- Filtre de sélection de pays -->
        <div class="row mb-4">
            <div class="filter-box">
                <label for="country-select" class="form-label">Sélectionnez un pays :</label>
                <select id="country-select" class="form-select" onchange="applyCountryFilter()">
                    <option value="" <?php if ($selectedCountry == null) echo 'selected'; ?>>Tous les pays</option>
                    <?php foreach ($countries as $country) { ?>
                        <option value="<?php echo htmlspecialchars($country); ?>" <?php if ($selectedCountry == $country) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($country); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>


        <!-- Conteneur des graphiques Bootstrap -->
        <div class="row text-center">
            <!-- Graphique Junior -->
            <div class="col-md-3">
                <div class="card chart-box p-3">
                    <h5><?php echo $junior_tp; ?></h5>
                    <canvas id="chartJunior"></canvas>
                    <div class="stats-frame">
                        <div>
                            <p><?php echo $numberOfTasksJunior; ?></p>
                            <span><?php echo $nbre_task; ?></span>
                        </div>
                        <div>
                            <p><?php echo $numberOfTechniciansJunior; ?></p>
                            <span><?php echo $nbreTechie; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique Senior -->
            <div class="col-md-3">
                <div class="card chart-box p-3">
                    <h5><?php echo $senior_tp; ?></h5>
                    <canvas id="chartSenior"></canvas>
                    <div class="stats-frame">
                        <div>
                            <p><?php echo $numberOfTasksSenior; ?></p>
                            <span><?php echo $nbre_task; ?></span>
                        </div>
                        <div>
                            <p><?php echo $numberOfTechniciansSenior; ?></p>
                            <span><?php echo $nbreTechie; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique Expert -->
            <div class="col-md-3">
                <div class="card chart-box p-3">
                    <h5><?php echo $expert_tp; ?></h5>
                    <canvas id="chartExpert"></canvas>
                    <div class="stats-frame">
                        <div>
                            <p><?php echo $numberOfTasksExpert; ?></p>
                            <span><?php echo $nbre_task; ?></span>
                        </div>
                        <div>
                            <p><?php echo $numberOfTechniciansExpert; ?></p>
                            <span><?php echo $nbreTechie; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique Total -->
            <div class="col-md-3">
                <div class="card chart-box p-3">
                    <h5><?php echo $total_tp; ?></h5>
                    <canvas id="chartTotal"></canvas>
                    <div class="stats-frame">
                        <div>
                            <p><?php echo $numberOfTasksTotal; ?></p>
                            <span><?php echo $nbre_task; ?></span>
                        </div>
                        <div>
                            <p><?php echo $numberOfTechniciansTotal; ?></p>
                            <span><?php echo $nbreTechie; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div>
        <!-- Modal Bootstrap -->
        <div class="modal fade" id="questionsModal" tabindex="-1" aria-labelledby="questionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="questionsModalLabel"></h5> <!-- Le titre sera mis à jour dynamiquement -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Le contenu des questions sera chargé ici -->
                        <div id="questionsContent"></div>
                    </div>
                </div>
            </div>
        </div>

        <br><br><br><br>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script src="../public/js/main.js"></script>

    <!-- Inclusion de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script JavaScript -->
    <script>
        console.log("Test");
        // Données pour les niveaux
        var levels = ['Junior', 'Senior', 'Expert', 'Total'];
        var percentagesData = {
            'Junior': <?php echo json_encode($percentagesJunior); ?>,
            'Senior': <?php echo json_encode($percentagesSenior); ?>,
            'Expert': <?php echo json_encode($percentagesExpert); ?>,
            'Total': <?php echo json_encode($percentagesTotal); ?>
        };

        var statsData = {
            'chartJunior': <?php echo json_encode($statsJunior); ?>,
            'chartSenior': <?php echo json_encode($statsSenior); ?>,
            'chartExpert': <?php echo json_encode($statsExpert); ?>,
            'chartTotal': <?php echo json_encode($statsTotal); ?>
        };

        var legendsData = {
            'chartJunior': <?php echo json_encode($legendsJunior); ?>,
            'chartSenior': <?php echo json_encode($legendsSenior); ?>,
            'chartExpert': <?php echo json_encode($legendsExpert); ?>,
            'chartTotal': <?php echo json_encode($legendsTotal); ?>
        };



        // Couleurs pour les segments
        var backgroundColors = [
            '#d3d3d3', // Gris pour "Aucun technicien maîtrise"
            '#fddde6', // Variante claire pour "1 seul technicien maîtrise"
            '#f8d7da', // Couleur pour "Seuls 2 techniciens maîtrisent"
            '#f5c6cb'// "Les 3 techniciens maîtrisent" ou "Plus de 3 techniciens maîtrisent"
        ];

        // Fonction pour créer un graphique doughnut
        function createDoughnutChart(ctx, percentages, chartId, stats, legends) {
            var segmentInfo = [];
            var numTechnicians = stats.numTechnicians;
            console.log(numTechnicians); // Nombre de techniciens pour ce niveau
            console.log(percentages);
            if (numTechnicians == 1) {
                // Cas avec 1 technicien
                segmentInfo.push({
                    type: 'nonMaitrise',
                    label: legends.nonMaitrise,
                    value: percentages.nonMaitrise,
                    color: backgroundColors[0]
                });
                if (percentages.singleMaitrise > 0) {
                    segmentInfo.push({
                        type: 'singleMaitrise',
                        label: legends.singleMaitrise,
                        value: percentages.singleMaitrise,
                        color: backgroundColors[3]
                    });
                }
            } else if (numTechnicians == 2) {
                // Cas avec 2 techniciens
                segmentInfo.push({
                    type: 'nonMaitrise',
                    label: legends.nonMaitrise,
                    value: percentages.nonMaitrise,
                    color: backgroundColors[0]
                });
                if (percentages.singleMaitrise > 0) {
                    segmentInfo.push({
                        type: 'singleMaitrise',
                        label: legends.singleMaitrise,
                        value: percentages.singleMaitrise,
                        color: backgroundColors[1]
                    });
                }
                if (percentages.others > 0) {
                    segmentInfo.push({
                        type: 'allMaitrise',
                        label: legends.allMaitrise,
                        value: percentages.others,
                        color: backgroundColors[3]
                    });
                }
            } else if (numTechnicians == 3) {
                // Cas avec exactement 3 techniciens
                segmentInfo.push({
                    type: 'nonMaitrise',
                    label: legends.nonMaitrise,
                    value: percentages.nonMaitrise,
                    color: backgroundColors[0]
                });
                if (percentages.singleMaitrise > 0) {
                    segmentInfo.push({
                        type: 'singleMaitrise',
                        label: legends.singleMaitrise,
                        value: percentages.singleMaitrise,
                        color: backgroundColors[1]
                    });
                }
                if (percentages.doubleMaitrise > 0) {
                    segmentInfo.push({
                        type: 'doubleMaitrise',
                        label: legends.doubleMaitrise,
                        value: percentages.doubleMaitrise,
                        color: backgroundColors[2]
                    });
                }
                if (percentages.others > 0) {
                    segmentInfo.push({
                        type: 'allMaitrise',
                        label: legends.allMaitrise,
                        value: percentages.others,
                        color: backgroundColors[3]
                    });
                }
            } else {
                // Cas avec plus de 3 techniciens
                segmentInfo.push({
                    type: 'nonMaitrise',
                    label: legends.nonMaitrise,
                    value: percentages.nonMaitrise,
                    color: backgroundColors[0]
                });
                if (percentages.singleMaitrise > 0) {
                    segmentInfo.push({
                        type: 'singleMaitrise',
                        label: legends.singleMaitrise,
                        value: percentages.singleMaitrise,
                        color: backgroundColors[1]
                    });
                }
                if (percentages.doubleMaitrise > 0) {
                    segmentInfo.push({
                        type: 'doubleMaitrise',
                        label: legends.doubleMaitrise,
                        value: percentages.doubleMaitrise,
                        color: backgroundColors[2]
                    });
                }
                if (percentages.others > 0) {
                    segmentInfo.push({
                        type: 'moreMaitrise',
                        label: legends.moreMaitrise,
                        value: percentages.others,
                        color: backgroundColors[3]
                    });
                }
            }

            // Filter out entries with zero value
            var filteredData = [];
            var filteredLabels = [];
            var filteredBackgroundColors = [];
            var filteredSegmentTypes = [];

            for (var i = 0; i < segmentInfo.length; i++) {
                if (segmentInfo[i].value !== 0) {
                    filteredData.push(segmentInfo[i].value);
                    filteredLabels.push(segmentInfo[i].label);
                    filteredBackgroundColors.push(segmentInfo[i].color);
                    filteredSegmentTypes.push(segmentInfo[i].type);
                }
            }

            var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: filteredLabels,
                    datasets: [{
                        data: filteredData,
                        backgroundColor: filteredBackgroundColors,
                    }]
                },
                options: commonOptions
            });

            // Store segment types in chart instance
            chart.segmentTypes = filteredSegmentTypes;
            return chart;
        }

        // Fonction pour générer les labels de légende avec pourcentage
        function generateLegendLabels(chart) {
            var data = chart.data;
            if (data.labels.length && data.datasets.length) {
                var legendItems = [];
                for (var i = 0; i < data.labels.length; i++) {
                    var value = data.datasets[0].data[i];
                    var label = data.labels[i];
                    legendItems.push({
                        text: label + ' ' + value + '% T.P',
                        fillStyle: data.datasets[0].backgroundColor[i],
                        hidden: !chart.getDataVisibility(i),
                        index: i
                    });
                }
                return legendItems;
            } else {
                return [];
            }
        }

        // Fonction pour ajouter le symbole '%' dans les tooltips
        function tooltipLabelCallback(context) {
            var label = context.label || '';
            var chart = context.chart;
            var chartId = chart.canvas.id;
            var index = context.dataIndex;
            var value = context.parsed !== undefined ? context.parsed : 0;
            var taskCount = 0;

            // Get the segment type from the chart instance
            var segmentType = chart.segmentTypes[index];

            if (statsData[chartId]) {
                if (segmentType === 'others' || segmentType === 'moreMaitrise' || segmentType === 'allMaitrise') {
                    taskCount = statsData[chartId]['othersCount'];
                } else {
                    taskCount = statsData[chartId][segmentType];
                }
            }

            var message = label + ' ' + value + '% T.P';
            var taskMessage = taskCount + ' <?php echo $Tasks_Professional_Plural; ?>';

            return [message, taskMessage];
        }

        // Configuration commune pour les graphiques
        var commonOptions = {
            onClick: chartClickEvent,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: tooltipLabelCallback
                    }
                },
                legend: {
                    labels: {
                        generateLabels: generateLegendLabels
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutBounce',
                animateRotate: true,
                animateScale: true
            },
            responsive: true,
            maintainAspectRatio: false,
        };

        window.onload = function() {
            // Création des graphiques
            var ctxJunior = document.getElementById('chartJunior').getContext('2d');
            console.log("Junior level : ");
            var chartJunior = createDoughnutChart(ctxJunior, percentagesData['Junior'], 'chartJunior', statsData['chartJunior'], legendsData['chartJunior']);

            var ctxSenior = document.getElementById('chartSenior').getContext('2d');
            console.log("Senior level : ");
            var chartSenior = createDoughnutChart(ctxSenior, percentagesData['Senior'], 'chartSenior', statsData['chartSenior'], legendsData['chartSenior']);

            var ctxExpert = document.getElementById('chartExpert').getContext('2d');
            console.log("Expert level : ");
            var chartExpert = createDoughnutChart(ctxExpert, percentagesData['Expert'], 'chartExpert', statsData['chartExpert'], legendsData['chartExpert']);

            var ctxTotal = document.getElementById('chartTotal').getContext('2d');
            var chartTotal = createDoughnutChart(ctxTotal, percentagesData['Total'], 'chartTotal', statsData['chartTotal'], legendsData['chartTotal']);
        };

        // Fonction pour appliquer le filtre de pays
        function applyCountryFilter() {
            var selectedCountry = document.getElementById('country-select').value;
            var urlParams = new URLSearchParams(window.location.search);

            // Mettre à jour ou ajouter le paramètre 'country' dans l'URL
            if (selectedCountry) {
                urlParams.set('country', selectedCountry);
            } else {
                urlParams.delete('country');
            }

            // Rediriger vers l'URL mise à jour
            window.location.search = urlParams.toString();
        }

        // Fonction pour appliquer le filtre d'agence
        function applyAgencyFilter() {
            var selectedAgency = document.getElementById('agency-select').value;
            var urlParams = new URLSearchParams(window.location.search);

            // Mettre à jour ou ajouter le paramètre 'agency' dans l'URL
            if (selectedAgency) {
                urlParams.set('agency', selectedAgency);
            } else {
                urlParams.delete('agency');
            }

            // Rediriger vers l'URL mise à jour
            window.location.search = urlParams.toString();
        }

        function chartClickEvent(event, elements) {
            if (elements.length > 0) {
                var chart = this;
                var elementIndex = elements[0].index;
                var segmentType = chart.segmentTypes[elementIndex];
                var chartId = chart.canvas.id;

                // Construire les paramètres nécessaires
                var level = '';
                if (chartId === 'chartJunior') {
                    level = 'Junior';
                } else if (chartId === 'chartSenior') {
                    level = 'Senior';
                } else if (chartId === 'chartExpert') {
                    level = 'Expert';
                } else {
                    level = 'Total';
                }

                var country = '<?php echo urlencode($selectedCountry); ?>'; // Le pays sélectionné
                var agency = '<?php echo urlencode($selectedAgency); ?>';  // L'agence sélectionnée

                // Utilisation de AJAX pour charger le contenu du modal
                $.ajax({
                    url: 'listQuestions.php',
                    type: 'GET',
                    data: {
                        level: level,
                        type: segmentType,
                        country: country,
                        agency: agency
                    },
                    success: function(response) {
                        // Injecter le contenu dans le modal
                        $('#questionsContent').html(response);
                        
                        // Mettre à jour le titre du modal avec la variable PHP $task_list
                        $('#questionsModalLabel').text('<?php echo $task_list; ?>');
                        
                        // Afficher le modal
                        $('#questionsModal').modal('show');
                    }
                });
            }
        }
    </script>
    <!-- Bootstrap et jQuery -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>