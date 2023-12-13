<?php
session_start();

if (!isset($_SESSION["profile"])) {
    header("Location: ./index.php");
    exit();
} else {

    require_once '../vendor/autoload.php';

    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;
    $allocations = $academy->allocations;

    $countUser = $users->find(['profile' => 'Technicien'])->toArray();
    $countUsers = count($countUser);
    $countManager = $users->find(['profile' => 'Manager'])->toArray();
    $countManagers = count($countManager);
    $countAdmin = $users->find(['profile' => 'Admin'])->toArray();
    $countAdmins = count($countAdmin);
    $countVehicle = $vehicles->find()->toArray();
    $countVehicles = count($countVehicle);
?>
<?php
include('./partials/header.php')
?>
<!--begin::Title-->
<title>Tableau de Bord | CFAO Mobility Academy</title>
<!--end::Title-->
<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Tableau de bord
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <?php if ($_SESSION["profile"] == "Manager") {?>
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <div class="card-body d-flex align-items-center p-5 p-lg-8">
                    <!--begin::Icon-->
                    <div
                        class="d-flex h-50px w-50px h-lg-80px w-lg-80px flex-shrink-0 flex-center position-relative align-self-start align-self-lg-center mt-3 mt-lg-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="text-primary h-75px w-75px h-lg-100px w-lg-100px position-absolute opacity-5">
                            <path fill="currentColor"
                                d="M10.2,21.23,4.91,18.17a3.58,3.58,0,0,1-1.8-3.11V8.94a3.58,3.58,0,0,1,1.8-3.11L10.2,2.77a3.62,3.62,0,0,1,3.6,0l5.29,3.06a3.58,3.58,0,0,1,1.8,3.11v6.12a3.58,3.58,0,0,1-1.8,3.11L13.8,21.23A3.62,3.62,0,0,1,10.2,21.23Z" />
                        </svg>
                        <i class="ki-duotone ki-user fs-2x fs-lg-3x text-primary position-absolute"><span
                                class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Description-->
                    <div class="ms-6">
                        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                            Bienvenue sur votre espace de developpement des
                            compétences de CFAO Mobility Academy Panafrican. <br><br>
                            CFAO souhaite créer et adapter un parcours de
                            développement individuel des compétences pour chacun des techniciens,
                            afin de leurs proposer des formations correspondant à vos besoins
                            et ceux de l'entreprise.<br><br>
                            Pour élaborer ce parcours, nous avons besoin d'identifier les
                            compétences actuelles des techniciens sur les trois niveaux (Junior, Senior et Expert) et
                            nous leurs
                            proposons de repondre
                            aux questionnaires suivants: <br>
                            - Questionnaires sur vos connaissances techniques, <br>
                            - Questionnaires sur la maîtrise de vos tâches professionnelles. <br> <br>
                            Pour s'assurer de la certitude des reponses des techniciens concernant
                            la maitrise des tâches professionnelles, nous vous demandons d'évaluer vos techniciens
                            sur la maitrises des tâches professionnelles.
                        </p>
                    </div>
                    <!--end::Description-->
                </div>
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <div class="card-body d-flex align-items-center p-5 p-lg-8">
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5 pb-3">
                            <!--begin::Heading-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800 fs-2">Mes
                                    techniciens à évaluer</span>
                            </h3>
                            <!--end::Heading-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-bordered table-row-dashed gy-5"
                                id="kt_table_widget_1">
                                <tbody>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                                        <th class="w-20px ps-0">
                                        </th>
                                        <th class="min-w-125px px-0">
                                            Techniciens</th>
                                        <th class="min-w-150px px-0 text-center">
                                            Tests</th>
                                        <th class="min-w-125px">Vehicules</th>
                                        <th class="min-w-125px">Département</th>
                                        <th class="min-w-125px">Niveau Junior</th>
                                        <th class="min-w-125px">Niveau Senior</th>
                                        <th class="min-w-125px">Niveau Expert</th>
                                    </tr>
                                    <?php
                                        $manager = $users->findOne([
                                            '$and' => [
                                                [
                                                    '_id' => new MongoDB\BSON\ObjectId($_SESSION["id"]),
                                                    'active' => true,
                                                ],
                                            ]
                                        ]);
                                        foreach ($manager->users as $userr) {
                                            $allocate = $allocations->findOne([
                                                'user' => $userr,
                                                'type' => 'Declaratif',
                                            ]);
                                            $user = $users->findOne([
                                                '$and' => [
                                                    [
                                                        '_id' => new MongoDB\BSON\ObjectId($allocate["user"]),
                                                        'active' => true,
                                                    ],
                                                ]
                                            ]);
                                            $vehicle = $vehicles->findOne([
                                                '$and' => [
                                                    [
                                                        '_id' => new MongoDB\BSON\ObjectId($allocate["vehicle"]),
                                                        'active' => true,
                                                    ],
                                                ]
                                            ]);
                                            
                                            $verified = $allocations->findOne([
                                                'user' => new MongoDB\BSON\ObjectId($user->_id),
                                                'vehicle' => new MongoDB\BSON\ObjectId($vehicle->_id),
                                            ]);

                                            if ($verified->activeManager == false) {
                                    ?>
                                    <tr>
                                        <td class="p-0">
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $user->firstName ?> <?php echo $user->lastName ?>
                                            </span>
                                        </td>
                                        <td class="pe-0">
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                Questionnaire sur la maitrise tâches professionnelles des techniciens
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $vehicle->label ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $vehicle->brand ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $user->department ?>
                                            </span>
                                        </td>
                                        <?php if ($allocate->level == "Junior") { ?>
                                        <td>
                                            <a href="./userEvaluation.php?brand=<?php echo $vehicle->brand ?>&vehicle=<?php echo $vehicle->label ?>&level=<?php echo $allocate->level ?>&user=<?php echo $user->_id ?>&id=<?php echo $manager->_id ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
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
                                        <?php } ?>
                                        <?php if ($allocate->level == "Senior") { ?>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <td>
                                            <a href="./userEvaluation.php?brand=<?php echo $vehicle->brand ?>&vehicle=<?php echo $vehicle->label ?>&level=<?php echo $allocate->level ?>&user=<?php echo $user->_id ?>&id=<?php echo $manager->_id ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <?php if ($allocate->level == "Expert") { ?>
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
                                        <td>
                                            <a href="./userEvaluation.php?brand=<?php echo $vehicle->brand ?>&vehicle=<?php echo $vehicle->label ?>&level=<?php echo $allocate->level ?>&user=<?php echo $user->_id ?>&id=<?php echo $manager->_id ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <?php } ?>
    <?php if ($_SESSION["profile"] == "Technicien") {?>
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <div class="card-body d-flex align-items-center p-5 p-lg-8">
                    <!--begin::Icon-->
                    <div
                        class="d-flex h-50px w-50px h-lg-80px w-lg-80px flex-shrink-0 flex-center position-relative align-self-start align-self-lg-center mt-3 mt-lg-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="text-primary h-75px w-75px h-lg-100px w-lg-100px position-absolute opacity-5">
                            <path fill="currentColor"
                                d="M10.2,21.23,4.91,18.17a3.58,3.58,0,0,1-1.8-3.11V8.94a3.58,3.58,0,0,1,1.8-3.11L10.2,2.77a3.62,3.62,0,0,1,3.6,0l5.29,3.06a3.58,3.58,0,0,1,1.8,3.11v6.12a3.58,3.58,0,0,1-1.8,3.11L13.8,21.23A3.62,3.62,0,0,1,10.2,21.23Z" />
                        </svg>
                        <i class="ki-duotone ki-user fs-2x fs-lg-3x text-primary position-absolute"><span
                                class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Description-->
                    <div class="ms-6">
                        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                            Bienvenue sur votre espace de developpement des
                            compétences de CFAO Mobility Academy Panafrican. <br><br>
                            CFAO souhaite créer et adapter un parcours de
                            développement individuel des compétences pour chacun des techniciens,
                            afin de vous proposer des formations correspondant à vos besoins
                            et ceux de l'entreprise.<br><br>
                            Pour élaborer ce parcours, nous avons besoin d'identifier vos
                            compétences actuelles sur les trois niveaux (Junior, Senior et Expert) et nous vous
                            proposons de repondre
                            aux questionnaires suivants: <br>
                            - Questionnaires sur vos connaissances techniques, <br>
                            - Questionnaires sur la maîtrise de vos tâches professionnelles. <br> <br>
                            Merci de repondre intégralement à tous les questionnaires ci-dessous.
                        </p>
                    </div>
                    <!--end::Description-->
                </div>
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <div class="card-body d-flex align-items-center p-5 p-lg-8">
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5 pb-3">
                            <!--begin::Heading-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800 fs-2">Mes
                                    Questionnaires sur vos connaissances techniques</span>
                            </h3>
                            <!--end::Heading-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-bordered table-row-dashed gy-5"
                                id="kt_table_widget_1">
                                <tbody>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                                        <th class="w-20px ps-0">
                                        </th>
                                        <th class="min-w-200px px-0">
                                            Tests</th>
                                        <th class="min-w-125px">Véhicules</th>
                                        <th class="min-w-125px">Marques</th>
                                        <th class="min-w-125px">Niveau Junior</th>
                                        <th class="min-w-125px">Niveau Senior</th>
                                        <th class="min-w-125px">Niveau Expert</th>
                                    </tr>
                                    <?php
                                        $testFac = $allocations->find([
                                            '$and' => [
                                                ["user" => new MongoDB\BSON\ObjectId($_SESSION["id"])],
                                                ['active' => false],
                                                ['type' => 'Factuel'],
                                            ]
                                        ]);
                                        foreach ($testFac as $test) {
                                            $vehicle = $vehicles->findOne([
                                                '$and' => [
                                                    ["_id" => new MongoDB\BSON\ObjectId($test["vehicle"])],
                                                    ['active' => true],
                                                ]
                                            ]);
                                    ?>
                                    <?php if ($test) { ?>
                                    <tr>
                                        <td class="p-0">
                                        </td>
                                        <td class="pe-0">
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                Questionnaire sur vos connaissances techniques
                                            </span>
                                        </td>
                                        <td class="pe-0">
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $vehicle["label"] ?>
                                            </span>
                                        </td>
                                        <td class="pe-0">
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $vehicle["brand"] ?>
                                            </span>
                                        </td>
                                        <?php if ($test->level == "Junior") { ?>
                                        <td>
                                            <a href="./userQuizFactuel.php?brand=<?php echo $vehicle["brand"] ?>&vehicle=<?php echo $vehicle["label"] ?>&level=Junior&id=<?php echo $_SESSION["id"] ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
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
                                        <?php } ?>
                                        <?php if ($test->level == "Senior") { ?>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <td>
                                            <a href="./userQuizFactuel.php?brand=<?php echo $vehicle["brand"] ?>&vehicle=<?php echo $vehicle["label"] ?>&level=Senior&id=<?php echo $_SESSION["id"] ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <?php if ($test->level == "Expert") { ?>
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
                                        <td>
                                            <a href="./userQuizFactuel.php?brand=<?php echo $vehicle["brand"] ?>&vehicle=<?php echo $vehicle["label"] ?>&level=Expert&id=<?php echo $_SESSION["id"] ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                       }
                                       }
                                   ?>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <div class="card-body d-flex align-items-center p-5 p-lg-8">
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5 pb-3">
                            <!--begin::Heading-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800 fs-2">Mes
                                    Questionnaires sur la maîtrise de vos tâches professionnelles</span>
                            </h3>
                            <!--end::Heading-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-bordered table-row-dashed gy-5"
                                id="kt_table_widget_1">
                                <tbody>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                                        <th class="w-20px ps-0">
                                        </th>
                                        <th class="min-w-200px px-0">
                                            Tests</th>
                                        <th class="min-w-125px">Véhicules</th>
                                        <th class="min-w-125px">Marques</th>
                                        <th class="min-w-125px">Niveau Junior</th>
                                        <th class="min-w-125px">Niveau Senior</th>
                                        <th class="min-w-125px">Niveau Expert</th>
                                    </tr>
                                    <?php
                                        $testDecla = $allocations->find([
                                            '$and' => [
                                                ["user" => new MongoDB\BSON\ObjectId($_SESSION["id"])],
                                                ['active' => false],
                                                ['type' => 'Declaratif'],
                                            ]
                                        ]);
                                        foreach ($testDecla as $test) {
                                            $vehicle = $vehicles->findOne([
                                                '$and' => [
                                                    ["_id" => new MongoDB\BSON\ObjectId($test["vehicle"])],
                                                    ['active' => true],
                                                ]
                                            ])
                                    ?>
                                    <?php if ($test ) { ?>
                                    <tr>
                                        <td class="p-0">
                                        </td>
                                        <td class="pe-0">
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                Questionnaire sur la maitrise de vos tâches professionnelles
                                            </span>
                                        </td>
                                        <td class="pe-0">
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $vehicle["label"] ?>
                                            </span>
                                        </td>
                                        <td class="pe-0">
                                            <span class="text-gray-800 fw-bolder fs-5 d-block">
                                                <?php echo $vehicle["brand"] ?>
                                            </span>
                                        </td>
                                        <?php if ($test->level == "Junior") { ?>
                                        <td>
                                            <a href="./userQuizDeclaratif.php?brand=<?php echo $vehicle["brand"] ?>&vehicle=<?php echo $vehicle["label"] ?>&level=Junior&id=<?php echo $_SESSION["id"] ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
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
                                        <?php } ?>
                                        <?php if ($test->level == "Senior") { ?>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <td>
                                            <a href="./userQuizDeclaratif.php?brand=<?php echo $vehicle["brand"] ?>&vehicle=<?php echo $vehicle["label"] ?>&level=Senior&id=<?php echo $_SESSION["id"] ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 m-1">
                                                Non disponible
                                            </span>
                                        </td>
                                        <?php } ?>
                                        <?php if ($test->level == "Expert") { ?>
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
                                        <td>
                                            <a href="./userQuizDeclaratif.php?brand=<?php echo $vehicle["brand"] ?>&vehicle=<?php echo $vehicle["label"] ?>&level=Expert&id=<?php echo $_SESSION["id"] ?>"
                                                class="btn btn-light btn-active-light-success text-success btn-sm"
                                                title="Cliquez ici pour ouvrir le questionnaire"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                A faire
                                            </a>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php
                                       }
                                       }
                                   ?>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <?php } ?>
    <?php if ($_SESSION["profile"] == "Super Admin" || $_SESSION["profile"] == "Admin") {?>
    <!--begin::Content-->
    <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div class=" container-xxl ">
                <!--begin::Row-->
                <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countUsers ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Techniciens </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countManagers ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Managers </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <?php if ($_SESSION["profile"] == "Super Admin") {?>
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countAdmins ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Administrateurs
                                </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <?php } ?>
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countVehicles ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Véhicules </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end:Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
    <?php } ?>
    <!-- begin::Row -->
    <div class="m-5">
        <figure class="highcharts-figure">
            <div id="courbe_evolution"></div>
        </figure>
    </div>
    <!-- end::Row -->
</div>
<!--end::Content-->
<?php
include('./partials/footer.php')
?>
<?php
}
?>