<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ./index.php");
    exit();
} else {
     ?>
<?php
require_once "../vendor/autoload.php"; // Create connection
$conn = new MongoDB\Client("mongodb://localhost:27017"); // Connecting in database
$academy = $conn->academy; // Connecting in collections
$users = $academy->users;
$allocations = $academy->allocations;
$exams = $academy->exams;
?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title>Liste Tests Connaissances | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Liste des tests sur les connaissances</h1>
                <!--end::Title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="Recherche...">
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <!-- <div class="card-header border-0 pt-6"> -->
                <!--begin::Card title-->
                <!-- <div class="card-title"> -->
                <!--begin::Search-->
                <!-- <div
                            class="d-flex align-items-center position-relative my-1">
                            <i
                                class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                    class="path1"></span><span
                                    class="path2"></span></i> <input type="text"
                                data-kt-customer-table-filter="search"
                                id="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Recherche">
                        </div> -->
                <!--end::Search-->
                <!-- </div> -->
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <!-- <div class="card-toolbar"> -->
                <!--begin::Toolbar-->
                <!-- <div class="d-flex justify-content-end"
                            data-kt-customer-table-toolbar="base"> -->
                <!--begin::Export-->
                <!-- <button type="button" id="excel"
                                class="btn btn-light-primary me-3"
                                data-bs-toggle="modal"
                                data-bs-target="#kt_customers_export_modal">
                                <i class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i> Excel
                            </button> -->
                <!--end::Export-->
                <!-- </div> -->
                <!--end::Toolbar-->
                <!-- </div> -->
                <!--end::Card toolbar-->
                <!-- </div> -->
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table aria-describedby=""
                                class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-200px sorting text-center" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="3"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Tests
                                        </th>
                                        <th class="min-w-150px sorting text-center" tabindex="0"
                                            aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">
                                            Niveau</th>
                                        <th class="min-w-150px sorting text-center" tabindex="0"
                                            aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">
                                            Etat</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <?php
                                    $allocateFacJu = $allocations->findOne([
                                        '$and' => [
                                            [
                                                "user" => new MongoDB\BSON\ObjectId(
                                                    $_SESSION["id"]
                                                ),
                                            ],
                                            ["type" => "Factuel"],
                                            ["level" => "Junior"],
                                            ["activeTest" => true],
                                        ],
                                    ]);
                                    $allocateFacSe = $allocations->findOne([
                                        '$and' => [
                                            [
                                                "user" => new MongoDB\BSON\ObjectId(
                                                    $_SESSION["id"]
                                                ),
                                            ],
                                            ["type" => "Factuel"],
                                            ["level" => "Senior"],
                                            ["activeTest" => true],
                                        ],
                                    ]);
                                    $allocateFacEx = $allocations->findOne([
                                        '$and' => [
                                            [
                                                "user" => new MongoDB\BSON\ObjectId(
                                                    $_SESSION["id"]
                                                ),
                                            ],
                                            ["type" => "Factuel"],
                                            ["level" => "Expert"],
                                            ["activeTest" => true],
                                        ],
                                    ]);
                                    $examJuFac = $exams->findOne([
                                        '$and' => [
                                            [
                                                "user" => new MongoDB\BSON\ObjectId(
                                                    $_SESSION["id"]
                                                ),
                                            ],
                                            [
                                                "test" => new MongoDB\BSON\ObjectId(
                                                    $allocateFacJu["test"]
                                                ),
                                            ],
                                            ["active" => true],
                                        ],
                                    ]);
                                    $examSeFac = $exams->findOne([
                                        '$and' => [
                                            [
                                                "user" => new MongoDB\BSON\ObjectId(
                                                    $_SESSION["id"]
                                                ),
                                            ],
                                            [
                                                "test" => new MongoDB\BSON\ObjectId(
                                                    $allocateFacSe["test"]
                                                ),
                                            ],
                                            ["active" => true],
                                        ],
                                    ]);
                                    $examExFac = $exams->findOne([
                                        '$and' => [
                                            [
                                                "user" => new MongoDB\BSON\ObjectId(
                                                    $_SESSION["id"]
                                                ),
                                            ],
                                            [
                                                "test" => new MongoDB\BSON\ObjectId(
                                                    $allocateFacEx["test"]
                                                ),
                                            ],
                                            ["active" => true],
                                        ],
                                    ]);
                                    ?>
                                    <tr class="odd">
                                        <td class="text-center">
                                            Des connaissances
                                        </td>
                                        <td class="text-center">
                                            Junior
                                        </td>
                                        <td class="text-center">
                                            <?php if ($allocateFacJu) { ?>
                                            <?php if ($examJuFac) { ?>
                                                    <a href="./userQuizFactuel.php?test=<?php echo $allocateFacJu[
                                                        "test"
                                                    ]; ?>&level=<?php echo $allocateFacJu[
    "level"
]; ?>&id=<?php echo $_SESSION["id"]; ?>"
                                                        class="btn btn-light btn-active-light-primary text-primary fw-bolder btn-sm"
                                                        title="Cliquez ici pour ouvrir le test"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        En cours
                                                    </a>
                                            <?php } else { ?>
                                                <?php if (
                                                    $allocateFacJu->active ==
                                                    false
                                                ) { ?>
                                                    <a href="./userQuizFactuel.php?test=<?php echo $allocateFacJu[
                                                        "test"
                                                    ]; ?>&level=<?php echo $allocateFacJu[
    "level"
]; ?>&id=<?php echo $_SESSION["id"]; ?>"
                                                        class="btn btn-light btn-active-light-primary text-primary fw-bolder btn-sm"
                                                        title="Cliquez ici pour ouvrir le test"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        A compléter par vous
                                                    </a>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-success fs-7 m-1">
                                                        Effectué
                                                    </span>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php } else { ?>
                                                <span class="badge badge-light-danger fs-7 m-1">
                                                    Non disponible
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
                                    <tr class="odd">
                                        <td class="text-center">
                                            Des connaissances
                                        </td>
                                        <td class="text-center">
                                            Senior
                                        </td>
                                        <td class="text-center">
                                            <?php if ($allocateFacSe) { ?>
                                            <?php if ($examSeFac) { ?>
                                                    <a href="./userQuizFactuel.php?test=<?php echo $allocateFacSe[
                                                        "test"
                                                    ]; ?>&level=<?php echo $allocateFacSe[
    "level"
]; ?>&id=<?php echo $_SESSION["id"]; ?>"
                                                        class="btn btn-light btn-active-light-primary text-primary fw-bolder btn-sm"
                                                        title="Cliquez ici pour ouvrir le test"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        En cours
                                                    </a>
                                            <?php } else { ?>
                                                <?php if (
                                                    $allocateFacSe->active ==
                                                    false
                                                ) { ?>
                                                    <a href="./userQuizFactuel.php?test=<?php echo $allocateFacSe[
                                                        "test"
                                                    ]; ?>&level=<?php echo $allocateFacSe[
    "level"
]; ?>&id=<?php echo $_SESSION["id"]; ?>"
                                                        class="btn btn-light btn-active-light-primary text-primary fw-bolder btn-sm"
                                                        title="Cliquez ici pour ouvrir le test"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        A compléter par vous
                                                    </a>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-success fs-7 m-1">
                                                        Effectué
                                                    </span>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php } else { ?>
                                                <span class="badge badge-light-danger fs-7 m-1">
                                                    Non disponible
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
                                    <tr class="odd">
                                        <td class="text-center">
                                            Des connaissances
                                        </td>
                                        <td class="text-center">
                                            Expert
                                        </td>
                                        <td class="text-center">
                                            <?php if ($allocateFacEx) { ?>
                                            <?php if ($examExFac) { ?>
                                                    <a href="./userQuizFactuel.php?test=<?php echo $allocateFacEx[
                                                        "test"
                                                    ]; ?>&level=<?php echo $allocateFacEx[
    "level"
]; ?>&id=<?php echo $_SESSION["id"]; ?>"
                                                        class="btn btn-light btn-active-light-primary text-primary fw-bolder btn-sm"
                                                        title="Cliquez ici pour ouvrir le test"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        En cours
                                                    </a>
                                            <?php } else { ?>
                                                <?php if (
                                                    $allocateFacEx->active ==
                                                    false
                                                ) { ?>
                                                    <a href="./userQuizFactuel.php?test=<?php echo $allocateFacEx[
                                                        "test"
                                                    ]; ?>&level=<?php echo $allocateFacEx[
    "level"
]; ?>&id=<?php echo $_SESSION["id"]; ?>"
                                                        class="btn btn-light btn-active-light-primary text-primary fw-bolder btn-sm"
                                                        title="Cliquez ici pour ouvrir le test"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        A compléter par vous
                                                    </a>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-success fs-7 m-1">
                                                        Effectué
                                                    </span>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php } else { ?>
                                                <span class="badge badge-light-danger fs-7 m-1">
                                                    Non disponible
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label><select id="kt_customers_table_length" name="kt_customers_table_length"
                                            class="form-select form-select-sm form-select-solid">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select></label>
                                </div>
                            </div>
                            <div
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination" id="kt_customers_table_paginate">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
            <!--begin::Export dropdown-->
            <!-- <div class="d-flex justify-content-end align-items-center" style="margin-top: 20px;">
                <button type="button" id="excel" title="Cliquez ici pour importer la table" class="btn btn-primary">
                    <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span class="path2"></span></i>
                    Excel
                </button>
            </div> -->
            <!--end::Export dropdown-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->
<?php include_once "partials/footer.php"; ?>
<?php
} ?>
