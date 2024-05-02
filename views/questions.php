<?php
session_start();
include_once "language.php";

if (!isset($_SESSION["id"])) {
    header("Location: ../");
    exit();
} else {
     ?>
<?php
require_once "../vendor/autoload.php"; // Create connection
$conn = new MongoDB\Client("mongodb://localhost:27017"); // Connecting in database
$academy = $conn->academy; // Connecting in collections
$users = $academy->users;
$quizzes = $academy->quizzes;
$questions = $academy->questions;
$allocations = $academy->allocations;
if (isset($_POST["update"])) {
    $id = $_POST["questionID"];
    $ref = $_POST["ref"];
    $label = $_POST["label"];
    $proposal1 = $_POST["proposal1"];
    $proposal2 = $_POST["proposal2"];
    $proposal3 = $_POST["proposal3"];
    $proposal4 = $_POST["proposal4"];
    $answer = $_POST["answer"];
    $type = $_POST["type"];
    $speciality = $_POST["speciality"];
    $level = $_POST["level"];
    $image = $_FILES["image"]["name"];
    $question = [
        "ref" => $ref,
        "label" => ucfirst($label),
        "proposal1" => ucfirst($proposal1),
        "proposal2" => ucfirst($proposal2),
        "proposal3" => ucfirst($proposal3),
        "proposal4" => ucfirst($proposal4),
        "type" => ucfirst($type),
        "speciality" => ucfirst($speciality),
        "level" => ucfirst($level),
        "answer" => ucfirst($answer),
        "updated" => date("d-m-Y"),
    ]; // If there is a file, update the question data with the image URL
    if (!empty($image)) {
        $tmp_name = $_FILES["image"]["tmp_name"];
        $folder = "../public/files/" . $image;
        move_uploaded_file($tmp_name, $folder);
        $questions->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($id)],
            [
                '$set' => [
                    "ref" => $ref,
                    "label" => ucfirst($label),
                    "proposal1" => ucfirst($proposal1),
                    "proposal2" => ucfirst($proposal2),
                    "proposal3" => ucfirst($proposal3),
                    "proposal4" => ucfirst($proposal4),
                    "type" => ucfirst($type),
                    "speciality" => ucfirst($speciality),
                    "level" => ucfirst($level),
                    "answer" => ucfirst($answer),
                    "image" => $image,
                    "updated" => date("d-m-Y"),
                ],
            ]
        );
        $success_msg = "Question modifiée avec succès.";
    } else {
        // Update the question in the collection
        $questions->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($id)],
            ['$set' => $question]
        );
        $success_msg = "Question modifiée avec succès.";
    }
}
if (isset($_POST["delet"])) {
    $id = new MongoDB\BSON\ObjectId($_POST["questionID"]);
    $question = $questions->findOne(["_id" => $id]);
    $question->active = false;
    $question->updated = date("d-m-Y");
    $questions->updateOne(["_id" => $id], ['$set' => $question]);
    $success_msg = "Question supprimée avec succes.";
}
?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $list_question ?> | CFAO Mobility Academy</title>
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
                    <?php echo $list_question ?> </h1>
                <!--end::Title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="<?php echo $recherche?>">
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Info-->
            <!--begin::Actions-->
            <!-- <div class="d-flex align-items-center flex-nowrap text-nowrap py-1">
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="edit" title="Cliquez ici pour modifier la question" data-bs-toggle="modal"
                        class="btn btn-primary">
                        Modifier
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="delete" title="Cliquez ici pour supprimer la question"
                        data-bs-toggle="modal" class="btn btn-danger">
                        Supprimer
                    </button>
                </div>
            </div> -->
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->

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
                                data-kt-customer-table-filter="search" id="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Recherche">
                        </div> -->
                <!--end::Search-->
                <!-- </div> -->
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <!--begin::Card toolbar-->
                <!-- <div class="card-toolbar"> -->
                <!--begin::Toolbar-->
                <!-- <div class="d-flex justify-content-end"
                            data-kt-customer-table-toolbar="base"> -->
                <!--begin::Filter-->
                <!-- <div class="w-150px me-3" id="etat"> -->
                <!--begin::Select2-->
                <!-- <select
                                    id="select"
                                    class="form-select form-select-solid"
                                    data-control="select2"
                                    data-hide-search="true"
                                    data-placeholder="Etat"
                                    data-kt-ecommerce-order-filter="etat">
                                    <option></option>
                                    <option value="tous">Tous
                                    </option>
                                    <option value="true">
                                        Active</option>
                                    <option value="false">
                                        Supprimé</option>
                                </select> -->
                <!--end::Select2-->
                <!-- </div> -->
                <!--end::Filter-->
                <!--begin::Export dropdown-->
                <!-- <button type="button" id="excel"
                                class="btn btn-light-primary">
                                <i
                                    class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i>
                                Excel
                            </button> -->
                <!--end::Export dropdown-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                                <button type="button" id="edit"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Modifier
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                                <button type="button" id="delete"
                                    data-bs-toggle="modal"
                                    class="btn btn-danger">
                                    Supprimer
                                </button>
                            </div> -->
                <!--end::Group actions-->
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
                                    <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" aria-label=""
                                            style="width: 29.8906px;">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            </div>
                                        </th>
                                        <th class="min-w-100px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;"><?php echo $ref ?>
                                        </th>
                                        <th class="min-w-250px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;"><?php echo $question ?>
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;"><?php echo $answer ?>
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $type ?></th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $level ?></th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $speciality ?></th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <?php
                                    $question = $questions->find([
                                        "active" => true,
                                    ]);
                                    foreach ($question as $question) { ?>
                                    <tr class="odd" etat="<?php echo $question->active; ?>">
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" id="checkbox" type="checkbox"
                                                    onclick="enable()" value="<?php echo $question->_id; ?>">
                                            </div>
                                        </td>
                                        <td data-filter="search">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary mb-1">
                                                <?php echo $question->ref ??
                                                    ""; ?>
                                            </a>
                                        </td>
                                        <td data-filter="search">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary mb-1">
                                                <?php echo $question->label; ?>
                                            </a>
                                        </td>
                                        <td data-filter="phone">
                                            <?php echo $question->answer ??
                                                ""; ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php if (
                                                $question->type == "Factuelle"
                                            ) { ?>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                <?php echo $question->type; ?>
                                            </span>
                                            <?php } ?>
                                            <?php if (
                                                $question->type == "Declarative"
                                            ) { ?>
                                            <span class="badge badge-light-warning  fs-7 m-1">
                                                <?php echo $question->type; ?>
                                            </span>
                                            <?php } ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php if (
                                                $question->level == "Junior"
                                            ) { ?>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                <?php echo $question->level; ?>
                                            </span>
                                            <?php } ?>
                                            <?php if (
                                                $question->level == "Senior"
                                            ) { ?>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                <?php echo $question->level; ?>
                                            </span>
                                            <?php } ?>
                                            <?php if (
                                                $question->level == "Expert"
                                            ) { ?>
                                            <span class="badge badge-light-warning  fs-7 m-1">
                                                <?php echo $question->level; ?>
                                            </span>
                                            <?php } ?>
                                        </td>
                                        <td data-order="subsidiary">
                                            <?php echo $question->speciality; ?>
                                        </td>
                                    </tr>
                                    <?php }
                                    ?>
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
