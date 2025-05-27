<?php
session_start();
include_once "../language.php";
include_once "getValidatedResults.php"; 
include_once "score_decla.php"; // Inclusion du fichier pour les scores déclaratifs
include_once "score_fact.php";  // Inclusion du fichier pour les scores factuels
include_once "userFilters.php"; // Inclusion du fichier contenant les fonctions de filtrage

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION["profile"])) {
    header("Location: ../../");
    exit();
} else {

    // Récupérer les paramètres depuis l'URL
    $selectedLevel = isset($_GET['level']) ? $_GET['level'] : 'Junior';
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null; // Utiliser 'country' pour le filtrage
    $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null; // Utiliser 'agency' pour le filtrage
    $selectedUser = isset($_GET['user']) ? $_GET['user'] : null;
    //var_dump($_GET);

    // Appel de la fonction pour obtenir les données
    $tableauResultats = getValidatedResults($selectedLevel);

    // Créer une connexion
    $conn = new MongoDB\Client("mongodb://localhost:27017");
    $academy = $conn->academy;

    // Connexion à la collection des questions
    $questions = $academy->questions;
    
    $questionDecla = $questions->find([
        '$and' => [
            ["type" => "Declarative"],
            ["level" => $selectedLevel],
            ["active" => true]
        ],
    ])->toArray();

    // **Définir le nombre total de questions**
    $totalQuestions = count($questionDecla);

    // Récupérer le profil utilisateur de la session
    $profile = $_SESSION['profile'];
    $userCountry = isset($_SESSION['country']) ? $_SESSION['country'] : null;

    // Filtrer pour obtenir uniquement les techniciens selon le profil de l'utilisateur
    $technicians = filterUsersByProfile($academy, $profile, $selectedCountry, $selectedLevel, $selectedAgency);

    // Vérifier ce que la fonction retourne
    //var_dump($technicians);

    // Arrêter l'exécution pour voir les valeurs si nécessaire
    //die("Debugging values before fetching technicians.");
    // Définir le titre en fonction du niveau sélectionné
    $taux_de_cover = "";
    switch ($selectedLevel) {
        case 'Junior':
            $taux_de_cover = $taux_de_couverture_ju;
            break;
        case 'Senior':
            $taux_de_cover = $taux_de_couverture_se;
            break;
        case 'Expert':
            $taux_de_cover = $taux_de_couverture_ex;
            break;
        default:
            $taux_de_cover =$taux_de_couverture_ju;
            break;
    }

    // Assuming you have the country stored in a session variable
    $country = $_SESSION["country"]; // Set this session variable based on your logic
    // Map countries to their respective agencies
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
    
    // Retrieve the selected subsidiary from session
    $selectedSubsidiary = isset($_SESSION['country']) ? $_SESSION['country'] : '';
    
    // Get the agencies for the selected subsidiary
    $agencyList = isset($agencies[$selectedSubsidiary]) ? $agencies[$selectedSubsidiary] : [];
    
    // Convert the agency list to JSON for JavaScript
    $agencyListJson = json_encode($agencyList);
    
    // Récupérer les paramètres depuis l'URL
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;
    $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null;


    // Récupérer les agences du pays sélectionné si le profil est Directeur de Filiale ou Super Admin
    if ($_SESSION['profile'] == 'Directeur Filiale' || $_SESSION['profile'] == 'Super Admin') {
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
    function calculateQuestionMasteryStats($academy, $niveau, $country, $tableauResultats, $agency = null) {
        // Récupérer les questions "Déclaratives" actives pour le niveau donné
        $questions = $academy->questions->find([
            "type" => "Declarative",
            "level" => $niveau,
            "active" => true
        ])->toArray();
        
        // Filtrer les techniciens en fonction du profil, du pays, du niveau et de l'agence
        $technicienss = filterUsersByProfile($academy, $_SESSION['profile'], $country, $niveau, $agency);
        $totalQuestions = count($questions);
        $totalNonMaitriseQuestions = 0;
        $totalSingleMaitriseQuestions = 0;
        $totalDoubleMaitriseQuestions = 0; // Nouveau compteur pour totalMaitrise == 2
    
        foreach ($questions as $question) { 
            $totalMaitrise = 0;
            $questionId = (string)$question['_id']; 
            
            foreach ($technicienss as $technician) {
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
    
        // Retourner les statistiques
        return [
            'totalQuestions' => $totalQuestions,
            'nonMaitrise' => $totalNonMaitriseQuestions,
            'singleMaitrise' => $totalSingleMaitriseQuestions,
            'doubleMaitrise' => $totalDoubleMaitriseQuestions // Ajouter ce champ
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
    function calculatePercentages($stats) {
        $totalQuestions = $stats['totalQuestions'];
        $percentages = [
            'nonMaitrise' => ($totalQuestions > 0) ? round(($stats['nonMaitrise'] / $totalQuestions) * 100) : 0,
            'singleMaitrise' => ($totalQuestions > 0) ? round(($stats['singleMaitrise'] / $totalQuestions) * 100) : 0,
            'doubleMaitrise' => ($totalQuestions > 0) ? round(($stats['doubleMaitrise'] / $totalQuestions) * 100) : 0,
        ];
        // Calculer le pourcentage restant pour les autres tâches
        $percentages['others'] = 100 - ($percentages['nonMaitrise'] + $percentages['singleMaitrise'] + $percentages['doubleMaitrise']);
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

    $statsTotal = [
        'totalQuestions' => $totalQuestionsAllLevels,
        'nonMaitrise' => $totalNonMaitriseAllLevels,
        'singleMaitrise' => $totalSingleMaitriseAllLevels,
        'doubleMaitrise' => $totalDoubleMaitriseAllLevels
    ];

    $statsJunior['othersCount'] = $statsJunior['totalQuestions'] - ($statsJunior['nonMaitrise'] + $statsJunior['singleMaitrise'] + $statsJunior['doubleMaitrise']);
    $statsSenior['othersCount'] = $statsSenior['totalQuestions'] - ($statsSenior['nonMaitrise'] + $statsSenior['singleMaitrise'] + $statsSenior['doubleMaitrise']);
    $statsExpert['othersCount'] = $statsExpert['totalQuestions'] - ($statsExpert['nonMaitrise'] + $statsExpert['singleMaitrise'] + $statsExpert['doubleMaitrise']);
    $statsTotal['othersCount'] = $statsTotal['totalQuestions'] - ($statsTotal['nonMaitrise'] + $statsTotal['singleMaitrise'] + $statsTotal['doubleMaitrise']);

    $percentagesTotal = calculatePercentages($statsTotal);

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

    // Nombre total de tâches pour chaque niveau
    $numberOfTasksJunior = $statsJunior['totalQuestions'];
    $numberOfTasksSenior = $statsSenior['totalQuestions'];
    $numberOfTasksExpert = $statsExpert['totalQuestions'];
    $numberOfTasksTotal = $statsTotal['totalQuestions'];

    // Récupérer les paramètres depuis l'URL
    $selectedLevel = isset($_GET['level']) ? $_GET['level'] : 'Junior';
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;
    $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null;
    $selectedUser = isset($_GET['user']) ? $_GET['user'] : null;
 
    // Récupérer les questions déclaratives actives pour le niveau sélectionné
    $questionDeclaCursor = $questions->find([
        '$and' => [
            ["type" => "Declarative"],
            ["level" => $selectedLevel],
            ["active" => true]
        ],
    ]);
 
    $questionDecla = iterator_to_array($questionDeclaCursor);
 
    $questionDeclaCursorFact = $questions->find([
         '$and' => [
             ["type" => "Factuelle"],
             ["level" => $selectedLevel],
             ["active" => true]
         ],
     ]);
 
     $questionDeclaF = iterator_to_array($questionDeclaCursorFact);
 
    // Récupérer toutes les questions pour créer une liste d'ID de questions
    $allQuestionIds = [];
    foreach ($questionDecla as $question) {
        $allQuestionIds[] = (string)$question['_id'];
    }
 
    $allQuestionIdsF = [];
    foreach ($questionDeclaF as $questionF) {
        $allQuestionIdsF[] = (string)$questionF['_id'];
    }
 
    // Récupérer le profil utilisateur de la session
    $profile = $_SESSION['profile'];
    $userCountry = isset($_SESSION['country']) ? $_SESSION['country'] : null;
 
    // Filtrer pour obtenir uniquement les techniciens selon le profil de l'utilisateur
    $technicians = filterUsersByProfile($academy, $profile, $selectedCountry, $selectedLevel, $selectedAgency);
    $techniciansF = filterUsersByProfile($academy, $profile, $selectedCountry, $selectedLevel, $selectedAgency);
 
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
 
    // Connexion aux collections nécessaires
    $resultsCollection = $academy->results;
 
    // Récupérer les résultats validés par technicien et par question
    $tableauResultats = getTechnicianResults($selectedLevel);
    $tableauResultatsF = getTechnicianResults3($selectedLevel);
 
    $agencies = [
        "Burkina Faso" => ["Ouaga"],
        "Cameroun" => ["Bafoussam","Bertoua", "Douala", "Garoua", "Ngaoundere", "Yaoundé"],
        "Cote d'Ivoire" => ["Vridi - Equip"],
        "Gabon" => ["Libreville"],
        "Mali" => ["Bamako"],
        "RCA" => ["Bangui"],
        "RDC" => ["Kinshasa", "Kolwezi", "Lubumbashi"],
        "Senegal" => ["Dakar"],
        // Ajoutez d'autres pays et agences ici
    ];
    $countries = array_keys($agencies);  // Extraction des pays
 
    $niveaus = ['Junior', 'Senior', 'Expert'];
 
    // Initialiser les tableaux pour stocker les informations
    $technicianPercentagesByLevel = [];
    $technicianCountsByLevel = [];
    $totalTechniciansByLevel = [];
 
    $technicianPercentagesByLevelF = [];
    $technicianCountsByLevelF = [];
    $totalTechniciansByLevelF = [];
 
    // Boucles pour chaque niveau (Questions Déclaratives)
    foreach ($niveaus as $niveau) {
        // Récupérer les techniciens par niveau
        $techniciensss = filterUsersByProfile($academy, "Directeur Groupe", null, $niveau, null);
        $totalTechniciansByLevel[$niveau] = count($technicians);
        $tableauResultats = getTechnicianResults($niveau);
 
        $totalPercentage = 0;
        $count = 0;
 
        // Calculer les pourcentages de maîtrise
        foreach ($techniciensss as $technician) {
            $techId = (string)$technician['_id'];
            if (isset($tableauResultats[$techId])) {
                $totalPercentage += $tableauResultats[$techId];
                $count++;
            }
        }
 
        $technicianPercentagesByLevel[$niveau] = $count > 0 ? ($totalPercentage / $count) : 0;
        $technicianCountsByLevel[$niveau] = $count;
    }
 
    // Calculer les moyennes par niveau (Questions Déclaratives)
    $averageMasteryByLevel = [];
    foreach ($niveaus as $niveau) {
        $averageMasteryByLevel[$niveau] = round($technicianPercentagesByLevel[$niveau]);
    }
 
    // Calculer les totaux pour 'Total' (Questions Déclaratives)
    $totalTechnicians = array_sum($totalTechniciansByLevel);
    $techniciansWhoTookTest = array_sum($technicianCountsByLevel);
    $totalPercentage = array_sum($averageMasteryByLevel);
 
    $totalTechniciansByLevel['Total'] = $totalTechnicians;
    $technicianCountsByLevel['Total'] = $techniciansWhoTookTest;
    $averageMasteryByLevel['Total'] = $techniciansWhoTookTest > 0 ? round($totalPercentage / count($niveaus)) : 0;
 
    // Boucles pour chaque niveau (Questions Factuelles)
    foreach ($niveaus as $niveauF) {
        // Récupérer les techniciens par niveau
        $techniciansF = filterUsersByProfile($academy, $profile, $selectedCountry, $niveauF, $selectedAgency);
        $totalTechniciansByLevelF[$niveauF] = count($techniciansF);
        $tableauResultatsF = getTechnicianResults3($niveauF);
 
        $totalPercentageF = 0;
        $countF = 0;
 
        // Calculer les pourcentages de maîtrise
        foreach ($techniciansF as $technicianF) {
            $techIdF = (string)$technicianF['_id'];
            if (isset($tableauResultatsF[$techIdF])) {
                $totalPercentageF += $tableauResultatsF[$techIdF];
                $countF++;
            }
        }
 
        $technicianPercentagesByLevelF[$niveauF] = $countF > 0 ? ($totalPercentageF / $countF) : 0;
        $technicianCountsByLevelF[$niveauF] = $countF;
    }
 
    // Calculer les moyennes par niveau (Questions Factuelles)
    $averageMasteryByLevelF = [];
    foreach ($niveaus as $niveauF) {
        $averageMasteryByLevelF[$niveauF] = round($technicianPercentagesByLevelF[$niveauF]);
    }
 
    // Calculer les totaux pour 'Total' (Questions Factuelles)
    $totalTechniciansF = array_sum($totalTechniciansByLevelF);
    $techniciansWhoTookTestF = array_sum($technicianCountsByLevelF);
    $totalPercentageF = array_sum($averageMasteryByLevelF);
 
    $totalTechniciansByLevelF['Total'] = $totalTechniciansF;
    $technicianCountsByLevelF['Total'] = $techniciansWhoTookTestF;
    $averageMasteryByLevelF['Total'] = $techniciansWhoTookTestF > 0 ? round($totalPercentageF / count($niveaus)) : 0;
 
    // Passer les données au JavaScript
    ?>

    <?php include_once "partials/header.php"; ?>
    <!--begin::Title-->
    <title><?php echo $taux_de_cover ?> | CFAO Mobility Academy</title>
    <!--end::Title-->

    <style>
        #kt_customers_table_wrapper td:nth-child(1) {
            position: sticky;
            left: 0;
        }
        #kt_customers_table_wrapper td:nth-child(1) {
            background: #edf2f7;
        }
        #kt_customers_table_wrapper th:nth-child(1) {
            z-index:2;
        }
    </style>
    <!--begin::Body-->
    <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
        data-select2-id="select2-data-kt_content">
        <div class="card-title text-center mb-5">
        <!--begin::Content-->
        <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Post-->
            <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
                <!--begin::Container-->
                <div class=" container-xxl ">
                    <!--begin::Title-->
                    <div class="toolbar" id="kt_toolbar" style="margin-left: -50px">
                        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
                            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                                <h1 class="text-dark fw-bold my-1 fs-2"><?php echo $taux_cover_tache_pro ?> </h1>
                            </div>
                        </div>
                    </div>
                    <!--end::Title-->
                    <!--begin::Row-->
                    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                        <!-- Filtre de sélection de pays -->
                        <div class="row mb-4">
                            <div class="col-md-4 offset-md-4">
                                <label for="country-select" class="form-label">Sélectionnez un pays :</label>
                                <select id="country-select" class="form-select" onchange="applyCountryFilter()">
                                    <option value="tous" <?php if ($selectedCountry == null) echo 'selected'; ?>>Tous les pays</option>
                                    <?php foreach ($countries as $country) { ?>
                                        <option value="<?php echo htmlspecialchars($country); ?>" <?php if ($selectedCountry == $country) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($country); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    
                        <!-- begin::Row -->
                        <div>
                            <div id="chartGF" class="row">
                                <!-- Dynamic cards will be appended here -->
                            </div>
                        </div>
                        <!-- endr::Row -->
                        <!-- Modal Bootstrap -->
                        <div class="modal fade" id="questionsModal" tabindex="-1" aria-labelledby="questionsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="questionsModalLabel"></h5> <!-- Le titre sera mis à jour dynamiquement -->
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-10 px-lg-17">
                                        <!-- Le contenu des questions sera chargé ici -->
                                        <div id="questionsContent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Post-->
        </div>
        <!--end::Content-->
            <div class="toolbar" id="kt_toolbar">
                <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
                    <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                        <h1 class="text-dark fw-bold my-1 fs-2"><?php echo $taux_de_cover ?> </h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-3">
                    <label for="level-select" class="form-label"><?php echo htmlspecialchars($levelTech); ?></label>
                    <select id="level-select" class="form-select" onchange="applyFilters()">
                        <option value="Junior" <?php if ($selectedLevel == 'Junior') echo 'selected'; ?>>Junior</option>
                        <option value="Senior" <?php if ($selectedLevel == 'Senior') echo 'selected'; ?>>Senior</option>
                        <option value="Expert" <?php if ($selectedLevel == 'Expert') echo 'selected'; ?>>Expert</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="location-select" class="form-label">Pays / Agence</label>
                    <select id="location-select" class="form-select" onchange="applyFilters()">
                    <!-- Option par défaut : Tous -->
                        <?php if ($profile === "Directeur Groupe" || $profile === "Super Admin") { ?>
                            <option value="tous" <?php if ($selectedCountry == 'tous' || $selectedCountry == null) echo 'selected'; ?>>Tous les pays</option>
                            <!-- Afficher chaque pays comme une option -->
                            <?php foreach ($agencies as $country => $agencyList) { ?>
                                <option value="<?php echo htmlspecialchars($country); ?>" <?php if ($selectedCountry == $country) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($country); ?>
                                </option>
                            <?php } ?>
                        <?php } elseif ($profile === "Directeur Filiale" && $userCountry) { ?>
                            <!-- Directeur de Filiale peut choisir "Tous" pour toutes les agences de son pays, ou une agence spécifique -->
                            <option value="<?php echo $userCountry ?>" <?php if ($selectedAgency == $userCountry || $selectedAgency == null) echo 'selected'; ?>>Toutes les agences</option>

                            <!-- Afficher uniquement les agences du pays du directeur -->
                            <?php if (isset($agencies[$userCountry])) { ?>
                                <optgroup label="<?php echo htmlspecialchars($userCountry); ?>">
                                    <?php foreach ($agencies[$userCountry] as $agency) { ?>
                                        <option value="<?php echo htmlspecialchars($agency); ?>" <?php if ($selectedAgency == $agency) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($agency); ?>
                                        </option>
                                    <?php } ?>
                                </optgroup>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>

            </div>
        </div>
        
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
            <!--begin::Container-->
            <div class="container-xxl" data-select2-id="select2-data-194-27hh">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                            <table class="table align-middle table-bordered table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_customers_table">
                                <thead>
                                    <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                        <!--<th class="min-w-100px sorting text-center text-uppercase">Numéro</th>-->
                                        <th class="min-w-200px sorting text-center text-uppercase">
                                            <?php echo $titre_question ?>
                                        </th>
                                        <th class="min-w-200px sorting text-center text-uppercase">
                                            <?php echo $groupe_fonctionnel ?>
                                        </th>
                                        <?php foreach ($technicians as $technician) { ?>
                                            <th class="min-w-150px sorting text-center text-uppercase">
                                                <?php echo htmlspecialchars($technician['firstName'] . ' ' . $technician['lastName']); ?>
                                            </th>
                                        <?php } ?>
                                        <th class="min-w-150px sorting text-center text-uppercase">Totaux</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        // Fonction pour obtenir la classe Bootstrap en fonction du pourcentage
                                        function getBootstrapClass($pourcentage) {
                                            if ($pourcentage >= 50) {
                                                return 'text-danger'; // Rouge pour plus de 50% non maîtrisé
                                            } elseif ($pourcentage >= 10) {
                                                return 'text-warning'; // Orange pour 10-49% non maîtrisé
                                            } else {
                                                return 'text-success'; // Vert pour moins de 10% non maîtrisé
                                            }
                                        }

                                        $totalNonMaitriseQuestions = 0; // Initialiser le nombre de questions non maîtrisées
                                        $totalTechniciansCount = count($technicians); // Nombre total de techniciens du niveau sélectionné et du pays

                                        foreach ($questionDecla as $question) { 
                                            $totalMaitrise = 0; // Initialiser le compteur de maîtrise
                                            $questionId = (string)$question['_id'];
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo htmlspecialchars($question['label']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($question['speciality']); ?></td>

                                                <?php 
                                                // Parcourir chaque technicien et vérifier s'il a maîtrisé la question
                                                foreach ($technicians as $technician) {
                                                    $techId = (string)$technician['_id'];

                                                    // Vérifier si le résultat est disponible dans le tableau des résultats
                                                    $status = isset($tableauResultats[$techId][$questionId]) ? $tableauResultats[$techId][$questionId] : $non_maitrise;

                                                    // Compter le nombre de "Maîtrisé"
                                                    if ($status == "Maîtrisé") {
                                                        $totalMaitrise++;
                                                    }

                                                    echo "<td class='text-center'>" . htmlspecialchars($status) . "</td>";
                                                } ?>

                                                <!-- Afficher le nombre total de techniciens ayant "Maîtrisé" pour cette question suivi par "/" et le nombre total de techniciens -->
                                                <td class='text-center'>
                                                    <?php echo "$totalMaitrise / $totalTechniciansCount"; ?>
                                                </td>
                                            </tr>

                                            <?php
                                            // Si le nombre de maîtrise est égal à zéro, cela signifie que la question n'a été maîtrisée par aucun technicien
                                            if ($totalMaitrise == 0) {
                                                $totalNonMaitriseQuestions++;
                                            }
                                        } ?>

                                    <!-- Ajouter une ligne pour le pourcentage de tâches non couvertes -->
                                    <tr style="background-color: #EDF2F7; position: sticky; top: 0; z-index: 2;">
                                        <td colspan="<?php echo count($technicians) + 2; ?>" class="text-center fw-bold">POURCENTAGE DE TÂCHES NON COUVERTES</td>

                                        <?php
                                        // Calculer le nombre total de questions non maîtrisées
                                        $pourcentageNonCouverts = ($totalQuestions > 0) ? round(($totalNonMaitriseQuestions / $totalQuestions) * 100) : 0;

                                        // Utiliser la classe Bootstrap appropriée
                                        $bootstrapClass = getBootstrapClass($pourcentageNonCouverts);

                                        echo "<td colspan='1' class='text-center fw-bold $bootstrapClass' style='background-color: #EDF2F7;'>$pourcentageNonCouverts%</td>";
                                        ?>
                                    </tr>
                                </tbody>
                            </table>

                            </div>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
                <!--begin::Export dropdown-->
                <div class="d-flex justify-content-end align-items-center" style="margin-top: 20px;">
                    <button type="button" id="excel" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                        data-bs-target="#kt_customers_export_modal">
                        <i class="ki-duotone ki-exit-up fs-2"></i> <?php echo $excel ?>
                    </button>
                </div>
                <!--end::Export dropdown-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Body-->

    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script src="../public/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $("#excel").on("click", function() {
                let table = document.getElementsByTagName("table");
                TableToExcel.convert(table[0], {
                    name: `Users.xlsx`
                });
            });
        });
    </script>
    <script>
        function applyFilters() {
            var level = document.querySelector("#level-select").value;
            var location = document.querySelector("#location-select").value;
            var url = "?level=" + level;    

            if (location !== "tous") {
                var countries = <?php echo json_encode(array_keys($agencies)); ?>;

                if (countries.includes(location)) {
                    // Si l'option sélectionnée est un pays (Directeur Groupe)
                    url += "&country=" + location; // Filtrer par pays
                } else {
                    // Sinon, c'est une agence spécifique (Directeur Filiale)
                    url += "&agency=" + location;
                }
            } else {
                // Quand 'tous' est sélectionné
                url += "&country=<?php echo $userCountry ?? 'tous' ?>";
            }

            window.location.search = url;
        }
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

        // Graphiques pour les niveaux de maitrises des tâches professionnelles
        document.addEventListener('DOMContentLoaded', function() {
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
            
            var statJu = <?php echo json_encode($statsJunior) ?>;
            var statSe = <?php echo json_encode($statsSenior) ?>;
            var statEx = <?php echo json_encode($statsExpert) ?>;
            var statTo = <?php echo json_encode($statsTotal) ?>;
            
            var percentageJu = <?php echo json_encode($percentagesJunior) ?>;
            var percentageSe = <?php echo json_encode($percentagesSenior) ?>;
            var percentageEx = <?php echo json_encode($percentagesExpert) ?>;
            var percentageTo = <?php echo json_encode($percentagesTotal) ?>;

            // Data for each chart
            const chartData = [{
                    title: '<?php echo $junior_tp; ?>',
                    type: ['nonMaitrise', 'singleMaitrise', 'doubleMaitrise', 'others'],
                    total: statJu.totalQuestions,
                    percentage: [percentageJu.nonMaitrise, percentageJu.singleMaitrise, percentageJu.doubleMaitrise, percentageJu.others], // Test réalisés
                    data: [statJu.nonMaitrise, statJu.singleMaitrise, statJu.doubleMaitrise, statJu.othersCount], // Test réalisés vs. Test à réaliser
                    labels: ['<?php echo $legend_zero_mastery; ?>', '<?php echo $legend_one_mastery; ?>','<?php echo $legend_two_mastery; ?>','<?php echo $legend_more_mastery; ?>'],
                    backgroundColor: [
                        '#d3d3d3',   // Gris pour "Aucun technicien maîtrise"
                        '#fddde6',   // Variante claire pour "1 seul technicien maîtrise"
                        '#f8d7da',   // Couleur pour "Seuls 2 techniciens maîtrisent"
                        '#f5c6cb'    // Couleur pour "Plus de 3 techniciens maîtrisent"
                    ]
                },
                {
                    title: '<?php echo $senior_tp; ?>',
                    type: ['nonMaitrise', 'singleMaitrise', 'doubleMaitrise', 'others'],
                    total: statSe.totalQuestions,
                    percentage: [percentageSe.nonMaitrise, percentageSe.singleMaitrise, percentageSe.doubleMaitrise, percentageSe.others], // Test réalisés
                    data: [statSe.nonMaitrise, statSe.singleMaitrise, statSe.doubleMaitrise, statSe.othersCount], // Test réalisés vs. Test à réaliser
                    labels: ['<?php echo $legend_zero_mastery; ?>', '<?php echo $legend_one_mastery; ?>','<?php echo $legend_two_mastery; ?>','<?php echo $legend_more_mastery; ?>'],
                    backgroundColor: [
                        '#d3d3d3',   // Gris pour "Aucun technicien maîtrise"
                        '#fddde6',   // Variante claire pour "1 seul technicien maîtrise"
                        '#f8d7da',   // Couleur pour "Seuls 2 techniciens maîtrisent"
                        '#f5c6cb'    // Couleur pour "Plus de 3 techniciens maîtrisent"
                    ]
                },
                {
                    title: '<?php echo $expert_tp; ?>',
                    type: ['nonMaitrise', 'singleMaitrise', 'doubleMaitrise', 'others'],
                    total: statEx.totalQuestions,
                    percentage: [percentageEx.nonMaitrise, percentageEx.singleMaitrise, percentageEx.doubleMaitrise, percentageEx.others], // Test réalisés
                    data: [statEx.nonMaitrise, statEx.singleMaitrise, statEx.doubleMaitrise, statEx.othersCount], // Test réalisés vs. Test à réaliser
                    labels: ['<?php echo $legend_zero_mastery; ?>', '<?php echo $legend_one_mastery; ?>','<?php echo $legend_two_mastery; ?>','<?php echo $legend_more_mastery; ?>'],
                    backgroundColor: [
                        '#d3d3d3',   // Gris pour "Aucun technicien maîtrise"
                        '#fddde6',   // Variante claire pour "1 seul technicien maîtrise"
                        '#f8d7da',   // Couleur pour "Seuls 2 techniciens maîtrisent"
                        '#f5c6cb'    // Couleur pour "Plus de 3 techniciens maîtrisent"
                    ]
                },
                {
                    title: '<?php echo $total_tp; ?>',
                    type: ['nonMaitrise', 'singleMaitrise', 'doubleMaitrise', 'others'],
                    total: statTo.totalQuestions,
                    percentage: [percentageTo.nonMaitrise, percentageTo.singleMaitrise, percentageTo.doubleMaitrise, percentageTo.others], // Test réalisés
                    data: [statTo.nonMaitrise, statTo.singleMaitrise, statTo.doubleMaitrise, statTo.othersCount], // Test réalisés vs. Test à réaliser
                    labels: ['<?php echo $legend_zero_mastery; ?>', '<?php echo $legend_one_mastery; ?>','<?php echo $legend_two_mastery; ?>','<?php echo $legend_more_mastery; ?>'],
                    backgroundColor: [
                        '#d3d3d3',   // Gris pour "Aucun technicien maîtrise"
                        '#fddde6',   // Variante claire pour "1 seul technicien maîtrise"
                        '#f8d7da',   // Couleur pour "Seuls 2 techniciens maîtrisent"
                        '#f5c6cb'    // Couleur pour "Plus de 3 techniciens maîtrisent"
                    ]
                }
            ];

            const container = document.getElementById('chartGF');

            // Loop through the data to create and append cards
            chartData.forEach((data, index) => {
                // Calculate the completed percentage
                // const completedPercentage = Math.round((data.completed / data.total) * 100);

                // Create the card element
                const cardHtml = `
                    <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                                <h5>Nombres de Tâches Professionnelles: ${data.total}</h5>
                                <canvas id="doughnutChart${index}" width="200" height="200"></canvas>
                                <h5 class="mt-2">${data.title}</h5>
                            </div>
                        </div>
                    </div>
                `;

                // Append the card to the container
                container.insertAdjacentHTML('beforeend', cardHtml);
                // Initialize the Chart.js doughnut chart
                new Chart(document.getElementById(`doughnutChart${index}`).getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Data',
                            data: data.percentage,
                            backgroundColor: data.backgroundColor,
                            borderColor: 'white',
                            borderWidth: 1
                        }],
                    },
                    options: {
                        onClick: function(e, elements) {
                            if (elements.length > 0) {
                                var elementIndex = elements[0].index;
                                var segmentType = data.type[elementIndex];
                                var chartId = data.title;
                        
                                // Rediriger vers la page correspondante
                                if (segmentType !== '') {
                                    // Construire l'URL avec les paramètres nécessaires
                                    var level = '';
                                    if (chartId === '<?php echo $junior_tp; ?>') {
                                        level = 'Junior';
                                    } else if (chartId === '<?php echo $senior_tp; ?>') {
                                        level = 'Senior';
                                    } else if (chartId === '<?php echo $expert_tp; ?>') {
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
                                            $('#questionsModalLabel').text('Listes des Tâches Professionnelles');
                                            
                                            // Afficher le modal
                                            $('#questionsModal').modal('show');
                                        }
                                    });
                                }                   
                            }
                        },
                            
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    // Customize legend labels to include numbers
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        return data.labels.map((label, i) => ({
                                            text: `${label}: ${data.datasets[0].data[i]} % T.P`,
                                            fillStyle: data.datasets[0].backgroundColor[
                                                i],
                                            strokeStyle: data.datasets[0].borderColor[
                                                i],
                                            lineWidth: data.datasets[0].borderWidth,
                                            hidden: false
                                        }));
                                    }
                                }
                            },
                            datalabels: {
                                formatter: (value, ctx) => {
                                    let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a +
                                        b, 0);
                                    let percentage = Math.round((value / sum) * 100);
                                    // Round up to the nearest whole number
                                    return percentage + '%';
                                },
                                color: '#fff',
                                display: true,
                                anchor: 'center',
                                align: 'center',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        const value = tooltipItem.raw || 0;
                                        const dataset = tooltipItem.dataset.data;
                                        let sum = dataset.reduce((a, b) => a + b, 0);
                                        let percentage = Math.round((value / sum) * 100);
                                        // Round up to the nearest whole number
                                        return `Nombre: ${value}, Pourcentage: ${percentage}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
    <?php include_once "partials/footer.php"; ?>
<?php } ?>
