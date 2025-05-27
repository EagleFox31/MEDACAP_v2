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
    if (isset($_POST["ref"])) {
        $ref = $_POST["ref"];
    }
    if (isset($_POST['title'])) {
        $title = $_POST["title"];
    }
    if (isset($_POST["label"])) {
        $label = $_POST["label"];
    }
    if (isset($_POST["proposal1"])) {
        $proposal1 = $_POST["proposal1"];
    }
    if (isset($_POST["proposal2"])) {
        $proposal2 = $_POST["proposal2"];
    }
    if (isset($_POST["proposal3"])) {
        $proposal3 = $_POST["proposal3"];
    }
    if (isset($_POST["proposal4"])) {
        $proposal4 = $_POST["proposal4"];
    }
    if (isset($_POST["answer"])) {
        $answers = $_POST["answer"];
    }
    $picture = $_FILES["image"]["name"];

    $question = $questions->findOne(["_id" => new MongoDB\BSON\ObjectId($id)]);
    if ($question->type == "Declarative") {
        $questions->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($id)],
            [
                '$set' => [
                    "ref" => $ref,
                    "label" => ucfirst($label),
                    "proposal1" => "1-".$question->speciality."-".$question->level ."-".$label."-1",
                    "proposal2" => "2-".$question->speciality."-".$question->level ."-".$label."-2",
                    "proposal3" => "3-".$question->speciality."-".$question->level ."-".$label."-3",
                    "updated" => date("d-m-Y H:I:S"),
                ],
            ]
        );
        $success_msg = $success_question_edit;
    } else {
        $question = [
            "ref" => $ref,
            "label" => ucfirst($label),
            "proposal1" => ucfirst($proposal1),
            "proposal2" => ucfirst($proposal2),
            "proposal3" => ucfirst($proposal3),
            "proposal4" => ucfirst($proposal4),
            "type" => ucfirst($type),
            "answer" => ucfirst($answers),
            "updated" => date("d-m-Y H:I:S"),
        ]; // If there is a file, update the question data with the image URL
        if (!empty($picture)) {
            $tmp_name = $_FILES["image"]["tmp_name"];
            $folder = "../public/files/" . $picture;
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
                        "answer" => ucfirst($answers),
                        "image" => $picture,
                        "updated" => date("d-m-Y H:I:S"),
                    ],
                ]
            );
            $success_msg = $success_question_edit;
        } else {
            // Update the question in the collection
            $questions->updateOne(
                ["_id" => new MongoDB\BSON\ObjectId($id)],
                ['$set' => $question]
            );
            $success_msg = $success_question_edit;
        }
    }
}

if (isset($_POST['title'])) {
    $id = $_POST["questionID"];
    $title = $_POST["title"];

    $questions->updateOne(
        ["_id" => new MongoDB\BSON\ObjectId($id)],
        ['$set' => ["title" => ucfirst($title)]]
    );
    $success_msg = $success_question_edit;
}

if (isset($_POST['speciality'])) {
    $id = $_POST["questionID"];
    $speciality = $_POST["speciality"];

    $question = $questions->findOne(["_id" => new MongoDB\BSON\ObjectId($id)]);

    $quizD = $quizzes->findOne(["questions" =>  new MongoDB\BSON\ObjectId($id)]);

    $quiz = $quizzes->findOne([
        '$and' => [
            [
                "speciality" => $speciality,
                "level" => $question->level,
                "active" => true
            ]
        ],
    ]);

    if ($question->speciality != $speciality) {
        $quizzes->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($quizD['_id'])],
            ['$pull' => ["questions" => new MongoDB\BSON\ObjectId($id)]]
        );
        $quizD['total'] -= 1;
        $quizzes->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($quizD['_id'])],
            ['$set' => $quizD]
        );
        $quizzes->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($quiz['_id'])],
            ['$push' => ["questions" => new MongoDB\BSON\ObjectId($id)]]
        );
        $quiz['total'] += 1;
        $quizzes->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($quizD['_id'])],
            ['$set' => $quizD]
        );
        $questions->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ["speciality" => ucfirst($speciality)]]
        );
    }

    $success_msg = $success_question_edit;
}

