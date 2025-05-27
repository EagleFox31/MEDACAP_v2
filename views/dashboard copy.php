<?php
session_start();
include_once "language.php";

if (!isset($_SESSION["profile"])) {
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
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;
    $tests = $academy->tests;
    $exams = $academy->exams;
    $results = $academy->results;
    $allocations = $academy->allocations;
    $connections = $academy->connections;

    $countOnlineUser = $connections->find([
        '$and' => [
            [
                "status" => "Online",
                "active" => true,
            ],
        ],
    ])->toArray();
    $countOnlineUsers = count($countOnlineUser);

    $countUsers = [];
    $countUser = $users->find([
        '$and' => [
            [
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($countUser as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($countUsers, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($countUsers, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $countUsersJu = [];
    $countUserJu = $users->find([
        '$and' => [
            [
                'level' => 'Junior',
                "active" => true
            ],
        ],
    ])->toArray();
    foreach ($countUserJu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($countUsersJu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($countUsersJu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $countUsersSe = [];
    $countUserSe = $users->find([
        '$and' => [
            [
                'level' => 'Senior',
                "active" => true
            ],
        ],
    ])->toArray();
    foreach ($countUserSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($countUsersSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($countUsersSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $countUsersEx = [];
    $countUserEx = $users->find([
        '$and' => [
            [
                'level' => 'Expert',
                "active" => true
            ],
        ],
    ])->toArray();
    foreach ($countUserEx as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($countUsersEx, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($countUsersEx, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $testJu = [];
    $testSe = [];
    $testEx = [];
    foreach ($countUsers as $technician) { 
        $allocateFacJu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "level" => "Junior",
                    "type" => "Factuel",
                    "active" => true,
                ],
            ],
        ]);
        $allocateDeclaJu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "level" => "Junior",
                    "type" => "Declaratif",
                    "activeManager" => true,
                    "active" => true,
                ],
            ],
        ]);
        $allocateFac = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "level" => "Junior",
                    "type" => "Factuel",
                    "active" => true,
                ],
            ],
        ]);
        $allocateDecla = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "level" => "Junior",
                    "type" => "Declaratif",
                    "activeManager" => true,
                    "active" => true,
                ],
            ],
        ]);
        $allocateFac = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "level" => "Junior",
                    "type" => "Factuel",
                    "active" => true,
                ],
            ],
        ]);
        $allocateDecla = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "level" => "Junior",
                    "type" => "Declaratif",
                    "activeManager" => true,
                    "active" => true,
                ],
            ],
        ]);
        if (isset($allocateFacJu )&& isset($allocateDeclaJu)) {
            $testJu[] = $technician;
        }
        if (isset($allocateFacSe) && isset($allocateDeclaSe)) {
            $testSe[] = $technician;
        }
        if (isset($allocateFacEx) && isset($allocateDeclaEx)) {
            $testEx[] = $technician;
        }
    }

    $countManager = $users->find([
        '$and' => [
            [
                "profile" => "Manager",
                "active" => true,
            ],
        ],
    ])->toArray();
    $countManagers = count($countManager);
    $countAdmin = $users->find([
        '$and' => [
            [
                "profile" => "Admin",
                "active" => true,
            ],
        ],
    ])->toArray();
    $countAdmins = count($countAdmin);
    $countDirecteurFiliale = $users->find([
        '$and' => [
            [
                "profile" => "Directeur Filiale",
                "active" => true,
            ],
        ],
    ])->toArray();
    $countDirecteurFiliales = count($countDirecteurFiliale);
    $countDirecteurGroupe = $users->find([
        '$and' => [
            [
                "profile" => "Directeur Groupe",
                "active" => true,
            ],
        ],
    ])->toArray();
    $countDirecteurGroupes = count($countDirecteurGroupe);
    $countVehicle = $vehicles->find()->toArray();
    $countVehicles = count($countVehicle);

    $countSavoirJu = [];
    $countSavoirSe = [];
    $countSavoirEx = [];
    $countTechSavFaiJu = [];
    $countTechSavFaiSe = [];
    $countTechSavFaiEx = [];
    $countMaSavFaiJu = [];
    $countMaSavFaiSe = [];
    $countMaSavFaiEx = [];
    $testsUserJu = [];
    $testsUserSe = [];
    $testsUserEx = [];
    $testsTotalJu = [];
    $testsTotalSe = [];
    $testsTotalEx = [];
    foreach ($countUsers as $user) {
        $countSavJu = $allocations
            ->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($user),
                        "level" => "Junior",
                        "type" => "Factuel",
                    ],
                ],
            ]);
    
        $countSavFaJu = $allocations
            ->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($user),
                        "level" => "Junior",
                        "type" => "Declaratif",
                    ],
                ],
            ]);
        if (isset($countSavJu) && $countSavJu['active'] == true) {
            $countSavoirJu[] = $countSavJu;
        }
        if (isset($countSavFaJu) && $countSavFaJu['activeManager'] == true) {
            $countMaSavFaiJu[] = $countSavFaJu;
        }
        if (isset($countSavFaJu) && $countSavFaJu['active'] == true) {
            $countTechSavFaiJu[] = $countSavFaJu;
        }
        if (isset($countSavJu) && isset($countSavFaJu) && $countSavJu['active'] == true && $countSavFaJu['active'] == true && $countSavFaJu['activeManager'] == true) {
            $testsUserJu[] = $user;
        }
        if (isset($countSavJu) && isset($countSavFaJu)) {
            $testsTotalJu[] = $user;
        }

        $countSavSe = $allocations
            ->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($user),
                        "level" => "Senior",
                        "type" => "Factuel",
                    ],
                ],
            ]);
    
        $countSavFaSe = $allocations
            ->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($user),
                        "level" => "Senior",
                        "type" => "Declaratif",
                    ],
                ],
            ]);
        if (isset($countSavSe) && $countSavSe['active'] == true) {
            $countSavoirSe[] = $countSavSe;
        }
        if (isset($countSavFaSe) && $countSavFaSe['activeManager'] == true) {
            $countMaSavFaiSe[] = $countSavFaSe;
        }
        if (isset($countSavFaSe) && $countSavFaSe['active'] == true) {
            $countTechSavFaiSe[] = $countSavFaSe;
        }
        if (isset($countSavSe) && isset($countSavFaSe) && $countSavSe['active'] == true && $countSavFaSe['active'] == true && $countSavFaSe['activeManager'] == true) {
            $testsUserSe[] = $user;
        }
        if (isset($countSavSe) && isset($countSavFaSe)) {
            $testsTotalSe[] = $user;
        }

        $countSavEx = $allocations
            ->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($user),
                        "level" => "Expert",
                        "type" => "Factuel",
                    ],
                ],
            ]);
    
        $countSavFaEx = $allocations
            ->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($user),
                        "level" => "Expert",
                        "type" => "Declaratif",
                    ],
                ],
            ]);
        if (isset($countSavEx) && $countSavEx['active'] == true) {
            $countSavoirEx[] = $countSavEx;
        }
        if (isset($countSavFaEx) && $countSavFaEx['activeManager'] == true) {
            $countMaSavFaiEx[] = $countSavFaEx;
        }
        if (isset($countSavFaEx) && $countSavFaEx['active'] == true) {
            $countTechSavFaiEx[] = $countSavFaEx;
        }
        if (isset($countSavEx) && isset($countSavFaEx) && $countSavEx['active'] == true && $countSavFaEx['active'] == true && $countSavFaEx['activeManager'] == true) {
            $testsUserEx[] = $user;
        }
        if (isset($countSavEx) && isset($countSavFaEx)) {
            $testsTotalEx[] = $user;
        }
    }

    $percentageSavoir = ceil(((count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx))  * 100) / (count($countUsers) + count($countUsersSe) + (count($countUsersEx)) * 2));

    $percentageMaSavoirFaire = ceil(((count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx)) * 100) / (count($countUsers) + count($countUsersSe) + (count($countUsersEx)) * 2));

    $percentageTechSavoirFaire = ceil(((count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx)) * 100) / (count($countUsers) + count($countUsersSe) + (count($countUsersEx)) * 2));

    $technicians = [];
    $techs = $users->find([
        '$and' => [
            [
                "subsidiary" => $_SESSION["subsidiary"],
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
    $techniciansJu = [];
    $techsJu = $users->find([
        '$and' => [
            [
                "subsidiary" => $_SESSION["subsidiary"],
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSe = [];
    $techsSe = $users->find([
        '$and' => [
            [
                "subsidiary" => $_SESSION["subsidiary"],
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansEx = [];
    $techsEx = $users->find([
        '$and' => [
            [
                "subsidiary" => $_SESSION["subsidiary"],
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsEx as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansEx, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansEx, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    $testsJu = [];
    $countSavoirsJu = [];
    $countMaSavFaisJu = [];
    $countTechSavFaisJu = [];
    $testsSe = [];
    $countSavoirsSe = [];
    $countMaSavFaisSe = [];
    $countTechSavFaisSe = [];
    $testsEx = [];
    $countSavoirsEx = [];
    $countMaSavFaisEx = [];
    $countTechSavFaisEx = [];
    foreach ($technicians as $technician) { 
        $allocateFacJu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJu) && $allocateFacJu['active'] == true) {
            $countSavoirsJu[] = $allocateFacJu;
        }
        if (isset($allocateDeclaJu) && $allocateDeclaJu['activeManager'] == true) {
            $countMaSavFaisJu[] = $allocateDeclaJu;
        }
        if (isset($allocateDeclaJu) && $allocateDeclaJu['active'] == true) {
            $countTechSavFaisJu[] = $allocateDeclaJu;
        }
        if (isset($allocateFacJu) && isset($allocateDeclaJu) && $allocateFacJu['active'] == true && $allocateDeclaJu['active'] == true && $allocateDeclaJu['activeManager'] == true) {
            $testsJu[] = $technician;
        }
        $allocateFacSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSe) && $allocateFacSe['active'] == true) {
            $countSavoirsSe[] = $allocateFacSe;
        }
        if (isset($allocateDeclaSe) && $allocateDeclaSe['activeManager'] == true) {
            $countMaSavFaisSe[] = $allocateDeclaSe;
        }
        if (isset($allocateDeclaSe) && $allocateDeclaSe['active'] == true) {
            $countTechSavFaisSe[] = $allocateDeclaSe;
        }
        if (isset($allocateFacSe) && isset($allocateDeclaSe) && $allocateFacSe['active'] == true && $allocateDeclaSe['active'] == true && $allocateDeclaSe['activeManager'] == true) {
            $testsSe[] = $technician;
        }
        $allocateFacEx = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaEx = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacEx) && $allocateFacEx['active'] == true) {
            $countSavoirsEx[] = $allocateFacEx;
        }
        if (isset($allocateDeclaEx) && $allocateDeclaEx['activeManager'] == true) {
            $countMaSavFaisEx[] = $allocateDeclaEx;
        }
        if (isset($allocateDeclaEx) && $allocateDeclaEx['active'] == true) {
            $countTechSavFaisEx[] = $allocateDeclaEx;
        }
        if (isset($allocateFacEx) && isset($allocateDeclaEx) && $allocateFacEx['active'] == true && $allocateDeclaEx['active'] == true && $allocateDeclaEx['activeManager'] == true) {
            $testsEx[] = $technician;
        }
    }

    $man = $users->find([
        '$and' => [
            [
                "profile" => "Manager",
                "subsidiary" => $_SESSION["subsidiary"],
                "active" => true,
            ],
        ],
    ])->toArray();
    $mgers = count($man);
    // var_dump(count($technicians))
    ?>
<?php include "./partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $tableau ?> | CFAO Mobility Academy</title>
<!--end::Title-->
<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <?php if ($_SESSION["profile"] == "Manager") { ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    <?php echo $intro ?>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
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
                            class="text-primary fw-bolder h-75px w-75px h-lg-100px w-lg-100px position-absolute opacity-5">
                            <path fill="currentColor"
                                d="M10.2,21.23,4.91,18.17a3.58,3.58,0,0,1-1.8-3.11V8.94a3.58,3.58,0,0,1,1.8-3.11L10.2,2.77a3.62,3.62,0,0,1,3.6,0l5.29,3.06a3.58,3.58,0,0,1,1.8,3.11v6.12a3.58,3.58,0,0,1-1.8,3.11L13.8,21.23A3.62,3.62,0,0,1,10.2,21.23Z" />
                        </svg>
                        <i class="ki-duotone ki-user fs-2x fs-lg-3x text-primary fw-bolder position-absolute"><span
                                class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Description-->
                    <div class="ms-6">
                        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                            <?php echo $intro_manager ?>
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
                <!--begin::Illustration-->
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-150px min-h-lg-350px"
                    style="background-image: url(../public/images/IMG-20230627-WA0084.jpg)">
                </div>
                <!--end::Illustration-->
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <?php } ?>
    <?php if ($_SESSION["profile"] == "Admin" || $_SESSION["profile"] == "Directeur Filiale" || $_SESSION["profile"] == "Directeur Groupe") { ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    <?php echo $tableau ?>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--end::Layout Builder Notice-->
            <!--begin::Row-->
            <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                <?php if ( $_SESSION["profile"] == "Directeur Groupe") { ?>
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $effectif_total_groupe ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($countUsersJu) ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss.' '.$level.' '.$junior ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($countUsersSe) ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss.' '.$level.' '.$senior ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($countUsersEx) ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss.' '.$level.' '.$expert ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($countUsers) ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss.' '.$global ?> </div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $level ?> <?php echo $junior ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirJu); ?> / <?php echo count($countUsers) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaiJu); ?> / <?php echo count($countUsers) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaiJu); ?> / <?php echo count($countUsers) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo ceil((count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu)) * 100 / (count($countUsers) * 3)) ?>%
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <!--begin::Title-->
                <center style="margin-top: 5px">
                    <div class="fs-6 mb-2"> <?php echo $test_junior_info ?> </div>
                </center>
                <!--end::Title-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $level ?> <?php echo $senior ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirSe); ?> /
                                    <?php echo count($countUsersSe) + count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaiSe); ?> /
                                    <?php echo count($countUsersSe) + count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaiSe); ?> /
                                    <?php echo count($countUsersSe) + count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo ceil((count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe)) * 100 / ((count($countUserSe) + count($countUserEx)) * 3)) ?>%
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <center style="margin-top: 5px">
                    <div class="fs-6 mb-2"> <?php echo $test_senior_info ?> </div>
                </center>
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $level ?> <?php echo $expert ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirEx); ?> / <?php echo count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaiEx); ?> / <?php echo count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaiEx); ?> / <?php echo count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo ceil((count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) * 100 / (count($countUsersEx) * 3)) ?>%
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <center style="margin-top: 5px">
                    <div class="fs-6 mb-2"> <?php echo $test_expert_info ?> </div>
                </center>
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $global ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <?php $total_percentage = ceil((count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + (count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe)) + (count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx))) * 100 / ((count($countUsers) + count($countUsersSe) + count($countUsersEx) + count($countUsersEx)) * 3)) ?>
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx) ?>
                                    /
                                    <?php echo count($countUsers) + count($countUsersSe) + count($countUsersEx) + count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx) ?>
                                    /
                                    <?php echo count($countUsers) + count($countUsersSe) + count($countUsersEx) + count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx) ?>
                                    /
                                    <?php echo count($countUsers) + count($countUsersSe) + count($countUsersEx) + count($countUsersEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo $total_percentage ?>%
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $moyenne_test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            <?php } ?>
            <?php if ( $_SESSION["profile"] == "Admin" || $_SESSION["profile"] == "Directeur Filiale") { ?>
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $effectif_filiale ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($techniciansJu) ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss ?> <?php echo $level ?> <?php echo $junior ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($techniciansSe) ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss ?> <?php echo $level ?> <?php echo $senior ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($techniciansEx) ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss ?> <?php echo $level ?> <?php echo $expert ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true"
                                    data-kt-countup-value="<?php echo count($technicians); ?>">
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $technicienss ?> <?php echo $subsidiary ?> <?php echo $global ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $level ?> <?php echo $junior ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirsJu); ?> / <?php echo count($technicians) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaisJu); ?> / <?php echo count($technicians) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaisJu); ?> / <?php echo count($technicians) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php $technicianCount = count($technicians);
                                    if ($technicianCount > 0) {
                                        $percentage = ceil((count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu)) * 100 / ($technicianCount * 3));
                                    } else {
                                        $percentage = 0; // or any other appropriate value or message
                                    }
                                    echo $percentage . '%'; ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <center style="margin-top: 5px">
                    <div class="fs-6 mb-2"> <?php echo $test_junior_info ?> </div>
                </center>
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $level ?> <?php echo $senior ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirsSe); ?> /
                                    <?php echo count($techniciansSe) +  count($techniciansEx)?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaisSe); ?> /
                                    <?php echo count($techniciansSe) +  count($techniciansEx)?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaisSe); ?> /
                                    <?php echo count($techniciansSe) +  count($techniciansEx)?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?> </div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php
                                    $technicianCountSe = count($techniciansSe);
                                    $technicianCountEx = count($techniciansEx);
                                    $totalTechnicianCount = $technicianCountSe + $technicianCountEx;
                                    
                                    if ($totalTechnicianCount > 0) {
                                        $percentage = ceil((count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe)) * 100 / ($totalTechnicianCount * 3));
                                    } else {
                                        $percentage = 0; // or any other appropriate value or message
                                    }
                                    echo $percentage . '%';?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <center style="margin-top: 5px">
                    <div class="fs-6 mb-2"> <?php echo $test_senior_info ?> </div>
                </center>
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $level ?> <?php echo $expert ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirsEx); ?> / <?php echo count($techniciansEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaisEx); ?> / <?php echo count($techniciansEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaisEx); ?> / <?php echo count($techniciansEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px"><?php
                                    $technicianCountEx = count($techniciansEx);
                                    
                                    if ($technicianCountEx > 0) {
                                        $percentage = ceil((count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx)) * 100 / ($technicianCountEx * 3));
                                    } else {
                                        $percentage = 0; // or any other appropriate value or message
                                    }
                                    echo $percentage . '%';
                                    ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <center style="margin-top: 5px">
                    <div class="fs-6 mb-2"> <?php echo $test_expert_info ?> </div>
                </center>
                <!--begin::Toolbar-->
                <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                    <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                        <!--begin::Info-->
                        <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_tests ?> <?php echo $global ?>
                            </h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Toolbar-->
                <?php $total_percentage = ceil((count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) + (count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe)) + (count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx))) * 100 / ((count($technicians) + count($techniciansSe) + count($techniciansEx) + count($techniciansEx)) * 3)) ?>
                <!--begin::Col-->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <!--begin::Card-->
                    <div class="card h-100 ">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <!--begin::Animation-->
                            <div
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countSavoirsJu) + count($countSavoirsSe) + count($countSavoirsEx) ?>
                                    /
                                    <?php echo count($technicians) + count($techniciansSe) + count($techniciansEx) + count($techniciansEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countTechSavFaisJu) + count($countTechSavFaisSe) + count($countTechSavFaisEx) ?>
                                    /
                                    <?php echo count($technicians) + count($techniciansSe) + count($techniciansEx) + count($techniciansEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_techs_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo count($countMaSavFaisJu) + count($countMaSavFaisSe) + count($countMaSavFaisEx) ?>
                                    /
                                    <?php echo count($technicians) + count($techniciansSe) + count($techniciansEx) + count($techniciansEx) ?>
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $nbre_qcm_manager_realises ?></div>
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
                                class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px">
                                    <?php echo $total_percentage ?>%
                                </div>
                            </div>
                            <!--end::Animation-->
                            <!--begin::Title-->
                            <div class="fs-5 fw-bold mb-2">
                                <?php echo $moyenne_test_realises ?></div>
                            <!--end::Title-->
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
                <?php } ?>
            </div>
            <!--end:Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <?php } ?>
    <?php if ($_SESSION["profile"] == "Technicien") { ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    <?php echo $intro ?>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
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
                            class="text-primary fw-bolder h-75px w-75px h-lg-100px w-lg-100px position-absolute opacity-5">
                            <path fill="currentColor"
                                d="M10.2,21.23,4.91,18.17a3.58,3.58,0,0,1-1.8-3.11V8.94a3.58,3.58,0,0,1,1.8-3.11L10.2,2.77a3.62,3.62,0,0,1,3.6,0l5.29,3.06a3.58,3.58,0,0,1,1.8,3.11v6.12a3.58,3.58,0,0,1-1.8,3.11L13.8,21.23A3.62,3.62,0,0,1,10.2,21.23Z" />
                        </svg>
                        <i class="ki-duotone ki-user fs-2x fs-lg-3x text-primary fw-bolder position-absolute"><span
                                class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Description-->
                    <div class="ms-6">
                        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                            <?php echo $intro_tech ?>
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
                <!--begin::Illustration-->
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-150px min-h-lg-350px"
                    style="background-image: url(../public/images/IMG-20230627-WA0093.jpg)">
                </div>
                <!--end::Illustration-->
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <?php } ?>
    <?php if (
        $_SESSION["profile"] == "Super Admin"
    ) { ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    <?php echo $tableau ?>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div class=" container-xxl ">
                <!--begin::Row-->
                <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo count($countUsers) ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $technicienss ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countManagers; ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $manageur ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countAdmins; ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $adminss ?>
                                </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countDirecteurFiliales ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $directeurs_filiales ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countDirecteurGroupes ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $directeurs_groupe ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countOnlineUsers ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $user_online ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->

                    <!-- Container for the dynamic cards -->

                    <?php if ($_SESSION["profile"] == "Super Admin" && $_SESSION["profile"] == "Directeur Groupe") { ?>
                    <!--begin::Title-->
                    <div style="margin-top: 55px; margin-bottom : 25px">
                        <div>
                            <h6 class="text-dark fw-bold my-1 fs-2">
                                <?php echo "État d'avancement du groupe CFAO des Tests complétés par les Techniciens" ?>
                            </h6>
                        </div>
                    </div>
                    <!--end::Title-->
                    <!-- begin::Row -->
                    <div>
                        <div id="chartTest" class="row">
                            <!-- Dynamic cards will be appended here -->
                        </div>
                    </div>
                    <!-- endr::Row -->
                    <?php } ?>
                    <!--begin::Title-->
                    <div style="margin-top: 55px; margin-bottom : 25px">
                        <div>
                            <h6 class="text-dark fw-bold my-1 fs-2">
                                <?php echo "État d'avancement du groupe CFAO des QCM complétés par les Techniciens" ?>
                            </h6>
                        </div>
                    </div>
                    <!--end::Title-->
                    <!-- begin::Row -->
                    <div>
                        <div id="chartTech" class="row">
                            <!-- Dynamic cards will be appended here -->
                        </div>
                    </div>
                    <!-- endr::Row -->
                    <!--begin::Title-->
                    <div style="margin-top: 55px; margin-bottom : 25px">
                        <div>
                            <h6 class="text-dark fw-bold my-1 fs-2">
                                <?php echo "État d'avancement du groupe CFAO des QCM complétés par les Managers et Techniciens" ?>
                            </h6>
                        </div>
                    </div>
                    <!--end::Title-->
                    <!-- begin::Row -->
                    <div>
                        <div id="chartContainer" class="row">
                            <!-- Dynamic cards will be appended here -->
                        </div>
                    </div>
                    <!-- endr::Row -->
                </div>
                <!-- begin::Row -->
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
                                            <span
                                                class="card-label fw-bolder text-gray-800 fs-2"><?php echo $taux_realisation ?></span>
                                        </h3>
                                        <!--end::Heading-->
                                    </div>
                                    <!--end::Header-->
                                    <div style="display: relative; box-sizing: border-box;">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                        </div>
                        <!--end::Layout Builder Notice-->
                    </div>
                    <!--end::Container-->
                </div>
                <!-- end::Row -->
                <!-- begin::Row -->
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
                                            <span
                                                class="card-label fw-bolder text-gray-800 fs-2"><?php echo "Moyennes de tests complétés des Techniciens du groupe CFAO" ?></span>
                                        </h3>
                                        <!--end::Heading-->
                                    </div>
                                    <!--end::Header-->
                                    <div style="display: relative; box-sizing: border-box;">
                                        <canvas id="chart"></canvas>
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                        </div>
                        <!--end::Layout Builder Notice-->
                    </div>
                    <!--end::Container-->
                </div>
                <!-- end::Row -->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
    <?php } ?>
