<?php
session_start();
require_once "../vendor/autoload.php";
include_once 'userFilters.php';
include_once "language.php";

if (!isset($_SESSION["profile"])) {
    header("Location: ../");
    exit();
} else {
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

    // Définir le nombre total de questions
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
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content" data-select2-id="select2-data-kt_content">
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
                            <table class="table table-striped table-hover table-bordered table-sm fs-6 gy-5 dataTable no-footer" id="kt_customers_table">
                                <thead class="table-dark">
                                    <tr class="text-start text-white fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-200px text-center"><?php echo $titre_question ?></th>
                                        <th class="min-w-200px text-center"><?php echo $groupe_fonctionnel ?></th>
                                        <?php foreach ($technicians as $technician) { ?>
                                            <th class="min-w-150px text-center"><?php echo htmlspecialchars($technician['firstName'] . ' ' . $technician['lastName']); ?></th>
                                        <?php } ?>
                                        <th class="min-w-150px text-center">Totaux</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsBody">
                                    <tr>
                                        <td colspan="<?php echo count($technicians) + 3; ?>" class="text-center">Loading...</td>
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
            <div class="d-flex justify-content-end align-items-center mt-3">
                <button type="button" id="excel" class="btn btn-primary me-3">
                    <i class="fas fa-file-export"></i> Exporter en Excel
                </button>
            </div>
            <!--end::Export dropdown-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->

<script src="https://code.jquery.com/jquery-3.6.3.js"></script>
<script>
    $(document).ready(function() {
        // Fonction AJAX pour charger les résultats
        $.ajax({
            url: 'getValidatedResults2.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var tbody = $('#resultsBody');
                tbody.empty();
                
                <?php foreach ($questionDecla as $question) { ?>
                    var rowHtml = "<tr>";
                    rowHtml += "<td class='text-center'><?php echo htmlspecialchars($question['label']); ?></td>";
                    rowHtml += "<td class='text-center'><?php echo htmlspecialchars($question['speciality']); ?></td>";

                    <?php foreach ($technicians as $technician) { ?>
                        var techId = "<?php echo (string)$technician['_id']; ?>";
                        var questionId = "<?php echo (string)$question['_id']; ?>";
                        var status = data[techId] && data[techId][questionId] ? data[techId][questionId] : "Non maîtrisé";
                        rowHtml += "<td class='text-center'>" + status + "</td>";
                    <?php } ?>

                    <
                    
                    rowHtml += "</tr>";
                    tbody.append(rowHtml);
                <?php } ?>
            },
            error: function() {
                $('#resultsBody').html('<tr><td colspan="<?php echo count($technicians) + 3; ?>" class="text-center text-danger">Erreur de chargement des données</td></tr>');
            }
        });

        // Exporter le tableau en Excel
        $("#excel").on("click", function() {
            let table = document.getElementsByTagName("table");
            TableToExcel.convert(table[0], {
                name: `Users.xlsx`,
                sheet: {
                    name: 'Sheet 1'
                }
            });
        });
    });
</script>

<?php include_once "partials/footer.php"; ?>

<?php } ?>
