<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ./index.php");
    exit();
} else {

    require_once "../vendor/autoload.php";

    // Create connection
    $conn = new MongoDB\Client("mongodb://localhost:27017");

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $vehicles = $academy->vehicles;
    $quizzes = $academy->quizzes;
    $allocations = $academy->allocations;

    if (isset($_POST["submit"])) {
        $junior = $_POST["junior"];
        $senior = $_POST["senior"];
        $expert = $_POST["expert"];

        
        if (empty($junior) || empty($senior) || empty($expert)) {
            $error = "Veuillez d'abord remplir au moins un vide.";
        } else {
            $allocates = $allocations->find(["level" => $level])->toArray();
    
            foreach ($allocates as $allocate) {
                if ($allocate->activeTest == false) {
                    $allocate->activeTest = true;
    
                    $allocations->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($allocate["_id"])],
                        ['$set' => $allocate]
                    );
                }
            }
    
            $success_msg = "Tests du niveau " . $level . " disponible";
        }

    }
    ?>
<?php include_once "partials/header.php"; ?>

<!--begin::Title-->
<title>Activation des Tests | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Modal body-->
            <div class='container mt-5 w-50'>
                <img src='../public/images/logo.png' alt='10' height='170'
                    style='display: block; margin-left: auto; margin-right: auto; width: 50%;'>
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <h1 class='my-3 text-center'>Etat des tests actuel par niveau</h1><br><br>
                        <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table aria-describedby=""
                                    class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                    id="kt_customers_table">
                                    <thead>
                                        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" aria-label=""
                                                style="width: 29.8906px;">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input" type="checkbox" value="1">
                                                </div>
                                            </th>
                                            <th class="min-w-125px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                                rowspan="1" colspan="1"
                                                aria-label="Customer Name: activate to sort column ascending"
                                                style="width: 125px;">Niveau Junior
                                            </th>
                                            <th class="min-w-125px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                                rowspan="1" colspan="1"
                                                aria-label="Email: activate to sort column ascending"
                                                style="width: 155.266px;">Niveau Senior</th>
                                            <th class="min-w-125px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                                rowspan="1" colspan="1"
                                                aria-label="Created Date: activate to sort column ascending"
                                                style="width: 152.719px;">Niveau Expert</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600" id="table">
                                        <?php
                                        $allocateJunior = $allocations->findOne([ 'level' => 'Junior' ]);
                                        $allocateSenior = $allocations->findOne([ 'level' => 'Senior' ]);
                                        $allocateExpert = $allocations->findOne([ 'level' => 'Expert' ]); ?>
                                        <tr class="odd" etat="">
                                            <!-- <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" id="checkbox" type="checkbox"
                                                        onclick="enable()" value="<?php echo $allocates->_id; ?>">
                                                </div>
                                            </td> -->
                                            <td></td>
                                            <?php if ($allocateJunior->activeTest == true) { ?>
                                            <td class="text-center">
                                                Activé
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center">
                                                Désactivé
                                            </td>
                                            <?php } ?>
                                            <?php if (isset($allocateSenior->activeTest) == true) { ?>
                                            <td class="text-center">
                                                Activé
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center">
                                                Désactivé
                                            </td>
                                            <?php } ?>
                                            <?php if (isset($allocateExpert->activeTest) == true) { ?>
                                            <td class="text-center">
                                                Activé
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center">
                                                Désactivé
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body--><br><br>
                <h1 class='my-3 text-center'>Activation des tests</h1>

                <?php if (isset($success_msg)) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <center><strong><?php echo $success_msg; ?></strong></center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                <?php if (isset($error_msg)) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <center><strong><?php echo $error_msg; ?></strong></center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>

                <form method="POST"><br>
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table aria-describedby=""
                                    class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                    id="kt_customers_table">
                                    <thead>
                                        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" aria-label=""
                                                style="width: 29.8906px;">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input" type="checkbox" value="1">
                                                </div>
                                            </th>
                                            <th class="min-w-125px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                                rowspan="1" colspan="1"
                                                aria-label="Customer Name: activate to sort column ascending"
                                                style="width: 125px;">Niveau Junior
                                            </th>
                                            <th class="min-w-125px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                                rowspan="1" colspan="1"
                                                aria-label="Email: activate to sort column ascending"
                                                style="width: 155.266px;">Niveau Senior</th>
                                            <th class="min-w-125px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                                rowspan="1" colspan="1"
                                                aria-label="Created Date: activate to sort column ascending"
                                                style="width: 152.719px;">Niveau Expert</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600" id="table">
                                        <tr class="odd" etat="">
                                            <!-- <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input" id="checkbox" type="checkbox"
                                                        onclick="enable()" value="<?php echo $validates->_id; ?>">
                                                </div>
                                            </td> -->
                                            <td></td>
                                            <td class="text-center">
                                                <!--begin::Input group-->
                                                <div class="d-flex flex-column mb-7 fv-row">
                                                    <!--begin::Input-->
                                                        <select name="junior" multiple aria-label="Select a Country" data-control="select2"
                                                            data-placeholder="Sélectionnez..."
                                                            class="form-select form-select-solid fw-bold">
                                                            <option>Sélectionnez...</option>
                                                            <option value="Activé">
                                                                Activé
                                                            </option>
                                                            <option value="Désactivé">
                                                                Désactivé
                                                            </option>
                                                        </select>
                                                    <!--end::Input-->
                                                    <?php if (isset($error)) { ?>
                                                    <span class='text-danger'>
                                                        <?php echo $error; ?>
                                                    </span>
                                                    <?php } ?>
                                                </div>
                                                <!--end::Input group-->
                                                <input class='form-control form-control-solid' placeholder='' name='junior' />
                                            </td>
                                            <td class="text-center">
                                                <!--begin::Input group-->
                                                <div class="d-flex flex-column mb-7 fv-row">
                                                    <!--begin::Input-->
                                                        <select name="senior" multiple aria-label="Select a Country" data-control="select2"
                                                            data-placeholder="Sélectionnez..."
                                                            class="form-select form-select-solid fw-bold">
                                                            <option>Sélectionnez...</option>
                                                            <option value="Activé">
                                                                Activé
                                                            </option>
                                                            <option value="Désactivé">
                                                                Désactivé
                                                            </option>
                                                        </select>
                                                    <!--end::Input-->
                                                    <?php if (isset($error)) { ?>
                                                    <span class='text-danger'>
                                                        <?php echo $error; ?>
                                                    </span>
                                                    <?php } ?>
                                                </div>
                                                <!--end::Input group-->
                                            </td>
                                            <td class="text-center">
                                                <!--begin::Input group-->
                                                <div class="d-flex flex-column mb-7 fv-row">
                                                    <!--begin::Input-->
                                                        <select name="expert" multiple aria-label="Select a Country" data-control="select2"
                                                            data-placeholder="Sélectionnez..."
                                                            class="form-select form-select-solid fw-bold">
                                                            <option>Sélectionnez...</option>
                                                            <option value="Activé">
                                                                Activé
                                                            </option>
                                                            <option value="Désactivé">
                                                                Désactivé
                                                            </option>
                                                        </select>
                                                    <!--end::Input-->
                                                    <?php if (isset($error)) { ?>
                                                    <span class='text-danger'>
                                                        <?php echo $error; ?>
                                                    </span>
                                                    <?php } ?>
                                                </div>
                                                <!--end::Input group-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                    <!--end::Scroll-->
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="submit" name="submit" class=" btn btn-primary">
                            <span class="indicator-label">
                                Valider
                            </span>
                            <span class="indicator-progress">
                                Patientez... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
            <!--end::Modal body-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->
<?php include_once "partials/footer.php"; ?>
<?php
} ?>
