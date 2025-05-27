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

    // $technician->_id = $_GET["user"];
    $niveau = $_GET["level"];
    // $niveau = "Junior";
    // $numberTest = $_GET["numberTest"];
    $vide = "1";

    $technicians = [];

    // $filiales = ["Burkina Faso", "Cameroun", "Mali"];
    $filiales = ["Burkina Faso", "Cameroun", "Cote D'Ivoire", "Gabon", "Mali", "RCA", "RDC", "Senegal"];
    $sigle = ["Bu", "Ca", "Co", "Ga", "Mali", "Rca", "Rdc", "Se"];


    include_once "language.php";
?>

<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $result_filiales ?> | CFAO Mobility Academy</title>
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
                    <?php echo $result_mesure_competence ?> <?php echo $level ?> <?php echo $niveau ?> <?php echo $par_filiale ?> <?php echo $by_brand ?></h1>
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
                                    <tr class="text-start text-gray-400 fw-bolder text-uppercase gs-0">
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan="3"
                                        aria-label="Email: activate to sort column ascending" style="width: 10px;">
                                        <?php echo $subsidiary ?> CFAO (<?php echo $pays ?>)</th>
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
                                        <?php foreach ($filiales as $key => $filiale) {
                                            $technician = $users->find([
                                                '$and' => [
                                                    [
                                                        "country" => $filiale,
                                                        "active" => true,
                                                    ],
                                                ],
                                            ])->toArray();
                                            foreach ($technician as $techn) {
                                                if ($techn["profile"] == "Technicien") {
                                                    array_push($technicians, new MongoDB\BSON\ObjectId($techn['_id']));
                                                } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                                                    array_push($technicians, new MongoDB\BSON\ObjectId($techn['_id']));
                                                }
                                            }
                                        foreach ($technicians as $technician) { 
                                            $allocateFac = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => $niveau,
                                                        "type" => "Factuel",
                                                    ],
                                                ],
                                            ]);
                                            $allocateDecla = $allocations->findOne([
                                                '$and' => [
                                                    [
                                                        "user" => new MongoDB\BSON\ObjectId($technician),
                                                        "level" => $niveau,
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
                                                        ["speciality" => "Réducteur"],
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
                                                        ["level" => $niveau],
                                                        ["speciality" => "Réducteur"],
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
                                                        ["level" => $niveau],
                                                        ["speciality" => "Réducteur"],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                            ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
                                                        ["speciality" => "Transversale"],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultFac = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Factuel"],
                                                        ["typeR" => "Technicien"],
                                                        ["level" => $niveau],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultDecla = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["type" => "Declaratif"],
                                                        ["typeR" => "Techniciens"],
                                                        ["level" => $niveau],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Managers"],
                                                        ["level" => $niveau],
                                                        ["active" => true],
                                                    ],
                                                ]);
                                                $resultTechMa = $results->findOne([
                                                    '$and' => [
                                                        ["user" => new MongoDB\BSON\ObjectId($tech->_id)],
                                                        ["manager" => new MongoDB\BSON\ObjectId($tech->manager)],
                                                        ["typeR" => "Technicien - Manager"],
                                                        ["level" => $niveau],
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
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>kingLong">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'KING LONG'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($kingLongScoreFac * 100) / $kingLongFac + ($kingLongScore * 100) / $kingLongDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>kingLong">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>fuso">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'FUSO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($fusoScoreFac * 100) / $fusoFac + ($fusoScore * 100) / $fusoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>fuso">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>hino">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'HINO'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($hinoScoreFac * 100) / $hinoFac + ($hinoScore * 100) / $hinoDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>hino">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>mercedesTruck">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'MERCEDES TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesTruckScoreFac * 100) / $mercedesTruckFac + ($mercedesTruckScore * 100) / $mercedesTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>mercedesTruck">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>renaultTruck">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'RENAULT TRUCK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($renaultTruckScoreFac * 100) / $renaultTruckFac + ($renaultTruckScore * 100) / $renaultTruckDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>renaultTruck">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>sinotruk">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'SINOTRUK'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($sinotrukScoreFac * 100) / $sinotrukFac + ($sinotrukScore * 100) / $sinotrukDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>sinotruk">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>toyotaBt">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'TOYOTA BT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaBtScoreFac * 100) / $toyotaBtFac + ($toyotaBtScore * 100) / $toyotaBtDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>toyotaBt">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>toyotaForflift">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'TOYOTA FORKLIFT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaForfliftScoreFac * 100) / $toyotaForfliftFac + ($toyotaForfliftScore * 100) / $toyotaForfliftDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>toyotaForflift">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>jcb">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'JCB'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($jcbScoreFac * 100) / $jcbFac + ($jcbScore * 100) / $jcbDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>jcb">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>lovol">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'LOVOL'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($lovolScoreFac * 100) / $lovolFac + ($lovolScore * 100) / $lovolDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>lovol">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>citroen">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'CITROEN'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($citroenScoreFac * 100) / $citroenFac + ($citroenScore * 100) / $citroenDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>citroen">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>mercedes">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'MERCEDES'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($mercedesScoreFac * 100) / $mercedesFac + ($mercedesScore * 100) / $mercedesDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>mercedes">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>peugeot">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'PEUGEOT'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($peugeotScoreFac * 100) / $peugeotFac + ($peugeotScore * 100) / $peugeotDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>peugeot">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>suzuki">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'SUZUKI'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($suzukiScoreFac * 100) / $suzukiFac + ($suzukiScore * 100) / $suzukiDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>suzuki">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>toyota">
                                            <?php for ($i=0; $i < count($tech['brand'.$niveau]); $i++) {
                                            if (
                                                $tech['brand'.$niveau][$i] == 'TOYOTA'
                                            ) { ?>
                                                <?php echo
                                                    ceil((($toyotaScoreFac * 100) / $toyotaFac + ($toyotaScore * 100) / $toyotaDecla) / 2)
                                                ?>%
                                            <?php }
                                                } ?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>toyota">
                                                0%
                                            </td>
                                            <?php } ?>
                                            <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true) { ?> 
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>result">
                                                <?php echo
                                                    ceil((($resultFac->score * 100) / $resultFac->total + ($resultTechMa->score * 100) / $resultTechMa->total ) / 2)
                                                ?>%
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-center hidden" id="<?php echo $sigle[$key] ?>result">
                                                0%
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php } } ?>
                                        <tr class="odd" style="">
                                            <?php if ($_SESSION['profile'] == "Super Admin") { ?>
                                            <td class="sorting text-white text-center table-light text-uppercase gs-0"
                                                tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                                aria-label="Email: activate to sort column ascending"
                                                style="width: 155.266px; ">
                                                <a href="./userSubsidiaryResult.php?subsidiary=<?php echo $cfao_benin ?>&level=<?php echo $niveau ?>"
                                                    class="btn btn-light btn-active-light-primary text-black btn-sm"
                                                    title="Cliquez ici pour voir le résultat de la filiale"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    <?php echo $filiale ?>
                                                </a>
                                            </td>
                                            <?php } else { ?>
                                            <td class="sorting text-black text-center table-light text-uppercase gs-0"
                                            tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px; ">
                                            <?php echo $filiale ?>
                                            </td>
                                            <?php } ?>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultKingLong">
                                                
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultFuso">
                                                
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultHino">
                                                
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultMercedesTruck">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultRenaultTruck">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultSinotruk">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultToyotaBt">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultToyotaForklift">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultJcb">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultLovol">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultCitroen">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultMercedes">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultPeugeot">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultSuzuki">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultToyota">
                                            
                                            </td>
                                            <td class="text-center" id="<?php echo $sigle[$key] ?>resultTotal">
                                            
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <!--end::Menu-->
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

// var cells = table.getElementsByTagName("td");
// console.log(cells)
// for (let i = 0; i < cells.length; i++) {
//     if (cells[i].textContent == "") {
//         cells[i].innerHTML = "0%"
//     }
// }

// var filiales = ["Burkina Faso", "Cameroun", "Mali"];
var filiales = ["Burkina Faso", "Cameroun", "Cote D'Ivoire", "Gabon", "Mali", "RCA", "RDC", "Senegal"];
var sigle = ["Bu", "Ca", "Co", "Ga", "Mali", "Rca", "Rdc", "Se"];

var brand = [
    ['KING LONG', 'HINO', 'SINOTRUK', 'JCB', 'TOYOTA FORKLIFT', 'TOYOTA BT', 'LOVOL'], // marques Burkina
    ['KING LONG', 'HINO', 'SINOTRUK', 'RENAULT TRUCK', 'JCB', 'TOYOTA FORKLIFT', 'TOYOTA BT', 'LOVOL'], // marques Cameroun
    ['KING LONG', 'FUSO', 'HINO', 'RENAULT TRUCK', 'JCB', 'TOYOTA FORKLIFT', 'TOYOTA BT', 'LOVOL'], // marques Cote d'Ivoire
    ['KING LONG', 'HINO', 'RENAULT TRUCK', 'JCB', 'TOYOTA FORKLIFT', 'TOYOTA BT'], // marques Gabon
    ['KING LONG', 'HINO', 'RENAULT TRUCK', 'JCB', 'TOYOTA FORKLIFT', 'TOYOTA BT'], // marques Mali
    ['HINO', 'SINOTRUK', 'JCB'], // marques RCA
    ['KING LONG', 'HINO', 'SINOTRUK', 'RENAULT TRUCK', 'JCB', 'TOYOTA FORKLIFT', 'TOYOTA BT'], // marques RDC
    ['KING LONG', 'HINO', 'SINOTRUK', 'RENAULT TRUCK', 'JCB', 'TOYOTA FORKLIFT', 'TOYOTA BT'] // marques Senegal
]


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

let kingLong = [];
let fuso = [];
let hino = [];
let mercedesTruck = [];
let renaultTruck = [];
let sinotruk = [];
let toyotaBt = [];
let toyotaForflift = [];
let jcb = [];
let lovol = [];
let citroen = [];
let mercedes = [];
let peugeot = [];
let suzuki = [];
let toyota = [];
let result = [];

filiales.forEach((filiale, j) => {
    var kingLongBe = document.querySelectorAll("#" + sigle[j] + "kingLong")
    var fusoBe = document.querySelectorAll("#" + sigle[j] + "fuso")
    var hinoBe = document.querySelectorAll("#" + sigle[j] + "hino")
    var jcbBe = document.querySelectorAll("#" + sigle[j] + "jcb")
    var lovolBe = document.querySelectorAll("#" + sigle[j] + "lovol")
    var mercedesTruckBe = document.querySelectorAll("#" + sigle[j] + "mercedesTruck")
    var renaultTruckBe = document.querySelectorAll("#" + sigle[j] + "renaultTruck")
    var sinotrukBe = document.querySelectorAll("#" + sigle[j] + "sinotruk")
    var toyotaBtBe = document.querySelectorAll("#" + sigle[j] + "toyotaBt")
    var toyotaForfliftBe = document.querySelectorAll("#" + sigle[j] + "toyotaForflift")
    var citroenBe = document.querySelectorAll("#" + sigle[j] + "citroen")
    var mercedesBe = document.querySelectorAll("#" + sigle[j] + "mercedes")
    var peugeotBe = document.querySelectorAll("#" + sigle[j] + "peugeot")
    var suzukiBe = document.querySelectorAll("#" + sigle[j] + "suzuki")
    var toyotaBe = document.querySelectorAll("#" + sigle[j] + "toyota")
    var resultBe = document.querySelectorAll("#" + sigle[j] + "result")
    
    var resultKingLongBe = document.querySelector("#" + sigle[j] + "resultKingLong")
    var resultFusoBe = document.querySelector("#" + sigle[j] + "resultFuso")
    var resultHinoBe = document.querySelector("#" + sigle[j] + "resultHino")
    var resultJcbBe = document.querySelector("#" + sigle[j] + "resultJcb")
    var resultLovolBe = document.querySelector("#" + sigle[j] + "resultLovol")
    var resultMercedesTruckBe = document.querySelector("#" + sigle[j] + "resultMercedesTruck")
    var resultRenaultTruckBe = document.querySelector("#" + sigle[j] + "resultRenaultTruck")
    var resultSinotrukBe = document.querySelector("#" + sigle[j] + "resultSinotruk")
    var resultToyotaBtBe = document.querySelector("#" + sigle[j] + "resultToyotaBt")
    var resultToyotaForfliftBe = document.querySelector("#" + sigle[j] + "resultToyotaForklift")
    var resultCitroenBe = document.querySelector("#" + sigle[j] + "resultCitroen")
    var resultMercedesBe = document.querySelector("#" + sigle[j] + "resultMercedes")
    var resultPeugeotBe = document.querySelector("#" + sigle[j] + "resultPeugeot")
    var resultSuzukiBe = document.querySelector("#" + sigle[j] + "resultSuzuki")
    var resultToyotaBe = document.querySelector("#" + sigle[j] + "resultToyota")
    var resultTotalBe = document.querySelector("#" + sigle[j] + "resultTotal")
    
    var totalKingLongBe = 0;
    var totalFusoBe = 0;
    var totalHinoBe = 0;
    var totalMercedesTruckBe = 0;
    var totalRenaultTruckBe = 0;
    var totalSinotrukBe = 0;
    var totalToyotaBtBe = 0;
    var totalToyotaForfliftBe = 0;
    var totalJcbBe = 0;
    var totalLovolBe = 0;
    var totalCitroenBe = 0;
    var totalMercedesBe = 0;
    var totalPeugeotBe = 0;
    var totalSuzukiBe = 0;
    var totalToyotaBe = 0;
    var totalBe = 0;
    
    let arrayKingLongBe = [];
    let arrayFusoBe = [];
    let arrayHinoBe = [];
    let arrayMercedesTruckBe = [];
    let arrayRenaultTruckBe = [];
    let arraySinotrukBe = [];
    let arrayToyotaBtBe = [];
    let arrayToyotaForfliftBe = [];
    let arrayJcbBe = [];
    let arrayLovolBe = [];
    let arrayCitroenBe = [];
    let arrayMercedesBe = [];
    let arrayPeugeotBe = [];
    let arraySuzukiBe = [];
    let arrayToyotaBe = [];
    let arrayBe = [];
    
    if (brand[j].includes('KING LONG')) {
        for(var i = 0; i < kingLongBe.length; i++) {
            if(!isNaN(kingLongBe[i].innerText)) {
                kingLongBe[i].innerHTML = "0%"
            } else if(parseInt(kingLongBe[i].innerText) != 0) {
                arrayKingLongBe.push(kingLongBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayKingLongBe.length; i++) {
            totalKingLongBe += parseInt(arrayKingLongBe[i]);
            var avgKingBe = Math.ceil(totalKingLongBe / arrayKingLongBe.length);
        }
        if (avgKingBe == undefined) {
            resultKingLongBe.innerHTML = "-"
        } else {
            resultKingLongBe.innerHTML = avgKingBe + "%"
        }
    } else if (!brand[j].includes('KING LONG') && avgKingBe != undefined){
        resultKingLongBe.style.backgroundColor = "#f9f9f9"
        resultKingLongBe.innerHTML = avgKingBe + "%"
    } else {
        resultKingLongBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('FUSO')) {
        for(var i = 0; i < fusoBe.length; i++) {
            if(!isNaN(fusoBe[i].innerText)) {
                fusoBe[i].innerHTML = "0%"
            } else if(parseInt(fusoBe[i].innerText) != 0) {
                arrayFusoBe.push(fusoBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayFusoBe.length; i++) {
            totalFusoBe += parseInt(arrayFusoBe[i]);
            var avgFuBe = Math.ceil(totalFusoBe / arrayFusoBe.length);
        }
        if (avgFuBe == undefined) {
            resultFusoBe.innerHTML = "-"
        } else {
            resultFusoBe.innerHTML = avgFuBe + "%"
        }
    } else if (!brand[j].includes('FUSO') && avgFuBe != undefined){
        resultFusoBe.style.backgroundColor = "#f9f9f9"
        resultFusoBe.innerHTML = avgFuBe + "%"
    } else {
        resultFusoBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('HINO')) {
        for(var i = 0; i < hinoBe.length; i++) {
            if(!isNaN(hinoBe[i].innerText)) {
                hinoBe[i].innerHTML = "0%"
            } else if(parseInt(hinoBe[i].innerText) != 0) {
                arrayHinoBe.push(hinoBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayHinoBe.length; i++) {
            totalHinoBe += parseInt(arrayHinoBe[i]);
            var avgHiBe = Math.ceil(totalHinoBe / arrayHinoBe.length);
        }
        if (avgHiBe == undefined) {
            resultHinoBe.innerHTML = "-"
        } else {
            resultHinoBe.innerHTML = avgHiBe + "%"
        }
    } else if (!brand[j].includes('HINO') && avgHiBe != undefined){
        resultHinoBe.style.backgroundColor = "#f9f9f9"
        resultHinoBe.innerHTML = avgHiBe + "%"
    } else {
        resultHinoBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('MERCEDES TRUCK')) {
        for(var i = 0; i < mercedesTruckBe.length; i++) {
            if(!isNaN(mercedesTruckBe[i].innerText)) {
                mercedesTruckBe[i].innerHTML = "0%"
            } else if(parseInt(mercedesTruckBe[i].innerText) != 0) {
                arrayMercedesTruckBe.push(mercedesTruckBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayMercedesTruckBe.length; i++) {
            totalMercedesTruckBe += parseInt(arrayMercedesTruckBe[i]);
            var avgMeTrBe = Math.ceil(totalMercedesTruckBe / arrayMercedesTruckBe.length);
        }
        if (avgMeTrBe == undefined) {
            resultMercedesTruckBe.innerHTML = "-"
        } else {
            resultMercedesTruckBe.innerHTML = avgMeTrBe + "%"
        }
    } else if (!brand[j].includes('MERCEDES TRUCK') && avgMeTrBe != undefined){
        resultMercedesTruckBe.style.backgroundColor = "#f9f9f9"
        resultMercedesTruckBe.innerHTML = avgMeTrBe + "%"
    } else {
        resultMercedesTruckBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('RENAULT TRUCK')) {
        for(var i = 0; i < renaultTruckBe.length; i++) {
            if(!isNaN(renaultTruckBe[i].innerText)) {
                renaultTruckBe[i].innerHTML = "0%"
            } else if(parseInt(renaultTruckBe[i].innerText) != 0) {
                arrayRenaultTruckBe.push(renaultTruckBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayRenaultTruckBe.length; i++) {
            totalRenaultTruckBe += parseInt(arrayRenaultTruckBe[i]);
            var avgReTrBe = Math.ceil(totalRenaultTruckBe / arrayRenaultTruckBe.length);
        }
        if (avgReTrBe == undefined) {
            resultRenaultTruckBe.innerHTML = "-"
        } else {
            resultRenaultTruckBe.innerHTML = avgReTrBe + "%"
        }
    } else if (!brand[j].includes('RENAULT TRUCK') && avgReTrBe != undefined){
        resultRenaultTruckBe.style.backgroundColor = "#f9f9f9"
        resultRenaultTruckBe.innerHTML = avgReTrBe + "%"
    } else {
        resultRenaultTruckBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('SINOTRUK')) {
        for(var i = 0; i < sinotrukBe.length; i++) {
            if(!isNaN(sinotrukBe[i].innerText)) {
                sinotrukBe[i].innerHTML = "0%"
            } else if(parseInt(sinotrukBe[i].innerText) != 0) {
                arraySinotrukBe.push(sinotrukBe[i].innerText)
            }
        }
        for(var i = 0; i < arraySinotrukBe.length; i++) {
            totalSinotrukBe += parseInt(arraySinotrukBe[i]);
            var avgSiTrBe = Math.ceil(totalSinotrukBe / arraySinotrukBe.length);
        }
        if (avgSiTrBe == undefined) {
            resultSinotrukBe.innerHTML = "-"
        } else {
            resultSinotrukBe.innerHTML = avgSiTrBe + "%"
        }
    } else if (!brand[j].includes('SINOTRUK') && avgSiTrBe != undefined){
        resultSinotrukBe.style.backgroundColor = "#f9f9f9"
        resultSinotrukBe.innerHTML = avgSiTrBe + "%"
    } else {
        resultSinotrukBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('TOYOTA BT')) {
        for(var i = 0; i < toyotaBtBe.length; i++) {
            if(!isNaN(toyotaBtBe[i].innerText)) {
                toyotaBtBe[i].innerHTML = "0%"
            } else  if(parseInt(toyotaBtBe[i].innerText) != 0) {
                arrayToyotaBtBe.push(toyotaBtBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayToyotaBtBe.length; i++) {
            totalToyotaBtBe += parseInt(arrayToyotaBtBe[i]);
            var avgToBtBe = Math.ceil(totalToyotaBtBe / arrayToyotaBtBe.length);
        }
        if (avgToBtBe == undefined) {
            resultToyotaBtBe.innerHTML = "-"
        } else {
            resultToyotaBtBe.innerHTML = avgToBtBe + "%"
        }
    } else if (!brand[j].includes('TOYOTA BT') && avgToBtBe != undefined){
        resultToyotaBtBe.style.backgroundColor = "#f9f9f9"
        resultToyotaBtBe.innerHTML = avgToBtBe + "%"
    } else {
        resultToyotaBtBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('TOYOTA FORKLIFT')) {
        for(var i = 0; i < toyotaForfliftBe.length; i++) {
            if(!isNaN(toyotaForfliftBe[i].innerText)) {
                toyotaForfliftBe[i].innerHTML = "0%"
            } else if(parseInt(toyotaForfliftBe[i].innerText) != 0) {
                arrayToyotaForfliftBe.push(toyotaForfliftBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayToyotaForfliftBe.length; i++) {
            totalToyotaForfliftBe += parseInt(arrayToyotaForfliftBe[i]);
            var avgToFoBe = Math.ceil(totalToyotaForfliftBe / arrayToyotaForfliftBe.length);
        }
        if (avgToFoBe == undefined) {
            resultToyotaForfliftBe.innerHTML = "-"
        } else {
            resultToyotaForfliftBe.innerHTML = avgToFoBe + "%"
        }
    } else if (!brand[j].includes('TOYOTA FORKLIFT') && avgToFoBe != undefined){
        resultToyotaForfliftBe.style.backgroundColor = "#f9f9f9"
        resultToyotaForfliftBe.innerHTML = avgToFoBe + "%"
    } else {
        resultToyotaForfliftBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('JCB')) {
        for(var i = 0; i < jcbBe.length; i++) {
            if(!isNaN(jcbBe[i].innerText)) {
                jcbBe[i].innerHTML = "0%"
            } else if(parseInt(jcbBe[i].innerText) != 0) {
                arrayJcbBe.push(jcbBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayJcbBe.length; i++) {
            totalJcbBe += parseInt(arrayJcbBe[i]);
            var avgJce = Math.ceil(totalJcbBe / arrayJcbBe.length);
        }
        if (avgJce == undefined) {
            resultJcbBe.innerHTML = "-"
        } else {
            resultJcbBe.innerHTML = avgJce + "%"
        }
    } else if (!brand[j].includes('JCB') && avgJce != undefined){
        resultJcbBe.style.backgroundColor = "#f9f9f9"
        resultJcbBe.innerHTML = avgJce + "%"
    } else {
        resultJcbBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('LOVOL')) {
        for(var i = 0; i < lovolBe.length; i++) {
            if(!isNaN(lovolBe[i].innerText)) {
                lovolBe[i].innerHTML = "0%"
            } else if(parseInt(lovolBe[i].innerText) != 0) {
                arrayLovolBe.push(lovolBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayLovolBe.length; i++) {
            totalLovolBe += parseInt(arrayLovolBe[i]);
            var avgLoBe = Math.ceil(totalLovolBe / arrayLovolBe.length);
        }
        if (avgLoBe == undefined) {
            resultLovolBe.innerHTML = "-"
        } else {
            resultLovolBe.innerHTML = avgLoBe + "%"
        }
    } else if (!brand[j].includes('LOVOL') && avgLoBe != undefined){
        resultLovolBe.style.backgroundColor = "#f9f9f9"
        resultLovolBe.innerHTML = avgLoBe + "%"
    } else {
        resultLovolBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('CITROEN')) {
        for(var i = 0; i < citroenBe.length; i++) {
            if(!isNaN(citroenBe[i].innerText)) {
                citroenBe[i].innerHTML = "0%"
            } else if(parseInt(citroenBe[i].innerText) != 0) {
                arrayCitroenBe.push(citroenBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayCitroenBe.length; i++) {
            totalCitroenBe += parseInt(arrayCitroenBe[i]);
            var avgCiBe = Math.ceil(totalCitroenBe / arrayCitroenBe.length);
        }
        if (avgCiBe == undefined) {
            resultCitroenBe.innerHTML = "-"
        } else {
            resultCitroenBe.innerHTML = avgCiBe + "%"
        }
    } else if (!brand[j].includes('CITROEN') && avgCiBe != undefined){
        resultCitroenBe.style.backgroundColor = "#f9f9f9"
        resultCitroenBe.innerHTML = avgCiBe + "%"
    } else {
        resultCitroenBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('MERCEDES')) {
        for(var i = 0; i < mercedesBe.length; i++) {
            if(!isNaN(mercedesBe[i].innerText)) {
                mercedesBe[i].innerHTML = "0%"
            } else if(parseInt(mercedesBe[i].innerText) != 0) {
                arrayMercedesBe.push(mercedesBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayMercedesBe.length; i++) {
            totalMercedesBe += parseInt(arrayMercedesBe[i]);
            var avgMeBe = Math.ceil(totalMercedesBe / arrayMercedesBe.length);
        }
        if (avgMeBe == undefined) {
            resultMercedesBe.innerHTML = "-"
        } else {
            resultMercedesBe.innerHTML = avgMeBe + "%"
        }
    } else if (!brand[j].includes('MERCEDES') && avgMeBe != undefined){
        resultMercedesBe.style.backgroundColor = "#f9f9f9"
        resultMercedesBe.innerHTML = avgMeBe + "%"
    } else {
        resultMercedesBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('PEUGEOT')) {
        for(var i = 0; i < peugeotBe.length; i++) {
            if(!isNaN(peugeotBe[i].innerText)) {
                peugeotBe[i].innerHTML = "0%"
            } else if(parseInt(peugeotBe[i].innerText) != 0) {
                arrayPeugeotBe.push(peugeotBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayPeugeotBe.length; i++) {
            totalPeugeotBe += parseInt(arrayPeugeotBe[i]);
            var avgPeBe = Math.ceil(totalPeugeotBe / arrayPeugeotBe.length);
        }
        if (avgPeBe == undefined) {
            resultPeugeotBe.innerHTML = "-"
        } else {
            resultPeugeotBe.innerHTML = avgPeBe + "%"
        }
    } else if (!brand[j].includes('PEUGEOT') && avgPeBe != undefined){
        resultPeugeotBe.style.backgroundColor = "#f9f9f9"
        resultPeugeotBe.innerHTML = avgPeBe + "%"
    } else {
        resultPeugeotBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('SUZUKI')) {
        for(var i = 0; i < suzukiBe.length; i++) {
            if(!isNaN(suzukiBe[i].innerText)) {
                suzukiBe[i].innerHTML = "0%"
            } else if(parseInt(suzukiBe[i].innerText) != 0) {
                arraySuzukiBe.push(suzukiBe[i].innerText)
            }
        }
        for(var i = 0; i < arraySuzukiBe.length; i++) {
            totalSuzukiBe += parseInt(arraySuzukiBe[i]);
            var avgSuBe = Math.ceil(totalSuzukiBe / arraySuzukiBe.length);
        }
        if (avgSuBe == undefined) {
            resultSuzukiBe.innerHTML = "-"
        } else {
            resultSuzukiBe.innerHTML = avgSuBe + "%"
        }
    } else if (!brand[j].includes('SUZUKI') && avgSuBe != undefined){
        resultSuzukiBe.style.backgroundColor = "#f9f9f9"
        resultSuzukiBe.innerHTML = avgSuBe + "%"
    } else {
        resultSuzukiBe.style.backgroundColor = "#f9f9f9"
    }
    
    if (brand[j].includes('TOYOTA')) {
        for(var i = 0; i < toyotaBe.length; i++) {
            if(!isNaN(toyotaBe[i].innerText)) {
                toyotaBe[i].innerHTML = "0%"
            } else if(parseInt(toyotaBe[i].innerText) != 0) {
                arrayToyotaBe.push(toyotaBe[i].innerText)
            }
        }
        for(var i = 0; i < arrayToyotaBe.length; i++) {
            totalToyotaBe += parseInt(arrayToyotaBe[i]);
            var avgToyotaBe = Math.ceil(totalToyotaBe / arrayToyotaBe.length);
        }
        if (avgToyotaBe == undefined) {
            resultToyotaBe.innerHTML = "-"
        } else {
            resultToyotaBe.innerHTML = avgToyotaBe + "%";
        }
    } else if (!brand[j].includes('TOYOTA') && avgToyotaBe != undefined){
        resultToyotaBe.style.backgroundColor = "#f9f9f9"
        resultToyotaBe.innerHTML = avgToyotaBe + "%"
    } else {
        resultToyotaBe.style.backgroundColor = "#f9f9f9"
    }
    
    for(var i = 0; i < resultBe.length; i++) {
        if(parseInt(resultBe[i].innerText) != 0) {
            arrayBe.push(resultBe[i].innerText)
        }
    }
    for(var i = 0; i < arrayBe.length; i++) {
        totalBe += parseInt(arrayBe[i]);
        var avgBe = Math.ceil(totalBe / arrayBe.length);
    }
    if (avgBe == undefined) {
        resultTotalBe.innerHTML = "-"
    } else {
        resultTotalBe.innerHTML = avgBe + "%";
    }


    if (resultKingLongBe.innerText != "-" && resultKingLongBe.innerText != "") {
        kingLong.push(resultKingLongBe.innerText)
    }
    if (resultFusoBe.innerText != "-" && resultFusoBe.innerText != "") {
        fuso.push(resultFusoBe.innerText)
    }
    if (resultHinoBe.innerText != "-" && resultHinoBe.innerText != "") {
        hino.push(resultHinoBe.innerText)
    }
    if (resultMercedesTruckBe.innerText != "-" && resultMercedesTruckBe.innerText != "") {
        mercedesTruck.push(resultMercedesTruckBe.innerText)
    }
    if (resultRenaultTruckBe.innerText != "-" && resultRenaultTruckBe.innerText != "") {
        renaultTruck.push(resultRenaultTruckBe.innerText)
    }
    if (resultSinotrukBe.innerText != "-" && resultSinotrukBe.innerText != "") {
        sinotruk.push(resultSinotrukBe.innerText)
    }
    if (resultToyotaBtBe.innerText != "-" && resultToyotaBtBe.innerText != "") {
        toyotaBt.push(resultToyotaBtBe.innerText)
    }
    if (resultToyotaForfliftBe.innerText != "-" && resultToyotaForfliftBe.innerText != "") {
        toyotaForflift.push(resultToyotaForfliftBe.innerText)
    }
    if (resultJcbBe.innerText != "-" && resultJcbBe.innerText != "") {
        jcb.push(resultJcbBe.innerText)
    }
    if (resultLovolBe.innerText != "-" && resultLovolBe.innerText != "") {
        lovol.push(resultLovolBe.innerText)
    }
    if (resultCitroenBe.innerText != "-" && resultCitroenBe.innerText != "") {
        citroen.push(resultCitroenBe.innerText)
    }
    if (resultMercedesBe.innerText != "-" && resultMercedesBe.innerText != "") {
        mercedes.push(resultMercedesBe.innerText)
    }
    if (resultPeugeotBe.innerText != "-" && resultPeugeotBe.innerText != "") {
        peugeot.push(resultPeugeotBe.innerText)
    }
    if (resultSuzukiBe.innerText != "-" && resultSuzukiBe.innerText != "") {
        suzuki.push(resultSuzukiBe.innerText)
    }
    if (resultToyotaBe.innerText != "-" && resultToyotaBe.innerText != "") {
        toyota.push(resultToyotaBe.innerText)
    }
    if (resultTotalBe.innerText != "-") {
        result.push(resultTotalBe.innerText)
    }
});


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

let totalToyotaBt = 0;
for(var i = 0; i < toyotaBt.length; i++) {
    totalToyotaBt += parseInt(toyotaBt[i]);
    var avgToBt = Math.ceil(totalToyotaBt / toyotaBt.length);
}
if (avgToBt == undefined) {
    resultToyotaBt.innerHTML = "-"
} else {
    resultToyotaBt.innerHTML = avgToBt + "%";
}

let totalToyotaForflift = 0;
for(var i = 0; i < toyotaForflift.length; i++) {
    totalToyotaForflift += parseInt(toyotaForflift[i]);
    var avgToForflift = Math.ceil(totalToyotaForflift / toyotaForflift.length);
}
if (avgToForflift == undefined) {
    resultToyotaForflift.innerHTML = "-"
} else {
    resultToyotaForflift.innerHTML = avgToForflift + "%";
}

let totalJcb = 0;
for(var i = 0; i < jcb.length; i++) {
    totalJcb += parseInt(jcb[i]);
    var avgJc = Math.ceil(totalJcb / jcb.length);
}
if (avgJc == undefined) {
    resultJcb.innerHTML = "-"
} else {
    resultJcb.innerHTML = avgJc + "%";
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
</script>
<?php include_once "partials/footer.php"; ?>
<?php
} ?>