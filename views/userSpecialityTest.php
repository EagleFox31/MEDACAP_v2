<?php
session_start();
include_once "language.php";
include_once "getValidatedResults.php"; // Inclusion du fichier contenant la logique des résultats
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
            $taux_de_couverture =$taux_de_couverture_ju;
            break;
    }
?>

<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $taux_de_couverture ?> | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <h1 class="text-dark fw-bold my-1 fs-2"><?php echo $taux_de_couverture ?> </h1>
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="<?php echo $recherche ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
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
                            <option value="tous" <?php if ($selectedAgency == 'all' || $selectedAgency == null) echo 'selected'; ?>>Toutes les agences</option>

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

        // Vérifiez si l'utilisateur est un Directeur Filiale
        var isDirectorFiliale = <?php echo json_encode($profile === "Directeur Filiale"); ?>;
        var userCountry = <?php echo json_encode($userCountry); ?>;

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
            if (isDirectorFiliale) {
                // Si l'utilisateur est un Directeur Filiale et sélectionne "tous", filtrer par son pays
                url += "&country=" + userCountry; // Filtrer par le pays du Directeur Filiale
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