</div>
<!--end::Content-->
<?php include "./partials/footer.php"; ?>
<?php
}
?>
<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['QCM Connaissances', 'QCM Tâches Professionnelles (Techniciens)',
            'QCM Tâches Professionnelles (Managers)'
        ],
        datasets: [{
            label: 'Pourcentage de questionnaires réalisés',
            data: [<?php echo $percentageSavoir; ?>, <?php echo $percentageTechSavoirFaire; ?>,
                <?php echo $percentageMaSavoirFaire; ?>
            ],
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
            }
        },
    }
});

const ctxC = document.getElementById('chart');
const data = {
  labels: ['Niveau Junior', 'Niveau Senior', 'Niveau Expert', 'Global: 03 Niveaux'],
  datasets: [{
    type: 'bar',
    label: 'Moyenne Général',
    data: [<?php echo ceil(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>, <?php echo ceil(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>, <?php echo ceil(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>, <?php echo (ceil(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + ceil(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + ceil(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?>],
    borderColor: 'rgb(255, 99, 132)',
    backgroundColor: ['rgb(255, 99, 0.2)', 'rgb(255, 99, 0.2)', 'rgb(255, 99, 0.2)', 'rgb(54, 162, 0.2)']
  }, {
    type: 'line',
    label: 'Line Dataset',
    data: [80, 80, 80, 80],
    fill: false,
    borderColor: 'rgb(54, 162, 235)'
  }]
};

new Chart(ctxC, {
    type: 'scatter',
    data: data,
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Data for each chart
    const chartData = [{
            title: 'QCM Junior',
            total: <?php echo count($countUsers) ?> * 3,
            completed: <?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) ?>, // QCM complétés
            data: [<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) ?>,
                <?php echo (count($countUsers) * 3) - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) ?> QCM complétés', '<?php echo (count($countUsers) * 3) - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'QCM Senior',
            total: <?php echo count($countUsersSe) + count($countUsersEx) ?> * 3,
            completed: <?php echo count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) ?>, // QCM complétés
            data: [<?php echo count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) ?>,
                <?php echo ((count($countUsersSe) + count($countUsersEx)) * 3) - (count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) ?> QCM complétés', '<?php echo ((count($countUsersSe) + count($countUsersEx)) * 3) - (count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'QCM Expert',
            total: <?php echo count($countUsersEx) ?> * 3,
            completed: <?php echo count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>, // QCM complétés
            data: [<?php echo count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>,
                <?php echo (count($countUsersEx) * 3) - (count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?> QCM complétés', '<?php echo (count($countUsersEx) * 3) - (count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Global : 03 Niveaux',
            total: <?php echo count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2) ?> *
                3,
            completed: <?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>, // QCM complétés
            data: [<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>,
                <?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) * 3 - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?> QCM complétés', '<?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) * 3 - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        }
    ];

    const container = document.getElementById('chartContainer');

    // Loop through the data to create and append cards
    chartData.forEach((data, index) => {
        // Calculate the completed percentage
        const completedPercentage = Math.ceil((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <h5>Total QCM à réaliser: ${data.total}</h5>
                        <h5>Pourcentage complétion: ${completedPercentage}%</h5>
                        <canvas id="doughnutChart${index}" width="200" height="200"></canvas>
                        <h5 class="mt-2">${data.title}</h5>
                    </div>
                </div>
            </div>
        `;

        // Append the card to the container
        container.insertAdjacentHTML('beforeend', cardHtml);

        // Initialize the Chart.js doughnut chart
        new Chart(document.getElementById(`doughnutChart${index}`).getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Data',
                    data: data.data,
                    backgroundColor: data.backgroundColor,
                    borderColor: data.backgroundColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data
                                .reduce((a, b) => a + b, 0);
                            let percentage = Math.ceil((value / sum) *
                                100
                            ); // Round up to the nearest whole number
                            console.log(
                                `Value: ${value}, Sum: ${sum}, Percentage: ${percentage}`
                            ); // Debugging line
                            return percentage + '%';
                        },
                        color: '#fff',
                        display: true,
                        anchor: 'center',
                        align: 'center',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' +
                                    tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
    
    // Data for each chart
    const chartDatas = [{
            title: 'QCM Junior',
            total: <?php echo count($countUsers) ?> * 2,
            completed: <?php echo count($countSavoirJu) + count($countTechSavFaiJu) ?>, // QCM complétés
            data: [<?php echo count($countSavoirJu) + count($countTechSavFaiJu) ?>,
                <?php echo (count($countUsers) * 2) - (count($countSavoirJu) + count($countTechSavFaiJu)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirJu) + count($countTechSavFaiJu) ?> QCM complétés', '<?php echo (count($countUsers) * 2) - (count($countSavoirJu) + count($countTechSavFaiJu)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'QCM Senior',
            total: <?php echo count($countUsersSe) + count($countUsersEx) ?> * 2,
            completed: <?php echo count($countSavoirSe) + count($countTechSavFaiSe) ?>, // QCM complétés
            data: [<?php echo count($countSavoirSe) + count($countTechSavFaiSe) ?>,
                <?php echo ((count($countUsersSe) + count($countUsersEx)) * 2) - (count($countSavoirSe) + count($countTechSavFaiSe)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirSe) + count($countTechSavFaiSe) ?> QCM complétés', '<?php echo ((count($countUsersSe) + count($countUsersEx)) * 2) - (count($countSavoirSe) + count($countTechSavFaiSe)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'QCM Expert',
            total: <?php echo count($countUsersEx) ?> * 2,
            completed: <?php echo count($countSavoirEx) + count($countTechSavFaiEx) ?>, // QCM complétés
            data: [<?php echo count($countSavoirEx) + count($countTechSavFaiEx) ?>,
                <?php echo (count($countUsersEx) * 2) - (count($countSavoirEx) + count($countTechSavFaiEx)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirEx) + count($countTechSavFaiEx) ?> QCM complétés', '<?php echo (count($countUsersEx) * 2) - (count($countSavoirEx) + count($countTechSavFaiEx)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Global : 03 Niveaux',
            total: <?php echo count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2) ?> * 2,
            completed: <?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) ?>, // QCM complétés
            data: [<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) ?>,
                <?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) * 2 - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx)) ?>
            ], // QCM complétés vs. QCM à compléter
            labels: ['<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) ?> QCM complétés', '<?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) * 2 - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx)) ?> QCM restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        }
    ];

    const containers = document.getElementById('chartTech');
    
    // Loop through the data to create and append cards
    chartDatas.forEach((data, index) => {
        console.log(containers);
        // Calculate the completed percentage
        const completedPercentage = Math.ceil((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <h5>Total QCM à réaliser: ${data.total}</h5>
                        <h5>Pourcentage complétion: ${completedPercentage}%</h5>
                        <canvas id="doughnutChart${index}" width="200" height="200"></canvas>
                        <h5 class="mt-2">${data.title}</h5>
                    </div>
                </div>
            </div>
        `;

        // Append the card to the container
        containers.insertAdjacentHTML('beforeend', cardHtml);

        // Initialize the Chart.js doughnut chart
        new Chart(document.getElementById(`doughnutChart${index}`).getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Data',
                    data: data.data,
                    backgroundColor: data.backgroundColor,
                    borderColor: data.backgroundColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data
                                .reduce((a, b) => a + b, 0);
                            let percentage = Math.ceil((value / sum) *
                                100
                            ); // Round up to the nearest whole number
                            console.log(
                                `Value: ${value}, Sum: ${sum}, Percentage: ${percentage}`
                            ); // Debugging line
                            return percentage + '%';
                        },
                        color: '#fff',
                        display: true,
                        anchor: 'center',
                        align: 'center',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' +
                                    tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Data for each chart
    const chartData = [{
            title: 'Test Junior',
            total: <?php echo count($testsTotalJu) ?>,
            completed: <?php echo count($testsUserJu) ?>, // Test complétés
            data: [<?php echo count($testsUserJu) ?>, <?php echo (count($testsTotalJu) - count($testsUserJu)) ?>], // Test complétés vs. Test à compléter
            labels: ['<?php echo count($testsUserJu) ?> Test complétés', '<?php echo (count($testsTotalJu)) - (count($testsUserJu)) ?> Test restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Test Senior',
            total: <?php echo count($testsTotalSe) ?>,
            completed: <?php echo count($testsUserSe) ?>, // Test complétés
            data: [<?php echo count($testsUserSe) ?>, <?php echo (count($testsTotalSe) - count($testsUserSe)) ?>], // Test complétés vs. Test à compléter
            labels: ['<?php echo count($testsUserSe) ?> Test complétés', '<?php echo (count($testsTotalSe)) - (count($testsUserSe)) ?> Test restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Test Expert',
            total: <?php echo count($testsTotalEx) ?>,
            completed: <?php echo count($testsUserEx) ?>, // Test complétés
            data: [<?php echo count($testsUserEx) ?>, <?php echo (count($testsTotalEx) - count($testsUserEx)) ?>], // Test complétés vs. Test à compléter
            labels: ['<?php echo count($testsUserEx) ?> Test complétés', '<?php echo (count($testsTotalEx)) - (count($testsUserEx)) ?> Test restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Global : 03 Niveaux',
            total: <?php echo count($testsTotalJu) + count($testsTotalSe) + count($testsTotalEx) ?>,
            completed: <?php echo count($testsUserJu) + count($testsUserSe) + count($testsUserEx) ?>, // Test complétés
            data: [<?php echo count($testsUserJu) + count($testsUserSe) + count($testsUserEx) ?>, <?php echo (count($testsTotalJu) + count($testsTotalSe) + count($testsTotalEx)) - (count($testsUserJu) + count($testsUserSe) + count($testsUserEx)) ?>], // Test complétés vs. Test à compléter
            labels: ['<?php echo count($testsUserJu) + count($testsUserSe) + count($testsUserEx) ?> Test complétés', '<?php echo (count($testsTotalJu) + count($testsTotalSe) + count($testsTotalEx)) - (count($testsUserJu) + count($testsUserSe) + count($testsUserEx)) ?> Test restants à compléter'],
            backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
        }
    ];

    const container = document.getElementById('chartTest');

    // Loop through the data to create and append cards
    chartData.forEach((data, index) => {
        // Calculate the completed percentage
        const completedPercentage = Math.ceil((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <h5>Total Test à réaliser: ${data.total}</h5>
                        <h5>Pourcentage complétion: ${completedPercentage}%</h5>
                        <canvas id="doughnutChart${index}" width="200" height="200"></canvas>
                        <h5 class="mt-2">${data.title}</h5>
                    </div>
                </div>
            </div>
        `;

        // Append the card to the container
        container.insertAdjacentHTML('beforeend', cardHtml);

        // Initialize the Chart.js doughnut chart
        new Chart(document.getElementById(`doughnutChart${index}`).getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Data',
                    data: data.data,
                    backgroundColor: data.backgroundColor,
                    borderColor: data.backgroundColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data
                                .reduce((a, b) => a + b, 0);
                            let percentage = Math.ceil((value / sum) *
                                100
                            ); // Round up to the nearest whole number
                            console.log(
                                `Value: ${value}, Sum: ${sum}, Percentage: ${percentage}`
                            ); // Debugging line
                            return percentage + '%';
                        },
                        color: '#fff',
                        display: true,
                        anchor: 'center',
                        align: 'center',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' +
                                    tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
});
</script>