<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
    require_once '../vendor/autoload.php';
    
    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );
    
    // Connecting in database
    $academy = $conn->academy;
    
    // Connecting in collections
    $users = $academy->users;
    $vehicles = $academy->vehicles;
    $allocations = $academy->allocations;

    $user = $_SESSION[ 'id' ];
    $technician = $users->findOne([
        '$and' => [
            [
                '_id' => new MongoDB\BSON\ObjectId($user),
                'active' => true,
            ],
        ]
    ]);
}
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Mes Affectations | CFAO Mobility Academy</title>
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
                    Mes tests</h1>
                <!--end::Title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i> <input type="text" data-kt-customer-table-filter="search"
                            id="search" class="form-control form-control-solid w-250px ps-12" placeholder="Recherche">
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
                <!--begin::Filter-->
                <!-- <div class="w-150px me-3"> -->
                <!--begin::Select2-->
                <!-- <select id="select"
                                    class="form-select form-select-solid"
                                    data-control="select2"
                                    data-hide-search="true"
                                    data-placeholder="Etat"
                                    data-kt-ecommerce-order-filter="etat">
                                    <option></option>
                                    <option value="tous">Tous
                                    </option>
                                    <option value="false">
                                        Effectué</option>
                                    <option value="true">
                                        En attente</option>
                                </select> -->
                <!--end::Select2-->
                <!-- </div> -->
                <!--end::Filter-->
                <!--begin::Export dropdown-->
                <!-- <button type="button" id="excel"
                                class="btn btn-light-primary">
                                <i class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i>
                                Excel
                            </button> -->
                <!--end::Export dropdown-->
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
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">
                                            Tests</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">Niveau Junior
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">
                                            Niveau Senior</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">Niveau Expert
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <?php
                                        $vehicleJuFac = $allocations->findOne([
                                            '$and' => [
                                                ['user' => new MongoDB\BSON\ObjectId($user)],
                                                ['level' => 'Junior'],
                                                ['type' => 'Factuel'],
                                            ]
                                        ]);
                                        $vehicleSeFac = $allocations->findOne([
                                            '$and' => [
                                                ['user' => new MongoDB\BSON\ObjectId($user)],
                                                ['level' => 'Senior'],
                                                ['type' => 'Factuel'],
                                            ]
                                        ]);
                                        $vehicleExFac = $allocations->findOne([
                                            '$and' => [
                                                ['user' => new MongoDB\BSON\ObjectId($user)],
                                                ['level' => 'Expert'],
                                                ['type' => 'Factuel'],
                                            ]
                                        ]);
                                        $vehicleJuDecla = $allocations->findOne([
                                            '$and' => [
                                                ['user' => new MongoDB\BSON\ObjectId($user)],
                                                ['level' => 'Junior'],
                                                ['type' => 'Declaratif'],
                                            ]
                                        ]);
                                        $vehicleSeDecla= $allocations->findOne([
                                            '$and' => [
                                                ['user' => new MongoDB\BSON\ObjectId($user)],
                                                ['level' => 'Senior'],
                                                ['type' => 'Declaratif'],
                                            ]
                                        ]);
                                        $vehicleExDecla = $allocations->findOne([
                                            '$and' => [
                                                ['user' => new MongoDB\BSON\ObjectId($user)],
                                                ['level' => 'Expert'],
                                                ['type' => 'Declaratif'],
                                            ]
                                        ]);
                                    ?>
                                    <?php if ($vehicleJuFac) { ?>
                                    <tr class="odd" etat="">
                                        <td>
                                            Questionnaire sur les connaissances techniques
                                        </td>
                                        <?php if ($vehicleJuFac->active == false) { ?>
                                        <td>
                                            <a href="./userQuizFactuel.php?level=Junior&id=<?php echo $technician->_id ?>"
                                                class="btn btn-light text-success fw-bolder btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                        <?php if ($vehicleJuFac->active == true) { ?>
                                        <td data-filter="email">
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
                                    <?php } ?>
                                    <?php if ($vehicleSeFac) { ?>
                                    <tr class="odd" etat="">
                                        <td>
                                            Questionnaire sur les connaissances techniques
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php if ($vehicleSeFac->active == false) { ?>
                                        <td>
                                            <a href="./userQuizFactuel.php?level=Senior&id=<?php echo $technician->_id ?>"
                                                class="btn btn-light text-success fw-bolder btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                        <?php if ($vehicleSeFac->active == true) { ?>
                                        <td data-filter="email">
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
                                    <?php } ?>
                                    <?php if ($vehicleExFac) { ?>
                                    <tr class="odd" etat="">
                                        <td>
                                            Questionnaire sur les connaissances techniques
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        </td>
                                        <?php if ($vehicleExFac->active == false) { ?>
                                        <td>
                                            <a href="./userQuizFactuel.php?level=Expert&id=<?php echo $technician->_id ?>"
                                                class="btn btn-light text-success fw-bolder btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                        <?php if ($vehicleExFac->active == true) { ?>
                                        <td data-filter="email">
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <!--end::Menu-->
                                    </tr>
                                    <?php } ?>
                                    <?php if ($vehicleJuDecla) { ?>
                                    <tr class="odd" etat="">
                                        <td>
                                            Questionnaire sur la maitrise des tâches professionnelles
                                        </td>
                                        <?php if ($vehicleJuDecla->active == false) { ?>
                                        <td>
                                            <a href="./userQuizDeclaratif.php?level=Junior&id=<?php echo $technician->_id ?>"
                                                class="btn btn-light text-success fw-bolder btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                        <?php if ($vehicleJuDecla->active == true) { ?>
                                        <td data-filter="email">
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
                                    <?php } ?>
                                    <?php if ($vehicleSeDecla) { ?>
                                    <tr class="odd" etat="">
                                        <td>
                                            Questionnaire sur la maitrise des tâches professionnelles
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php if ($vehicleSeDecla->active == false) { ?>
                                        <td>
                                            <a href="./userQuizDeclaratif.php?level=Senior&id=<?php echo $technician->_id ?>"
                                                class="btn btn-light text-success fw-bolder btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                        <?php if ($vehicleSeDecla->active == true) { ?>
                                        <td data-filter="email">
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
                                    <?php } ?>
                                    <?php if ($vehicleExDecla) { ?>
                                    <tr class="odd" etat="">
                                        <td>
                                            Questionnaire sur la maitrise des tâches professionnelles
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php if ($vehicleExDecla->active == false) { ?>
                                        <td>
                                            <a href="./userQuizDeclaratif.php?level=Senior&id=<?php echo $technician->_id ?>"
                                                class="btn btn-light text-success fw-bolder btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                        <?php if ($vehicleExDecla->active == true) { ?>
                                        <td data-filter="email">
                                            <span class="badge badge-light-success fs-7 m-1">
                                                Effectué
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <!--end::Menu-->
                                        <?php } ?>
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
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->
<?php
include_once 'partials/footer.php'
?>