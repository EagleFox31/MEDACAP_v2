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

    $managerTechnician = $_GET["user"];
    $niveau = $_GET["level"];

    $technicians = [];
    if ($managerTechnician == "tous") {
        $techs = $users->find([
            '$and' => [
                [
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
    } else {
        $techs = $users->find([
            '$and' => [
                [
                    "manager" => new MongoDB\BSON\ObjectId($managerTechnician),
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
    }
    $managers = $users->find([
        '$and' => [
            [
                "profile" => "Manager",
                "active" => true,
            ],
        ],
    ])->toArray();
    
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
                    <?php echo $result_techs ?> <?php echo $level ?> <?php echo $niveau ?> <?php echo $by_brand ?></h1>
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
                <div class="d-flex justify-content-end"
                            data-kt-customer-table-toolbar="base">
                <!--begin::Filter-->
                <div class="w-200px me-3" style="margin-top: 10px; margin-bottom: 20px;" id="etat">
                <span class="fw-bolder"  style="margin-bottom: 10px;"> Selectionner le niveau</span>
                    <!--begin::Select2-->
                    <select id="niveau"
                        onchange="level()"
                        class="form-select form-select-solid"
                        data-placeholder="niveau">
                        <?php if  ($niveau == "Junior") { ?>
                            <option value="Junior" selected>Junior</option>
                        <?php } else { ?>
                            <option value="Junior">Junior</option>
                        <?php } ?>
                        <?php if  ($niveau == "Senior") { ?>
                            <option value="Senior" selected>Senior</option>
                        <?php } else { ?>
                            <option value="Senior">Senior</option>
                        <?php } ?>
                        <?php if  ($niveau == "Expert") { ?>
                            <option value="Expert" selected>Expert</option>
                        <?php } else { ?>
                            <option value="Expert">Expert</option>
                        <?php } ?>
                    </select>
                    <!--end::Select2-->
                </div>
                <!--end::Filter-->
                <!--begin::Filter-->
                <div class="w-250px me-3" style="margin-top: 10px; margin-bottom: 20px;" id="etat">
                <span class="fw-bolder"  style="margin-bottom: 10px;"> Selectionner le Manager</span>
                    <!--begin::Select2-->
                    <select id="select"
                        onchange="manager()"
                        class="form-select form-select-solid"
                        data-control="select2"
                        data-hide-search="true"
                        data-placeholder="Manager"
                        data-kt-ecommerce-order-filter="etat">
                        <option></option>
                        <option value="tous">Tous</option>
                        <?php foreach  ($managers as $managers) { ?>
                        <?php if  ($managers['_id'] == $managerTechnician) { ?>
                            <option value="<?php echo $managers['_id'] ?>" selected><?php echo $managers['firstName'].' '.$managers['lastName'] ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $managers['_id'] ?>"><?php echo $managers['firstName'].' '.$managers['lastName'] ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                    <!--end::Select2-->
                </div>
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
                                <button type="button" id="users"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Liste des techniciens
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="questions"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Liste des questions
                                </button>
                            </div> -->
                <!--end::Group actions-->
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
                                <button type="button" id="delete"
                                    data-bs-toggle="modal"
                                    class="btn btn-danger">
                                    Supprimer
                                </button>
                            </div> -->
                <!--end::Group actions-->
                </div>
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
                                    <!-- <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
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
                                    <!-- <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        Connaissances</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Technicien</th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Tâches Professionnelles du Manager</th> -->
                                    <!-- <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                        <?php echo $global ?></th>
                                    <th class=" sorting  text-center table-light fw-bolder text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $global ?></th> -->
                                </thead>
                                    <tbody class="fw-semibolder text-gray-600" id="table">
                                        <?php 
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
                                            if (isset($allocateFac) && isset($allocateDecla)) {
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
                                                        ["level" => $niveau],
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
                                                        ["level" => $niveau],
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
                                        <tr class="odd" etat="<?php echo $tech['manager'] ?>">
                                            <td class=" sorting text-black text-center table-light text-uppercase gs-0"
                                            tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px; ">
                                            <?php echo $tech->firstName ?> <?php echo $tech->lastName ?>
                                            </td>
                                            <td class="text-center" id="kingLong">
                                            <?php $brandNiveau = [];
                                            foreach ($tech['brand'.$niveau] as $brand) {
                                                array_push($brandNiveau, $brand);
                                            }
                                            if (
                                                in_array("KING LONG", $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $kingLongFac != 0 && $kingLongDecla != 0) { ?>
                                                <?php $percentageKingLong = ceil((($kingLongScoreFac * 100) / $kingLongFac + ($kingLongScore * 100) / $kingLongDecla) / 2); ?>
                                                <?php if($percentageKingLong <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageKingLong ?>
                                                </span>
                                                <?php } else if($percentageKingLong < 80 ) { ?>
                                                    <?php if($percentageKingLong > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageKingLong ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageKingLong >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageKingLong ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            <td class="text-center" id="fuso">
                                            <?php if (
                                                in_array('FUSO', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $fusoFac != 0 && $fusoDecla != 0) { ?>
                                                <?php $percentageFuso = ceil((($fusoScoreFac * 100) / $fusoFac + ($fusoScore * 100) / $fusoDecla) / 2); ?>
                                                <?php if($percentageFuso <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageFuso ?>
                                                </span>
                                                <?php } else if($percentageFuso < 80 ) { ?>
                                                    <?php if($percentageFuso > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageFuso ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageFuso >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageFuso ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="hino">
                                            <?php if (
                                                in_array('HINO', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $hinoFac != 0 && $hinoDecla != 0) { ?>
                                                <?php $percentageHino = ceil((($hinoScoreFac * 100) / $hinoFac + ($hinoScore * 100) / $hinoDecla) / 2); ?>
                                                <?php if($percentageHino <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageHino ?>
                                                </span>
                                                <?php } else if($percentageHino < 80 ) { ?>
                                                    <?php if($percentageHino > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageHino ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageHino >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageHino ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="mercedesTruck">
                                            <?php if (
                                                in_array('MERCEDES TRUCK', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $mercedesTruckFac != 0 && $mercedesTruckDecla != 0) { ?>
                                                <?php $percentageMercedesTruck = ceil((($mercedesTruckScoreFac * 100) / $mercedesTruckFac + ($mercedesTruckScore * 100) / $mercedesTruckDecla) / 2); ?>
                                                <?php if($percentageMercedesTruck <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageMercedesTruck ?>
                                                </span>
                                                <?php } else if($percentageMercedesTruck < 80 ) { ?>
                                                    <?php if($percentageMercedesTruck > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageMercedesTruck ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageMercedesTruck >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageMercedesTruck ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="renaultTruck">
                                            <?php if (
                                                in_array('RENAULT TRUCK', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $renaultTruckFac != 0 && $renaultTruckDecla != 0) { ?>
                                                <?php $percentageRenaultTruck = ceil((($renaultTruckScoreFac * 100) / $renaultTruckFac + ($renaultTruckScore * 100) / $renaultTruckDecla) / 2); ?>
                                                <?php if($percentageRenaultTruck <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageRenaultTruck ?>
                                                </span>
                                                <?php } else if($percentageRenaultTruck < 80 ) { ?>
                                                    <?php if($percentageRenaultTruck > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageRenaultTruck ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageRenaultTruck >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageRenaultTruck ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="sinotruk">
                                            <?php if (
                                                in_array('SINOTRUK', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $sinotrukFac != 0 && $sinotrukDecla != 0) { ?>
                                                <?php $percentageSinotruk = ceil((($sinotrukScoreFac * 100) / $sinotrukFac + ($sinotrukScore * 100) / $sinotrukDecla) / 2); ?>
                                                <?php if($percentageSinotruk <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageSinotruk ?>
                                                </span>
                                                <?php } else if($percentageSinotruk < 80 ) { ?>
                                                    <?php if($percentageSinotruk > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageSinotruk ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageSinotruk >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageSinotruk ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="toyotaBt">
                                            <?php if (
                                                in_array('TOYOTA BT', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $toyotaBtFac != 0 && $toyotaBtDecla != 0) { ?>
                                                <?php $percentageToyotaBt = ceil((($toyotaBtScoreFac * 100) / $toyotaBtFac + ($toyotaBtScore * 100) / $toyotaBtDecla) / 2); ?>
                                                <?php if($percentageToyotaBt <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageToyotaBt ?>
                                                </span>
                                                <?php } else if($percentageToyotaBt < 80 ) { ?>
                                                    <?php if($percentageToyotaBt > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageToyotaBt ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageToyotaBt >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageToyotaBt ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="toyotaForflift">
                                            <?php if (
                                                in_array('TOYOTA FORKLIFT', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $toyotaForfliftFac != 0 && $toyotaForfliftDecla != 0) { ?>
                                                <?php $percentageToyotaForflift = ceil((($toyotaForfliftScoreFac * 100) / $toyotaForfliftFac + ($toyotaForfliftScore * 100) / $toyotaForfliftDecla) / 2); ?>
                                                <?php if($percentageToyotaForflift <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageToyotaForflift ?>
                                                </span>
                                                <?php } else if($percentageToyotaForflift < 80 ) { ?>
                                                    <?php if($percentageToyotaForflift > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageToyotaForflift ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageToyotaForflift >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageToyotaForflift ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="jcb">
                                            <?php  if (
                                                in_array('JCB', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $jcbFac != 0 && $jcbDecla != 0) { ?>
                                                <?php $percentageJcb = ceil((($jcbScoreFac * 100) / $jcbFac + ($jcbScore * 100) / $jcbDecla) / 2); ?>
                                                <?php if($percentageJcb <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageJcb ?>
                                                </span>
                                                <?php } else if($percentageJcb < 80 ) { ?>
                                                    <?php if($percentageJcb > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageJcb ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageJcb >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageJcb ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="lovol">
                                            <?php if (
                                                in_array('LOVOL', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $lovolFac != 0 && $lovolDecla != 0) { ?>
                                                <?php $percentageLovol = ceil((($lovolScoreFac * 100) / $lovolFac + ($lovolScore * 100) / $lovolDecla) / 2); ?>
                                                <?php if($percentageLovol <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageLovol ?>
                                                </span>
                                                <?php } else if($percentageLovol < 80 ) { ?>
                                                    <?php if($percentageLovol > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageLovol ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageLovol >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageLovol ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="citroen">
                                            <?php if (
                                                in_array('CITROEN', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $citroenFac != 0 && $citroenDecla != 0) { ?>
                                                <?php $percentageCitroen = ceil((($citroenScoreFac * 100) / $citroenFac + ($citroenScore * 100) / $citroenDecla) / 2); ?>
                                                <?php if($percentageCitroen <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageCitroen ?>
                                                </span>
                                                <?php } else if($percentageCitroen < 80 ) { ?>
                                                    <?php if($percentageCitroen > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageCitroen ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageCitroen >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageCitroen ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="mercedes">
                                            <?php if (
                                                in_array('MERCEDES', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $mercedesFac != 0 && $mercedesDecla != 0) { ?>
                                                <?php $percentageMercedes = ceil((($mercedesScoreFac * 100) / $mercedesFac + ($mercedesScore * 100) / $mercedesDecla) / 2); ?>
                                                <?php if($percentageMercedes <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageMercedes ?>
                                                </span>
                                                <?php } else if($percentageMercedes < 80 ) { ?>
                                                    <?php if($percentageMercedes > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageMercedes ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageMercedes >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageMercedes ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="peugeot">
                                            <?php if (
                                                in_array('PEUGEOT', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $peugeotFac != 0 && $peugeotDecla != 0) { ?>
                                                <?php $percentagePeugeot = ceil((($peugeotScoreFac * 100) / $peugeotFac + ($peugeotScore * 100) / $peugeotDecla) / 2); ?>
                                                <?php if($percentagePeugeot <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentagePeugeot ?>
                                                </span>
                                                <?php } else if($percentagePeugeot < 80 ) { ?>
                                                    <?php if($percentagePeugeot > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentagePeugeot ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentagePeugeot >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentagePeugeot ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="suzuki">
                                            <?php if (
                                                in_array('SUZUKI', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $suzukiFac != 0 && $suzukiDecla != 0) { ?>
                                                <?php $percentageSuzuki = ceil((($suzukiScoreFac * 100) / $suzukiFac + ($suzukiScore * 100) / $suzukiDecla) / 2); ?>
                                                <?php if($percentageSuzuki <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageSuzuki ?>
                                                </span>
                                                <?php } else if($percentageSuzuki < 80 ) { ?>
                                                    <?php if($percentageSuzuki > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageSuzuki ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageSuzuki >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageSuzuki ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="toyota">
                                            <?php if (
                                                in_array('TOYOTA', $brandNiveau)
                                            ) { ?>
                                                <?php if ($allocateFac['active'] == true && $allocateDecla['active'] == true && $allocateDecla['activeManager'] == true && $toyotaFac != 0 && $toyotaDecla != 0) { ?>
                                                <?php $percentageToyota = ceil((($toyotaScoreFac * 100) / $toyotaFac + ($toyotaScore * 100) / $toyotaDecla) / 2); ?>
                                                <?php if($percentageToyota <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentageToyota ?>
                                                </span>
                                                <?php } else if($percentageToyota < 80 ) { ?>
                                                    <?php if($percentageToyota > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentageToyota ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentageToyota >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentageToyota ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            <td class="text-center" id="result">
                                                <?php if (isset($resultFac) && isset($resultTechMa)) { ?>
                                                <?php $percentage = ceil((($resultFac->score * 100) / $resultFac->total + ($resultTechMa->score * 100) / $resultTechMa->total ) / 2); ?>
                                                <?php if($percentage <= 60 ) { ?>
                                                <span class="badge text-danger fs-7 m-1">
                                                    <?php echo $percentage ?>
                                                </span>
                                                <?php } else if($percentage < 80 ) { ?>
                                                    <?php if($percentage > 60 ) { ?>
                                                    <span class="badge text-warning fs-7 m-1">
                                                        <?php echo $percentage ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } else if($percentage >= 80) { ?>
                                                <span class="badge text-success fs-7 m-1">
                                                    <?php echo $percentage ?>
                                                </span>
                                                <?php } ?>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                        <!--end::Menu-->
                                        <tr class="odd" id="resultEtat">
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" aria-controls="kt_customers_table"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                <?php echo $result ?>
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultKingLong"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultFuso"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultHino"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultMercedesTruck"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultRenaultTruck"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultSinotruk"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultToyotaBt"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultToyotaForklift"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultJcb"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultLovol"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultCitroen"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultMercedes"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultPeugeot"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultSuzuki"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
                                                tabindex="0" colspan="1" aria-controls="kt_customers_table" id="resultToyota"
                                                aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                                
                                            </th>
                                            <th class=" sorting text-center table-light fw-bolder text-uppercase gs-0"
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

function level () {
    var level = document.querySelector("#niveau")
    if (level.value == "Junior") {
        window.location.search = "?user=tous&level=Junior";
    } else if (level.value == "Senior") {
        window.location.search = "?user=tous&level=Senior";
    }  else {
        window.location.search = "?user=tous&level=Expert";
    }
}
 
function manager() {
    var level = document.querySelector("#niveau")
    var manager = document.querySelector("#select")
    if (level.value == "Junior") {
        window.location.search = `?user=${manager.value}&level=Junior`;
    } else if (level.value == "Senior") {
        window.location.search = `?user=${manager.value}&level=Senior`;
    }  else {
        window.location.search = `?user=${manager.value}&level=Expert`;
    }
}


var kingLong = document.querySelectorAll("#kingLong")
var fuso = document.querySelectorAll("#fuso")
var hino = document.querySelectorAll("#hino")
var jcb = document.querySelectorAll("#jcb")
var lovol = document.querySelectorAll("#lovol")
var mercedesTruck = document.querySelectorAll("#mercedesTruck")
var renaultTruck = document.querySelectorAll("#renaultTruck")
var sinotruk = document.querySelectorAll("#sinotruk")
var toyotaBt = document.querySelectorAll("#toyotaBt")
var toyotaForflift = document.querySelectorAll("#toyotaForflift")
var citroen = document.querySelectorAll("#citroen")
var mercedes = document.querySelectorAll("#mercedes")
var peugeot = document.querySelectorAll("#peugeot")
var suzuki = document.querySelectorAll("#suzuki")
var toyota = document.querySelectorAll("#toyota")
var result = document.querySelectorAll("#result")

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

var totalKingLong = 0;
var totalFuso = 0;
var totalHino = 0;
var totalMercedesTruck = 0;
var totalRenaultTruck = 0;
var totalSinotruk = 0;
var totalToyotaBt = 0;
var totalToyotaForflift = 0;
var totalJcb = 0;
var totalLovol = 0;
var totalCitroen = 0;
var totalMercedes = 0;
var totalPeugeot= 0;
var totalSuzuki = 0;
var totalToyota = 0;
var total = 0;

let arrayKingLong = [];
let arrayFuso = [];
let arrayHino = [];
let arrayMercedesTruck = [];
let arrayRenaultTruck = [];
let arraySinotruk = [];
let arrayToyotaBt = [];
let arrayToyotaForflift = [];
let arrayJcb = [];
let arrayLovol = [];
let arrayCitroen = [];
let arrayMercedes = [];
let arrayPeugeot = [];
let arraySuzuki = [];
let arrayToyota = [];
let array = [];

for(var i = 0; i < kingLong.length; i++) {
    if(kingLong[i].innerText != "" && kingLong[i].innerText != "-") {
        arrayKingLong.push(kingLong[i].innerText)
    } else if(kingLong[i].innerText == "") {
        kingLong[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayKingLong.length; i++) {
    totalKingLong += parseInt(arrayKingLong[i]);
    var avgKing = Math.ceil(totalKingLong / arrayKingLong.length);
}
if (avgKing == undefined) {
    resultKingLong.innerHTML = "-"
} else {
    if (avgKing <= 60) {
        resultKingLong.innerHTML = avgKing + "%"
        resultKingLong.style.color = "#d9534f"
    }
    if (avgKing > 60) {
        if (avgKing < 80) {
            resultKingLong.innerHTML = avgKing + "%"
            resultKingLong.style.color = "#f0ad4e"
        }
    } 
    if (avgKing >= 80) {
        resultKingLong.innerHTML = avgKing + "%"
        resultKingLong.style.color = "#5cb85c "
    }
}

for(var i = 0; i < fuso.length; i++) {
    if(fuso[i].innerText != "" && fuso[i].innerText != "-") {
        arrayFuso.push(fuso[i].innerText)
    } else if(fuso[i].innerText == "") {
        fuso[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayFuso.length; i++) {
    totalFuso += parseInt(arrayFuso[i]);
    var avgFu = Math.ceil(totalFuso / arrayFuso.length);
}
if (avgFu == undefined) {
    resultFuso.innerHTML = "-"
} else {
    if (avgFu <= 60) {
        resultFuso.innerHTML = avgFu + "%"
        resultFuso.style.color = "#d9534f"
    }
    if (avgFu > 60) {
        if (avgFu < 80) {
            resultFuso.innerHTML = avgFu + "%"
            resultFuso.style.color = "#f0ad4e"
        }
    } 
    if (avgFu >= 80) {
        resultFuso.innerHTML = avgFu + "%"
        resultFuso.style.color = "#5cb85c "
    }
}

for(var i = 0; i < hino.length; i++) {
    if(hino[i].innerText != "" && hino[i].innerText != "-") {
        arrayHino.push(hino[i].innerText)
    } else if(hino[i].innerText == "") {
        hino[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayHino.length; i++) {
    totalHino += parseInt(arrayHino[i]);
    var avgHi = Math.ceil(totalHino / arrayHino.length);
}
if (avgHi == undefined) {
    resultHino.innerHTML = "-"
} else {
    if (avgHi <= 60) {
        resultHino.innerHTML = avgHi + "%"
        resultHino.style.color = "#d9534f"
    }
    if (avgHi > 60) {
        if (avgHi < 80) {
            resultHino.innerHTML = avgHi + "%"
            resultHino.style.color = "#f0ad4e"
        }
    } 
    if (avgHi >= 80) {
        resultHino.innerHTML = avgHi + "%"
        resultHino.style.color = "#5cb85c "
    }
}

for(var i = 0; i < mercedesTruck.length; i++) {
    if(mercedesTruck[i].innerText != "" && mercedesTruck[i].innerText != "-") {
        arrayMercedesTruck.push(mercedesTruck[i].innerText)
    } else if(mercedesTruck[i].innerText == "") {
        mercedesTruck[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayMercedesTruck.length; i++) {
    totalMercedesTruck += parseInt(arrayMercedesTruck[i]);
    var avgMeTr = Math.ceil(totalMercedesTruck / arrayMercedesTruck.length);
}
if (avgMeTr == undefined) {
    resultMercedesTruck.innerHTML = "-"
} else {
    if (avgMeTr <= 60) {
        resultMercedesTruck.innerHTML = avgMeTr + "%"
        resultMercedesTruck.style.color = "#d9534f"
    }
    if (avgMeTr > 60) {
        if (avgMeTr < 80) {
            resultMercedesTruck.innerHTML = avgMeTr + "%"
            resultMercedesTruck.style.color = "#f0ad4e"
        }
    } 
    if (avgMeTr >= 80) {
        resultMercedesTruck.innerHTML = avgMeTr + "%"
        resultMercedesTruck.style.color = "#5cb85c "
    }
}

for(var i = 0; i < renaultTruck.length; i++) {
    if(renaultTruck[i].innerText != "" && renaultTruck[i].innerText != "-") {
        arrayRenaultTruck.push(renaultTruck[i].innerText)
    } else if(renaultTruck[i].innerText == "") {
        renaultTruck[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayRenaultTruck.length; i++) {
    totalRenaultTruck += parseInt(arrayRenaultTruck[i]);
    var avgReTr = Math.ceil(totalRenaultTruck / arrayRenaultTruck.length);
}
if (avgReTr == undefined) {
    resultRenaultTruck.innerHTML = "-"
} else {
    if (avgReTr <= 60) {
        resultRenaultTruck.innerHTML = avgReTr + "%"
        resultRenaultTruck.style.color = "#d9534f"
    }
    if (avgReTr > 60) {
        if (avgReTr < 80) {
            resultRenaultTruck.innerHTML = avgReTr + "%"
            resultRenaultTruck.style.color = "#f0ad4e"
        }
    } 
    if (avgReTr >= 80) {
        resultRenaultTruck.innerHTML = avgReTr + "%"
        resultRenaultTruck.style.color = "#5cb85c "
    }
}

for(var i = 0; i < sinotruk.length; i++) {
    if(sinotruk[i].innerText != "" && sinotruk[i].innerText != "-") {
        arraySinotruk.push(sinotruk[i].innerText)
    } else if(sinotruk[i].innerText == "") {
        sinotruk[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arraySinotruk.length; i++) {
    totalSinotruk += parseInt(arraySinotruk[i]);
    var avgSiTr = Math.ceil(totalSinotruk / arraySinotruk.length);
}
if (avgSiTr == undefined) {
    resultSinotruk.innerHTML = "-"
} else {
    if (avgSiTr <= 60) {
        resultSinotruk.innerHTML = avgSiTr + "%"
        resultSinotruk.style.color = "#d9534f"
    }
    if (avgSiTr > 60) {
        if (avgSiTr < 80) {
            resultSinotruk.innerHTML = avgSiTr + "%"
            resultSinotruk.style.color = "#f0ad4e"
        }
    } 
    if (avgSiTr >= 80) {
        resultSinotruk.innerHTML = avgSiTr + "%"
        resultSinotruk.style.color = "#5cb85c "
    }
}

for(var i = 0; i < toyotaBt.length; i++) {
    if(toyotaBt[i].innerText != "" && toyotaBt[i].innerText != "-") {
        arrayToyotaBt.push(toyotaBt[i].innerText)
    } else if(toyotaBt[i].innerText == "") {
        toyotaBt[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayToyotaBt.length; i++) {
    totalToyotaBt += parseInt(arrayToyotaBt[i]);
    var avgToBt = Math.ceil(totalToyotaBt / arrayToyotaBt.length);
}
if (avgToBt == undefined) {
    resultToyotaBt.innerHTML = "-"
} else {
    if (avgToBt <= 60) {
        resultToyotaBt.innerHTML = avgToBt + "%"
        resultToyotaBt.style.color = "#d9534f"
    }
    if (avgToBt > 60) {
        if (avgToBt < 80) {
            resultToyotaBt.innerHTML = avgToBt + "%"
            resultToyotaBt.style.color = "#f0ad4e"
        }
    } 
    if (avgToBt >= 80) {
        resultToyotaBt.innerHTML = avgToBt + "%"
        resultToyotaBt.style.color = "#5cb85c "
    }
}

for(var i = 0; i < toyotaForflift.length; i++) {
    if(toyotaForflift[i].innerText != "" && toyotaForflift[i].innerText != "-") {
        arrayToyotaForflift.push(toyotaForflift[i].innerText)
    } else if(toyotaForflift[i].innerText == "") {
        toyotaForflift[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayToyotaForflift.length; i++) {
    totalToyotaForflift += parseInt(arrayToyotaForflift[i]);
    var avgToFo = Math.ceil(totalToyotaForflift / arrayToyotaForflift.length);
}
if (avgToFo == undefined) {
    resultToyotaForflift.innerHTML = "-"
} else {
    if (avgToFo <= 60) {
        resultToyotaForflift.innerHTML = avgToFo + "%"
        resultToyotaForflift.style.color = "#d9534f"
    }
    if (avgToFo > 60) {
        if (avgToFo < 80) {
            resultToyotaForflift.innerHTML = avgToFo + "%"
            resultToyotaForflift.style.color = "#f0ad4e"
        }
    } 
    if (avgToFo >= 80) {
        resultToyotaForflift.innerHTML = avgToFo + "%"
        resultToyotaForflift.style.color = "#5cb85c "
    }
}

for(var i = 0; i < jcb.length; i++) {
    if(jcb[i].innerText != "" && jcb[i].innerText != "-") {
        arrayJcb.push(jcb[i].innerText)
    } else if(jcb[i].innerText == "") {
        jcb[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayJcb.length; i++) {
    totalJcb += parseInt(arrayJcb[i]);
    var avgJc = Math.ceil(totalJcb / arrayJcb.length);
}
if (avgJc == undefined) {
    resultJcb.innerHTML = "-"
} else {
    if (avgJc <= 60) {
        resultJcb.innerHTML = avgJc + "%"
        resultJcb.style.color = "#d9534f"
    }
    if (avgJc > 60) {
        if (avgJc < 80) {
            resultJcb.innerHTML = avgJc + "%"
            resultJcb.style.color = "#f0ad4e"
        }
    } 
    if (avgJc >= 80) {
        resultJcb.innerHTML = avgJc + "%"
        resultJcb.style.color = "#5cb85c "
    }
}

for(var i = 0; i < lovol.length; i++) {
    if(lovol[i].innerText != "" && lovol[i].innerText != "-") {
        arrayLovol.push(lovol[i].innerText)
    } else if(lovol[i].innerText == "") {
        lovol[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayLovol.length; i++) {
    totalLovol += parseInt(arrayLovol[i]);
    var avgLo = Math.ceil(totalLovol / arrayLovol.length);
}
if (avgLo == undefined) {
    resultLovol.innerHTML = "-"
} else {
    if (avgLo <= 60) {
        resultLovol.innerHTML = avgLo + "%"
        resultLovol.style.color = "#d9534f"
    }
    if (avgLo > 60) {
        if (avgLo < 80) {
            resultLovol.innerHTML = avgLo + "%"
            resultLovol.style.color = "#f0ad4e"
        }
    } 
    if (avgLo >= 80) {
        resultLovol.innerHTML = avgLo + "%"
        resultLovol.style.color = "#5cb85c "
    }
}

for(var i = 0; i < citroen.length; i++) {
    if(citroen[i].innerText != "" && citroen[i].innerText != "-") {
        arrayCitroen.push(citroen[i].innerText)
    } else if(citroen[i].innerText == "") {
        citroen[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayCitroen.length; i++) {
    totalCitroen += parseInt(arrayCitroen[i]);
    var avgCi = Math.ceil(totalCitroen / arrayCitroen.length);
}
if (avgCi == undefined) {
    resultCitroen.innerHTML = "-"
} else {
    if (avgCi <= 60) {
        resultCitroen.innerHTML = avgCi + "%"
        resultCitroen.style.color = "#d9534f"
    }
    if (avgCi > 60) {
        if (avgCi < 80) {
            resultCitroen.innerHTML = avgCi + "%"
            resultCitroen.style.color = "#f0ad4e"
        }
    } 
    if (avgCi >= 80) {
        resultCitroen.innerHTML = avgCi + "%"
        resultCitroen.style.color = "#5cb85c "
    }
}

for(var i = 0; i < mercedes.length; i++) {
    if(mercedes[i].innerText != "" && mercedes[i].innerText != "-") {
        arrayMercedes.push(mercedes[i].innerText)
    } else if(mercedes[i].innerText == "") {
        mercedes[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayMercedes.length; i++) {
    totalMercedes += parseInt(arrayMercedes[i]);
    var avgMe = Math.ceil(totalMercedes / arrayMercedes.length);
}
if (avgMe == undefined) {
    resultMercedes.innerHTML = "-"
} else {
    if (avgMe <= 60) {
        resultMercedes.innerHTML = avgMe + "%"
        resultMercedes.style.color = "#d9534f"
    }
    if (avgMe > 60) {
        if (avgMe < 80) {
            resultMercedes.innerHTML = avgMe + "%"
            resultMercedes.style.color = "#f0ad4e"
        }
    } 
    if (avgMe >= 80) {
        resultMercedes.innerHTML = avgMe + "%"
        resultMercedes.style.color = "#5cb85c "
    }
}

for(var i = 0; i < peugeot.length; i++) {
    if(peugeot[i].innerText != "" && peugeot[i].innerText != "-") {
        arrayPeugeot.push(peugeot[i].innerText)
    } else if(peugeot[i].innerText == "") {
        peugeot[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayPeugeot.length; i++) {
    totalPeugeot += parseInt(arrayPeugeot[i]);
    var avgPe = Math.ceil(totalPeugeot / arrayPeugeot.length);
}
if (avgPe == undefined) {
    resultPeugeot.innerHTML = "-"
} else {
    if (avgPe <= 60) {
        resultPeugeot.innerHTML = avgPe + "%"
        resultPeugeot.style.color = "#d9534f"
    }
    if (avgPe > 60) {
        if (avgPe < 80) {
            resultPeugeot.innerHTML = avgPe + "%"
            resultPeugeot.style.color = "#f0ad4e"
        }
    } 
    if (avgPe >= 80) {
        resultPeugeot.innerHTML = avgPe + "%"
        resultPeugeot.style.color = "#5cb85c "
    }
}

for(var i = 0; i < suzuki.length; i++) {
    if(suzuki[i].innerText != "" && suzuki[i].innerText != "-") {
        arraySuzuki.push(suzuki[i].innerText)
    } else if(suzuki[i].innerText == "") {
        suzuki[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arraySuzuki.length; i++) {
    totalSuzuki += parseInt(arraySuzuki[i]);
    var avgSu = Math.ceil(totalSuzuki / arraySuzuki.length);
}
if (avgSu == undefined) {
    resultSuzuki.innerHTML = "-"
} else {
    if (avgSu <= 60) {
        resultSuzuki.innerHTML = avgSu + "%"
        resultSuzuki.style.color = "#d9534f"
    }
    if (avgSu > 60) {
        if (avgSu < 80) {
            resultSuzuki.innerHTML = avgSu + "%"
            resultSuzuki.style.color = "#f0ad4e"
        }
    } 
    if (avgSu >= 80) {
        resultSuzuki.innerHTML = avgSu + "%"
        resultSuzuki.style.color = "#5cb85c "
    }
}

for(var i = 0; i < toyota.length; i++) {
    if(toyota[i].innerText != "" && toyota[i].innerText != "-") {
        arrayToyota.push(toyota[i].innerText)
    } else if(toyota[i].innerText == "") {
        toyota[i].style.backgroundColor = "#f9f9f9"
    }
}
for(var i = 0; i < arrayToyota.length; i++) {
    totalToyota += parseInt(arrayToyota[i]);
    var avgToyota = Math.ceil(totalToyota / arrayToyota.length);
}
if (avgToyota == undefined) {
    resultToyota.innerHTML = "-"
} else {
    if (avgToyota <= 60) {
        resultToyota.innerHTML = avgToyota + "%"
        resultToyota.style.color = "#d9534f"
    }
    if (avgToyota > 60) {
        if (avgToyota < 80) {
            resultToyota.innerHTML = avgToyota + "%"
            resultToyota.style.color = "#f0ad4e"
        }
    } 
    if (avgToyota >= 80) {
        resultToyota.innerHTML = avgToyota + "%"
        resultToyota.style.color = "#5cb85c "
    }
}

for(var i = 0; i < result.length; i++) {
    if(result[i].innerText != "" && result[i].innerText != "-") {
        array.push(result[i].innerText)
    }
}

for(var i = 0; i < array.length; i++) {
    total += parseInt(array[i]);
    var avg = Math.ceil(total / array.length);
}
if (avg == undefined) {
    resultTotal.innerHTML = "-"
} else {
    if (avg <= 60) {
        resultTotal.innerHTML = avg + "%"
        resultTotal.style.color = "#d9534f"
    }
    if (avg > 60) {
        if (avg < 80) {
            resultTotal.innerHTML = avg + "%"
            resultTotal.style.color = "#f0ad4e"
        }
    } 
    if (avg >= 80) {
        resultTotal.innerHTML = avg + "%"
        resultTotal.style.color = "#5cb85c "
    }
}

// console.log(array)
</script>
<?php include_once "partials/footer.php"; ?>
<?php
} ?>
