<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../");
    exit();
} else {

    require_once "../vendor/autoload.php";

    // Create connection
    $conn = new MongoDB\Client("mongodb://localhost:27017");

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $results = $academy->results;
    $allocations = $academy->allocations;

    // $technician = $_GET["user"];
    // $niveau = $_GET["level"];
    // $niveau = "Junior";
    // $numberTest = $_GET["numberTest"];
    $subsidiar = $_GET["subsidiary"];

    $technicians = [];
    $techs = $users->find([
        '$and' => [
            [
                "subsidiary" => $subsidiar,
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techs as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($technicians, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($technicians, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    include_once "language.php";
?>

<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $result_tech ?> | CFAO Mobility Academy</title>
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
                <h1 class="text-dark fw-bolder my-1 fs-2">
                    <?php echo $result_techs ?> <?php echo $global ?> <?php echo $by_brand ?></h1>
                <!--end::Title-->
                <!-- <div class="card-title"> -->
                    <!--begin::Search-->
                    <!-- <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="<?php echo $recherche?>">
                    </div> -->
                    <!--end::Search-->
                <!-- </div> -->
            </div>
            <!--end::Info-->
            <!--begin::Actions-->
            <!-- <div class="d-flex align-items-center flex-nowrap text-nowrap py-1">
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="users" data-bs-toggle="modal" class="btn btn-primary">
                        Liste subordonnés
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="edit" title="Cliquez ici pour modifier le technicien"
                        data-bs-toggle="modal" class="btn btn-primary">
                        Modifier
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="password" data-bs-toggle="modal"
                        title="Cliquez ici pour modifier le mot de passe du technicien" class="btn btn-primary">
                        Modifier mot de passe
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="delete" title="Cliquez ici pour supprimer le technicien"
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
                                    class="path2"></span></i>
                            <input type="text" id="search"
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
                <!-- <div class="w-150px me-3" id="etat"> -->
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
                                <i class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i>
                                Excel
                            </button> -->
                <!--end::Export dropdown-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="edit"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Modifier
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="password"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Modifier mot de passe
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
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
                                class="table align-middle table-bordered table-row-dashed fs-7 gy-3 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bolder text-uppercase ">
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan="3"
                                        aria-label="Email: activate to sort column ascending">
                                        <?php echo $technicienss ?></th>
                                    <!-- <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending">
                                        Bâteaux</th> -->
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending">
                                        <?php echo $bus ?></th>
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="5"
                                        aria-label="Email: activate to sort column ascending">
                                        <?php echo $camions ?></th>
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="2"
                                        aria-label="Email: activate to sort column ascending">
                                        <?php echo $chariots ?></th>
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="2"
                                        aria-label="Email: activate to sort column ascending">
                                        <?php echo $engins ?></th>
                                    <!-- <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending">
                                        Moto</th> -->
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="5"
                                        aria-label="Email: activate to sort column ascending">
                                        <?php echo $vl ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan="3"
                                        aria-label="Email: activate to sort column ascending">
                                        <?php echo $total_marques ?></th>
                                        <tr></tr>
                                    <!-- <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        Yamaha</th> -->
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $kingLong ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $fuso ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $hino ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.366px;">
                                        <?php echo $mercedesTruck ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $renaultTruck ?></th>
                                    <th class="min-w-135px sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $sinotruk ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $toyotaBt ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $toyotaForklift ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $jcb ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $lovol ?></th>
                                    <!-- <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        Yamaha</th> -->
                                    <!-- <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        BYD</th> -->
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.366px;">
                                        <?php echo $citroen ?></th>
                                    <th class="min-w-135px sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $mercedes ?></th>
                                    <!-- <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        Mitsubishi</th> -->
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $peugeot ?></th>
                                    <th class="min-w-135px sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.366px;">
                                        <?php echo $suzuki ?></th>
                                    <th class="min-w-135px sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="1"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                        <?php echo $toyota ?></th>
                                        <tr></tr>
                                    <!-- <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class="sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th> -->
                                </thead>
                                    <tbody class="fw-semibold text-gray-600" id="table">
                                        <?php 
                                        foreach ($technicians as $technician) {
                                            $allocateFac = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => "Junior",
                                                        "type" => "Factuel",
                                                    ],
                                                ],
                                            ]);
                                            $allocateDecla = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => "Junior",
                                                        "type" => "Declaratif",
                                                    ],
                                                ],
                                            ]);
                                            if (isset($allocateFac)) {
                                                $tech = $users->findOne([
                                                    '$and' => [
                                                        [
                                                            "_id" => $allocateFac['user'],
                                                            "active" => true,
                                                        ],
                                                    ],
                                                ]);
                                                $transmissionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Climatisation"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Climatisation"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Climatisation"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Direction"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Direction"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Direction"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Freinage"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Freinage"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Freinage"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Pont"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Pont"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Pont"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Reducteur"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Reducteur"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Reducteur"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionPneumatiqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Suspension Pneumatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionPneumatiqueDecla = $results->findOne(
                                                    [
                                                        '$and' => [
                                                            [
                                                                "user" => new MongoDB\BSON\ObjectId(
                                                                    $tech->_id
                                                                ),
                                                            ],
                                                            ["level" => "Junior"],
                                                            [
                                                                "speciality" =>
                                                                    "Suspension Pneumatique",
                                                            ],
                                                            ["type" => "Declaratif"],
                                                            ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                        ],
                                                    ]
                                                );
                                                $suspensionPneumatiqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        [
                                                            "speciality" =>
                                                                "Suspension Pneumatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transversaleFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Transversale"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transversaleDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Transversale"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                // var_dump($transversaleDecla);
                                                $transversaleMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Junior"],
                                                        ["speciality" => "Transversale"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultFac = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Factuel"],
                                                        ["typeR" => "Technicien"],
                                                        ["level" => "Junior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultDecla = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Techniciens"],
                                                        ["level" => "Junior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Managers"],
                                                        ["level" => "Junior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultTechMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Technicien - Manager"],
                                                        ["level" => "Junior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionTotalFac = $transmissionFac->total ?? 0;
                                                $assistanceConduiteTotalFac = $assistanceConduiteFac->total ?? 0;
                                                $transfertTotalFac = $transfertFac->total ?? 0;
                                                $boiteTotalFac = $boiteFac->total ?? 0;
                                                $boiteManTotalFac = $boiteManFac->total ?? 0;
                                                $boiteAutoTotalFac = $boiteAutoFac->total ?? 0;
                                                $boiteVaCoTotalFac = $boiteVaCoFac->total ?? 0;
                                                $climatisationTotalFac = $climatisationFac->total ?? 0;
                                                $demiTotalFac = $demiFac->total ?? 0;
                                                $directionTotalFac = $directionFac->total ?? 0;
                                                $electriciteTotalFac = $electriciteFac->total ?? 0;
                                                $freiTotalFac = $freiFac->total ?? 0;
                                                $freinageElecTotalFac = $freinageElecFac->total ?? 0;
                                                $freinageTotalFac = $freinageFac->total ?? 0;
                                                $freinTotalFac = $freinFac->total ?? 0;
                                                $hydrauliqueTotalFac = $hydrauliqueFac->total ?? 0;
                                                $moteurDieselTotalFac = $moteurDieselFac->total ?? 0;
                                                $moteurEssenceTotalFac = $moteurEssenceFac->total ?? 0;
                                                $moteurElecTotalFac = $moteurElecFac->total ?? 0;
                                                $moteurThermiqueTotalFac = $moteurThermiqueFac->total ?? 0;
                                                $multiplexageTotalFac = $multiplexageFac->total ?? 0;
                                                $pneuTotalFac = $pneuFac->total ?? 0;
                                                $pontTotalFac = $pontFac->total ?? 0;
                                                $reducteurTotalFac = $reducteurFac->total ?? 0;
                                                $suspensionTotalFac = $suspensionFac->total ?? 0;
                                                $suspensionLameTotalFac = $suspensionLameFac->total ?? 0;
                                                $suspensionRessortTotalFac = $suspensionRessortFac->total ?? 0;
                                                $suspensionPneumatiqueTotalFac = $suspensionPneumatiqueFac->total ?? 0;
                                                $transversaleTotalFac = $transversaleFac->total ?? 0;
                                                
                                                $transmissionTotalDecla = $transmissionDecla->total ?? 0;
                                                $assistanceConduiteTotalDecla = $assistanceConduiteDecla->total ?? 0;
                                                $transfertTotalDecla = $transfertDecla->total ?? 0;
                                                $boiteTotalDecla = $boiteDecla->total ?? 0;
                                                $boiteManTotalDecla = $boiteManDecla->total ?? 0;
                                                $boiteAutoTotalDecla = $boiteAutoDecla->total ?? 0;
                                                $boiteVaCoTotalDecla = $boiteVaCoDecla->total ?? 0;
                                                $climatisationTotalDecla = $climatisationDecla->total ?? 0;
                                                $demiTotalDecla = $demiDecla->total ?? 0;
                                                $directionTotalDecla = $directionDecla->total ?? 0;
                                                $electriciteTotalDecla = $electriciteDecla->total ?? 0;
                                                $freiTotalDecla = $freiDecla->total ?? 0;
                                                $freinageElecTotalDecla = $freinageElecDecla->total ?? 0;
                                                $freinageTotalDecla = $freinageDecla->total ?? 0;
                                                $freinTotalDecla = $freinDecla->total ?? 0;
                                                $hydrauliqueTotalDecla = $hydrauliqueDecla->total ?? 0;
                                                $moteurDieselTotalDecla = $moteurDieselDecla->total ?? 0;
                                                $moteurEssenceTotalDecla = $moteurEssenceDecla->total ?? 0;
                                                $moteurElecTotalDecla = $moteurElecDecla->total ?? 0;
                                                $moteurThermiqueTotalDecla = $moteurThermiqueDecla->total ?? 0;
                                                $multiplexageTotalDecla = $multiplexageDecla->total ?? 0;
                                                $pneuTotalDecla = $pneuDecla->total ?? 0;
                                                $pontTotalDecla = $pontDecla->total ?? 0;
                                                $reducteurTotalDecla = $reducteurDecla->total ?? 0;
                                                $suspensionTotalDecla = $suspensionDecla->total ?? 0;
                                                $suspensionLameTotalDecla = $suspensionLameDecla->total ?? 0;
                                                $suspensionRessortTotalDecla = $suspensionRessortDecla->total ?? 0;
                                                $suspensionPneumatiqueTotalDecla = $suspensionPneumatiqueDecla->total ?? 0;
                                                $transversaleTotalDecla = $transversaleDecla->total ?? 0;
                                            
                                                $transmissionScoreFac = $transmissionFac->score ?? 0;
                                                $assistanceConduiteScoreFac = $assistanceConduiteFac->score ?? 0;
                                                $transfertScoreFac = $transfertFac->score ?? 0;
                                                $boiteScoreFac = $boiteFac->score ?? 0;
                                                $boiteManScoreFac = $boiteManFac->score ?? 0;
                                                $boiteAutoScoreFac = $boiteAutoFac->score ?? 0;
                                                $boiteVaCoScoreFac = $boiteVaCoFac->score ?? 0;
                                                $climatisationScoreFac = $climatisationFac->score ?? 0;
                                                $demiScoreFac = $demiFac->score ?? 0;
                                                $directionScoreFac = $directionFac->score ?? 0;
                                                $electriciteScoreFac = $electriciteFac->score ?? 0;
                                                $freiScoreFac = $freiFac->score ?? 0;
                                                $freinageElecScoreFac = $freinageElecFac->score ?? 0;
                                                $freinageScoreFac = $freinageFac->score ?? 0;
                                                $freinScoreFac = $freinFac->score ?? 0;
                                                $hydrauliqueScoreFac = $hydrauliqueFac->score ?? 0;
                                                $moteurDieselScoreFac = $moteurDieselFac->score ?? 0;
                                                $moteurEssenceScoreFac = $moteurEssenceFac->score ?? 0;
                                                $moteurElecScoreFac = $moteurElecFac->score ?? 0;
                                                $moteurThermiqueScoreFac = $moteurThermiqueFac->score ?? 0;
                                                $multiplexageScoreFac = $multiplexageFac->score ?? 0;
                                                $pneuScoreFac = $pneuFac->score ?? 0;
                                                $pontScoreFac = $pontFac->score ?? 0;
                                                $reducteurScoreFac = $reducteurFac->score ?? 0;
                                                $suspensionScoreFac = $suspensionFac->score ?? 0;
                                                $suspensionLameScoreFac = $suspensionLameFac->score ?? 0;
                                                $suspensionRessortScoreFac = $suspensionRessortFac->score ?? 0;
                                                $suspensionPneumatiqueScoreFac = $suspensionPneumatiqueFac->score ?? 0;
                                                $transversaleScoreFac = $transversaleFac->score ?? 0;
                                                
                                                $transmissionScoreDecla = $transmissionDecla->score ?? 0;
                                                $assistanceConduiteScoreDecla = $assistanceConduiteDecla->score ?? 0;
                                                $transfertScoreDecla = $transfertDecla->score ?? 0;
                                                $boiteScoreDecla = $boiteDecla->score ?? 0;
                                                $boiteManScoreDecla = $boiteManDecla->score ?? 0;
                                                $boiteAutoScoreDecla = $boiteAutoDecla->score ?? 0;
                                                $boiteVaCoScoreDecla = $boiteVaCoDecla->score ?? 0;
                                                $climatisationScoreDecla = $climatisationDecla->score ?? 0;
                                                $demiScoreDecla = $demiDecla->score ?? 0;
                                                $directionScoreDecla = $directionDecla->score ?? 0;
                                                $electriciteScoreDecla = $electriciteDecla->score ?? 0;
                                                $freiScoreDecla = $freiDecla->score ?? 0;
                                                $freinageElecScoreDecla = $freinageElecDecla->score ?? 0;
                                                $freinageScoreDecla = $freinageDecla->score ?? 0;
                                                $freinScoreDecla = $freinDecla->score ?? 0;
                                                $hydrauliqueScoreDecla = $hydrauliqueDecla->score ?? 0;
                                                $moteurDieselScoreDecla = $moteurDieselDecla->score ?? 0;
                                                $moteurEssenceScoreDecla = $moteurEssenceDecla->score ?? 0;
                                                $moteurElecScoreDecla = $moteurElecDecla->score ?? 0;
                                                $moteurThermiqueScoreDecla = $moteurThermiqueDecla->score ?? 0;
                                                $multiplexageScoreDecla = $multiplexageDecla->score ?? 0;
                                                $pneuScoreDecla = $pneuDecla->score ?? 0;
                                                $pontScoreDecla = $pontDecla->score ?? 0;
                                                $reducteurScoreDecla = $reducteurDecla->score ?? 0;
                                                $suspensionScoreDecla = $suspensionDecla->score ?? 0;
                                                $suspensionLameScoreDecla = $suspensionLameDecla->score ?? 0;
                                                $suspensionRessortScoreDecla = $suspensionRessortDecla->score ?? 0;
                                                $suspensionPneumatiqueScoreDecla = $suspensionPneumatiqueDecla->score ?? 0;
                                                $transversaleScoreDecla = $transversaleDecla->score ?? 0;
                                                
                                                $transmissionScoreMa = $transmissionMa->score ?? 0;
                                                $assistanceConduiteScoreMa = $assistanceConduiteMa->score ?? 0;
                                                $transfertScoreMa = $transfertMa->score ?? 0;
                                                $boiteScoreMa = $boiteMa->score ?? 0;
                                                $boiteManScoreMa = $boiteManMa->score ?? 0;
                                                $boiteAutoScoreMa = $boiteAutoMa->score ?? 0;
                                                $boiteVaCoScoreMa = $boiteVaCoMa->score ?? 0;
                                                $climatisationScoreMa = $climatisationMa->score ?? 0;
                                                $demiScoreMa = $demiMa->score ?? 0;
                                                $directionScoreMa = $directionMa->score ?? 0;
                                                $electriciteScoreMa = $electriciteMa->score ?? 0;
                                                $freiScoreMa = $freiMa->score ?? 0;
                                                $freinageElecScoreMa = $freinageElecMa->score ?? 0;
                                                $freinageScoreMa = $freinageMa->score ?? 0;
                                                $freinScoreMa = $freinMa->score ?? 0;
                                                $hydrauliqueScoreMa = $hydrauliqueMa->score ?? 0;
                                                $moteurDieselScoreMa = $moteurDieselMa->score ?? 0;
                                                $moteurEssenceScoreMa = $moteurEssenceMa->score ?? 0;
                                                $moteurElecScoreMa = $moteurElecMa->score ?? 0;
                                                $moteurThermiqueScoreMa = $moteurThermiqueMa->score ?? 0;
                                                $multiplexageScoreMa = $multiplexageMa->score ?? 0;
                                                $pneuScoreMa = $pneuMa->score ?? 0;
                                                $pontScoreMa = $pontMa->score ?? 0;
                                                $reducteurScoreMa = $reducteurMa->score ?? 0;
                                                $suspensionScoreMa = $suspensionMa->score ?? 0;
                                                $suspensionLameScoreMa = $suspensionLameMa->score ?? 0;
                                                $suspensionRessortScoreMa = $suspensionRessortMa->score ?? 0;
                                                $suspensionPneumatiqueScoreMa = $suspensionPneumatiqueMa->score ?? 0;
                                                $transversaleScoreMa = $transversaleMa->score ?? 0;
                                            
                                                if (isset($resultFac)) {
                                                    $percentageFac = ($resultFac['score'] * 100) / $resultFac['total'];
                                                }
                                                if (isset($resultTechMa)) {
                                                    $percentageTechMa = ($resultTechMa['score'] * 100) / $resultTechMa['total'];
                                                }
                                            
                                                $scoreTransmission = 0;
                                                if (isset($transmissionDecla)) {
                                                    for ($i = 0; $i < count($transmissionDecla["answers"]); ++$i) {
                                                        if (
                                                            $transmissionDecla["answers"][$i] == "Oui" &&
                                                            $transmissionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransmission;
                                                        }
                                                    }
                                                }
                                                $scoreTransfert = 0;
                                                if (isset($transfertDecla)) {
                                                    for ($i = 0; $i < count($transfertDecla["answers"]); ++$i) {
                                                        if (
                                                            $transfertDecla["answers"][$i] == "Oui" &&
                                                            $transfertMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransfert;
                                                        }
                                                    }
                                                }
                                                $scoreAssistance= 0;
                                                if (isset($assistanceConduiteDecla)) {
                                                    for ($i = 0; $i < count($assistanceConduiteDecla["answers"]); ++$i) {
                                                        if (
                                                            $assistanceConduiteDecla["answers"][$i] == "Oui" &&
                                                            $assistanceConduiteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreAssistance;
                                                        }
                                                    }
                                                }
                                                $scoreBoite = 0;
                                                if (isset($boiteDecla)) {
                                                    for ($i = 0; $i < count($boiteDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteDecla["answers"][$i] == "Oui" &&
                                                            $boiteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoite;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteMan = 0;
                                                if (isset($boiteManDecla)) {
                                                    for ($i = 0; $i < count($boiteManDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteManDecla["answers"][$i] == "Oui" &&
                                                            $boiteManMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteMan;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteAuto = 0;
                                                if (isset($boiteAutoDecla)) {
                                                    for ($i = 0; $i < count($boiteAutoDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteAutoDecla["answers"][$i] == "Oui" &&
                                                            $boiteAutoMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteAuto;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteVaCo = 0;
                                                if (isset($boiteVaCoDecla)) {
                                                    for ($i = 0; $i < count($boiteVaCoDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteVaCoDecla["answers"][$i] == "Oui" &&
                                                            $boiteVaCoMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteVaCo;
                                                        }
                                                    }
                                                }
                                                $scoreClim = 0;
                                                if (isset($climatisationDecla)) {
                                                    for ($i = 0; $i < count($climatisationDecla["answers"]); ++$i) {
                                                        if (
                                                            $climatisationDecla["answers"][$i] == "Oui" &&
                                                            $climatisationMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreClim;
                                                        }
                                                    }
                                                }
                                                $scoreDemi = 0;
                                                if (isset($demiDecla)) {
                                                    for ($i = 0; $i < count($demiDecla["answers"]); ++$i) {
                                                        if (
                                                            $demiDecla["answers"][$i] == "Oui" &&
                                                            $demiMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreDemi;
                                                        }
                                                    }
                                                }
                                                $scoreDirection = 0;
                                                if (isset($directionDecla)) {
                                                    for ($i = 0; $i < count($directionDecla["answers"]); ++$i) {
                                                        if (
                                                            $directionDecla["answers"][$i] == "Oui" &&
                                                            $directionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreDirection;
                                                        }
                                                    }
                                                }
                                                $scoreElectricite = 0;
                                                if (isset($electriciteDecla)) {
                                                    for ($i = 0; $i < count($electriciteDecla["answers"]); ++$i) {
                                                        if (
                                                            $electriciteDecla["answers"][$i] == "Oui" &&
                                                            $electriciteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreElectricite;
                                                        }
                                                    }
                                                }
                                                $scoreFrein = 0;
                                                if (isset($freiDecla)) {
                                                    for ($i = 0; $i < count($freiDecla["answers"]); ++$i) {
                                                        if (
                                                            $freiDecla["answers"][$i] == "Oui" &&
                                                            $freiMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFrein;
                                                        }
                                                    }
                                                }
                                                $scoreFreinElec = 0;
                                                if (isset($freinageElecDecla)) {
                                                    for ($i = 0; $i < count($freinageElecDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinageElecDecla["answers"][$i] == "Oui" &&
                                                            $freinageElecMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinElec;
                                                        }
                                                    }
                                                }
                                                $scoreFreinHydro = 0;
                                                if (isset($freinageDecla)) {
                                                    for ($i = 0; $i < count($freinageDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinageDecla["answers"][$i] == "Oui" &&
                                                            $freinageMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinHydro;
                                                        }
                                                    }
                                                }
                                                $scoreFreinPneu = 0;
                                                if (isset($freinDecla)) {
                                                    for ($i = 0; $i < count($freinDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinDecla["answers"][$i] == "Oui" &&
                                                            $freinMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinPneu;
                                                        }
                                                    }
                                                }
                                                $scoreHydro = 0;
                                                if (isset($hydrauliqueDecla)) {
                                                    for ($i = 0; $i < count($hydrauliqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $hydrauliqueDecla["answers"][$i] == "Oui" &&
                                                            $hydrauliqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreHydro;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurDiesel = 0;
                                                if (isset($moteurDieselDecla)) {
                                                    for ($i = 0; $i < count($moteurDieselDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurDieselDecla["answers"][$i] == "Oui" &&
                                                            $moteurDieselMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurDiesel;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurElec = 0;
                                                if (isset($moteurElecDecla)) {
                                                    for ($i = 0; $i < count($moteurElecDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurElecDecla["answers"][$i] == "Oui" &&
                                                            $moteurElecMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurElec;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurEssence = 0;
                                                if (isset($moteurEssenceDecla)) {
                                                    for ($i = 0; $i < count($moteurEssenceDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurEssenceDecla["answers"][$i] == "Oui" &&
                                                            $moteurEssenceMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurEssence;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurThermique = 0;
                                                if (isset($moteurThermiqueDecla)) {
                                                    for ($i = 0; $i < count($moteurThermiqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurThermiqueDecla["answers"][$i] == "Oui" &&
                                                            $moteurThermiqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurThermique;
                                                        }
                                                    }
                                                }
                                                $scoreMultiplexage = 0;
                                                if (isset($multiplexageDecla)) {
                                                    for ($i = 0; $i < count($multiplexageDecla["answers"]); ++$i) {
                                                        if (
                                                            $multiplexageDecla["answers"][$i] == "Oui" &&
                                                            $multiplexageMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMultiplexage;
                                                        }
                                                    }
                                                }
                                                $scorePneu = 0;
                                                if (isset($pneuDecla)) {
                                                    for ($i = 0; $i < count($pneuDecla["answers"]); ++$i) {
                                                        if (
                                                            $pneuDecla["answers"][$i] == "Oui" &&
                                                            $pneuMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scorePneu;
                                                        }
                                                    }
                                                }
                                                $scorePont = 0;
                                                if (isset($pontDecla)) {
                                                    for ($i = 0; $i < count($pontDecla["answers"]); ++$i) {
                                                        if (
                                                            $pontDecla["answers"][$i] == "Oui" &&
                                                            $pontMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scorePont;
                                                        }
                                                    }
                                                }
                                                $scoreRed = 0;
                                                if (isset($reducteurDecla)) {
                                                    for ($i = 0; $i < count($reducteurDecla["answers"]); ++$i) {
                                                        if (
                                                            $reducteurDecla["answers"][$i] == "Oui" &&
                                                            $reducteurMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreRed;
                                                        }
                                                    }
                                                }
                                                $scoreSuspension = 0;
                                                if (isset($suspensionDecla)) {
                                                    for ($i = 0; $i < count($suspensionDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionDecla["answers"][$i] == "Oui" &&
                                                            $suspensionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspension;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionLame = 0;
                                                if (isset($suspensionLameDecla)) {
                                                    for ($i = 0; $i < count($suspensionLameDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionLameDecla["answers"][$i] == "Oui" &&
                                                            $suspensionLameMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionLame;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionRessort = 0;
                                                if (isset($suspensionRessortDecla)) {
                                                    for ($i = 0; $i < count($suspensionRessortDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionRessortDecla["answers"][$i] == "Oui" &&
                                                            $suspensionRessortMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionRessort;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionPneu = 0;
                                                if (isset($suspensionPneumatiqueDecla)) {
                                                    for ($i = 0; $i < count($suspensionPneumatiqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionPneumatiqueDecla["answers"][$i] == "Oui" &&
                                                            $suspensionPneumatiqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionPneu;
                                                        }
                                                    }
                                                }
                                                $scoreTransversale = 0;
                                                if (isset($transversaleDecla)) {
                                                    for ($i = 0; $i < count($transversaleDecla["answers"]); ++$i) {
                                                        if (
                                                            $transversaleDecla["answers"][$i] == "Oui" &&
                                                            $transversaleMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransversale;
                                                        }
                                                    }
                                                }
                                                // if (isset($Toyota)) {
                                                    $toyotaFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $toyotaDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                
                                                    $toyotaScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $toyotaScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $toyotaScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $toyotaScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Suzuki)) {
                                                    $suzukiFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $transversaleTotalFac;
                                                    $suzukiDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $suzukiScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreTransversale;
                                                    
                                                    $suzukiScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $transversaleScoreFac;
                                                    $suzukiScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $transversaleScoreDecla;
                                                    $suzukiScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurElecScoreMa + $moteurDieselScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Mercedes)) {
                                                    $mercedesFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $mercedesDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla+ $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $mercedesScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $mercedesScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $mercedesScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $mercedesScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Peugeot)) {
                                                    $peugeotFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $peugeotDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                            
                                                    $peugeotScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $peugeotScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $peugeotScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $peugeotScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Citroen)) {
                                                    $citroenFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $citroenDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                            
                                                    $citroenScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $citroenScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $citroenScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $citroenScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($KingLong)) {
                                                    $kingLongFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $kingLongDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $kingLongScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreTransversale;
                                                    
                                                    $kingLongScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $kingLongScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $kingLongScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Fuso)) {
                                                    $fusoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $fusoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $fusoScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $fusoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $fusoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $fusoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Hino)) {
                                                    $hinoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $hinoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $hinoScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $hinoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $hinoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $hinoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($RenalutTruck)) {
                                                    $renaultTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $renaultTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $renaultTruckScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $renaultTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $renaultTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $renaultTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($MercedesTruck)) {
                                                    $mercedesTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $mercedesTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $mercedesTruckScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $mercedesTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $mercedesTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $mercedesTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Sinotruk)) {
                                                    $sinotrukFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $sinotrukDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $sinotrukScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $sinotrukScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $sinotrukScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $sinotrukScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Jcb)) {
                                                    $jcbFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $jcbDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $jcbScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $jcbScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $jcbScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $jcbScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Lovol)) {
                                                    $lovolFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $lovolDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $lovolScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $lovolScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $lovolScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $lovolScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($ToyotaBt)) {
                                                    $toyotaBtFac = $assistanceConduiteTotalFac + $boiteTotalFac + $climatisationTotalFac + $directionTotalFac + $electriciteTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurElecTotalFac + $multiplexageTotalFac + $pneuTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $toyotaBtDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $climatisationTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurElecTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $toyotaBtScore = $scoreAssistance + $scoreBoite + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFreinElec + $scoreFreinHydro + $scoreMoteurElec + $scoreMultiplexage + $scorePneu + $scoreRed + $scoreTransversale;
                                                    
                                                    $toyotaBtScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $climatisationScoreFac + $directionScoreFac + $electriciteScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurElecScoreFac + $multiplexageScoreFac + $pneuScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $toyotaBtScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $climatisationScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurElecScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $toyotaBtScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $climatisationScoreMa + $directionScoreMa + $electriciteScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurElecScoreMa + $multiplexageScoreMa + $pneuScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($ToyotaForflift)) {
                                                    $toyotaForfliftFac = $assistanceConduiteTotalFac + $boiteTotalFac + $boiteAutoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $toyotaForfliftDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteAutoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $toyotaForfliftScore = $scoreAssistance + $scoreBoite + $scoreBoiteAuto + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinElec + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $toyotaForfliftScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $boiteAutoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $toyotaForfliftScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteAutoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $toyotaForfliftScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $boiteAutoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                            
                                            ?>
                                        <tr class="odd" style="">
                                            <td class="sorting text-black text-center hidden table-light text-uppercase gs-0"
                                            tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px; ">
                                            <?php echo $tech->firstName ?> <?php echo $tech->lastName ?>
                                        </td>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="kingLongJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'KING LONG'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($kingLongScoreFac * 100) / $kingLongFac + ($kingLongScore * 100) / $kingLongDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="kingLongJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="fusoJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'FUSO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($fusoScoreFac * 100) / $fusoFac + ($fusoScore * 100) / $fusoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="fusoJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="hinoJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'HINO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($hinoScoreFac * 100) / $hinoFac + ($hinoScore * 100) / $hinoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="hinoJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="mercedesTruckJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'MERCEDES TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesTruckScoreFac * 100) / $mercedesTruckFac + ($mercedesTruckScore * 100) / $mercedesTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="mercedesTruckJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="renaultTruckJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'RENAULT TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($renaultTruckScoreFac * 100) / $renaultTruckFac + ($renaultTruckScore * 100) / $renaultTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="renaultTruckJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="sinotrukJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'SINOTRUK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($sinotrukScoreFac * 100) / $sinotrukFac + ($sinotrukScore * 100) / $sinotrukDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="sinotrukJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaBtJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'TOYOTA BT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaBtScoreFac * 100) / $toyotaBtFac + ($toyotaBtScore * 100) / $toyotaBtDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaBtJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaForfliftJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'TOYOTA FORKLIFT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaForfliftScoreFac * 100) / $toyotaForfliftFac + ($toyotaForfliftScore * 100) / $toyotaForfliftDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaForfliftJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="jcbJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'JCB'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($jcbScoreFac * 100) / $jcbFac + ($jcbScore * 100) / $jcbDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="jcbJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="lovolJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'LOVOL'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($lovolScoreFac * 100) / $lovolFac + ($lovolScore * 100) / $lovolDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="lovolJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="citroenJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'CITROEN'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($citroenScoreFac * 100) / $citroenFac + ($citroenScore * 100) / $citroenDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="citroenJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="mercedesJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'MERCEDES'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesScoreFac * 100) / $mercedesFac + ($mercedesScore * 100) / $mercedesDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="mercedesJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="peugeotJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'PEUGEOT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($peugeotScoreFac * 100) / $peugeotFac + ($peugeotScore * 100) / $peugeotDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="peugeotJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="suzukiJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'SUZUKI'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($suzukiScoreFac * 100) / $suzukiFac + ($suzukiScore * 100) / $suzukiDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="suzukiJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaJunior">
                                            <?php for ($i=0; $i < count($tech['brand'."Junior"]); $i++) {
                                            if (
                                                $tech['brand'."Junior"][$i] == 'TOYOTA'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaScoreFac * 100) / $toyotaFac + ($toyotaScore * 100) / $toyotaDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?> 
                                            <td class="text-center hidden" id="resultJunior">
                                                <?php echo
                                                    ceil((($resultFac->score * 100) / $resultFac->total + ($resultTechMa->score * 100) / $resultTechMa->total ) / 2)
                                                ?>%
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="resultJunior">
                                                0%
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php } } ?>
                                        <tr>
                                            <td class="sorting text-black text-center table-light text-uppercase gs-0"
                                                tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                                aria-label="Email: activate to sort column ascending"
                                                style="width: 155.266px; ">
                                                <?php echo $level ?> <?php echo $junior ?>
                                            </td>
                                            <td class="text-center" id="resultKingLongJunior">
                                            </td>
                                            <td class="text-center" id="resultFusoJunior">
                                            </td>
                                            <td class="text-center" id="resultHinoJunior">
                                            </td>
                                            <td class="text-center" id="resultMercedesTruckJunior">
                                            </td>
                                            <td class="text-center" id="resultRenaultTruckJunior">
                                            </td>
                                            <td class="text-center" id="resultSinotrukJunior">
                                            </td>
                                            <td class="text-center" id="resultToyotaBtJunior">
                                            </td>
                                            <td class="text-center" id="resultToyotaForkliftJunior">
                                            </td>
                                            <td class="text-center" id="resultJcbJunior">
                                            </td>
                                            <td class="text-center" id="resultLovolJunior">
                                            </td>
                                            <td class="text-center" id="resultCitroenJunior">
                                            </td>
                                            <td class="text-center" id="resultMercedesJunior">
                                            </td>
                                            <td class="text-center" id="resultPeugeotJunior">
                                            </td>
                                            <td class="text-center" id="resultSuzukiJunior">
                                            </td>
                                            <td class="text-center" id="resultToyotaJunior">
                                            </td>
                                            <td class="text-center" id="resultTotalJunior">
                                            </td>
                                        </tr>
                                        <!--end::Menu-->
                                        <?php 
                                        foreach ($technicians as $technician) {
                                            $allocateFac = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => "Senior",
                                                        "type" => "Factuel",
                                                    ],
                                                ],
                                            ]);
                                            $allocateDecla = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => "Senior",
                                                        "type" => "Declaratif",
                                                    ],
                                                ],
                                            ]);
                                            if (isset($allocateFac)) {
                                                $tech = $users->findOne([
                                                    '$and' => [
                                                        [
                                                            "_id" => $allocateFac['user'],
                                                            "active" => true,
                                                        ],
                                                    ],
                                                ]);
                                                $transmissionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Climatisation"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Climatisation"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Climatisation"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Direction"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Direction"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Direction"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Freinage"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Freinage"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Freinage"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Pont"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Pont"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Pont"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Reducteur"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Reducteur"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Reducteur"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionPneumatiqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Suspension Pneumatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionPneumatiqueDecla = $results->findOne(
                                                    [
                                                        '$and' => [
                                                            [
                                                                "user" => new MongoDB\BSON\ObjectId(
                                                                    $tech->_id
                                                                ),
                                                            ],
                                                            ["level" => "Senior"],
                                                            [
                                                                "speciality" =>
                                                                    "Suspension Pneumatique",
                                                            ],
                                                            ["type" => "Declaratif"],
                                                            ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                        ],
                                                    ]
                                                );
                                                $suspensionPneumatiqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        [
                                                            "speciality" =>
                                                                "Suspension Pneumatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transversaleFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Transversale"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transversaleDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Transversale"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                // var_dump($transversaleDecla);
                                                $transversaleMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Senior"],
                                                        ["speciality" => "Transversale"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultFac = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Factuel"],
                                                        ["typeR" => "Technicien"],
                                                        ["level" => "Senior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultDecla = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Techniciens"],
                                                        ["level" => "Senior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Managers"],
                                                        ["level" => "Senior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultTechMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Technicien - Manager"],
                                                        ["level" => "Senior"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionTotalFac = $transmissionFac->total ?? 0;
                                                $assistanceConduiteTotalFac = $assistanceConduiteFac->total ?? 0;
                                                $transfertTotalFac = $transfertFac->total ?? 0;
                                                $boiteTotalFac = $boiteFac->total ?? 0;
                                                $boiteManTotalFac = $boiteManFac->total ?? 0;
                                                $boiteAutoTotalFac = $boiteAutoFac->total ?? 0;
                                                $boiteVaCoTotalFac = $boiteVaCoFac->total ?? 0;
                                                $climatisationTotalFac = $climatisationFac->total ?? 0;
                                                $demiTotalFac = $demiFac->total ?? 0;
                                                $directionTotalFac = $directionFac->total ?? 0;
                                                $electriciteTotalFac = $electriciteFac->total ?? 0;
                                                $freiTotalFac = $freiFac->total ?? 0;
                                                $freinageElecTotalFac = $freinageElecFac->total ?? 0;
                                                $freinageTotalFac = $freinageFac->total ?? 0;
                                                $freinTotalFac = $freinFac->total ?? 0;
                                                $hydrauliqueTotalFac = $hydrauliqueFac->total ?? 0;
                                                $moteurDieselTotalFac = $moteurDieselFac->total ?? 0;
                                                $moteurEssenceTotalFac = $moteurEssenceFac->total ?? 0;
                                                $moteurElecTotalFac = $moteurElecFac->total ?? 0;
                                                $moteurThermiqueTotalFac = $moteurThermiqueFac->total ?? 0;
                                                $multiplexageTotalFac = $multiplexageFac->total ?? 0;
                                                $pneuTotalFac = $pneuFac->total ?? 0;
                                                $pontTotalFac = $pontFac->total ?? 0;
                                                $reducteurTotalFac = $reducteurFac->total ?? 0;
                                                $suspensionTotalFac = $suspensionFac->total ?? 0;
                                                $suspensionLameTotalFac = $suspensionLameFac->total ?? 0;
                                                $suspensionRessortTotalFac = $suspensionRessortFac->total ?? 0;
                                                $suspensionPneumatiqueTotalFac = $suspensionPneumatiqueFac->total ?? 0;
                                                $transversaleTotalFac = $transversaleFac->total ?? 0;
                                                
                                                $transmissionTotalDecla = $transmissionDecla->total ?? 0;
                                                $assistanceConduiteTotalDecla = $assistanceConduiteDecla->total ?? 0;
                                                $transfertTotalDecla = $transfertDecla->total ?? 0;
                                                $boiteTotalDecla = $boiteDecla->total ?? 0;
                                                $boiteManTotalDecla = $boiteManDecla->total ?? 0;
                                                $boiteAutoTotalDecla = $boiteAutoDecla->total ?? 0;
                                                $boiteVaCoTotalDecla = $boiteVaCoDecla->total ?? 0;
                                                $climatisationTotalDecla = $climatisationDecla->total ?? 0;
                                                $demiTotalDecla = $demiDecla->total ?? 0;
                                                $directionTotalDecla = $directionDecla->total ?? 0;
                                                $electriciteTotalDecla = $electriciteDecla->total ?? 0;
                                                $freiTotalDecla = $freiDecla->total ?? 0;
                                                $freinageElecTotalDecla = $freinageElecDecla->total ?? 0;
                                                $freinageTotalDecla = $freinageDecla->total ?? 0;
                                                $freinTotalDecla = $freinDecla->total ?? 0;
                                                $hydrauliqueTotalDecla = $hydrauliqueDecla->total ?? 0;
                                                $moteurDieselTotalDecla = $moteurDieselDecla->total ?? 0;
                                                $moteurEssenceTotalDecla = $moteurEssenceDecla->total ?? 0;
                                                $moteurElecTotalDecla = $moteurElecDecla->total ?? 0;
                                                $moteurThermiqueTotalDecla = $moteurThermiqueDecla->total ?? 0;
                                                $multiplexageTotalDecla = $multiplexageDecla->total ?? 0;
                                                $pneuTotalDecla = $pneuDecla->total ?? 0;
                                                $pontTotalDecla = $pontDecla->total ?? 0;
                                                $reducteurTotalDecla = $reducteurDecla->total ?? 0;
                                                $suspensionTotalDecla = $suspensionDecla->total ?? 0;
                                                $suspensionLameTotalDecla = $suspensionLameDecla->total ?? 0;
                                                $suspensionRessortTotalDecla = $suspensionRessortDecla->total ?? 0;
                                                $suspensionPneumatiqueTotalDecla = $suspensionPneumatiqueDecla->total ?? 0;
                                                $transversaleTotalDecla = $transversaleDecla->total ?? 0;
                                            
                                                $transmissionScoreFac = $transmissionFac->score ?? 0;
                                                $assistanceConduiteScoreFac = $assistanceConduiteFac->score ?? 0;
                                                $transfertScoreFac = $transfertFac->score ?? 0;
                                                $boiteScoreFac = $boiteFac->score ?? 0;
                                                $boiteManScoreFac = $boiteManFac->score ?? 0;
                                                $boiteAutoScoreFac = $boiteAutoFac->score ?? 0;
                                                $boiteVaCoScoreFac = $boiteVaCoFac->score ?? 0;
                                                $climatisationScoreFac = $climatisationFac->score ?? 0;
                                                $demiScoreFac = $demiFac->score ?? 0;
                                                $directionScoreFac = $directionFac->score ?? 0;
                                                $electriciteScoreFac = $electriciteFac->score ?? 0;
                                                $freiScoreFac = $freiFac->score ?? 0;
                                                $freinageElecScoreFac = $freinageElecFac->score ?? 0;
                                                $freinageScoreFac = $freinageFac->score ?? 0;
                                                $freinScoreFac = $freinFac->score ?? 0;
                                                $hydrauliqueScoreFac = $hydrauliqueFac->score ?? 0;
                                                $moteurDieselScoreFac = $moteurDieselFac->score ?? 0;
                                                $moteurEssenceScoreFac = $moteurEssenceFac->score ?? 0;
                                                $moteurElecScoreFac = $moteurElecFac->score ?? 0;
                                                $moteurThermiqueScoreFac = $moteurThermiqueFac->score ?? 0;
                                                $multiplexageScoreFac = $multiplexageFac->score ?? 0;
                                                $pneuScoreFac = $pneuFac->score ?? 0;
                                                $pontScoreFac = $pontFac->score ?? 0;
                                                $reducteurScoreFac = $reducteurFac->score ?? 0;
                                                $suspensionScoreFac = $suspensionFac->score ?? 0;
                                                $suspensionLameScoreFac = $suspensionLameFac->score ?? 0;
                                                $suspensionRessortScoreFac = $suspensionRessortFac->score ?? 0;
                                                $suspensionPneumatiqueScoreFac = $suspensionPneumatiqueFac->score ?? 0;
                                                $transversaleScoreFac = $transversaleFac->score ?? 0;
                                                
                                                $transmissionScoreDecla = $transmissionDecla->score ?? 0;
                                                $assistanceConduiteScoreDecla = $assistanceConduiteDecla->score ?? 0;
                                                $transfertScoreDecla = $transfertDecla->score ?? 0;
                                                $boiteScoreDecla = $boiteDecla->score ?? 0;
                                                $boiteManScoreDecla = $boiteManDecla->score ?? 0;
                                                $boiteAutoScoreDecla = $boiteAutoDecla->score ?? 0;
                                                $boiteVaCoScoreDecla = $boiteVaCoDecla->score ?? 0;
                                                $climatisationScoreDecla = $climatisationDecla->score ?? 0;
                                                $demiScoreDecla = $demiDecla->score ?? 0;
                                                $directionScoreDecla = $directionDecla->score ?? 0;
                                                $electriciteScoreDecla = $electriciteDecla->score ?? 0;
                                                $freiScoreDecla = $freiDecla->score ?? 0;
                                                $freinageElecScoreDecla = $freinageElecDecla->score ?? 0;
                                                $freinageScoreDecla = $freinageDecla->score ?? 0;
                                                $freinScoreDecla = $freinDecla->score ?? 0;
                                                $hydrauliqueScoreDecla = $hydrauliqueDecla->score ?? 0;
                                                $moteurDieselScoreDecla = $moteurDieselDecla->score ?? 0;
                                                $moteurEssenceScoreDecla = $moteurEssenceDecla->score ?? 0;
                                                $moteurElecScoreDecla = $moteurElecDecla->score ?? 0;
                                                $moteurThermiqueScoreDecla = $moteurThermiqueDecla->score ?? 0;
                                                $multiplexageScoreDecla = $multiplexageDecla->score ?? 0;
                                                $pneuScoreDecla = $pneuDecla->score ?? 0;
                                                $pontScoreDecla = $pontDecla->score ?? 0;
                                                $reducteurScoreDecla = $reducteurDecla->score ?? 0;
                                                $suspensionScoreDecla = $suspensionDecla->score ?? 0;
                                                $suspensionLameScoreDecla = $suspensionLameDecla->score ?? 0;
                                                $suspensionRessortScoreDecla = $suspensionRessortDecla->score ?? 0;
                                                $suspensionPneumatiqueScoreDecla = $suspensionPneumatiqueDecla->score ?? 0;
                                                $transversaleScoreDecla = $transversaleDecla->score ?? 0;
                                                
                                                $transmissionScoreMa = $transmissionMa->score ?? 0;
                                                $assistanceConduiteScoreMa = $assistanceConduiteMa->score ?? 0;
                                                $transfertScoreMa = $transfertMa->score ?? 0;
                                                $boiteScoreMa = $boiteMa->score ?? 0;
                                                $boiteManScoreMa = $boiteManMa->score ?? 0;
                                                $boiteAutoScoreMa = $boiteAutoMa->score ?? 0;
                                                $boiteVaCoScoreMa = $boiteVaCoMa->score ?? 0;
                                                $climatisationScoreMa = $climatisationMa->score ?? 0;
                                                $demiScoreMa = $demiMa->score ?? 0;
                                                $directionScoreMa = $directionMa->score ?? 0;
                                                $electriciteScoreMa = $electriciteMa->score ?? 0;
                                                $freiScoreMa = $freiMa->score ?? 0;
                                                $freinageElecScoreMa = $freinageElecMa->score ?? 0;
                                                $freinageScoreMa = $freinageMa->score ?? 0;
                                                $freinScoreMa = $freinMa->score ?? 0;
                                                $hydrauliqueScoreMa = $hydrauliqueMa->score ?? 0;
                                                $moteurDieselScoreMa = $moteurDieselMa->score ?? 0;
                                                $moteurEssenceScoreMa = $moteurEssenceMa->score ?? 0;
                                                $moteurElecScoreMa = $moteurElecMa->score ?? 0;
                                                $moteurThermiqueScoreMa = $moteurThermiqueMa->score ?? 0;
                                                $multiplexageScoreMa = $multiplexageMa->score ?? 0;
                                                $pneuScoreMa = $pneuMa->score ?? 0;
                                                $pontScoreMa = $pontMa->score ?? 0;
                                                $reducteurScoreMa = $reducteurMa->score ?? 0;
                                                $suspensionScoreMa = $suspensionMa->score ?? 0;
                                                $suspensionLameScoreMa = $suspensionLameMa->score ?? 0;
                                                $suspensionRessortScoreMa = $suspensionRessortMa->score ?? 0;
                                                $suspensionPneumatiqueScoreMa = $suspensionPneumatiqueMa->score ?? 0;
                                                $transversaleScoreMa = $transversaleMa->score ?? 0;
                                            
                                                if (isset($resultFac)) {
                                                    $percentageFac = ($resultFac['score'] * 100) / $resultFac['total'];
                                                }
                                                if (isset($resultTechMa)) {
                                                    $percentageTechMa = ($resultTechMa['score'] * 100) / $resultTechMa['total'];
                                                }
                                            
                                                $scoreTransmission = 0;
                                                if (isset($transmissionDecla)) {
                                                    for ($i = 0; $i < count($transmissionDecla["answers"]); ++$i) {
                                                        if (
                                                            $transmissionDecla["answers"][$i] == "Oui" &&
                                                            $transmissionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransmission;
                                                        }
                                                    }
                                                }
                                                $scoreTransfert = 0;
                                                if (isset($transfertDecla)) {
                                                    for ($i = 0; $i < count($transfertDecla["answers"]); ++$i) {
                                                        if (
                                                            $transfertDecla["answers"][$i] == "Oui" &&
                                                            $transfertMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransfert;
                                                        }
                                                    }
                                                }
                                                $scoreAssistance= 0;
                                                if (isset($assistanceConduiteDecla)) {
                                                    for ($i = 0; $i < count($assistanceConduiteDecla["answers"]); ++$i) {
                                                        if (
                                                            $assistanceConduiteDecla["answers"][$i] == "Oui" &&
                                                            $assistanceConduiteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreAssistance;
                                                        }
                                                    }
                                                }
                                                $scoreBoite = 0;
                                                if (isset($boiteDecla)) {
                                                    for ($i = 0; $i < count($boiteDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteDecla["answers"][$i] == "Oui" &&
                                                            $boiteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoite;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteMan = 0;
                                                if (isset($boiteManDecla)) {
                                                    for ($i = 0; $i < count($boiteManDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteManDecla["answers"][$i] == "Oui" &&
                                                            $boiteManMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteMan;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteAuto = 0;
                                                if (isset($boiteAutoDecla)) {
                                                    for ($i = 0; $i < count($boiteAutoDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteAutoDecla["answers"][$i] == "Oui" &&
                                                            $boiteAutoMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteAuto;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteVaCo = 0;
                                                if (isset($boiteVaCoDecla)) {
                                                    for ($i = 0; $i < count($boiteVaCoDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteVaCoDecla["answers"][$i] == "Oui" &&
                                                            $boiteVaCoMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteVaCo;
                                                        }
                                                    }
                                                }
                                                $scoreClim = 0;
                                                if (isset($climatisationDecla)) {
                                                    for ($i = 0; $i < count($climatisationDecla["answers"]); ++$i) {
                                                        if (
                                                            $climatisationDecla["answers"][$i] == "Oui" &&
                                                            $climatisationMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreClim;
                                                        }
                                                    }
                                                }
                                                $scoreDemi = 0;
                                                if (isset($demiDecla)) {
                                                    for ($i = 0; $i < count($demiDecla["answers"]); ++$i) {
                                                        if (
                                                            $demiDecla["answers"][$i] == "Oui" &&
                                                            $demiMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreDemi;
                                                        }
                                                    }
                                                }
                                                $scoreDirection = 0;
                                                if (isset($directionDecla)) {
                                                    for ($i = 0; $i < count($directionDecla["answers"]); ++$i) {
                                                        if (
                                                            $directionDecla["answers"][$i] == "Oui" &&
                                                            $directionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreDirection;
                                                        }
                                                    }
                                                }
                                                $scoreElectricite = 0;
                                                if (isset($electriciteDecla)) {
                                                    for ($i = 0; $i < count($electriciteDecla["answers"]); ++$i) {
                                                        if (
                                                            $electriciteDecla["answers"][$i] == "Oui" &&
                                                            $electriciteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreElectricite;
                                                        }
                                                    }
                                                }
                                                $scoreFrein = 0;
                                                if (isset($freiDecla)) {
                                                    for ($i = 0; $i < count($freiDecla["answers"]); ++$i) {
                                                        if (
                                                            $freiDecla["answers"][$i] == "Oui" &&
                                                            $freiMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFrein;
                                                        }
                                                    }
                                                }
                                                $scoreFreinElec = 0;
                                                if (isset($freinageElecDecla)) {
                                                    for ($i = 0; $i < count($freinageElecDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinageElecDecla["answers"][$i] == "Oui" &&
                                                            $freinageElecMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinElec;
                                                        }
                                                    }
                                                }
                                                $scoreFreinHydro = 0;
                                                if (isset($freinageDecla)) {
                                                    for ($i = 0; $i < count($freinageDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinageDecla["answers"][$i] == "Oui" &&
                                                            $freinageMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinHydro;
                                                        }
                                                    }
                                                }
                                                $scoreFreinPneu = 0;
                                                if (isset($freinDecla)) {
                                                    for ($i = 0; $i < count($freinDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinDecla["answers"][$i] == "Oui" &&
                                                            $freinMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinPneu;
                                                        }
                                                    }
                                                }
                                                $scoreHydro = 0;
                                                if (isset($hydrauliqueDecla)) {
                                                    for ($i = 0; $i < count($hydrauliqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $hydrauliqueDecla["answers"][$i] == "Oui" &&
                                                            $hydrauliqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreHydro;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurDiesel = 0;
                                                if (isset($moteurDieselDecla)) {
                                                    for ($i = 0; $i < count($moteurDieselDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurDieselDecla["answers"][$i] == "Oui" &&
                                                            $moteurDieselMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurDiesel;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurElec = 0;
                                                if (isset($moteurElecDecla)) {
                                                    for ($i = 0; $i < count($moteurElecDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurElecDecla["answers"][$i] == "Oui" &&
                                                            $moteurElecMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurElec;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurEssence = 0;
                                                if (isset($moteurEssenceDecla)) {
                                                    for ($i = 0; $i < count($moteurEssenceDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurEssenceDecla["answers"][$i] == "Oui" &&
                                                            $moteurEssenceMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurEssence;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurThermique = 0;
                                                if (isset($moteurThermiqueDecla)) {
                                                    for ($i = 0; $i < count($moteurThermiqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurThermiqueDecla["answers"][$i] == "Oui" &&
                                                            $moteurThermiqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurThermique;
                                                        }
                                                    }
                                                }
                                                $scoreMultiplexage = 0;
                                                if (isset($multiplexageDecla)) {
                                                    for ($i = 0; $i < count($multiplexageDecla["answers"]); ++$i) {
                                                        if (
                                                            $multiplexageDecla["answers"][$i] == "Oui" &&
                                                            $multiplexageMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMultiplexage;
                                                        }
                                                    }
                                                }
                                                $scorePneu = 0;
                                                if (isset($pneuDecla)) {
                                                    for ($i = 0; $i < count($pneuDecla["answers"]); ++$i) {
                                                        if (
                                                            $pneuDecla["answers"][$i] == "Oui" &&
                                                            $pneuMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scorePneu;
                                                        }
                                                    }
                                                }
                                                $scorePont = 0;
                                                if (isset($pontDecla)) {
                                                    for ($i = 0; $i < count($pontDecla["answers"]); ++$i) {
                                                        if (
                                                            $pontDecla["answers"][$i] == "Oui" &&
                                                            $pontMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scorePont;
                                                        }
                                                    }
                                                }
                                                $scoreRed = 0;
                                                if (isset($reducteurDecla)) {
                                                    for ($i = 0; $i < count($reducteurDecla["answers"]); ++$i) {
                                                        if (
                                                            $reducteurDecla["answers"][$i] == "Oui" &&
                                                            $reducteurMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreRed;
                                                        }
                                                    }
                                                }
                                                $scoreSuspension = 0;
                                                if (isset($suspensionDecla)) {
                                                    for ($i = 0; $i < count($suspensionDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionDecla["answers"][$i] == "Oui" &&
                                                            $suspensionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspension;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionLame = 0;
                                                if (isset($suspensionLameDecla)) {
                                                    for ($i = 0; $i < count($suspensionLameDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionLameDecla["answers"][$i] == "Oui" &&
                                                            $suspensionLameMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionLame;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionRessort = 0;
                                                if (isset($suspensionRessortDecla)) {
                                                    for ($i = 0; $i < count($suspensionRessortDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionRessortDecla["answers"][$i] == "Oui" &&
                                                            $suspensionRessortMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionRessort;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionPneu = 0;
                                                if (isset($suspensionPneumatiqueDecla)) {
                                                    for ($i = 0; $i < count($suspensionPneumatiqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionPneumatiqueDecla["answers"][$i] == "Oui" &&
                                                            $suspensionPneumatiqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionPneu;
                                                        }
                                                    }
                                                }
                                                $scoreTransversale = 0;
                                                if (isset($transversaleDecla)) {
                                                    for ($i = 0; $i < count($transversaleDecla["answers"]); ++$i) {
                                                        if (
                                                            $transversaleDecla["answers"][$i] == "Oui" &&
                                                            $transversaleMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransversale;
                                                        }
                                                    }
                                                }
                                                // if (isset($Toyota)) {
                                                    $toyotaFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $toyotaDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                
                                                    $toyotaScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $toyotaScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $toyotaScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $toyotaScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Suzuki)) {
                                                    $suzukiFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $transversaleTotalFac;
                                                    $suzukiDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $suzukiScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreTransversale;
                                                    
                                                    $suzukiScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $transversaleScoreFac;
                                                    $suzukiScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $transversaleScoreDecla;
                                                    $suzukiScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurElecScoreMa + $moteurDieselScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Mercedes)) {
                                                    $mercedesFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $mercedesDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla+ $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $mercedesScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $mercedesScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $mercedesScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $mercedesScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Peugeot)) {
                                                    $peugeotFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $peugeotDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                            
                                                    $peugeotScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $peugeotScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $peugeotScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $peugeotScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Citroen)) {
                                                    $citroenFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $citroenDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                            
                                                    $citroenScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $citroenScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $citroenScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $citroenScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($KingLong)) {
                                                    $kingLongFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $kingLongDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $kingLongScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreTransversale;
                                                    
                                                    $kingLongScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $kingLongScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $kingLongScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Fuso)) {
                                                    $fusoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $fusoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $fusoScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $fusoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $fusoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $fusoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Hino)) {
                                                    $hinoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $hinoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $hinoScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $hinoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $hinoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $hinoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($RenalutTruck)) {
                                                    $renaultTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $renaultTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $renaultTruckScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $renaultTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $renaultTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $renaultTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($MercedesTruck)) {
                                                    $mercedesTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $mercedesTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $mercedesTruckScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $mercedesTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $mercedesTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $mercedesTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Sinotruk)) {
                                                    $sinotrukFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $sinotrukDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $sinotrukScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $sinotrukScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $sinotrukScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $sinotrukScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Jcb)) {
                                                    $jcbFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $jcbDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $jcbScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $jcbScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $jcbScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $jcbScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Lovol)) {
                                                    $lovolFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $lovolDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $lovolScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $lovolScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $lovolScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $lovolScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($ToyotaBt)) {
                                                    $toyotaBtFac = $assistanceConduiteTotalFac + $boiteTotalFac + $climatisationTotalFac + $directionTotalFac + $electriciteTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurElecTotalFac + $multiplexageTotalFac + $pneuTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $toyotaBtDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $climatisationTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurElecTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $toyotaBtScore = $scoreAssistance + $scoreBoite + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFreinElec + $scoreFreinHydro + $scoreMoteurElec + $scoreMultiplexage + $scorePneu + $scoreRed + $scoreTransversale;
                                                    
                                                    $toyotaBtScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $climatisationScoreFac + $directionScoreFac + $electriciteScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurElecScoreFac + $multiplexageScoreFac + $pneuScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $toyotaBtScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $climatisationScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurElecScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $toyotaBtScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $climatisationScoreMa + $directionScoreMa + $electriciteScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurElecScoreMa + $multiplexageScoreMa + $pneuScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($ToyotaForflift)) {
                                                    $toyotaForfliftFac = $assistanceConduiteTotalFac + $boiteTotalFac + $boiteAutoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $toyotaForfliftDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteAutoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $toyotaForfliftScore = $scoreAssistance + $scoreBoite + $scoreBoiteAuto + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinElec + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $toyotaForfliftScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $boiteAutoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $toyotaForfliftScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteAutoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $toyotaForfliftScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $boiteAutoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                            
                                            ?>
                                        <tr class="odd" style="">
                                            <td class="sorting text-black text-center hidden table-light text-uppercase gs-0"
                                            tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px; ">
                                            <?php echo $tech->firstName ?> <?php echo $tech->lastName ?>
                                        </td>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="kingLongSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'KING LONG'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($kingLongScoreFac * 100) / $kingLongFac + ($kingLongScore * 100) / $kingLongDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="kingLongSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="fusoSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'FUSO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($fusoScoreFac * 100) / $fusoFac + ($fusoScore * 100) / $fusoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="fusoSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="hinoSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'HINO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($hinoScoreFac * 100) / $hinoFac + ($hinoScore * 100) / $hinoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="hinoSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="mercedesTruckSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'MERCEDES TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesTruckScoreFac * 100) / $mercedesTruckFac + ($mercedesTruckScore * 100) / $mercedesTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="mercedesTruckSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="renaultTruckSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'RENAULT TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($renaultTruckScoreFac * 100) / $renaultTruckFac + ($renaultTruckScore * 100) / $renaultTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="renaultTruckSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="sinotrukSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'SINOTRUK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($sinotrukScoreFac * 100) / $sinotrukFac + ($sinotrukScore * 100) / $sinotrukDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="sinotrukSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaBtSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'TOYOTA BT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaBtScoreFac * 100) / $toyotaBtFac + ($toyotaBtScore * 100) / $toyotaBtDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaBtSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaForfliftSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'TOYOTA FORKLIFT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaForfliftScoreFac * 100) / $toyotaForfliftFac + ($toyotaForfliftScore * 100) / $toyotaForfliftDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaForfliftSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="jcbSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'JCB'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($jcbScoreFac * 100) / $jcbFac + ($jcbScore * 100) / $jcbDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="jcbSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="lovolSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'LOVOL'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($lovolScoreFac * 100) / $lovolFac + ($lovolScore * 100) / $lovolDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="lovolSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="citroenSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'CITROEN'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($citroenScoreFac * 100) / $citroenFac + ($citroenScore * 100) / $citroenDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="citroenSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="mercedesSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'MERCEDES'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesScoreFac * 100) / $mercedesFac + ($mercedesScore * 100) / $mercedesDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="mercedesSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="peugeotSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'PEUGEOT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($peugeotScoreFac * 100) / $peugeotFac + ($peugeotScore * 100) / $peugeotDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="peugeotSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="suzukiSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'SUZUKI'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($suzukiScoreFac * 100) / $suzukiFac + ($suzukiScore * 100) / $suzukiDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="suzukiSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaSenior">
                                            <?php for ($i=0; $i < count($tech['brand'."Senior"]); $i++) {
                                            if (
                                                $tech['brand'."Senior"][$i] == 'TOYOTA'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaScoreFac * 100) / $toyotaFac + ($toyotaScore * 100) / $toyotaDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?> 
                                            <td class="text-center hidden" id="resultSenior">
                                                <?php echo
                                                    ceil((($resultFac->score * 100) / $resultFac->total + ($resultTechMa->score * 100) / $resultTechMa->total ) / 2)
                                                ?>%
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="resultSenior">
                                                0%
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <!--end::Menu-->
                                        <?php } } ?>
                                        <tr>
                                            <td class="sorting text-black text-center table-light text-uppercase gs-0"
                                                tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                                aria-label="Email: activate to sort column ascending"
                                                style="width: 155.266px; ">
                                                <?php echo $level ?> <?php echo $senior ?>
                                            </td>
                                            <td class="text-center" id="resultKingLongSenior">
                                            </td>
                                            <td class="text-center" id="resultFusoSenior">
                                            </td>
                                            <td class="text-center" id="resultHinoSenior">
                                            </td>
                                            <td class="text-center" id="resultMercedesTruckSenior">
                                            </td>
                                            <td class="text-center" id="resultRenaultTruckSenior">
                                            </td>
                                            <td class="text-center" id="resultSinotrukSenior">
                                            </td>
                                            <td class="text-center" id="resultToyotaBtSenior">
                                            </td>
                                            <td class="text-center" id="resultToyotaForkliftSenior">
                                            </td>
                                            <td class="text-center" id="resultJcbSenior">
                                            </td>
                                            <td class="text-center" id="resultLovolSenior">
                                            </td>
                                            <td class="text-center" id="resultCitroenSenior">
                                            </td>
                                            <td class="text-center" id="resultMercedesSenior">
                                            </td>
                                            <td class="text-center" id="resultPeugeotSenior">
                                            </td>
                                            <td class="text-center" id="resultSuzukiSenior">
                                            </td>
                                            <td class="text-center" id="resultToyotaSenior">
                                            </td>
                                            <td class="text-center" id="resultTotalSenior">
                                            </td>
                                        </tr>
                                        <?php 
                                        foreach ($technicians as $technician) {
                                            $allocateFac = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => "Expert",
                                                        "type" => "Factuel",
                                                    ],
                                                ],
                                            ]);
                                            $allocateDecla = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => "Expert",
                                                        "type" => "Declaratif",
                                                    ],
                                                ],
                                            ]);
                                            if (isset($allocateFac)) {
                                                $tech = $users->findOne([
                                                    '$and' => [
                                                        [
                                                            "_id" => $allocateFac['user'],
                                                            "active" => true,
                                                        ],
                                                    ],
                                                ]);
                                                $transmissionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Arbre de Transmission",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $assistanceConduiteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Assistance à la Conduite",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transfertMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Boite de Transfert"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Boite de Vitesse"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteManMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Mécanique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteAutoMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse Automatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $boiteVaCoMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Boite de Vitesse à Variation Continue",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Climatisation"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Climatisation"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $climatisationMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Climatisation"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $demiMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Demi Arbre de Roue"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Direction"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Direction"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $directionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Direction"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $electriciteMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Electricité et Electronique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Freinage"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Freinage"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freiMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Freinage"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageElecMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Electromagnétique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinageMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Hydraulique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $freinMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Freinage Pneumatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $hydrauliqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Hydraulique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurDieselMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Diesel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurElecMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Electrique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurEssenceMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Essence"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $moteurThermiqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Moteur Thermique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $multiplexageMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Réseaux de Communication"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pneuMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Pneumatique"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Pont"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Pont"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $pontMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Pont"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Reducteur"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Reducteur"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $reducteurMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Reducteur"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionLameMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension à Lame"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionRessortMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Suspension Ressort"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionPneumatiqueFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Suspension Pneumatique",
                                                        ],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $suspensionPneumatiqueDecla = $results->findOne(
                                                    [
                                                        '$and' => [
                                                            [
                                                                "user" => new MongoDB\BSON\ObjectId(
                                                                    $tech->_id
                                                                ),
                                                            ],
                                                            ["level" => "Expert"],
                                                            [
                                                                "speciality" =>
                                                                    "Suspension Pneumatique",
                                                            ],
                                                            ["type" => "Declaratif"],
                                                            ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                        ],
                                                    ]
                                                );
                                                $suspensionPneumatiqueMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        [
                                                            "speciality" =>
                                                                "Suspension Pneumatique",
                                                        ],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transversaleFac = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Transversale"],
                                                        ["type" => "Factuel"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transversaleDecla = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Transversale"],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Technicien"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                // var_dump($transversaleDecla);
                                                $transversaleMa = $results->findOne([
                                                    '$and' => [
                                                        [
                                                            "user" => new MongoDB\BSON\ObjectId(
                                                                $tech->_id
                                                            ),
                                                        ],
                                                        [
                                                            "manager" => new MongoDB\BSON\ObjectId(
                                                                $tech->manager
                                                            ),
                                                        ],
                                                        ["level" => "Expert"],
                                                        ["speciality" => "Transversale"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultFac = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Factuel"],
                                                        ["typeR" => "Technicien"],
                                                        ["level" => "Expert"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultDecla = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Techniciens"],
                                                        ["level" => "Expert"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Managers"],
                                                        ["level" => "Expert"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultTechMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Technicien - Manager"],
                                                        ["level" => "Expert"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $transmissionTotalFac = $transmissionFac->total ?? 0;
                                                $assistanceConduiteTotalFac = $assistanceConduiteFac->total ?? 0;
                                                $transfertTotalFac = $transfertFac->total ?? 0;
                                                $boiteTotalFac = $boiteFac->total ?? 0;
                                                $boiteManTotalFac = $boiteManFac->total ?? 0;
                                                $boiteAutoTotalFac = $boiteAutoFac->total ?? 0;
                                                $boiteVaCoTotalFac = $boiteVaCoFac->total ?? 0;
                                                $climatisationTotalFac = $climatisationFac->total ?? 0;
                                                $demiTotalFac = $demiFac->total ?? 0;
                                                $directionTotalFac = $directionFac->total ?? 0;
                                                $electriciteTotalFac = $electriciteFac->total ?? 0;
                                                $freiTotalFac = $freiFac->total ?? 0;
                                                $freinageElecTotalFac = $freinageElecFac->total ?? 0;
                                                $freinageTotalFac = $freinageFac->total ?? 0;
                                                $freinTotalFac = $freinFac->total ?? 0;
                                                $hydrauliqueTotalFac = $hydrauliqueFac->total ?? 0;
                                                $moteurDieselTotalFac = $moteurDieselFac->total ?? 0;
                                                $moteurEssenceTotalFac = $moteurEssenceFac->total ?? 0;
                                                $moteurElecTotalFac = $moteurElecFac->total ?? 0;
                                                $moteurThermiqueTotalFac = $moteurThermiqueFac->total ?? 0;
                                                $multiplexageTotalFac = $multiplexageFac->total ?? 0;
                                                $pneuTotalFac = $pneuFac->total ?? 0;
                                                $pontTotalFac = $pontFac->total ?? 0;
                                                $reducteurTotalFac = $reducteurFac->total ?? 0;
                                                $suspensionTotalFac = $suspensionFac->total ?? 0;
                                                $suspensionLameTotalFac = $suspensionLameFac->total ?? 0;
                                                $suspensionRessortTotalFac = $suspensionRessortFac->total ?? 0;
                                                $suspensionPneumatiqueTotalFac = $suspensionPneumatiqueFac->total ?? 0;
                                                $transversaleTotalFac = $transversaleFac->total ?? 0;
                                                
                                                $transmissionTotalDecla = $transmissionDecla->total ?? 0;
                                                $assistanceConduiteTotalDecla = $assistanceConduiteDecla->total ?? 0;
                                                $transfertTotalDecla = $transfertDecla->total ?? 0;
                                                $boiteTotalDecla = $boiteDecla->total ?? 0;
                                                $boiteManTotalDecla = $boiteManDecla->total ?? 0;
                                                $boiteAutoTotalDecla = $boiteAutoDecla->total ?? 0;
                                                $boiteVaCoTotalDecla = $boiteVaCoDecla->total ?? 0;
                                                $climatisationTotalDecla = $climatisationDecla->total ?? 0;
                                                $demiTotalDecla = $demiDecla->total ?? 0;
                                                $directionTotalDecla = $directionDecla->total ?? 0;
                                                $electriciteTotalDecla = $electriciteDecla->total ?? 0;
                                                $freiTotalDecla = $freiDecla->total ?? 0;
                                                $freinageElecTotalDecla = $freinageElecDecla->total ?? 0;
                                                $freinageTotalDecla = $freinageDecla->total ?? 0;
                                                $freinTotalDecla = $freinDecla->total ?? 0;
                                                $hydrauliqueTotalDecla = $hydrauliqueDecla->total ?? 0;
                                                $moteurDieselTotalDecla = $moteurDieselDecla->total ?? 0;
                                                $moteurEssenceTotalDecla = $moteurEssenceDecla->total ?? 0;
                                                $moteurElecTotalDecla = $moteurElecDecla->total ?? 0;
                                                $moteurThermiqueTotalDecla = $moteurThermiqueDecla->total ?? 0;
                                                $multiplexageTotalDecla = $multiplexageDecla->total ?? 0;
                                                $pneuTotalDecla = $pneuDecla->total ?? 0;
                                                $pontTotalDecla = $pontDecla->total ?? 0;
                                                $reducteurTotalDecla = $reducteurDecla->total ?? 0;
                                                $suspensionTotalDecla = $suspensionDecla->total ?? 0;
                                                $suspensionLameTotalDecla = $suspensionLameDecla->total ?? 0;
                                                $suspensionRessortTotalDecla = $suspensionRessortDecla->total ?? 0;
                                                $suspensionPneumatiqueTotalDecla = $suspensionPneumatiqueDecla->total ?? 0;
                                                $transversaleTotalDecla = $transversaleDecla->total ?? 0;
                                            
                                                $transmissionScoreFac = $transmissionFac->score ?? 0;
                                                $assistanceConduiteScoreFac = $assistanceConduiteFac->score ?? 0;
                                                $transfertScoreFac = $transfertFac->score ?? 0;
                                                $boiteScoreFac = $boiteFac->score ?? 0;
                                                $boiteManScoreFac = $boiteManFac->score ?? 0;
                                                $boiteAutoScoreFac = $boiteAutoFac->score ?? 0;
                                                $boiteVaCoScoreFac = $boiteVaCoFac->score ?? 0;
                                                $climatisationScoreFac = $climatisationFac->score ?? 0;
                                                $demiScoreFac = $demiFac->score ?? 0;
                                                $directionScoreFac = $directionFac->score ?? 0;
                                                $electriciteScoreFac = $electriciteFac->score ?? 0;
                                                $freiScoreFac = $freiFac->score ?? 0;
                                                $freinageElecScoreFac = $freinageElecFac->score ?? 0;
                                                $freinageScoreFac = $freinageFac->score ?? 0;
                                                $freinScoreFac = $freinFac->score ?? 0;
                                                $hydrauliqueScoreFac = $hydrauliqueFac->score ?? 0;
                                                $moteurDieselScoreFac = $moteurDieselFac->score ?? 0;
                                                $moteurEssenceScoreFac = $moteurEssenceFac->score ?? 0;
                                                $moteurElecScoreFac = $moteurElecFac->score ?? 0;
                                                $moteurThermiqueScoreFac = $moteurThermiqueFac->score ?? 0;
                                                $multiplexageScoreFac = $multiplexageFac->score ?? 0;
                                                $pneuScoreFac = $pneuFac->score ?? 0;
                                                $pontScoreFac = $pontFac->score ?? 0;
                                                $reducteurScoreFac = $reducteurFac->score ?? 0;
                                                $suspensionScoreFac = $suspensionFac->score ?? 0;
                                                $suspensionLameScoreFac = $suspensionLameFac->score ?? 0;
                                                $suspensionRessortScoreFac = $suspensionRessortFac->score ?? 0;
                                                $suspensionPneumatiqueScoreFac = $suspensionPneumatiqueFac->score ?? 0;
                                                $transversaleScoreFac = $transversaleFac->score ?? 0;
                                                
                                                $transmissionScoreDecla = $transmissionDecla->score ?? 0;
                                                $assistanceConduiteScoreDecla = $assistanceConduiteDecla->score ?? 0;
                                                $transfertScoreDecla = $transfertDecla->score ?? 0;
                                                $boiteScoreDecla = $boiteDecla->score ?? 0;
                                                $boiteManScoreDecla = $boiteManDecla->score ?? 0;
                                                $boiteAutoScoreDecla = $boiteAutoDecla->score ?? 0;
                                                $boiteVaCoScoreDecla = $boiteVaCoDecla->score ?? 0;
                                                $climatisationScoreDecla = $climatisationDecla->score ?? 0;
                                                $demiScoreDecla = $demiDecla->score ?? 0;
                                                $directionScoreDecla = $directionDecla->score ?? 0;
                                                $electriciteScoreDecla = $electriciteDecla->score ?? 0;
                                                $freiScoreDecla = $freiDecla->score ?? 0;
                                                $freinageElecScoreDecla = $freinageElecDecla->score ?? 0;
                                                $freinageScoreDecla = $freinageDecla->score ?? 0;
                                                $freinScoreDecla = $freinDecla->score ?? 0;
                                                $hydrauliqueScoreDecla = $hydrauliqueDecla->score ?? 0;
                                                $moteurDieselScoreDecla = $moteurDieselDecla->score ?? 0;
                                                $moteurEssenceScoreDecla = $moteurEssenceDecla->score ?? 0;
                                                $moteurElecScoreDecla = $moteurElecDecla->score ?? 0;
                                                $moteurThermiqueScoreDecla = $moteurThermiqueDecla->score ?? 0;
                                                $multiplexageScoreDecla = $multiplexageDecla->score ?? 0;
                                                $pneuScoreDecla = $pneuDecla->score ?? 0;
                                                $pontScoreDecla = $pontDecla->score ?? 0;
                                                $reducteurScoreDecla = $reducteurDecla->score ?? 0;
                                                $suspensionScoreDecla = $suspensionDecla->score ?? 0;
                                                $suspensionLameScoreDecla = $suspensionLameDecla->score ?? 0;
                                                $suspensionRessortScoreDecla = $suspensionRessortDecla->score ?? 0;
                                                $suspensionPneumatiqueScoreDecla = $suspensionPneumatiqueDecla->score ?? 0;
                                                $transversaleScoreDecla = $transversaleDecla->score ?? 0;
                                                
                                                $transmissionScoreMa = $transmissionMa->score ?? 0;
                                                $assistanceConduiteScoreMa = $assistanceConduiteMa->score ?? 0;
                                                $transfertScoreMa = $transfertMa->score ?? 0;
                                                $boiteScoreMa = $boiteMa->score ?? 0;
                                                $boiteManScoreMa = $boiteManMa->score ?? 0;
                                                $boiteAutoScoreMa = $boiteAutoMa->score ?? 0;
                                                $boiteVaCoScoreMa = $boiteVaCoMa->score ?? 0;
                                                $climatisationScoreMa = $climatisationMa->score ?? 0;
                                                $demiScoreMa = $demiMa->score ?? 0;
                                                $directionScoreMa = $directionMa->score ?? 0;
                                                $electriciteScoreMa = $electriciteMa->score ?? 0;
                                                $freiScoreMa = $freiMa->score ?? 0;
                                                $freinageElecScoreMa = $freinageElecMa->score ?? 0;
                                                $freinageScoreMa = $freinageMa->score ?? 0;
                                                $freinScoreMa = $freinMa->score ?? 0;
                                                $hydrauliqueScoreMa = $hydrauliqueMa->score ?? 0;
                                                $moteurDieselScoreMa = $moteurDieselMa->score ?? 0;
                                                $moteurEssenceScoreMa = $moteurEssenceMa->score ?? 0;
                                                $moteurElecScoreMa = $moteurElecMa->score ?? 0;
                                                $moteurThermiqueScoreMa = $moteurThermiqueMa->score ?? 0;
                                                $multiplexageScoreMa = $multiplexageMa->score ?? 0;
                                                $pneuScoreMa = $pneuMa->score ?? 0;
                                                $pontScoreMa = $pontMa->score ?? 0;
                                                $reducteurScoreMa = $reducteurMa->score ?? 0;
                                                $suspensionScoreMa = $suspensionMa->score ?? 0;
                                                $suspensionLameScoreMa = $suspensionLameMa->score ?? 0;
                                                $suspensionRessortScoreMa = $suspensionRessortMa->score ?? 0;
                                                $suspensionPneumatiqueScoreMa = $suspensionPneumatiqueMa->score ?? 0;
                                                $transversaleScoreMa = $transversaleMa->score ?? 0;
                                            
                                                if (isset($resultFac)) {
                                                    $percentageFac = ($resultFac['score'] * 100) / $resultFac['total'];
                                                }
                                                if (isset($resultTechMa)) {
                                                    $percentageTechMa = ($resultTechMa['score'] * 100) / $resultTechMa['total'];
                                                }
                                            
                                                $scoreTransmission = 0;
                                                if (isset($transmissionDecla)) {
                                                    for ($i = 0; $i < count($transmissionDecla["answers"]); ++$i) {
                                                        if (
                                                            $transmissionDecla["answers"][$i] == "Oui" &&
                                                            $transmissionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransmission;
                                                        }
                                                    }
                                                }
                                                $scoreTransfert = 0;
                                                if (isset($transfertDecla)) {
                                                    for ($i = 0; $i < count($transfertDecla["answers"]); ++$i) {
                                                        if (
                                                            $transfertDecla["answers"][$i] == "Oui" &&
                                                            $transfertMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransfert;
                                                        }
                                                    }
                                                }
                                                $scoreAssistance= 0;
                                                if (isset($assistanceConduiteDecla)) {
                                                    for ($i = 0; $i < count($assistanceConduiteDecla["answers"]); ++$i) {
                                                        if (
                                                            $assistanceConduiteDecla["answers"][$i] == "Oui" &&
                                                            $assistanceConduiteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreAssistance;
                                                        }
                                                    }
                                                }
                                                $scoreBoite = 0;
                                                if (isset($boiteDecla)) {
                                                    for ($i = 0; $i < count($boiteDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteDecla["answers"][$i] == "Oui" &&
                                                            $boiteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoite;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteMan = 0;
                                                if (isset($boiteManDecla)) {
                                                    for ($i = 0; $i < count($boiteManDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteManDecla["answers"][$i] == "Oui" &&
                                                            $boiteManMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteMan;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteAuto = 0;
                                                if (isset($boiteAutoDecla)) {
                                                    for ($i = 0; $i < count($boiteAutoDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteAutoDecla["answers"][$i] == "Oui" &&
                                                            $boiteAutoMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteAuto;
                                                        }
                                                    }
                                                }
                                                $scoreBoiteVaCo = 0;
                                                if (isset($boiteVaCoDecla)) {
                                                    for ($i = 0; $i < count($boiteVaCoDecla["answers"]); ++$i) {
                                                        if (
                                                            $boiteVaCoDecla["answers"][$i] == "Oui" &&
                                                            $boiteVaCoMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreBoiteVaCo;
                                                        }
                                                    }
                                                }
                                                $scoreClim = 0;
                                                if (isset($climatisationDecla)) {
                                                    for ($i = 0; $i < count($climatisationDecla["answers"]); ++$i) {
                                                        if (
                                                            $climatisationDecla["answers"][$i] == "Oui" &&
                                                            $climatisationMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreClim;
                                                        }
                                                    }
                                                }
                                                $scoreDemi = 0;
                                                if (isset($demiDecla)) {
                                                    for ($i = 0; $i < count($demiDecla["answers"]); ++$i) {
                                                        if (
                                                            $demiDecla["answers"][$i] == "Oui" &&
                                                            $demiMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreDemi;
                                                        }
                                                    }
                                                }
                                                $scoreDirection = 0;
                                                if (isset($directionDecla)) {
                                                    for ($i = 0; $i < count($directionDecla["answers"]); ++$i) {
                                                        if (
                                                            $directionDecla["answers"][$i] == "Oui" &&
                                                            $directionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreDirection;
                                                        }
                                                    }
                                                }
                                                $scoreElectricite = 0;
                                                if (isset($electriciteDecla)) {
                                                    for ($i = 0; $i < count($electriciteDecla["answers"]); ++$i) {
                                                        if (
                                                            $electriciteDecla["answers"][$i] == "Oui" &&
                                                            $electriciteMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreElectricite;
                                                        }
                                                    }
                                                }
                                                $scoreFrein = 0;
                                                if (isset($freiDecla)) {
                                                    for ($i = 0; $i < count($freiDecla["answers"]); ++$i) {
                                                        if (
                                                            $freiDecla["answers"][$i] == "Oui" &&
                                                            $freiMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFrein;
                                                        }
                                                    }
                                                }
                                                $scoreFreinElec = 0;
                                                if (isset($freinageElecDecla)) {
                                                    for ($i = 0; $i < count($freinageElecDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinageElecDecla["answers"][$i] == "Oui" &&
                                                            $freinageElecMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinElec;
                                                        }
                                                    }
                                                }
                                                $scoreFreinHydro = 0;
                                                if (isset($freinageDecla)) {
                                                    for ($i = 0; $i < count($freinageDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinageDecla["answers"][$i] == "Oui" &&
                                                            $freinageMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinHydro;
                                                        }
                                                    }
                                                }
                                                $scoreFreinPneu = 0;
                                                if (isset($freinDecla)) {
                                                    for ($i = 0; $i < count($freinDecla["answers"]); ++$i) {
                                                        if (
                                                            $freinDecla["answers"][$i] == "Oui" &&
                                                            $freinMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreFreinPneu;
                                                        }
                                                    }
                                                }
                                                $scoreHydro = 0;
                                                if (isset($hydrauliqueDecla)) {
                                                    for ($i = 0; $i < count($hydrauliqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $hydrauliqueDecla["answers"][$i] == "Oui" &&
                                                            $hydrauliqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreHydro;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurDiesel = 0;
                                                if (isset($moteurDieselDecla)) {
                                                    for ($i = 0; $i < count($moteurDieselDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurDieselDecla["answers"][$i] == "Oui" &&
                                                            $moteurDieselMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurDiesel;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurElec = 0;
                                                if (isset($moteurElecDecla)) {
                                                    for ($i = 0; $i < count($moteurElecDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurElecDecla["answers"][$i] == "Oui" &&
                                                            $moteurElecMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurElec;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurEssence = 0;
                                                if (isset($moteurEssenceDecla)) {
                                                    for ($i = 0; $i < count($moteurEssenceDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurEssenceDecla["answers"][$i] == "Oui" &&
                                                            $moteurEssenceMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurEssence;
                                                        }
                                                    }
                                                }
                                                $scoreMoteurThermique = 0;
                                                if (isset($moteurThermiqueDecla)) {
                                                    for ($i = 0; $i < count($moteurThermiqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $moteurThermiqueDecla["answers"][$i] == "Oui" &&
                                                            $moteurThermiqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMoteurThermique;
                                                        }
                                                    }
                                                }
                                                $scoreMultiplexage = 0;
                                                if (isset($multiplexageDecla)) {
                                                    for ($i = 0; $i < count($multiplexageDecla["answers"]); ++$i) {
                                                        if (
                                                            $multiplexageDecla["answers"][$i] == "Oui" &&
                                                            $multiplexageMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreMultiplexage;
                                                        }
                                                    }
                                                }
                                                $scorePneu = 0;
                                                if (isset($pneuDecla)) {
                                                    for ($i = 0; $i < count($pneuDecla["answers"]); ++$i) {
                                                        if (
                                                            $pneuDecla["answers"][$i] == "Oui" &&
                                                            $pneuMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scorePneu;
                                                        }
                                                    }
                                                }
                                                $scorePont = 0;
                                                if (isset($pontDecla)) {
                                                    for ($i = 0; $i < count($pontDecla["answers"]); ++$i) {
                                                        if (
                                                            $pontDecla["answers"][$i] == "Oui" &&
                                                            $pontMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scorePont;
                                                        }
                                                    }
                                                }
                                                $scoreRed = 0;
                                                if (isset($reducteurDecla)) {
                                                    for ($i = 0; $i < count($reducteurDecla["answers"]); ++$i) {
                                                        if (
                                                            $reducteurDecla["answers"][$i] == "Oui" &&
                                                            $reducteurMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreRed;
                                                        }
                                                    }
                                                }
                                                $scoreSuspension = 0;
                                                if (isset($suspensionDecla)) {
                                                    for ($i = 0; $i < count($suspensionDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionDecla["answers"][$i] == "Oui" &&
                                                            $suspensionMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspension;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionLame = 0;
                                                if (isset($suspensionLameDecla)) {
                                                    for ($i = 0; $i < count($suspensionLameDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionLameDecla["answers"][$i] == "Oui" &&
                                                            $suspensionLameMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionLame;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionRessort = 0;
                                                if (isset($suspensionRessortDecla)) {
                                                    for ($i = 0; $i < count($suspensionRessortDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionRessortDecla["answers"][$i] == "Oui" &&
                                                            $suspensionRessortMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionRessort;
                                                        }
                                                    }
                                                }
                                                $scoreSuspensionPneu = 0;
                                                if (isset($suspensionPneumatiqueDecla)) {
                                                    for ($i = 0; $i < count($suspensionPneumatiqueDecla["answers"]); ++$i) {
                                                        if (
                                                            $suspensionPneumatiqueDecla["answers"][$i] == "Oui" &&
                                                            $suspensionPneumatiqueMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreSuspensionPneu;
                                                        }
                                                    }
                                                }
                                                $scoreTransversale = 0;
                                                if (isset($transversaleDecla)) {
                                                    for ($i = 0; $i < count($transversaleDecla["answers"]); ++$i) {
                                                        if (
                                                            $transversaleDecla["answers"][$i] == "Oui" &&
                                                            $transversaleMa["answers"][$i] == "Oui"
                                                        ) {
                                                            ++$scoreTransversale;
                                                        }
                                                    }
                                                }
                                                // if (isset($Toyota)) {
                                                    $toyotaFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $toyotaDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                
                                                    $toyotaScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $toyotaScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $toyotaScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $toyotaScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Suzuki)) {
                                                    $suzukiFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $transversaleTotalFac;
                                                    $suzukiDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $suzukiScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreTransversale;
                                                    
                                                    $suzukiScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $transversaleScoreFac;
                                                    $suzukiScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $transversaleScoreDecla;
                                                    $suzukiScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurElecScoreMa + $moteurDieselScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Mercedes)) {
                                                    $mercedesFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $mercedesDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla+ $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $mercedesScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $mercedesScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $mercedesScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $mercedesScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Peugeot)) {
                                                    $peugeotFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $peugeotDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                            
                                                    $peugeotScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $peugeotScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $peugeotScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $peugeotScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Citroen)) {
                                                    $citroenFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $citroenDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                            
                                                    $citroenScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreBoiteAuto + $scoreBoiteVaCo + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $citroenScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $citroenScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $citroenScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($KingLong)) {
                                                    $kingLongFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $kingLongDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $kingLongScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionRessort + $scoreTransversale;
                                                    
                                                    $kingLongScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $kingLongScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $kingLongScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Fuso)) {
                                                    $fusoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $fusoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $fusoScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $fusoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $fusoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $fusoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Hino)) {
                                                    $hinoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $hinoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $hinoScore = $scoreTransmission + $scoreAssistance + $scoreBoite + $scoreBoiteMan + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $hinoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $hinoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $hinoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($RenalutTruck)) {
                                                    $renaultTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $renaultTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $renaultTruckScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $renaultTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $renaultTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $renaultTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($MercedesTruck)) {
                                                    $mercedesTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
                                                    $mercedesTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $mercedesTruckScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreSuspensionPneu + $scoreTransversale;
                                                    
                                                    $mercedesTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
                                                    $mercedesTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
                                                    $mercedesTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Sinotruk)) {
                                                    $sinotrukFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
                                                    $sinotrukDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $sinotrukScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinPneu + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreSuspension + $scoreSuspensionLame + $scoreTransversale;
                                                    
                                                    $sinotrukScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
                                                    $sinotrukScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
                                                    $sinotrukScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Jcb)) {
                                                    $jcbFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $jcbDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $jcbScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $jcbScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $jcbScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $jcbScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($Lovol)) {
                                                    $lovolFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $lovolDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $lovolScore = $scoreTransmission + $scoreAssistance + $scoreTransfert + $scoreBoite + $scoreBoiteMan + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $lovolScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $lovolScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $lovolScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($ToyotaBt)) {
                                                    $toyotaBtFac = $assistanceConduiteTotalFac + $boiteTotalFac + $climatisationTotalFac + $directionTotalFac + $electriciteTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurElecTotalFac + $multiplexageTotalFac + $pneuTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $toyotaBtDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $climatisationTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurElecTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $toyotaBtScore = $scoreAssistance + $scoreBoite + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFreinElec + $scoreFreinHydro + $scoreMoteurElec + $scoreMultiplexage + $scorePneu + $scoreRed + $scoreTransversale;
                                                    
                                                    $toyotaBtScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $climatisationScoreFac + $directionScoreFac + $electriciteScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurElecScoreFac + $multiplexageScoreFac + $pneuScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $toyotaBtScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $climatisationScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurElecScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $toyotaBtScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $climatisationScoreMa + $directionScoreMa + $electriciteScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurElecScoreMa + $multiplexageScoreMa + $pneuScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                                // if (isset($ToyotaForflift)) {
                                                    $toyotaForfliftFac = $assistanceConduiteTotalFac + $boiteTotalFac + $boiteAutoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
                                                    $toyotaForfliftDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteAutoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
                                                    
                                                    $toyotaForfliftScore = $scoreAssistance + $scoreBoite + $scoreBoiteAuto + $scoreClim + $scoreDemi + $scoreDirection + $scoreElectricite + $scoreHydro + $scoreFrein + $scoreFreinElec + $scoreFreinHydro + $scoreMoteurDiesel + $scoreMoteurElec + $scoreMoteurEssence + $scoreMoteurThermique + $scoreMultiplexage + $scorePneu + $scorePont + $scoreRed + $scoreTransversale;
                                                    
                                                    $toyotaForfliftScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $boiteAutoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
                                                    $toyotaForfliftScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteAutoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
                                                    $toyotaForfliftScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $boiteAutoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
                                                // }
                                            
                                            ?>
                                        <tr class="odd" style="">
                                            <td class="sorting text-black text-center hidden table-light text-uppercase gs-0"
                                            tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px; ">
                                            <?php echo $tech->firstName ?> <?php echo $tech->lastName ?>
                                        </td>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="kingLongExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'KING LONG'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($kingLongScoreFac * 100) / $kingLongFac + ($kingLongScore * 100) / $kingLongDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="kingLongExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="fusoExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'FUSO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($fusoScoreFac * 100) / $fusoFac + ($fusoScore * 100) / $fusoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="fusoExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="hinoExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'HINO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($hinoScoreFac * 100) / $hinoFac + ($hinoScore * 100) / $hinoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="hinoExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="mercedesTruckExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'MERCEDES TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesTruckScoreFac * 100) / $mercedesTruckFac + ($mercedesTruckScore * 100) / $mercedesTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="mercedesTruckExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="renaultTruckExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'RENAULT TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($renaultTruckScoreFac * 100) / $renaultTruckFac + ($renaultTruckScore * 100) / $renaultTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="renaultTruckExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="sinotrukExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'SINOTRUK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($sinotrukScoreFac * 100) / $sinotrukFac + ($sinotrukScore * 100) / $sinotrukDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="sinotrukExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaBtExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'TOYOTA BT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaBtScoreFac * 100) / $toyotaBtFac + ($toyotaBtScore * 100) / $toyotaBtDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaBtExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyotaForfliftExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'TOYOTA FORKLIFT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaForfliftScoreFac * 100) / $toyotaForfliftFac + ($toyotaForfliftScore * 100) / $toyotaForfliftDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyotaForfliftExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="jcbExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'JCB'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($jcbScoreFac * 100) / $jcbFac + ($jcbScore * 100) / $jcbDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="jcbExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="lovolExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'LOVOL'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($lovolScoreFac * 100) / $lovolFac + ($lovolScore * 100) / $lovolDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="lovolExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="citroenExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'CITROEN'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($citroenScoreFac * 100) / $citroenFac + ($citroenScore * 100) / $citroenDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="citroenExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="mercedesExpert">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'MERCEDES'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesScoreFac * 100) / $mercedesFac + ($mercedesScore * 100) / $mercedesDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="mercedes">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="peugeot">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'PEUGEOT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($peugeotScoreFac * 100) / $peugeotFac + ($peugeotScore * 100) / $peugeotDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="peugeot">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="suzuki">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'SUZUKI'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($suzukiScoreFac * 100) / $suzukiFac + ($suzukiScore * 100) / $suzukiDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="suzuki">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="toyota">
                                            <?php for ($i=0; $i < count($tech['brand'."Expert"]); $i++) {
                                            if (
                                                $tech['brand'."Expert"][$i] == 'TOYOTA'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaScoreFac * 100) / $toyotaFac + ($toyotaScore * 100) / $toyotaDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="toyota">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?> 
                                            <td class="text-center hidden" id="result">
                                                <?php echo
                                                    ceil((($resultFac->score * 100) / $resultFac->total + ($resultTechMa->score * 100) / $resultTechMa->total ) / 2)
                                                ?>%
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="resultExpert">
                                                0%
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php } } ?>
                                        <!--end::Menu-->
                                        <tr>
                                            <td class="sorting text-black text-center table-light text-uppercase gs-0"
                                                tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                                aria-label="Email: activate to sort column ascending"
                                                style="width: 155.266px; ">
                                                <?php echo $level ?> <?php echo $expert ?>
                                            </td>
                                            <td class="text-center" id="resultKingLongExpert">
                                            </td>
                                            <td class="text-center" id="resultFusoExpert">
                                            </td>
                                            <td class="text-center" id="resultHinoExpert">
                                            </td>
                                            <td class="text-center" id="resultMercedesTruckExpert">
                                            </td>
                                            <td class="text-center" id="resultRenaultTruckExpert">
                                            </td>
                                            <td class="text-center" id="resultSinotrukExpert">
                                            </td>
                                            <td class="text-center" id="resultToyotaBtExpert">
                                            </td>
                                            <td class="text-center" id="resultToyotaForkliftExpert">
                                            </td>
                                            <td class="text-center" id="resultJcbExpert">
                                            </td>
                                            <td class="text-center" id="resultLovolExpert">
                                            </td>
                                            <td class="text-center" id="resultCitroenExpert">
                                            </td>
                                            <td class="text-center" id="resultMercedesExpert">
                                            </td>
                                            <td class="text-center" id="resultPeugeotExpert">
                                            </td>
                                            <td class="text-center" id="resultSuzukiExpert">
                                            </td>
                                            <td class="text-center" id="resultToyotaExpert">
                                            </td>
                                            <td class="text-center" id="resultTotalExpert">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" aria-controls="kt_customers_table"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                <?php echo $result ?>
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultKingLong"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultFuso"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultHino"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultMercedesTruck"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultRenaultTruck"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultSinotruk"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultToyotaBt"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultToyotaForklift"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultJcb"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultLovol"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultCitroen"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultMercedes"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultPeugeot"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultSuzuki"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultToyota"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class="sorting text-black text-center table-light fw-bold text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultTotal"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
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
                <!--begin::Export-->
                <button type="button" id="excel" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                    data-bs-target="#kt_customers_export_modal">
                    <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span
                            class="path2"></span></i> <?php echo $excel ?>
                </button>
                <!--end::Export-->
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
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js">
</script>
<script src="../public/js/main.js"></script>
<script>
    
$(document).ready(function() {
    $("#excel").on("click", function() {
        let table = document.getElementsByTagName("table");
        debugger;
        TableToExcel.convert(table[0], {
            name: `Table.xlsx`
        })
    });
});

var kingLongJunior = document.querySelectorAll("#kingLongJunior")
var fusoJunior = document.querySelectorAll("#fusoJunior")
var hinoJunior = document.querySelectorAll("#hinoJunior")
var jcbJunior = document.querySelectorAll("#jcbJunior")
var lovolJunior = document.querySelectorAll("#lovolJunior")
var mercedesTruckJunior = document.querySelectorAll("#mercedesTruckJunior")
var renaultTruckJunior = document.querySelectorAll("#renaultTruckJunior")
var sinotrukJunior = document.querySelectorAll("#sinotrukJunior")
var toyotaBtJunior = document.querySelectorAll("#toyotaBtJunior")
var toyotaForfliftJunior = document.querySelectorAll("#toyotaForfliftJunior")
var citroenJunior = document.querySelectorAll("#citroenJunior")
var mercedesJunior = document.querySelectorAll("#mercedesJunior")
var peugeotJunior = document.querySelectorAll("#peugeotJunior")
var suzukiJunior = document.querySelectorAll("#suzukiJunior")
var toyotaJunior = document.querySelectorAll("#toyotaJunior")
var resultJunior = document.querySelectorAll("#resultJunior")

var kingLongSenior = document.querySelectorAll("#kingLongSenior")
var fusoSenior = document.querySelectorAll("#fusoSenior")
var hinoSenior = document.querySelectorAll("#hinoSenior")
var jcbSenior = document.querySelectorAll("#jcbSenior")
var lovolSenior = document.querySelectorAll("#lovolSenior")
var mercedesTruckSenior = document.querySelectorAll("#mercedesTruckSenior")
var renaultTruckSenior = document.querySelectorAll("#renaultTruckSenior")
var sinotrukSenior = document.querySelectorAll("#sinotrukSenior")
var toyotaBtSenior = document.querySelectorAll("#toyotaBtSenior")
var toyotaForfliftSenior = document.querySelectorAll("#toyotaForfliftSenior")
var citroenSenior = document.querySelectorAll("#citroenSenior")
var mercedesSenior = document.querySelectorAll("#mercedesSenior")
var peugeotSenior = document.querySelectorAll("#peugeotSenior")
var suzukiSenior = document.querySelectorAll("#suzukiSenior")
var toyotaSenior = document.querySelectorAll("#toyotaSenior")
var resultSenior = document.querySelectorAll("#resultSenior")

var kingLongExpert = document.querySelectorAll("#kingLongExpert")
var fusoExpert = document.querySelectorAll("#fusoExpert")
var hinoExpert = document.querySelectorAll("#hinoExpert")
var jcbExpert = document.querySelectorAll("#jcbExpert")
var lovolExpert = document.querySelectorAll("#lovolExpert")
var mercedesTruckExpert = document.querySelectorAll("#mercedesTruckExpert")
var renaultTruckExpert = document.querySelectorAll("#renaultTruckExpert")
var sinotrukExpert = document.querySelectorAll("#sinotrukExpert")
var toyotaBtExpert = document.querySelectorAll("#toyotaBtExpert")
var toyotaForfliftExpert = document.querySelectorAll("#toyotaForfliftExpert")
var citroenExpert = document.querySelectorAll("#citroenExpert")
var mercedesExpert = document.querySelectorAll("#mercedesExpert")
var peugeotExpert = document.querySelectorAll("#peugeotExpert")
var suzukiExpert = document.querySelectorAll("#suzukiExpert")
var toyotaExpert = document.querySelectorAll("#toyotaExpert")
var resultExpert = document.querySelectorAll("#resultExpert")

var resultKingLongJunior = document.querySelector("#resultKingLongJunior")
var resultFusoJunior = document.querySelector("#resultFusoJunior")
var resultHinoJunior = document.querySelector("#resultHinoJunior")
var resultJcbJunior = document.querySelector("#resultJcbJunior")
var resultLovolJunior = document.querySelector("#resultLovolJunior")
var resultMercedesTruckJunior = document.querySelector("#resultMercedesTruckJunior")
var resultRenaultTruckJunior = document.querySelector("#resultRenaultTruckJunior")
var resultSinotrukJunior = document.querySelector("#resultSinotrukJunior")
var resultToyotaBtJunior = document.querySelector("#resultToyotaBtJunior")
var resultToyotaForfliftJunior = document.querySelector("#resultToyotaForkliftJunior")
var resultCitroenJunior = document.querySelector("#resultCitroenJunior")
var resultMercedesJunior = document.querySelector("#resultMercedesJunior")
var resultPeugeotJunior = document.querySelector("#resultPeugeotJunior")
var resultSuzukiJunior = document.querySelector("#resultSuzukiJunior")
var resultToyotaJunior = document.querySelector("#resultToyotaJunior")
var resultTotalJunior = document.querySelector("#resultTotalJunior")

var resultKingLongSenior = document.querySelector("#resultKingLongSenior")
var resultFusoSenior = document.querySelector("#resultFusoSenior")
var resultHinoSenior = document.querySelector("#resultHinoSenior")
var resultJcbSenior = document.querySelector("#resultJcbSenior")
var resultLovolSenior = document.querySelector("#resultLovolSenior")
var resultMercedesTruckSenior = document.querySelector("#resultMercedesTruckSenior")
var resultRenaultTruckSenior = document.querySelector("#resultRenaultTruckSenior")
var resultSinotrukSenior = document.querySelector("#resultSinotrukSenior")
var resultToyotaBtSenior = document.querySelector("#resultToyotaBtSenior")
var resultToyotaForfliftSenior = document.querySelector("#resultToyotaForkliftSenior")
var resultCitroenSenior = document.querySelector("#resultCitroenSenior")
var resultMercedesSenior = document.querySelector("#resultMercedesSenior")
var resultPeugeotSenior = document.querySelector("#resultPeugeotSenior")
var resultSuzukiSenior = document.querySelector("#resultSuzukiSenior")
var resultToyotaSenior = document.querySelector("#resultToyotaSenior")
var resultTotalSenior = document.querySelector("#resultTotalSenior")

var resultKingLongExpert = document.querySelector("#resultKingLongExpert")
var resultFusoExpert = document.querySelector("#resultFusoExpert")
var resultHinoExpert = document.querySelector("#resultHinoExpert")
var resultJcbExpert = document.querySelector("#resultJcbExpert")
var resultLovolExpert = document.querySelector("#resultLovolExpert")
var resultMercedesTruckExpert = document.querySelector("#resultMercedesTruckExpert")
var resultRenaultTruckExpert = document.querySelector("#resultRenaultTruckExpert")
var resultSinotrukExpert = document.querySelector("#resultSinotrukExpert")
var resultToyotaBtExpert = document.querySelector("#resultToyotaBtExpert")
var resultToyotaForfliftExpert = document.querySelector("#resultToyotaForkliftExpert")
var resultCitroenExpert = document.querySelector("#resultCitroenExpert")
var resultMercedesExpert = document.querySelector("#resultMercedesExpert")
var resultPeugeotExpert = document.querySelector("#resultPeugeotExpert")
var resultSuzukiExpert = document.querySelector("#resultSuzukiExpert")
var resultToyotaExpert = document.querySelector("#resultToyotaExpert")
var resultTotalExpert = document.querySelector("#resultTotalExpert")

var resultKingLong = document.querySelector("#resultKingLong")
var resultFuso = document.querySelector("#resultFuso")
var resultHino = document.querySelector("#resultHino")
var resultJcb = document.querySelector("#resultJcb")
var resultLovol = document.querySelector("#resultLovol")
var resultMercedesTruck = document.querySelector("#resultMercedesTruck")
var resultRenaultTruck = document.querySelector("#resultRenaultTruck")
var resultSinotruk = document.querySelector("#resultSinotruk")
var resultToyotaBt = document.querySelector("#resultToyotaBt")
var resultToyotaForflift = document.querySelector("#resultToyotaForklift")
var resultCitroen = document.querySelector("#resultCitroen")
var resultMercedes = document.querySelector("#resultMercedes")
var resultPeugeot = document.querySelector("#resultPeugeot")
var resultSuzuki = document.querySelector("#resultSuzuki")
var resultToyota = document.querySelector("#resultToyota")
var resultTotal = document.querySelector("#resultTotal")

var totalKingLongJunior = 0;
var totalFusoJunior = 0;
var totalHinoJunior = 0;
var totalMercedesTruckJunior = 0;
var totalRenaultTruckJunior = 0;
var totalSinotrukJunior = 0;
var totalToyotaBtJunior = 0;
var totalToyotaForfliftJunior = 0;
var totalJcbJunior = 0;
var totalLovolJunior = 0;
var totalCitroenJunior = 0;
var totalMercedesJunior = 0;
var totalPeugeotJunior = 0;
var totalSuzukiJunior = 0;
var totalToyotaJunior = 0;
var totalJunior = 0;

let arrayKingLongJunior = [];
let arrayFusoJunior = [];
let arrayHinoJunior = [];
let arrayMercedesTruckJunior = [];
let arrayRenaultTruckJunior = [];
let arraySinotrukJunior = [];
let arrayToyotaBtJunior = [];
let arrayToyotaForfliftJunior = [];
let arrayJcbJunior = [];
let arrayLovolJunior = [];
let arrayCitroenJunior = [];
let arrayMercedesJunior = [];
let arrayPeugeotJunior = [];
let arraySuzukiJunior = [];
let arrayToyotaJunior = [];
let arrayJunior = [];

for(var i = 0; i < kingLongJunior.length; i++) {
    if(!isNaN(kingLongJunior[i].innerText)) {
        kingLongJunior[i].innerHTML = "0%"
    } else if(parseInt(kingLongJunior[i].innerText) != 0) {
        arrayKingLongJunior.push(kingLongJunior[i].innerText)
    }
}
for(var i = 0; i < arrayKingLongJunior.length; i++) {
    totalKingLongJunior += parseInt(arrayKingLongJunior[i]);
    var avgKingJunior = Math.ceil(totalKingLongJunior / arrayKingLongJunior.length);
}
if (avgKingJunior == undefined) {
    resultKingLongJunior.innerHTML = "-"
} else {
    resultKingLongJunior.innerHTML = avgKingJunior + "%"
}

for(var i = 0; i < fusoJunior.length; i++) {
    if(!isNaN(fusoJunior[i].innerText)) {
        fusoJunior[i].innerHTML = "0%"
    } else if(parseInt(fusoJunior[i].innerText) != 0) {
        arrayFusoJunior.push(fusoJunior[i].innerText)
    }
}
for(var i = 0; i < arrayFusoJunior.length; i++) {
    totalFusoJunior += parseInt(arrayFusoJunior[i]);
    var avgFuJunior = Math.ceil(totalFusoJunior / arrayFusoJunior.length);
}
if (avgFuJunior == undefined) {
    resultFusoJunior.innerHTML = "-"
} else {
    resultFusoJunior.innerHTML = avgFuJunior + "%"
}

for(var i = 0; i < hinoJunior.length; i++) {
    if(!isNaN(hinoJunior[i].innerText)) {
        hinoJunior[i].innerHTML = "0%"
    } else if(parseInt(hinoJunior[i].innerText) != 0) {
        arrayHinoJunior.push(hinoJunior[i].innerText)
    }
}
for(var i = 0; i < arrayHinoJunior.length; i++) {
    totalHinoJunior += parseInt( arrayHinoJunior[i]);
    var avgHiJunior = Math.ceil(totalHinoJunior / arrayHinoJunior.length);
}
if (avgHiJunior == undefined) {
    resultHinoJunior.innerHTML = "-"
} else {
    resultHinoJunior.innerHTML = avgHiJunior + "%"
}

for(var i = 0; i < mercedesTruckJunior.length; i++) {
    if(!isNaN(mercedesTruckJunior[i].innerText)) {
        mercedesTruckJunior[i].innerHTML = "0%"
    } else if(parseInt(mercedesTruckJunior[i].innerText) != 0) {
        arrayMercedesTruckJunior.push(mercedesTruckJunior[i].innerText)
    }
}
for(var i = 0; i < arrayMercedesTruckJunior.length; i++) {
    totalMercedesTruckJunior += parseInt(arrayMercedesTruckJunior[i]);
    var avgMeTrJunior = Math.ceil(totalMercedesTruckJunior / arrayMercedesTruckJunior.length);
}
if (avgMeTrJunior == undefined) {
    resultMercedesTruckJunior.innerHTML = "-"
} else {
    resultMercedesTruckJunior.innerHTML = avgMeTrJunior + "%"
}

for(var i = 0; i < renaultTruckJunior.length; i++) {
    if(!isNaN(renaultTruckJunior[i].innerText)) {
        renaultTruckJunior[i].innerHTML = "0%"
    } else if(parseInt(renaultTruckJunior[i].innerText) != 0) {
        arrayRenaultTruckJunior.push(renaultTruckJunior[i].innerText)
    }
}
for(var i = 0; i < arrayRenaultTruckJunior.length; i++) {
    totalRenaultTruckJunior += parseInt(arrayRenaultTruckJunior[i]);
    var avgReTrJunior = Math.ceil(totalRenaultTruckJunior / arrayRenaultTruckJunior.length);
}
if (avgReTrJunior == undefined) {
    resultRenaultTruckJunior.innerHTML = "-"
} else {
    resultRenaultTruckJunior.innerHTML = avgReTrJunior + "%"
}

for(var i = 0; i < sinotrukJunior.length; i++) {
    if(!isNaN(sinotrukJunior[i].innerText)) {
        sinotrukJunior[i].innerHTML = "0%"
    } else if(parseInt(sinotrukJunior[i].innerText) != 0) {
        arraySinotrukJunior.push(sinotrukJunior[i].innerText)
    }
}
for(var i = 0; i < arraySinotrukJunior.length; i++) {
    totalSinotrukJunior += parseInt(arraySinotrukJunior[i]);
    var avgSiTrJunior = Math.ceil(totalSinotrukJunior / arraySinotrukJunior.length);
}
if (avgSiTrJunior == undefined) {
    resultSinotrukJunior.innerHTML = "-"
} else {
    resultSinotrukJunior.innerHTML = avgSiTrJunior + "%"
}

for(var i = 0; i < toyotaBtJunior.length; i++) {
    if(!isNaN(toyotaBtJunior[i].innerText)) {
        toyotaBtJunior[i].innerHTML = "0%"
    } else if(parseInt(toyotaBtJunior[i].innerText) != 0) {
        arrayToyotaBtJunior.push(toyotaBtJunior[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaBtJunior.length; i++) {
    totalToyotaBtJunior += parseInt(arrayToyotaBtJunior[i]);
    var avgToBtJunior = Math.ceil(totalToyotaBtJunior / arrayToyotaBtJunior.length);
}
if (avgToBtJunior == undefined) {
    resultToyotaBtJunior.innerHTML = "-"
} else {
    resultToyotaBtJunior.innerHTML = avgToBtJunior + "%"
}

for(var i = 0; i < toyotaForfliftJunior.length; i++) {
    if(!isNaN(toyotaForfliftJunior[i].innerText)) {
        toyotaForfliftJunior[i].innerHTML = "0%"
    } else if(parseInt(toyotaForfliftJunior[i].innerText) != 0) {
        arrayToyotaForfliftJunior.push(toyotaForfliftJunior[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaForfliftJunior.length; i++) {
    totalToyotaForfliftJunior += parseInt(arrayToyotaForfliftJunior[i]);
    var avgToFoJunior = Math.ceil(totalToyotaForfliftJunior / arrayToyotaForfliftJunior.length);
}
if (avgToFoJunior == undefined) {
    resultToyotaForfliftJunior.innerHTML = "-"
} else {
    resultToyotaForfliftJunior.innerHTML = avgToFoJunior + "%"
}

for(var i = 0; i < jcbJunior.length; i++) {
    if(!isNaN(jcbJunior[i].innerText)) {
        jcbJunior[i].innerHTML = "0%"
    } else if(parseInt(jcbJunior[i].innerText) != 0) {
        arrayJcbJunior.push(jcbJunior[i].innerText)
    }
}
for(var i = 0; i < arrayJcbJunior.length; i++) {
    totalJcbJunior += parseInt(arrayJcbJunior[i]);
    var avgJcJunior = Math.ceil(totalJcbJunior / arrayJcbJunior.length);
}
if (avgJcJunior == undefined) {
    resultJcbJunior.innerHTML = "-"
} else {
    resultJcbJunior.innerHTML = avgJcJunior + "%"
}

for(var i = 0; i < lovolJunior.length; i++) {
    if(!isNaN(lovolJunior[i].innerText)) {
        lovolJunior[i].innerHTML = "0%"
    } else if(parseInt(lovolJunior[i].innerText) != 0) {
        arrayLovolJunior.push(lovolJunior[i].innerText)
    }
}
for(var i = 0; i < arrayLovolJunior.length; i++) {
    totalLovolJunior += parseInt(arrayLovolJunior[i]);
    var avgLoJunior = Math.ceil(totalLovolJunior / arrayLovolJunior.length);
}
if (avgLoJunior == undefined) {
    resultLovolJunior.innerHTML = "-"
} else {
    resultLovolJunior.innerHTML = avgLoJunior + "%"
}

for(var i = 0; i < citroenJunior.length; i++) {
    if(!isNaN(citroenJunior[i].innerText)) {
        citroenJunior[i].innerHTML = "0%"
    } else if(parseInt(citroenJunior[i].innerText) != 0) {
        arrayCitroenJunior.push(citroenJunior[i].innerText)
    }
}
for(var i = 0; i < arrayCitroenJunior.length; i++) {
    totalCitroenJunior += parseInt(arrayCitroenJunior[i]);
    var avgCiJunior = Math.ceil(totalCitroenJunior / arrayCitroenJunior.length);
}
if (avgCiJunior == undefined) {
    resultCitroenJunior.innerHTML = "-"
} else {
    resultCitroenJunior.innerHTML = avgCiJunior + "%"
}

for(var i = 0; i < mercedesJunior.length; i++) {
    if(!isNaN(mercedesJunior[i].innerText)) {
        mercedesJunior[i].innerHTML = "0%"
    } else if(parseInt(mercedesJunior[i].innerText) != 0) {
        arrayMercedesJunior.push(mercedesJunior[i].innerText)
    }
}
for(var i = 0; i < arrayMercedesJunior.length; i++) {
    totalMercedesJunior += parseInt(arrayMercedesJunior[i]);
    var avgMeJunior = Math.ceil(totalMercedesJunior / arrayMercedesJunior.length);
}
if (avgMeJunior == undefined) {
    resultMercedesJunior.innerHTML = "-"
} else {
    resultMercedesJunior.innerHTML = avgMeJunior + "%"
}

for(var i = 0; i < peugeotJunior.length; i++) {
    if(!isNaN(peugeotJunior[i].innerText)) {
        peugeotJunior[i].innerHTML = "0%"
    } else if(parseInt(peugeotJunior[i].innerText) != 0) {
        arrayPeugeotJunior.push(peugeotJunior[i].innerText)
    }
}
for(var i = 0; i < arrayPeugeotJunior.length; i++) {
    totalPeugeotJunior += parseInt(arrayPeugeotJunior[i]);
    var avgPeJunior = Math.ceil(totalPeugeotJunior / arrayPeugeotJunior.length);
}
if (avgPeJunior == undefined) {
    resultPeugeotJunior.innerHTML = "-"
} else {
    resultPeugeotJunior.innerHTML = avgPeJunior + "%"
}

for(var i = 0; i < suzukiJunior.length; i++) {
    if(!isNaN(suzukiJunior[i].innerText)) {
        suzukiJunior[i].innerHTML = "0%"
    } else if(parseInt(suzukiJunior[i].innerText) != 0) {
        arraySuzukiJunior.push(suzukiJunior[i].innerText)
    }
}
for(var i = 0; i < arraySuzukiJunior.length; i++) {
    totalSuzukiJunior += parseInt(arraySuzukiJunior[i]);
    var avgSuJunior = Math.ceil(totalSuzukiJunior / arraySuzukiJunior.length);
}
if (avgSuJunior == undefined) {
    resultSuzukiJunior.innerHTML = "-"
} else {
    resultSuzukiJunior.innerHTML = avgSuJunior + "%"
}

for(var i = 0; i < toyotaJunior.length; i++) {
    if(!isNaN(toyotaJunior[i].innerText)) {
        toyotaJunior[i].innerHTML = "0%"
    } else if(parseInt(toyotaJunior[i].innerText) != 0) {
        arrayToyotaJunior.push(toyotaJunior[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaJunior.length; i++) {
    totalToyotaJunior += parseInt(arrayToyotaJunior[i]);
    var avgToyotaJunior = Math.ceil(totalToyotaJunior / arrayToyotaJunior.length);
}
if (avgToyotaJunior == undefined) {
    resultToyotaJunior.innerHTML = "-"
} else {
    resultToyotaJunior.innerHTML = avgToyotaJunior + "%";
}

for(var i = 0; i < resultJunior.length; i++) {
    if(parseInt(resultJunior[i].innerText) != 0) {
        arrayJunior.push(resultJunior[i].innerText)
    }
}
for(var i = 0; i < arrayJunior.length; i++) {
    totalJunior += parseInt(arrayJunior[i]);
    var avgJunior = Math.ceil(totalJunior / arrayJunior.length);
}
if (avgJunior == undefined) {
    resultTotalJunior.innerHTML = "-"
} else {
    resultTotalJunior.innerHTML = avgJunior + "%";
}

var totalKingLongSenior = 0;
var totalFusoSenior = 0;
var totalHinoSenior = 0;
var totalMercedesTruckSenior = 0;
var totalRenaultTruckSenior = 0;
var totalSinotrukSenior = 0;
var totalToyotaBtSenior = 0;
var totalToyotaForfliftSenior = 0;
var totalJcbSenior = 0;
var totalLovolSenior = 0;
var totalCitroenSenior = 0;
var totalMercedesSenior = 0;
var totalPeugeotSenior = 0;
var totalSuzukiSenior = 0;
var totalToyotaSenior = 0;
var totalSenior = 0;

let arrayKingLongSenior = [];
let arrayFusoSenior = [];
let arrayHinoSenior = [];
let arrayMercedesTruckSenior = [];
let arrayRenaultTruckSenior = [];
let arraySinotrukSenior = [];
let arrayToyotaBtSenior = [];
let arrayToyotaForfliftSenior = [];
let arrayJcbSenior = [];
let arrayLovolSenior = [];
let arrayCitroenSenior = [];
let arrayMercedesSenior = [];
let arrayPeugeotSenior = [];
let arraySuzukiSenior = [];
let arrayToyotaSenior = [];
let arraySenior = [];

for(var i = 0; i < kingLongSenior.length; i++) {
    if(!isNaN(kingLongSenior[i].innerText)) {
        kingLongSenior[i].innerHTML = "0%"
    } else if(parseInt(kingLongSenior[i].innerText) != 0) {
        arrayKingLongSenior.push(kingLongSenior[i].innerText)
    }
}
for(var i = 0; i < arrayKingLongSenior.length; i++) {
    totalKingLongSenior += parseInt(arrayKingLongSenior[i]);
    var avgKingSenior = Math.ceil(totalKingLongSenior / arrayKingLongSenior.length);
}
if (avgKingSenior == undefined) {
    resultKingLongSenior.innerHTML = "-"
} else {
    resultKingLongSenior.innerHTML = avgKingSenior + "%"
}

for(var i = 0; i < fusoSenior.length; i++) {
    if(!isNaN(fusoSenior[i].innerText)) {
        fusoSenior[i].innerHTML = "0%"
    } else if(parseInt(fusoSenior[i].innerText) != 0) {
        arrayFusoSenior.push(fusoSenior[i].innerText)
    }
}
for(var i = 0; i < arrayFusoSenior.length; i++) {
    totalFusoSenior += parseInt(arrayFusoSenior[i]);
    var avgFuSenior = Math.ceil(totalFusoSenior / arrayFusoSenior.length);
}
if (avgFuSenior == undefined) {
    resultFusoSenior.innerHTML = "-"
} else {
    resultFusoSenior.innerHTML = avgFuSenior + "%"
}

for(var i = 0; i < hinoSenior.length; i++) {
    if(!isNaN(hinoSenior[i].innerText)) {
        hinoSenior[i].innerHTML = "0%"
    } else if(parseInt(hinoSenior[i].innerText) != 0) {
        arrayHinoSenior.push(hinoSenior[i].innerText)
    }
}
for(var i = 0; i < arrayHinoSenior.length; i++) {
    totalHinoSenior += parseInt( arrayHinoSenior[i]);
    var avgHiSenior = Math.ceil(totalHinoSenior / arrayHinoSenior.length);
}
if (avgHiSenior == undefined) {
    resultHinoSenior.innerHTML = "-"
} else {
    resultHinoSenior.innerHTML = avgHiSenior + "%"
}

for(var i = 0; i < mercedesTruckSenior.length; i++) {
    if(!isNaN(mercedesTruckSenior[i].innerText)) {
        mercedesTruckSenior[i].innerHTML = "0%"
    } else if(parseInt(mercedesTruckSenior[i].innerText) != 0) {
        arrayMercedesTruckSenior.push(mercedesTruckSenior[i].innerText)
    }
}
for(var i = 0; i < arrayMercedesTruckSenior.length; i++) {
    totalMercedesTruckSenior += parseInt(arrayMercedesTruckSenior[i]);
    var avgMeTrSenior = Math.ceil(totalMercedesTruckSenior / arrayMercedesTruckSenior.length);
}
if (avgMeTrSenior == undefined) {
    resultMercedesTruckSenior.innerHTML = "-"
} else {
    resultMercedesTruckSenior.innerHTML = avgMeTrSenior + "%"
}

for(var i = 0; i < renaultTruckSenior.length; i++) {
    if(!isNaN(renaultTruckSenior[i].innerText)) {
        renaultTruckSenior[i].innerHTML = "0%"
    } else if(parseInt(renaultTruckSenior[i].innerText) != 0) {
        arrayRenaultTruckSenior.push(renaultTruckSenior[i].innerText)
    }
}
for(var i = 0; i < arrayRenaultTruckSenior.length; i++) {
    totalRenaultTruckSenior += parseInt(arrayRenaultTruckSenior[i]);
    var avgReTrSenior = Math.ceil(totalRenaultTruckSenior / arrayRenaultTruckSenior.length);
}
if (avgReTrSenior == undefined) {
    resultRenaultTruckSenior.innerHTML = "-"
} else {
    resultRenaultTruckSenior.innerHTML = avgReTrSenior + "%"
}

for(var i = 0; i < sinotrukSenior.length; i++) {
    if(!isNaN(sinotrukSenior[i].innerText)) {
        sinotrukSenior[i].innerHTML = "0%"
    } else if(parseInt(sinotrukSenior[i].innerText) != 0) {
        arraySinotrukSenior.push(sinotrukSenior[i].innerText)
    }
}
for(var i = 0; i < arraySinotrukSenior.length; i++) {
    totalSinotrukSenior += parseInt(arraySinotrukSenior[i]);
    var avgSiTrSenior = Math.ceil(totalSinotrukSenior / arraySinotrukSenior.length);
}
if (avgSiTrSenior == undefined) {
    resultSinotrukSenior.innerHTML = "-"
} else {
    resultSinotrukSenior.innerHTML = avgSiTrSenior + "%"
}

for(var i = 0; i < toyotaBtSenior.length; i++) {
    if(!isNaN(toyotaBtSenior[i].innerText)) {
        toyotaBtSenior[i].innerHTML = "0%"
    } else if(parseInt(toyotaBtSenior[i].innerText) != 0) {
        arrayToyotaBtSenior.push(toyotaBtSenior[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaBtSenior.length; i++) {
    totalToyotaBtSenior += parseInt(arrayToyotaBtSenior[i]);
    var avgToBtSenior = Math.ceil(totalToyotaBtSenior / arrayToyotaBtSenior.length);
}
if (avgToBtSenior == undefined) {
    resultToyotaBtSenior.innerHTML = "-"
} else {
    resultToyotaBtSenior.innerHTML = avgToBtSenior + "%"
}

for(var i = 0; i < toyotaForfliftSenior.length; i++) {
    if(!isNaN(toyotaForfliftSenior[i].innerText)) {
        toyotaForfliftSenior[i].innerHTML = "0%"
    } else if(parseInt(toyotaForfliftSenior[i].innerText) != 0) {
        arrayToyotaForfliftSenior.push(toyotaForfliftSenior[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaForfliftSenior.length; i++) {
    totalToyotaForfliftSenior += parseInt(arrayToyotaForfliftSenior[i]);
    var avgToFoSenior = Math.ceil(totalToyotaForfliftSenior / arrayToyotaForfliftSenior.length);
}
if (avgToFoSenior == undefined) {
    resultToyotaForfliftSenior.innerHTML = "-"
} else {
    resultToyotaForfliftSenior.innerHTML = avgToFoSenior + "%"
}

for(var i = 0; i < jcbSenior.length; i++) {
    if(!isNaN(jcbSenior[i].innerText)) {
        jcbSenior[i].innerHTML = "0%"
    } else if(parseInt(jcbSenior[i].innerText) != 0) {
        arrayJcbSenior.push(jcbSenior[i].innerText)
    }
}
for(var i = 0; i < arrayJcbSenior.length; i++) {
    totalJcbSenior += parseInt(arrayJcbSenior[i]);
    var avgJcSenior = Math.ceil(totalJcbSenior / arrayJcbSenior.length);
}
if (avgJcSenior == undefined) {
    resultJcbSenior.innerHTML = "-"
} else {
    resultJcbSenior.innerHTML = avgJcSenior + "%"
}

for(var i = 0; i < lovolSenior.length; i++) {
    if(!isNaN(lovolSenior[i].innerText)) {
        lovolSenior[i].innerHTML = "0%"
    } else if(parseInt(lovolSenior[i].innerText) != 0) {
        arrayLovolSenior.push(lovolSenior[i].innerText)
    }
}
for(var i = 0; i < arrayLovolSenior.length; i++) {
    totalLovolSenior += parseInt(arrayLovolSenior[i]);
    var avgLoSenior = Math.ceil(totalLovolSenior / arrayLovolSenior.length);
}
if (avgLoSenior == undefined) {
    resultLovolSenior.innerHTML = "-"
} else {
    resultLovolSenior.innerHTML = avgLoSenior + "%"
}

for(var i = 0; i < citroenSenior.length; i++) {
    if(!isNaN(citroenSenior[i].innerText)) {
        citroenSenior[i].innerHTML = "0%"
    } else if(parseInt(citroenSenior[i].innerText) != 0) {
        arrayCitroenSenior.push(citroenSenior[i].innerText)
    }
}
for(var i = 0; i < arrayCitroenSenior.length; i++) {
    totalCitroenSenior += parseInt(arrayCitroenSenior[i]);
    var avgCiSenior = Math.ceil(totalCitroenSenior / arrayCitroenSenior.length);
}
if (avgCiSenior == undefined) {
    resultCitroenSenior.innerHTML = "-"
} else {
    resultCitroenSenior.innerHTML = avgCiSenior + "%"
}

for(var i = 0; i < mercedesSenior.length; i++) {
    if(!isNaN(mercedesSenior[i].innerText)) {
        mercedesSenior[i].innerHTML = "0%"
    } else if(parseInt(mercedesSenior[i].innerText) != 0) {
        arrayMercedesSenior.push(mercedesSenior[i].innerText)
    }
}
for(var i = 0; i < arrayMercedesSenior.length; i++) {
    totalMercedesSenior += parseInt(arrayMercedesSenior[i]);
    var avgMeSenior = Math.ceil(totalMercedesSenior / arrayMercedesSenior.length);
}
if (avgMeSenior == undefined) {
    resultMercedesSenior.innerHTML = "-"
} else {
    resultMercedesSenior.innerHTML = avgMeSenior + "%"
}

for(var i = 0; i < peugeotSenior.length; i++) {
    if(!isNaN(peugeotSenior[i].innerText)) {
        peugeotSenior[i].innerHTML = "0%"
    } else if(parseInt(peugeotSenior[i].innerText) != 0) {
        arrayPeugeotSenior.push(peugeotSenior[i].innerText)
    }
}
for(var i = 0; i < arrayPeugeotSenior.length; i++) {
    totalPeugeotSenior += parseInt(arrayPeugeotSenior[i]);
    var avgPeSenior = Math.ceil(totalPeugeotSenior / arrayPeugeotSenior.length);
}
if (avgPeSenior == undefined) {
    resultPeugeotSenior.innerHTML = "-"
} else {
    resultPeugeotSenior.innerHTML = avgPeSenior + "%"
}

for(var i = 0; i < suzukiSenior.length; i++) {
    if(!isNaN(suzukiSenior[i].innerText)) {
        suzukiSenior[i].innerHTML = "0%"
    } else if(parseInt(suzukiSenior[i].innerText) != 0) {
        arraySuzukiSenior.push(suzukiSenior[i].innerText)
    }
}
for(var i = 0; i < arraySuzukiSenior.length; i++) {
    totalSuzukiSenior += parseInt(arraySuzukiSenior[i]);
    var avgSuSenior = Math.ceil(totalSuzukiSenior / arraySuzukiSenior.length);
}
if (avgSuSenior == undefined) {
    resultSuzukiSenior.innerHTML = "-"
} else {
    resultSuzukiSenior.innerHTML = avgSuSenior + "%"
}

for(var i = 0; i < toyotaSenior.length; i++) {
    if(!isNaN(toyotaSenior[i].innerText)) {
        toyotaSenior[i].innerHTML = "0%"
    } else if(parseInt(toyotaSenior[i].innerText) != 0) {
        arrayToyotaSenior.push(toyotaSenior[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaSenior.length; i++) {
    totalToyotaSenior += parseInt(arrayToyotaSenior[i]);
    var avgToyotaSenior = Math.ceil(totalToyotaSenior / arrayToyotaSenior.length);
}
if (avgToyotaSenior == undefined) {
    resultToyotaSenior.innerHTML = "-"
} else {
    resultToyotaSenior.innerHTML = avgToyotaSenior + "%";
}

for(var i = 0; i < resultSenior.length; i++) {
    if(parseInt(resultSenior[i].innerText) != 0) {
        arraySenior.push(resultSenior[i].innerText)
    }
}
for(var i = 0; i < arraySenior.length; i++) {
    totalSenior += parseInt(arraySenior[i]);
    var avgSenior = Math.ceil(totalSenior / arraySenior.length);
}
if (avgSenior == undefined) {
    resultTotalSenior.innerHTML = "-"
} else {
    resultTotalSenior.innerHTML = avgSenior + "%";
}

var totalKingLongExpert = 0;
var totalFusoExpert = 0;
var totalHinoExpert = 0;
var totalMercedesTruckExpert = 0;
var totalRenaultTruckExpert = 0;
var totalSinotrukExpert = 0;
var totalToyotaBtExpert = 0;
var totalToyotaForfliftExpert = 0;
var totalJcbExpert = 0;
var totalLovolExpert = 0;
var totalCitroenExpert = 0;
var totalMercedesExpert = 0;
var totalPeugeotExpert = 0;
var totalSuzukiExpert = 0;
var totalToyotaExpert = 0;
var totalExpert = 0;

let arrayKingLongExpert = [];
let arrayFusoExpert = [];
let arrayHinoExpert = [];
let arrayMercedesTruckExpert = [];
let arrayRenaultTruckExpert = [];
let arraySinotrukExpert = [];
let arrayToyotaBtExpert = [];
let arrayToyotaForfliftExpert = [];
let arrayJcbExpert = [];
let arrayLovolExpert = [];
let arrayCitroenExpert = [];
let arrayMercedesExpert = [];
let arrayPeugeotExpert = [];
let arraySuzukiExpert = [];
let arrayToyotaExpert = [];
let arrayExpert = [];

for(var i = 0; i < kingLongExpert.length; i++) {
    if(!isNaN(kingLongExpert[i].innerText)) {
        kingLongExpert[i].innerHTML = "0%"
    } else if(parseInt(kingLongExpert[i].innerText) != 0) {
        arrayKingLongExpert.push(kingLongExpert[i].innerText)
    }
}
for(var i = 0; i < arrayKingLongExpert.length; i++) {
    totalKingLongExpert += parseInt(arrayKingLongExpert[i]);
    var avgKingExpert = Math.ceil(totalKingLongExpert / arrayKingLongExpert.length);
}
if (avgKingExpert == undefined) {
    resultKingLongExpert.innerHTML = "-"
} else {
    resultKingLongExpert.innerHTML = avgKingExpert + "%"
}

for(var i = 0; i < fusoExpert.length; i++) {
    if(!isNaN(fusoExpert[i].innerText)) {
        fusoExpert[i].innerHTML = "0%"
    } else if(parseInt(fusoExpert[i].innerText) != 0) {
        arrayFusoExpert.push(fusoExpert[i].innerText)
    }
}
for(var i = 0; i < arrayFusoExpert.length; i++) {
    totalFusoExpert += parseInt(arrayFusoExpert[i]);
    var avgFuExpert = Math.ceil(totalFusoExpert / arrayFusoExpert.length);
}
if (avgFuExpert == undefined) {
    resultFusoExpert.innerHTML = "-"
} else {
    resultFusoExpert.innerHTML = avgFuExpert + "%"
}

for(var i = 0; i < hinoExpert.length; i++) {
    if(!isNaN(hinoExpert[i].innerText)) {
        hinoExpert[i].innerHTML = "0%"
    } else if(parseInt(hinoExpert[i].innerText) != 0) {
        arrayHinoExpert.push(hinoExpert[i].innerText)
    }
}
for(var i = 0; i < arrayHinoExpert.length; i++) {
    totalHinoExpert += parseInt( arrayHinoExpert[i]);
    var avgHiExpert = Math.ceil(totalHinoExpert / arrayHinoExpert.length);
}
if (avgHiExpert == undefined) {
    resultHinoExpert.innerHTML = "-"
} else {
    resultHinoExpert.innerHTML = avgHiExpert + "%"
}

for(var i = 0; i < mercedesTruckExpert.length; i++) {
    if(!isNaN(mercedesTruckExpert[i].innerText)) {
        mercedesTruckExpert[i].innerHTML = "0%"
    } else if(parseInt(mercedesTruckExpert[i].innerText) != 0) {
        arrayMercedesTruckExpert.push(mercedesTruckExpert[i].innerText)
    }
}
for(var i = 0; i < arrayMercedesTruckExpert.length; i++) {
    totalMercedesTruckExpert += parseInt(arrayMercedesTruckExpert[i]);
    var avgMeTrExpert = Math.ceil(totalMercedesTruckExpert / arrayMercedesTruckExpert.length);
}
if (avgMeTrExpert == undefined) {
    resultMercedesTruckExpert.innerHTML = "-"
} else {
    resultMercedesTruckExpert.innerHTML = avgMeTrExpert + "%"
}

for(var i = 0; i < renaultTruckExpert.length; i++) {
    if(!isNaN(renaultTruckExpert[i].innerText)) {
        renaultTruckExpert[i].innerHTML = "0%"
    } else if(parseInt(renaultTruckExpert[i].innerText) != 0) {
        arrayRenaultTruckExpert.push(renaultTruckExpert[i].innerText)
    }
}
for(var i = 0; i < arrayRenaultTruckExpert.length; i++) {
    totalRenaultTruckExpert += parseInt(arrayRenaultTruckExpert[i]);
    var avgReTrExpert = Math.ceil(totalRenaultTruckExpert / arrayRenaultTruckExpert.length);
}
if (avgReTrExpert == undefined) {
    resultRenaultTruckExpert.innerHTML = "-"
} else {
    resultRenaultTruckExpert.innerHTML = avgReTrExpert + "%"
}

for(var i = 0; i < sinotrukExpert.length; i++) {
    if(!isNaN(sinotrukExpert[i].innerText)) {
        sinotrukExpert[i].innerHTML = "0%"
    } else if(parseInt(sinotrukExpert[i].innerText) != 0) {
        arraySinotrukExpert.push(sinotrukExpert[i].innerText)
    }
}
for(var i = 0; i < arraySinotrukExpert.length; i++) {
    totalSinotrukExpert += parseInt(arraySinotrukExpert[i]);
    var avgSiTrExpert = Math.ceil(totalSinotrukExpert / arraySinotrukExpert.length);
}
if (avgSiTrExpert == undefined) {
    resultSinotrukExpert.innerHTML = "-"
} else {
    resultSinotrukExpert.innerHTML = avgSiTrExpert + "%"
}

for(var i = 0; i < toyotaBtExpert.length; i++) {
    if(!isNaN(toyotaBtExpert[i].innerText)) {
        toyotaBtExpert[i].innerHTML = "0%"
    } else if(parseInt(toyotaBtExpert[i].innerText) != 0) {
        arrayToyotaBtExpert.push(toyotaBtExpert[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaBtExpert.length; i++) {
    totalToyotaBtExpert += parseInt(arrayToyotaBtExpert[i]);
    var avgToBtExpert = Math.ceil(totalToyotaBtExpert / arrayToyotaBtExpert.length);
}
if (avgToBtExpert == undefined) {
    resultToyotaBtExpert.innerHTML = "-"
} else {
    resultToyotaBtExpert.innerHTML = avgToBtExpert + "%"
}

for(var i = 0; i < toyotaForfliftExpert.length; i++) {
    if(!isNaN(toyotaForfliftExpert[i].innerText)) {
        toyotaForfliftExpert[i].innerHTML = "0%"
    } else if(parseInt(toyotaForfliftExpert[i].innerText) != 0) {
        arrayToyotaForfliftExpert.push(toyotaForfliftExpert[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaForfliftExpert.length; i++) {
    totalToyotaForfliftExpert += parseInt(arrayToyotaForfliftExpert[i]);
    var avgToFoExpert = Math.ceil(totalToyotaForfliftExpert / arrayToyotaForfliftExpert.length);
}
if (avgToFoExpert == undefined) {
    resultToyotaForfliftExpert.innerHTML = "-"
} else {
    resultToyotaForfliftExpert.innerHTML = avgToFoExpert + "%"
}

for(var i = 0; i < jcbExpert.length; i++) {
    if(!isNaN(jcbExpert[i].innerText)) {
        jcbExpert[i].innerHTML = "0%"
    } else if(parseInt(jcbExpert[i].innerText) != 0) {
        arrayJcbExpert.push(jcbExpert[i].innerText)
    }
}
for(var i = 0; i < arrayJcbExpert.length; i++) {
    totalJcbExpert += parseInt(arrayJcbExpert[i]);
    var avgJcExpert = Math.ceil(totalJcbExpert / arrayJcbExpert.length);
}
if (avgJcExpert == undefined) {
    resultJcbExpert.innerHTML = "-"
} else {
    resultJcbExpert.innerHTML = avgJcExpert + "%"
}

for(var i = 0; i < lovolExpert.length; i++) {
    if(!isNaN(lovolExpert[i].innerText)) {
        lovolExpert[i].innerHTML = "0%"
    } else if(parseInt(lovolExpert[i].innerText) != 0) {
        arrayLovolExpert.push(lovolExpert[i].innerText)
    }
}
for(var i = 0; i < arrayLovolExpert.length; i++) {
    totalLovolExpert += parseInt(arrayLovolExpert[i]);
    var avgLoExpert = Math.ceil(totalLovolExpert / arrayLovolExpert.length);
}
if (avgLoExpert == undefined) {
    resultLovolExpert.innerHTML = "-"
} else {
    resultLovolExpert.innerHTML = avgLoExpert + "%"
}

for(var i = 0; i < citroenExpert.length; i++) {
    if(!isNaN(citroenExpert[i].innerText)) {
        citroenExpert[i].innerHTML = "0%"
    } else if(parseInt(citroenExpert[i].innerText) != 0) {
        arrayCitroenExpert.push(citroenExpert[i].innerText)
    }
}
for(var i = 0; i < arrayCitroenExpert.length; i++) {
    totalCitroenExpert += parseInt(arrayCitroenExpert[i]);
    var avgCiExpert = Math.ceil(totalCitroenExpert / arrayCitroenExpert.length);
}
if (avgCiExpert == undefined) {
    resultCitroenExpert.innerHTML = "-"
} else {
    resultCitroenExpert.innerHTML = avgCiExpert + "%"
}

for(var i = 0; i < mercedesExpert.length; i++) {
    if(!isNaN(mercedesExpert[i].innerText)) {
        mercedesExpert[i].innerHTML = "0%"
    } else if(parseInt(mercedesExpert[i].innerText) != 0) {
        arrayMercedesExpert.push(mercedesExpert[i].innerText)
    }
}
for(var i = 0; i < arrayMercedesExpert.length; i++) {
    totalMercedesExpert += parseInt(arrayMercedesExpert[i]);
    var avgMeExpert = Math.ceil(totalMercedesExpert / arrayMercedesExpert.length);
}
if (avgMeExpert == undefined) {
    resultMercedesExpert.innerHTML = "-"
} else {
    resultMercedesExpert.innerHTML = avgMeExpert + "%"
}

for(var i = 0; i < peugeotExpert.length; i++) {
    if(!isNaN(peugeotExpert[i].innerText)) {
        peugeotExpert[i].innerHTML = "0%"
    } else if(parseInt(peugeotExpert[i].innerText) != 0) {
        arrayPeugeotExpert.push(peugeotExpert[i].innerText)
    }
}
for(var i = 0; i < arrayPeugeotExpert.length; i++) {
    totalPeugeotExpert += parseInt(arrayPeugeotExpert[i]);
    var avgPeExpert = Math.ceil(totalPeugeotExpert / arrayPeugeotExpert.length);
}
if (avgPeExpert == undefined) {
    resultPeugeotExpert.innerHTML = "-"
} else {
    resultPeugeotExpert.innerHTML = avgPeExpert + "%"
}

for(var i = 0; i < suzukiExpert.length; i++) {
    if(!isNaN(suzukiExpert[i].innerText)) {
        suzukiExpert[i].innerHTML = "0%"
    } else if(parseInt(suzukiExpert[i].innerText) != 0) {
        arraySuzukiExpert.push(suzukiExpert[i].innerText)
    }
}
for(var i = 0; i < arraySuzukiExpert.length; i++) {
    totalSuzukiExpert += parseInt(arraySuzukiExpert[i]);
    var avgSuExpert = Math.ceil(totalSuzukiExpert / arraySuzukiExpert.length);
}
if (avgSuExpert == undefined) {
    resultSuzukiExpert.innerHTML = "-"
} else {
    resultSuzukiExpert.innerHTML = avgSuExpert + "%"
}

for(var i = 0; i < toyotaExpert.length; i++) {
    if(!isNaN(toyotaExpert[i].innerText)) {
        toyotaExpert[i].innerHTML = "0%"
    } else if(parseInt(toyotaExpert[i].innerText) != 0) {
        arrayToyotaExpert.push(toyotaExpert[i].innerText)
    }
}
for(var i = 0; i < arrayToyotaExpert.length; i++) {
    totalToyotaExpert += parseInt(arrayToyotaExpert[i]);
    var avgToyotaExpert = Math.ceil(totalToyotaExpert / arrayToyotaExpert.length);
}
if (avgToyotaExpert == undefined) {
    resultToyotaExpert.innerHTML = "-"
} else {
    resultToyotaExpert.innerHTML = avgToyotaExpert + "%";
}

for(var i = 0; i < resultExpert.length; i++) {
    if(parseInt(resultExpert[i].innerText) != 0) {
        arrayExpert.push(resultExpert[i].innerText)
    }
}
for(var i = 0; i < arrayExpert.length; i++) {
    totalExpert += parseInt(arrayExpert[i]);
    var avgExpert = Math.ceil(totalExpert / arrayExpert.length);
}
if (avgExpert == undefined) {
    resultTotalExpert.innerHTML = "-"
} else {
    resultTotalExpert.innerHTML = avgExpert + "%";
}

let kingLong = [];
if (resultKingLongJunior.innerText != "-") {
    kingLong.push(resultKingLongJunior.innerText)
}
if (resultKingLongSenior.innerText != "-") {
    kingLong.push(resultKingLongSenior.innerText)
}
if (resultKingLongExpert.innerText != "-") {
    kingLong.push(resultKingLongExpert.innerText)
}
let totalKingLong = 0;
for(var i = 0; i < kingLong.length; i++) {
    totalKingLong += parseInt(kingLong[i]);
    var avgKingLong = Math.ceil(totalKingLong / kingLong.length);
}
if (avgKingLong == undefined) {
    resultKingLong.innerHTML = "-"
} else {
    resultKingLong.innerHTML = avgKingLong + "%";
}
let fuso = [];
if (resultFusoJunior.innerText != "-") {
    fuso.push(resultFusoJunior.innerText)
}
if (resultFusoSenior.innerText != "-") {
    fuso.push(resultFusoSenior.innerText)
}
if (resultFusoExpert.innerText != "-") {
    fuso.push(resultFusoExpert.innerText)
}
let totalFuso = 0;
for(var i = 0; i < fuso.length; i++) {
    totalFuso += parseInt(fuso[i]);
    var avgFuso = Math.ceil(totalFuso / fuso.length);
}
if (avgFuso == undefined) {
    resultFuso.innerHTML = "-"
} else {
    resultFuso.innerHTML = avgFuso + "%";
}
let hino = [];
if (resultHinoJunior.innerText != "-") {
    hino.push(resultHinoJunior.innerText)
}
if (resultHinoSenior.innerText != "-") {
    hino.push(resultHinoSenior.innerText)
}
if (resultHinoExpert.innerText != "-") {
    hino.push(resultHinoExpert.innerText)
}
let totalHino = 0;
for(var i = 0; i < hino.length; i++) {
    totalHino += parseInt(hino[i]);
    var avgHino = Math.ceil(totalHino / hino.length);
}
if (avgHino == undefined) {
    resultHino.innerHTML = "-"
} else {
    resultHino.innerHTML = avgHino + "%";
}
let mercedesTruck = [];
if (resultMercedesTruckJunior.innerText != "-") {
    mercedesTruck.push(resultMercedesTruckJunior.innerText)
}
if (resultMercedesTruckSenior.innerText != "-") {
    mercedesTruck.push(resultMercedesTruckSenior.innerText)
}
if (resultMercedesTruckExpert.innerText != "-") {
    mercedesTruck.push(resultMercedesTruckExpert.innerText)
}
let totalMercedesTruck = 0;
for(var i = 0; i < mercedesTruck.length; i++) {
    totalMercedesTruck += parseInt(mercedesTruck[i]);
    var avgMercedesTruck = Math.ceil(totalMercedesTruck / mercedesTruck.length);
}
if (avgMercedesTruck == undefined) {
    resultMercedesTruck.innerHTML = "-"
} else {
    resultMercedesTruck.innerHTML = avgMercedesTruck + "%";
}
let renaultTruck = [];
if (resultRenaultTruckJunior.innerText != "-") {
    renaultTruck.push(resultRenaultTruckJunior.innerText)
}
if (resultRenaultTruckSenior.innerText != "-") {
    renaultTruck.push(resultRenaultTruckSenior.innerText)
}
if (resultRenaultTruckExpert.innerText != "-") {
    renaultTruck.push(resultRenaultTruckExpert.innerText)
}
let totalRenaultTruck = 0;
for(var i = 0; i < renaultTruck.length; i++) {
    totalRenaultTruck += parseInt(renaultTruck[i]);
    var avgRenaultTruck = Math.ceil(totalRenaultTruck / renaultTruck.length);
}
if (avgRenaultTruck == undefined) {
    resultRenaultTruck.innerHTML = "-"
} else {
    resultRenaultTruck.innerHTML = avgRenaultTruck + "%";
}
let sinotruk = [];
if (resultSinotrukJunior.innerText != "-") {
    sinotruk.push(resultSinotrukJunior.innerText)
}
if (resultSinotrukSenior.innerText != "-") {
    sinotruk.push(resultSinotrukSenior.innerText)
}
if (resultSinotrukExpert.innerText != "-") {
    sinotruk.push(resultSinotrukExpert.innerText)
}
let totalSinotruk = 0;
for(var i = 0; i < sinotruk.length; i++) {
    totalSinotruk += parseInt(sinotruk[i]);
    var avgSinotruk = Math.ceil(totalSinotruk / sinotruk.length);
}
if (avgSinotruk == undefined) {
    resultSinotruk.innerHTML = "-"
} else {
    resultSinotruk.innerHTML = avgSinotruk + "%";
}
let toyotaBt = [];
if (resultToyotaBtJunior.innerText != "-") {
    toyotaBt.push(resultTtoyotaBtJunior.innerText)
}
if (resultToyotaBtSenior.innerText != "-") {
    toyotaBt.push(resultToyotaBtSenior.innerText)
}
if (resultToyotaBtExpert.innerText != "-") {
    toyotaBt.push(resultTtoyotaBtExpert.innerText)
}
let totalToyotaBt = 0;
for(var i = 0; i < toyotaBt.length; i++) {
    totalToyotaBt += parseInt(toyotaBt[i]);
    var avgToyotaBt = Math.ceil(totalToyotaBt / toyotaBt.length);
}
if (avgToyotaBt == undefined) {
    resultToyotaBt.innerHTML = "-"
} else {
    resultToyotaBt.innerHTML = avgToyotaBt + "%";
}
let toyotaForflift = [];
if (resultToyotaForfliftJunior.innerText != "-") {
    toyotaForflift.push(resultToyotaForfliftJunior.innerText)
}
if (resultToyotaForfliftSenior.innerText != "-") {
    toyotaForflift.push(resultToyotaForfliftSenior.innerText)
}
if (resultToyotaForfliftExpert.innerText != "-") {
    toyotaForflift.push(resultToyotaForfliftExpert.innerText)
}
let totalToyotaForflift = 0;
for(var i = 0; i < toyotaForflift.length; i++) {
    totalToyotaForflift += parseInt(toyotaForflift[i]);
    var avgToyotaForflift = Math.ceil(totalToyotaForflift / toyotaForflift.length);
}
if (avgToyotaForflift == undefined) {
    resultToyotaForflift.innerHTML = "-"
} else {
    resultToyotaForflift.innerHTML = avgToyotaForflift + "%";
}
let jcb = [];
if (resultJcbJunior.innerText != "-") {
    jcb.push(resultJcbJunior.innerText)
}
if (resultJcbSenior.innerText != "-") {
    jcb.push(resultJcbSenior.innerText)
}
if (resultJcbExpert.innerText != "-") {
    jcb.push(resultJcbExpert.innerText)
}
let totalJcb = 0;
for(var i = 0; i < jcb.length; i++) {
    totalJcb += parseInt(jcb[i]);
    var avgJcb = Math.ceil(totalJcb / jcb.length);
}
if (avgJcb == undefined) {
    resultJcb.innerHTML = "-"
} else {
    resultJcb.innerHTML = avgJcb + "%";
}
let lovol = [];
if (resultLovolJunior.innerText != "-") {
    lovol.push(resultLovolJunior.innerText)
}
if (resultLovolSenior.innerText != "-") {
    lovol.push(resultLovolSenior.innerText)
}
if (resultLovolExpert.innerText != "-") {
    lovol.push(resultLovolExpert.innerText)
}
let totalLovol = 0;
for(var i = 0; i < lovol.length; i++) {
    totalLovol += parseInt(lovol[i]);
    var avgLovol = Math.ceil(totalLovol / lovol.length);
}
if (avgLovol == undefined) {
    resultLovol.innerHTML = "-"
} else {
    resultLovol.innerHTML = avgLovol + "%";
}
let citroen = [];
if (resultCitroenJunior.innerText != "-") {
    citroen.push(resultCitroenJunior.innerText)
}
if (resultCitroenSenior.innerText != "-") {
    citroen.push(resultCitroenSenior.innerText)
}
if (resultCitroenExpert.innerText != "-") {
    citroen.push(resultCitroenExpert.innerText)
}
let totalCitroen = 0;
for(var i = 0; i < citroen.length; i++) {
    totalCitroen += parseInt(citroen[i]);
    var avgCitroen = Math.ceil(totalCitroen / citroen.length);
}
if (avgCitroen == undefined) {
    resultCitroen.innerHTML = "-"
} else {
    resultCitroen.innerHTML = avgCitroen + "%";
}
let mercedes = [];
if (resultMercedesJunior.innerText != "-") {
    mercedes.push(resultMercedesJunior.innerText)
}
if (resultMercedesSenior.innerText != "-") {
    mercedes.push(resultMercedesSenior.innerText)
}
if (resultMercedesExpert.innerText != "-") {
    mercedes.push(resultMercedesExpert.innerText)
}
let totalMercedes = 0;
for(var i = 0; i < mercedes.length; i++) {
    totalMercedes += parseInt(mercedes[i]);
    var avgMercedes = Math.ceil(totalMercedes / mercedes.length);
}
if (avgMercedes == undefined) {
    resultMercedes.innerHTML = "-"
} else {
    resultMercedes.innerHTML = avgMercedes + "%";
}
let peugeot = [];
if (resultPeugeotJunior.innerText != "-") {
    peugeot.push(resultPeugeotJunior.innerText)
}
if (resultPeugeotSenior.innerText != "-") {
    peugeot.push(resultPeugeotSenior.innerText)
}
if (resultPeugeotExpert.innerText != "-") {
    peugeot.push(resultPeugeotExpert.innerText)
}
let totalPeugeot = 0;
for(var i = 0; i < peugeot.length; i++) {
    totalPeugeot += parseInt(peugeot[i]);
    var avgPeugeot = Math.ceil(totalPeugeot / peugeot.length);
}
if (avgPeugeot == undefined) {
    resultPeugeot.innerHTML = "-"
} else {
    resultPeugeot.innerHTML = avgPeugeot + "%";
}
let suzuki = [];
if (resultSuzukiJunior.innerText != "-") {
    suzuki.push(resultSuzukiJunior.innerText)
}
if (resultSuzukiSenior.innerText != "-") {
    suzuki.push(resultSuzukiSenior.innerText)
}
if (resultSuzukiExpert.innerText != "-") {
    suzuki.push(resultSuzukiExpert.innerText)
}
let totalSuzuki = 0;
for(var i = 0; i < suzuki.length; i++) {
    totalSuzuki += parseInt(suzuki[i]);
    var avgSuzuki = Math.ceil(totalSuzuki / suzuki.length);
}
if (avgSuzuki == undefined) {
    resultSuzuki.innerHTML = "-"
} else {
    resultSuzuki.innerHTML = avgSuzuki + "%";
}
let toyota = [];
if (resultToyotaJunior.innerText != "-") {
    toyota.push(resultToyotaJunior.innerText)
}
if (resultToyotaSenior.innerText != "-") {
    toyota.push(resultToyotaSenior.innerText)
}
if (resultToyotaExpert.innerText != "-") {
    toyota.push(resultToyotaExpert.innerText)
}
let totalToyota = 0;
for(var i = 0; i < toyota.length; i++) {
    totalToyota += parseInt(toyota[i]);
    var avgToyota = Math.ceil(totalToyota / toyota.length);
}
if (avgToyota == undefined) {
    resultToyota.innerHTML = "-"
} else {
    resultToyota.innerHTML = avgToyota + "%";
}
let result = [];
if (resultTotalJunior.innerText != "-") {
    result.push(resultTotalJunior.innerText)
}
if (resultTotalSenior.innerText != "-") {
    result.push(resultTotalSenior.innerText)
}
if (resultTotalExpert.innerText != "-") {
    result.push(resultTotalExpert.innerText)
}
let totalResult = 0;
for(var i = 0; i < result.length; i++) {
    totalResult += parseInt(result[i]);
    var avgResult = Math.ceil(totalResult / result.length);
}
if (avgResult == undefined) {
    resultTotal.innerHTML = "-"
} else {
    resultTotal.innerHTML = avgResult + "%";
}
// console.log(array)
</script>
<?php include_once "partials/footer.php"; ?>
<?php
} ?>