if (isset($_POST["delet"])) {
    $id = new MongoDB\BSON\ObjectId($_POST["questionID"]);
    $question = $questions->findOne(["_id" => $id]);
    $question->active = false;
    $question->deleted = date("d-m-Y H:I:S");
    $questions->updateOne(["_id" => $id], ['$set' => $question]);
    $quiz = $quizzes->findOne([
        '$and' => [
            [
                "questions" => $id ,
                "active" => true
            ],
        ],
    ]);
    $quiz['total']--;
    $quizzes->updateOne(["_id" => $quiz->_id], ['$set' => $quiz]);
    $quizzes->updateOne(["_id" => new MongoDB\BSON\ObjectId($quiz->_id)], ['$pull' => [ "questions" => $id ]]);
    $success_msg = $success_question_delet;
}
?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $title_edit_sup_question ?> | CFAO Mobility Academy</title>
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
                    <?php echo $title_edit_sup_question ?> </h1>
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
                                            <!-- <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            </div> -->
                                        </th>
                                        <th class="min-w-100px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;"><?php echo $Ref ?>
                                        </th>
                                        <th class="min-w-250px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;"><?php echo $questionType ?>
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;"><?php echo $Answer ?>
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $Type ?></th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $Level ?></th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $Speciality ?></th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $edit ?></th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;"><?php echo $delete ?></th>
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
                                            <!-- <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" id="checkbox" type="checkbox"
                                                    onclick="enable()" value="<?php echo $question->_id; ?>">
                                            </div> -->
                                        </td>
                                        <td data-filter="search">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary mb-1">
                                                <?php echo $question->ref; ?>
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
                                        <td>
                                            <button class="btn btn-icon btn-light-success w-30px h-30px me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_update_details<?php echo $question->_id; ?>">
                                                <i class="fas fa-edit fs-5"></i></button>
                                        </td>
                                        <td>
                                            <button class="btn btn-icon btn-light-danger w-30px h-30px" data-bs-toggle="modal" data-bs-target="#kt_modal_desactivate<?php echo $question->_id; ?>">
                                                <i class="fas fa-trash fs-5"></i></button>
                                        </td>
                                    </tr>
                                    <!-- begin:: Modal - Confirm suspend -->
                                    <div class="modal" id="kt_modal_desactivate<?php echo $question->_id; ?>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog modal-dialog-centered mw-450px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form" method="POST" id="kt_modal_update_user_form">
                                                    <input type="hidden" name="questionID"
                                                        value="<?php echo $question->_id; ?>">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header" id="kt_modal_update_user_header">
                                                        <!--begin::Modal title-->
                                                        <h2 class="fs-2 fw-bolder">
                                                            <?php echo $delet ?>
                                                        </h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close" data-bs-dismiss="modal">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                        height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
                                                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                        transform="rotate(45 7.41422 6)" fill="black" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->
                                                    <!--begin::Modal body-->
                                                    <div class="modal-body py-10 px-lg-17">
                                                        <h4>
                                                            <?php echo $delet_text ?>
                                                        </h4>
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset" class="btn btn-light me-3"
                                                            id="closeDesactivate" data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel">
                                                            <?php echo $non ?>
                                                        </button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit" name="delet" class="btn btn-danger">
                                                            <?php echo $oui ?>
                                                        </button>
                                                        <!--end::Button-->
                                                    </div>
                                                    <!--end::Modal footer-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                        <!-- end Modal dialog -->

                                    </div>
                                    <!-- end:: Modal - Confirm suspend -->
                                    <!--begin::Modal - Update question details-->
                                    <div class="modal" id="kt_modal_update_details<?php echo $question->_id; ?>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form" enctype="multipart/form-data" method="POST"
                                                    id="kt_modal_update_user_form">
                                                    <input type="hidden" name="questionID"
                                                        value="<?php echo $question->_id; ?>">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header" id="kt_modal_update_user_header">
                                                        <!--begin::Modal title-->
                                                        <h2 class="fs-2 fw-bolder">
                                                            <?php echo $editer_data ?></h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close"
                                                            data-kt-menu-dismiss="true" data-bs-dismiss="modal">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                        height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
                                                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                        transform="rotate(45 7.41422 6)" fill="black" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->
                                                    <!--begin::Modal body-->
                                                    <div class="modal-body py-10 px-lg-17">
                                                        <!--begin::Scroll-->
                                                        <div class="d-flex flex-column scroll-y me-n7 pe-7"
                                                            id="kt_modal_update_user_scroll" data-kt-scroll="true"
                                                            data-kt-scroll-activate="{default: false, lg: true}"
                                                            data-kt-scroll-max-height="auto"
                                                            data-kt-scroll-dependencies="#kt_modal_update_user_header"
                                                            data-kt-scroll-wrappers="#kt_modal_update_user_scroll"
                                                            data-kt-scroll-offset="300px">
                                                            <!--begin::User toggle-->
                                                            <div class="fw-boldest fs-3 rotate collapsible mb-7">
                                                                <?php echo $data ?>
                                                            </div>
                                                            <!--end::User toggle-->
                                                            <!--begin::User form-->
                                                            <div id="kt_modal_update_user_user_info"
                                                                class="collapse show">
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $Ref ?></label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="ref"
                                                                        value="<?php echo $question->ref ??
                                                                            ""; ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <?php if (
                                                                    $question->level == "Expert"
                                                                ) { ?>
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $titre_question ?></label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="title"
                                                                        value="<?php echo $question->title ?? "" ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <?php } ?>
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $label_question ?></label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="label"
                                                                        value="<?php echo $question->label; ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $image ?></label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="file"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="image" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <?php if (
                                                                    $question->type == "Factuelle"
                                                                ) { ?>
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $proposal ?>
                                                                        1</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="proposal1"
                                                                        value="<?php echo $question->proposal1; ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $proposal ?>
                                                                        2</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="proposal2"
                                                                        value="<?php echo $question->proposal2; ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $proposal ?>
                                                                        3</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="proposal3"
                                                                        value="<?php echo $question->proposal3 ??
                                                                            ""; ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2"><?php echo $proposal ?>
                                                                        4</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="proposal4"
                                                                        value="<?php echo $question->proposal4 ??
                                                                            ""; ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label class="fs-6 fw-bold mb-2">
                                                                        <span><?php echo $Answer ?></span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder="" name="answer"
                                                                        value="<?php echo $question->answer ??
                                                                            ""; ?>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <?php } ?>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div class="d-flex flex-column mb-7 fv-row">
                                                                    <!--begin::Label-->
                                                                    <label class="form-label fw-bolder text-dark fs-6">
                                                                        <span class=""><?php echo $speciality; ?></span>
                                                                        </span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                        <select name="speciality" aria-label="Select a Country" data-control="select2"
                                                                            data-placeholder="<?php echo $select_speciality; ?>"
                                                                            class="form-select form-select-solid fw-bold">
                                                                            <option value="<?php echo $question->speciality ?>"><?php echo $question->speciality ?></option>
                                                                            <option value="Arbre de Transmission">
                                                                                <?php echo $arbre; ?>
                                                                            </option>
                                                                            <option value="Assistance à la Conduite">
                                                                                <?php echo $assistanceConduite; ?>
                                                                            </option>
                                                                            <option value="Boite de Transfert">
                                                                                <?php echo $transfert; ?>
                                                                            </option>
                                                                            <option value="Boite de Vitesse">
                                                                                <?php echo $boite_vitesse; ?>
                                                                            </option>
                                                                            <option value="Boite de Vitesse Automatique">
                                                                                <?php echo $boite_vitesse_auto; ?>
                                                                            </option>
                                                                            <option value="Boite de Vitesse Mécanique">
                                                                                <?php echo $boite_vitesse_meca; ?>
                                                                            </option>
                                                                            <option value="Boite de Vitesse à Variation Continue">
                                                                                <?php echo $boite_vitesse_VC; ?>
                                                                            </option>
                                                                            <option value="Climatisation">
                                                                                <?php echo $clim; ?>
                                                                            </option>
                                                                            <option value="Demi Arbre de Roue">
                                                                                <?php echo $demi; ?>
                                                                            </option>
                                                                            <option value="Direction">
                                                                                <?php echo $direction; ?>
                                                                            </option>
                                                                            <option value="Electricité et Electronique">
                                                                                <?php echo $elec; ?>
                                                                            </option>
                                                                            <option value="Freinage">
                                                                                <?php echo $freinage; ?>
                                                                            </option>
                                                                            <option value="Freinage Electromagnétique">
                                                                                <?php echo $freinageElec; ?>
                                                                            </option>
                                                                            <option value="Freinage Hydraulique">
                                                                                <?php echo $freinageHydro; ?>
                                                                            </option>
                                                                            <option value="Freinage Pneumatique">
                                                                                <?php echo $freinagePneu; ?>
                                                                            </option>
                                                                            <option value="Hydraulique">
                                                                                <?php echo $hydraulique; ?>
                                                                            </option>
                                                                            <option value="Moteur Diesel">
                                                                                <?php echo $moteurDiesel; ?>
                                                                            </option>
                                                                            <option value="Moteur Electrique">
                                                                                <?php echo $moteurElectrique; ?>
                                                                            </option>
                                                                            <option value="Moteur Essence">
                                                                                <?php echo $moteurEssence; ?>
                                                                            </option>
                                                                            <option value="Moteur Thermique">
                                                                                <?php echo $moteurThermique; ?>
                                                                            </option>
                                                                            <option value="Réseaux de Communication">
                                                                                <?php echo $multiplexage; ?>
                                                                            </option>
                                                                            <option value="Pneumatique">
                                                                            <?php echo $pneu; ?>
                                                                            </option>
                                                                            <option value="Pont">
                                                                                <?php echo $pont; ?>
                                                                            </option>
                                                                            <option value="Reducteur">
                                                                                <?php echo $reducteur; ?>
                                                                            </option>
                                                                            <option value="Suspension">
                                                                                <?php echo $suspension; ?>
                                                                            </option>
                                                                            <option value="Suspension à Lame">
                                                                                <?php echo $suspensionLame; ?>
                                                                            </option>
                                                                            <option value="Suspension Ressort">
                                                                                <?php echo $suspensionRessort; ?>
                                                                            </option>
                                                                            <option value="Suspension Pneumatique">
                                                                                <?php echo $suspensionPneu; ?>
                                                                            </option>
                                                                            <option value="Transversale">
                                                                                <?php echo $transversale; ?>
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
                                                            </div>
                                                            <!--end::User form-->
                                                        </div>
                                                        <!--end::Scroll-->
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset" class="btn btn-light me-3"
                                                            data-kt-menu-dismiss="true" data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel"><?php echo $annuler ?></button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit" name="update" class="btn btn-primary">
                                                            <?php echo $valider ?>
                                                        </button>
                                                        <!--end::Button-->
                                                    </div>
                                                    <!--end::Modal footer-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Modal - Update user details-->
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
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                            <option value="300">300</option>
                                            <option value="500">500</option>
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
            <div class="d-flex justify-content-end align-items-center" style="margin-top: 20px;">
                <button type="button" id="excel" title="Cliquez ici pour importer la table" class="btn btn-primary">
                    <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span class="path2"></span></i>
                    Excel
                </button>
            </div>
            <!--end::Export dropdown-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->
<script>
    // Function to handle closing of the alert message
    document.addEventListener('DOMContentLoaded', function() {
        const closeButtons = document.querySelectorAll('.alert .close');
        closeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert');
                alert.remove();
            });
        });
    });
</script>
<?php include_once "partials/footer.php"; ?>
<?php
} ?>
