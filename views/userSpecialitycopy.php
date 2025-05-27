<?php
session_start();
include_once "language.php";
include_once "getValidatedResults.php"; // Inclusion du fichier contenant la logique des résultats

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION["profile"])) {
    header("Location: ../");
    exit();
} else {
    // Appel de la fonction pour obtenir les données
    $tableauResultats = getValidatedResults();

    // Créer une connexion
    $conn = new MongoDB\Client("mongodb://localhost:27017");
    $academy = $conn->academy;

    // Connexion à la collection des questions
    $questions = $academy->questions;

    // Récupérer les questions "Declaratif" de niveau Junior (pour l'affichage)
    $questionDecla = $questions->find([
        '$and' => [
            ["type" => "Declarative"],
            ["level" => "Junior"],
            ["active" => true]
        ],
    ])->toArray();

    // **Définir le nombre total de questions**
    $totalQuestions = count($questionDecla);

    // Filtrer pour obtenir uniquement les techniciens
    $technicians = $academy->users->find([
        '$and' => [
            ["level" => "Junior"],
            ["active" => true],
            ["profile" => "Technicien"]
        ]
    ])->toArray();
?>

<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $list_user ?> | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <h1 class="text-dark fw-bold my-1 fs-2"><?php echo $list_user ?> </h1>
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
                            <table class="table align-middle table-bordered table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="kt_customers_table">
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
                                            if ($pourcentage <= 60) {
                                                return 'text-danger'; // Rouge pour moins de 60%
                                            } elseif ($pourcentage <= 80) {
                                                return 'text-warning'; // Orange pour 61-80%
                                            } else {
                                                return 'text-success'; // Vert pour plus de 80%
                                            }
                                        }

                                        // Pourcentage de questions non maîtrisées par aucun technicien
                                        $totalNonMaitriseQuestions = 0;

                                        foreach ($questionDecla as $question) { 
                                            $totalMaitrise = 0;
                                            $questionId = (string)$question['_id']; ?>
                                        
                                            <tr>
                                                <td class="text-center"><?php echo htmlspecialchars($question['label']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($question['speciality']); ?></td>
                                                
                                                <?php 
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

                                                <!-- Afficher le nombre total de techniciens ayant "Maîtrisé" pour cette question -->
                                                <td class='text-center'><?php echo $totalMaitrise; ?></td>
                                            </tr>

                                            <?php
                                            // Si le nombre de maîtrise est égal à zéro, cela signifie que la question n'a été maîtrisée par aucun technicien
                                            if ($totalMaitrise == 0) {
                                                $totalNonMaitriseQuestions++;
                                            }
                                        } ?>

                                    <!-- Ajouter une ligne pour le pourcentage de tâches non couvertes -->
                                    <tr style="background-color: #EDF2F7; position: sticky; bottom: 0; z-index: 2;">
                                        <td colspan="<?php echo count($technicians) + 2; ?>" class="text-center fw-bold" >POURCENTAGE DE TÂCHES NON COUVERTES</td>

                                        <?php
                                        // Calculer le pourcentage de questions non couvertes par aucun technicien
                                        $pourcentageNonCouverts = ($totalQuestions > 0) ? round(($totalNonMaitriseQuestions / $totalQuestions) * 100) : 0;

                                        // Définir la classe Bootstrap en fonction du pourcentage
                                        $bootstrapClass = getBootstrapClass($pourcentageNonCouverts);

                                        // Afficher uniquement dans la colonne "Totaux"
                                        echo "<td class='text-center fw-bold $bootstrapClass'>$pourcentageNonCouverts%</td>";
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

<?php include_once "partials/footer.php"; ?>
<?php } ?>
