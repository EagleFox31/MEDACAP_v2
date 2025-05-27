<?php


session_start();
include_once "language.php";
include_once "getValidatedResults2.php"; // Inclusion du fichier contenant la logique des résultats
include_once "userFilters.php"; // Inclusion du fichier contenant les fonctions de filtrage
include_once "score_decla.php"; // Inclusion du fichier contenant la fonction getTechnicianResults

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
    $tableauPourcentages = \ScoreDecla\getTechnicianResults($selectedLevel);

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
    $totalPercentageMastery2 = 0;
    $numberOfTechniciansEvaluated2 = 0;

    foreach ($technicians as $technician) {
        $techId2 = (string)$technician['_id'];
        if (isset($tableauPourcentages[$techId2])) {
            $percentageMastery2 = $tableauPourcentages[$techId2];
            $totalPercentageMastery2 += $percentageMastery2;
            $numberOfTechniciansEvaluated2++;
        }
    }

    if ($numberOfTechniciansEvaluated2 > 0) {
        $averagePercentageMastery2 = round($totalPercentageMastery2 / $numberOfTechniciansEvaluated2);
    } else {
        $averagePercentageMastery2 = 0;
    }

 
?>

<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo htmlspecialchars($taux_de_couverture); ?> | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <!-- Votre code existant pour la barre d'outils -->
    <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
            <h1 class="text-dark fw-bold my-1 fs-2"><?php echo htmlspecialchars($taux_de_couverture); ?> </h1>
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                        placeholder="<?php echo htmlspecialchars($recherche); ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content" data-select2-id="select2-data-kt_content">
    <div class="card-title text-center mb-5">
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
                    <?php if ($profile === "Directeur Groupe") { ?>
                        <option value="tous" <?php if ($selectedCountry == 'tous' || $selectedCountry == null) echo 'selected'; ?>>Tous les pays</option>
                        <!-- Afficher chaque pays comme une option -->
                        <?php foreach ($agencies as $country => $agencyList) { ?>
                            <option value="<?php echo htmlspecialchars($country); ?>" <?php if ($selectedCountry == $country) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($country); ?>
                            </option>
                        <?php } ?>
                    <?php } elseif ($profile === "Directeur Filiale" && $userCountry) { ?>
                        <!-- Directeur de Filiale peut choisir "Tous" pour toutes les agences de son pays, ou une agence spécifique -->
                        <option value="tous" <?php if ($selectedAgency == 'tous' || $selectedAgency == null) echo 'selected'; ?>>Toutes les agences</option>

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
    <!-- Button to Show Charts -->
    <div class="d-flex justify-content-center mb-3">
        <button type="button" id="show-charts" class="btn btn-primary">
            <i class="bi bi-bar-chart-line-fill"></i> Voir les Graphiques
        </button>
    </div>
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class="container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px sorting text-center text-uppercase" style="background-color: #EDF2F7; position: sticky; left: 0;">
                                        <?php echo htmlspecialchars($titre_question); ?>
                                    </th>
                                    <th class="min-w-200px sorting text-center text-uppercase">
                                        <?php echo htmlspecialchars($groupe_fonctionnel); ?>
                                    </th>
                                    <?php foreach ($technicians as $technician) { 
                                        $techId = (string)$technician['_id'];
                                    ?>
                                        <th class="min-w-150px sorting text-center text-uppercase">
                                            <?php echo htmlspecialchars($technician['firstName'] . ' ' . $technician['lastName']); ?>
                                        </th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($questionDecla as $question) {
                                    $questionId = (string)$question['_id'];
                                ?>
                                    <tr>
                                        <td class="text-center" style="background-color: #EDF2F7; position: sticky; left : 0;"><?php echo htmlspecialchars($question['label']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($question['speciality']); ?></td>

                                        <?php
                                            foreach ($technicians as $technician) {
                                                $techId = (string)$technician['_id'];

                                                // Check if the technician has been evaluated on this question
                                                if (isset($tableauResultats[$techId][$questionId])) {
                                                    $status = $tableauResultats[$techId][$questionId];

                                                    if ($status === 1) {
                                                        $displayStatus = "1";
                                                        $cellClass = 'text-center text-success fw-bold';
                                                        // Increment mastery count if needed
                                                        $technicianMasteryCounts[$techId]++;
                                                    } elseif ($status === 0) {
                                                        $displayStatus = "0";
                                                        $cellClass = 'text-center text-danger fw-bold';
                                                    } elseif ($status === 'À Noter') {
                                                        $displayStatus = "À Noter";
                                                        $cellClass = 'text-center text-dark fw-bold';
                                                    } else {
                                                        $displayStatus = "-";
                                                        $cellClass = 'text-center text-secondary';
                                                    }

                                                    echo "<td class='{$cellClass}'>" . htmlspecialchars($displayStatus) . "</td>";
                                                } else {
                                                    // The technician has not been evaluated on this question
                                                    echo "<td class='text-center' style='background-color: #f0f0f0;'><i class='bi bi-x-circle text-secondary' aria-label='Non évalué' title='Non évalué'></i></td>";
                                                }

                                                // Update question counts
                                                if (!isset($technicianQuestionCounts[$techId])) {
                                                    $technicianQuestionCounts[$techId] = 0;
                                                }
                                                if ($status !== '-' && $status !== 'à noter') {
                                                    $technicianQuestionCounts[$techId]++;
                                                }
                                            }
                                            ?>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #EDF2F7; position: sticky; bottom: 0; z-index: 2;">
                                    <td colspan="2" class="text-center fw-bold">POURCENTAGE DE SCORE</td>
                                    <?php
                                    foreach ($technicians as $technician) {
                                        $techId2 = (string)$technician['_id'];
                                        $percentageMastery2 = isset($tableauPourcentages[$techId2]) ? round($tableauPourcentages[$techId2]) : 0;
                                    
                                        $bootstrapClass2 = getBootstrapClass($percentageMastery2);
                                    
                                        echo "<td class='text-center fw-bold {$bootstrapClass2}' style='background-color: #EDF2F7;'>{$percentageMastery2}%</td>";
                                    }
                                    ?>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            <!--begin::Average mastery percentage-->
            <?php
            //Calculer la moyenne des pourcentages de maîtrise
            $totalPercentageMastery = 0;
            $numberOfTechniciansEvaluated = 0;
            foreach ($technicians as $technician) {
                $techId = (string)$technician['_id'];
                $questionsEvaluated = isset($technicianQuestionCounts[$techId]) ? $technicianQuestionCounts[$techId] : 0;

                if ($questionsEvaluated > 0) {
                    $masteryCount = isset($technicianMasteryCounts[$techId]) ? $technicianMasteryCounts[$techId] : 0;
                    $percentageMastery = ($questionsEvaluated > 0) ? round(($masteryCount / $questionsEvaluated) * 100) : 0;
                    $totalPercentageMastery += $percentageMastery;
                    $numberOfTechniciansEvaluated++;
                }
            }

            if ($numberOfTechniciansEvaluated > 0) {
                $averagePercentageMastery = round($totalPercentageMastery / $numberOfTechniciansEvaluated);
            } else {
                $averagePercentageMastery = 0;
            }  

            // Déterminer la classe Bootstrap pour la moyenne
            $averageBootstrapClass = getBootstrapClass($averagePercentageMastery);

            //Calculer la moyenne des pourcentages de maîtrise


            ?>
            <!--end::Average mastery percentage-->
            <br><br><br>
            <!-- Nouvelle Section pour les Cartes de Statistiques -->
            <div class="row mb-4">
                <!-- Carte 1 : Nombre de Techniciens -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people-fill display-4 text-primary"></i>
                            <h5 class="card-title mt-2">Nombre de Techniciens</h5>
                            <p class="card-text display-6"><?php echo count($technicians); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Carte 2 : Moyenne de Maîtrise -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-bar-chart-line-fill display-4 text-warning"></i> <!-- Icône ajoutée -->
                            <h5 class="card-title mt-2">Moyenne de Maîtrise</h5>
                            <p class="card-text display-6 <?php echo htmlspecialchars($averageBootstrapClass); ?>">
                                <?php echo htmlspecialchars($averagePercentageMastery2); ?>%
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Carte 3 : Nombre Total de Questions -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-question-circle-fill display-4 text-success"></i>
                            <h5 class="card-title mt-2">Nombre Total de Tâches</h5>
                            <p class="card-text display-6"><?php echo count($questionDecla); ?></p>
                        </div>
                    </div>
                </div>
            </div>
<br><br>


            <!-- Nouvelle section pour les graphiques en dessous des statistiques
            <div class="row mb-4">
                <!-- Graphique Junior -->
                <!-- <div class="col-sm-12 col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $result; ?> <?php echo $junior; ?></h5>
                            <canvas id="chart-junior"></canvas>
                        </div>
                    </div>
                </div> -->

                <!-- Graphique Senior -->
                <!-- <div class="col-sm-12 col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $result; ?> <?php echo $senior; ?></h5>
                            <canvas id="chart-senior"></canvas>
                        </div>
                    </div>
                </div> -->

                <!-- Graphique Expert -->
                <!-- <div class="col-sm-12 col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $result; ?> <?php echo $expert; ?></h5>
                            <canvas id="chart-expert"></canvas>
                        </div>
                    </div>
                </div> -->

                <!-- Graphique Total -->
                <!-- <div class="col-sm-12 col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $total; ?></h5>
                            <canvas id="chart-total"></canvas>
                        </div>
                    </div>
                </div>
            </div> -->



            <!--begin::Export dropdown-->
            <div class="d-flex justify-content-end align-items-center" style="margin-top: 20px;">
                <button type="button" id="excel" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                    data-bs-target="#kt_customers_export_modal">
                    <i class="ki-duotone ki-exit-up fs-2"></i> <?php echo htmlspecialchars($excel); ?>
                </button>
            </div>
            <!--end::Export dropdown-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->

<!-- Scripts JS -->
<script src="https://code.jquery.com/jquery-3.6.3.js"
    integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
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
<!-- Bootstrap JS Bundle (inclut Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Votre script principal -->
<script src="../public/js/main.js"></script>
<script>


    function applyFilters() {
        var level = document.querySelector("#level-select").value;
        var location = document.querySelector("#location-select").value;
        var url = "?level=" + encodeURIComponent(level);

        // Vérifiez si l'utilisateur est un Directeur Filiale
        var isDirectorFiliale = <?php echo json_encode($profile === "Directeur Filiale"); ?>;
        var userCountry = <?php echo json_encode($userCountry); ?>;

        if (location !== "tous") {
            var countries = <?php echo json_encode(array_keys($agencies)); ?>;

            if (countries.includes(location)) {
                // Si l'option sélectionnée est un pays (Directeur Groupe)
                url += "&country=" + encodeURIComponent(location); // Filtrer par pays
            } else {
                // Sinon, c'est une agence spécifique (Directeur Filiale)
                url += "&agency=" + encodeURIComponent(location);
            }
        } else {
            if (isDirectorFiliale) {
                // Si l'utilisateur est un Directeur Filiale et sélectionne "tous", filtrer par son pays
                url += "&country=" + encodeURIComponent(userCountry); // Filtrer par le pays du Directeur Filiale
            } else {
                // Si c'est un Directeur Groupe, filtrer par tous les pays
                url += "&country=tous";
            }
        }

        window.location.search = url;
    }
</script>

<?php include_once "partials/footer.php"; ?>
<?php } ?>
