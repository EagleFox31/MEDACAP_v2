<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
?><?php
require_once '../vendor/autoload.php';
    
// Create connection
$conn = new MongoDB\Client('mongodb://localhost:27017');
    
 // Connecting in database
 $academy = $conn->academy;
    
// Connecting in collections
$users = $academy->users;
$results = $academy->results;


$manager = $users->findOne([
    '_id' => new MongoDB\BSON\ObjectId($_SESSION[ 'id' ])
]);

$resultFacJu = $results->aggregate([
    [
        '$match' => [
            '$and' => [
                [
                    'user' => [ '$in' => $manager[ 'users' ] ],
                    'level' => 'Junior',
                    'type' => 'Factuel',
                ],
            ],
        ],
    ],
    [
        '$group' => [
            '_id' => '$user',
            'total' => ['$sum' => '$total'],
            'score' => ['$sum' => '$score'],
        ],
    ],
    [
        '$project' => [
            '_id' => 0,
            'user' => '$_id',
            'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
        ],
    ],
]);
$resultFacSe = $results->aggregate([
    [
        '$match' => [
            '$and' => [
                [
                    'user' => [ '$in' => $manager[ 'users' ] ],
                    'level' => 'Senior',
                    'type' => 'Factuel',
                ],
            ],
        ],
    ],
    [
        '$group' => [
            '_id' => '$user',
            'total' => ['$sum' => '$total'],
            'score' => ['$sum' => '$score'],
        ],
    ],
    [
        '$project' => [
            '_id' => 0,
            'user' => '$_id',
            'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
        ],
    ],
]);
$resultFacEx = $results->aggregate([
    [
        '$match' => [
            '$and' => [
                [
                    'user' => [ '$in' => $manager[ 'users' ] ],
                    'level' => 'Expert',
                    'type' => 'Factuel',
                ],
            ],
        ],
    ],
    [
        '$group' => [
            '_id' => '$user',
            'total' => ['$sum' => '$total'],
            'score' => ['$sum' => '$score'],
        ],
    ],
    [
        '$project' => [
            '_id' => 0,
            'user' => '$_id',
            'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
        ],
    ],
]);

$resultDeclaJu = $results->aggregate([
    [
        '$match' => [
            '$and' => [
                [
                    'user' => [ '$in' => $manager[ 'users' ] ],
                    'level' => 'Junior',
                    'type' => 'Declaratif',
                    'typeR' => 'Technicien',
                ],
            ],
        ],
    ],
    [
        '$group' => [
            '_id' => '$user',
            'total' => ['$sum' => '$total'],
            'score' => ['$sum' => '$score'],
        ],
    ],
    [
        '$project' => [
            '_id' => 0,
            'user' => '$_id',
            'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
        ],
    ],
]);
$resultDeclaSe = $results->aggregate([
    [
        '$match' => [
            '$and' => [
                [
                    'user' => [ '$in' => $manager[ 'users' ] ],
                    'level' => 'Senior',
                    'type' => 'Declaratif',
                    'typeR' => 'Technicien',
                ],
            ],
        ],
    ],
    [
        '$group' => [
            '_id' => '$user',
            'total' => ['$sum' => '$total'],
            'score' => ['$sum' => '$score'],
        ],
    ],
    [
        '$project' => [
            '_id' => 0,
            'user' => '$_id',
            'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
        ],
    ],
]);
$resultDeclaEx = $results->aggregate([
    [
        '$match' => [
            '$and' => [
                [
                    'user' => [ '$in' => $manager[ 'users' ] ],
                    'level' => 'Expert',
                    'type' => 'Declaratif',
                    'typeR' => 'Technicien',
                ],
            ],
        ],
    ],
    [
        '$group' => [
            '_id' => '$user',
            'total' => ['$sum' => '$total'],
            'score' => ['$sum' => '$score'],
        ],
    ],
    [
        '$project' => [
            '_id' => 0,
            'user' => '$_id',
            'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
        ],
    ],
]);

$arrResultFacJu = iterator_to_array($resultFacJu);
$arrResultFacSe = iterator_to_array($resultFacSe);
$arrResultFacEx = iterator_to_array($resultFacEx);
$arrResultDeclaJu = iterator_to_array($resultDeclaJu);
$arrResultDeclaSe = iterator_to_array($resultDeclaSe);
$arrResultDeclaEx = iterator_to_array($resultDeclaEx);
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Listes des Résultats | CFAO Mobility Academy</title>
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
                    Listes des résultats </h1>
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
                                        <th class="min-w-200px sorting" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="3" aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Techniciens
                                        </th>
                                        <th class="min-w-150px sorting text-center" tabindex="0"
                                            aria-controls="kt_customers_table" colspan="2"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">
                                            Niveau Junior</th>
                                        <th class="min-w-150px sorting text-center" tabindex="0"
                                            aria-controls="kt_customers_table" colspan="2"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">
                                            Niveau Senior</th>
                                        <th class="min-w-150px sorting text-center" tabindex="0"
                                            aria-controls="kt_customers_table" colspan="2"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">
                                            Niveau Expert</th>
                                    <tr></tr>
                                    <th class="min-w-80px sorting text-gray-400 fw-bold fs-7 text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Payment Method: activate to sort column ascending"
                                        style="width: 126.516px;">Savoir
                                    </th>
                                    <th class="min-w-120px sorting text-gray-400 fw-bold fs-7 text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Created Date: activate to sort column ascending"
                                        style="width: 152.719px;">Savoir-faire
                                    </th>
                                    <th class="min-w-80px sorting text-gray-400 fw-bold fs-7 text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Payment Method: activate to sort column ascending"
                                        style="width: 126.516px;">Savoir
                                    </th>
                                    <th class="min-w-120px sorting text-gray-400 fw-bold fs-7 text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Created Date: activate to sort column ascending"
                                        style="width: 152.719px;">Savoir-faire
                                    </th>
                                    <th class="min-w-80px sorting text-gray-400 fw-bold fs-7 text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Payment Method: activate to sort column ascending"
                                        style="width: 126.516px;">Savoir
                                    </th>
                                    <th class="min-w-120px sorting text-gray-400 fw-bold fs-7 text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Created Date: activate to sort column ascending"
                                        style="width: 152.719px;">Savoir-faire
                                    </th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <?php
                                    for ($i = 0; $i < count($arrResultFacJu); $i++) {
                                        $user = $users->findone(['_id' => new MongoDB\BSON\ObjectId( $arrResultFacJu[$i]['user'] )]);
                                    ?>
                                    <tr class="odd">
                                        <td>
                                            <?php echo $user['firstName'] ?> <?php echo $user['lastName'] ?>
                                        </td>
                                        <td>
                                            <?php echo round($arrResultFacJu[$i]->percentage ?? "0", 0) ?>%
                                        </td>
                                        <td>
                                            <?php echo round($arrResultDeclaJu[$i]->percentage ?? "0", 0) ?>%
                                        </td>
                                        <td>
                                            <?php echo round($arrResultFacSe[$i]->percentage ?? "0", 0) ?>%
                                        </td>
                                        <td>
                                            <?php echo round($arrResultDeclaSe[$i]->percentage ?? "0", 0) ?>%
                                        </td>
                                        <td>
                                            <?php echo round($arrResultFacEx[$i]->percentage ?? "0", 0) ?>%
                                        </td>
                                        <td>
                                            <?php echo round($arrResultDeclaEx[$i]->percentage ?? "0", 0) ?>%
                                        </td>
                                        <!--end::Menu-->
                                    </tr>
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
<?php
include_once 'partials/footer.php'
?>
<?php } ?>