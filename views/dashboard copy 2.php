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

            $countUu = $users
            ->findOne([
                '$and' => [
                    [
                        "_id" => new MongoDB\BSON\ObjectId($user),
                        "active" => true
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
            if ($countUu['level'] == "Senior" ||  $countUu['level'] == "Expert") {
                $testsTotalSe[] = $user;
            }
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
            if ($countUu['level'] == "Expert") {
                $testsTotalEx[] = $user;
            }
        }
    }

    if ($_SESSION['profile'] == "Admin" || $_SESSION['profile'] == "Directeur Filiale") {
        $resultFacJu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => $_SESSION['subsidiary'],
                                "level" => "Junior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultDeclaJu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => $_SESSION['subsidiary'],
                                "level" => "Junior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultFacSe = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => $_SESSION['subsidiary'],
                                "level" => "Senior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaSe = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => $_SESSION['subsidiary'],
                                "level" => "Senior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacEx = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => $_SESSION['subsidiary'],
                                "level" => "Expert",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaEx = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => $_SESSION['subsidiary'],
                                "level" => "Expert",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
    } else {
        $resultFacJu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "level" => "Junior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaJu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "level" => "Junior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacSe = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "level" => "Senior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaSe = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "level" => "Senior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacEx = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "level" => "Expert",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaEx = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "level" => "Expert",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
    }
    
    $resultFacJuBu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CFAO MOTORS BURKINA',
                                "level" => "Junior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultDeclaJuBu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CFAO MOTORS BURKINA',
                                "level" => "Junior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultFacSeBu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CFAO MOTORS BURKINA',
                                "level" => "Senior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaSeBu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CFAO MOTORS BURKINA',
                                "level" => "Senior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacExBu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CFAO MOTORS BURKINA',
                                "level" => "Expert",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaExBu = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CFAO MOTORS BURKINA',
                                "level" => "Expert",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
            
    $resultFacJuCa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CAMEROON MOTORS INDUSTRIES',
                                "level" => "Junior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultDeclaJuCa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CAMEROON MOTORS INDUSTRIES',
                                "level" => "Junior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultFacSeCa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CAMEROON MOTORS INDUSTRIES',
                                "level" => "Senior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaSeCa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CAMEROON MOTORS INDUSTRIES',
                                "level" => "Senior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacExCa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CAMEROON MOTORS INDUSTRIES',
                                "level" => "Expert",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaExCa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => 'CAMEROON MOTORS INDUSTRIES',
                                "level" => "Expert",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();

    $resultFacJuCo = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                                "level" => "Junior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultDeclaJuCo = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                                "level" => "Junior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultFacSeCo = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                                "level" => "Senior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaSeCo = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                                "level" => "Senior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacExCo = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                                "level" => "Expert",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaExCo = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                                "level" => "Expert",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
                    
    $resultFacJuGa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS GABON",
                                "level" => "Junior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultDeclaJuGa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS GABON",
                                "level" => "Junior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultFacSeGa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS GABON",
                                "level" => "Senior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaSeGa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS GABON",
                                "level" => "Senior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacExGa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS GABON",
                                "level" => "Expert",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaExGa = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS GABON",
                                "level" => "Expert",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
                          
    $resultFacJuMali = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS MALI",
                                "level" => "Junior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultDeclaJuMali = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS MALI",
                                "level" => "Junior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ]
        )->toArray();
        
        $resultFacSeMali = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS MALI",
                                "level" => "Senior",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaSeMali = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS MALI",
                                "level" => "Senior",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultFacExMali = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS MALI",
                                "level" => "Expert",
                                "type" => "Factuel",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
        
        $resultDeclaExMali = $results
            ->aggregate([
                [
                    '$match' => [
                        '$and' => [
                            [
                                "subsidiary" => "CFAO MOTORS MALI",
                                "level" => "Expert",
                                "typeR" => "Technicien - Manager",
                                "type" => "Declaratif",
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        "_id" => 0,
                        "total" => ['$sum' => '$total'],
                        "score" => ['$sum' => '$score'],
                    ],
                ],
                [
                    '$project' => [
                        "_id" => 0,
                        "percentage" => [
                            '$multiply' => [
                                ['$divide' => ['$score', '$total']],
                                100,
                            ],
                        ],
                    ],
                ],
            ])->toArray();
                          
            $resultFacJuRca = $results
                    ->aggregate([
                        [
                            '$match' => [
                                '$and' => [
                                    [
                                        "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                                        "level" => "Junior",
                                        "type" => "Factuel",
                                    ],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => 0,
                                "total" => ['$sum' => '$total'],
                                "score" => ['$sum' => '$score'],
                            ],
                        ],
                        [
                            '$project' => [
                                "_id" => 0,
                                "percentage" => [
                                    '$multiply' => [
                                        ['$divide' => ['$score', '$total']],
                                        100,
                                    ],
                                ],
                            ],
                        ],
                    ]
                )->toArray();
                
                $resultDeclaJuRca = $results
                    ->aggregate([
                        [
                            '$match' => [
                                '$and' => [
                                    [
                                        "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                                        "level" => "Junior",
                                        "typeR" => "Technicien - Manager",
                                        "type" => "Declaratif",
                                    ],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => 0,
                                "total" => ['$sum' => '$total'],
                                "score" => ['$sum' => '$score'],
                            ],
                        ],
                        [
                            '$project' => [
                                "_id" => 0,
                                "percentage" => [
                                    '$multiply' => [
                                        ['$divide' => ['$score', '$total']],
                                        100,
                                    ],
                                ],
                            ],
                        ],
                    ]
                )->toArray();
                
                $resultFacSeRca = $results
                    ->aggregate([
                        [
                            '$match' => [
                                '$and' => [
                                    [
                                        "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                                        "level" => "Senior",
                                        "type" => "Factuel",
                                    ],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => 0,
                                "total" => ['$sum' => '$total'],
                                "score" => ['$sum' => '$score'],
                            ],
                        ],
                        [
                            '$project' => [
                                "_id" => 0,
                                "percentage" => [
                                    '$multiply' => [
                                        ['$divide' => ['$score', '$total']],
                                        100,
                                    ],
                                ],
                            ],
                        ],
                    ])->toArray();
                
                $resultDeclaSeRca = $results
                    ->aggregate([
                        [
                            '$match' => [
                                '$and' => [
                                    [
                                        "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                                        "level" => "Senior",
                                        "typeR" => "Technicien - Manager",
                                        "type" => "Declaratif",
                                    ],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => 0,
                                "total" => ['$sum' => '$total'],
                                "score" => ['$sum' => '$score'],
                            ],
                        ],
                        [
                            '$project' => [
                                "_id" => 0,
                                "percentage" => [
                                    '$multiply' => [
                                        ['$divide' => ['$score', '$total']],
                                        100,
                                    ],
                                ],
                            ],
                        ],
                    ])->toArray();
                
                $resultFacExRca = $results
                    ->aggregate([
                        [
                            '$match' => [
                                '$and' => [
                                    [
                                        "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                                        "level" => "Expert",
                                        "type" => "Factuel",
                                    ],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => 0,
                                "total" => ['$sum' => '$total'],
                                "score" => ['$sum' => '$score'],
                            ],
                        ],
                        [
                            '$project' => [
                                "_id" => 0,
                                "percentage" => [
                                    '$multiply' => [
                                        ['$divide' => ['$score', '$total']],
                                        100,
                                    ],
                                ],
                            ],
                        ],
                    ])->toArray();
                
                $resultDeclaExRca = $results
                    ->aggregate([
                        [
                            '$match' => [
                                '$and' => [
                                    [
                                        "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                                        "level" => "Expert",
                                        "typeR" => "Technicien - Manager",
                                        "type" => "Declaratif",
                                    ],
                                ],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => 0,
                                "total" => ['$sum' => '$total'],
                                "score" => ['$sum' => '$score'],
                            ],
                        ],
                        [
                            '$project' => [
                                "_id" => 0,
                                "percentage" => [
                                    '$multiply' => [
                                        ['$divide' => ['$score', '$total']],
                                        100,
                                    ],
                                ],
                            ],
                        ],
                    ])->toArray();
                          
                    $resultFacJuRdc = $results
                            ->aggregate([
                                [
                                    '$match' => [
                                        '$and' => [
                                            [
                                                "subsidiary" => "CFAO MOTORS RDC",
                                                "level" => "Junior",
                                                "type" => "Factuel",
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => 0,
                                        "total" => ['$sum' => '$total'],
                                        "score" => ['$sum' => '$score'],
                                    ],
                                ],
                                [
                                    '$project' => [
                                        "_id" => 0,
                                        "percentage" => [
                                            '$multiply' => [
                                                ['$divide' => ['$score', '$total']],
                                                100,
                                            ],
                                        ],
                                    ],
                                ],
                            ]
                        )->toArray();
                        
                        $resultDeclaJuRdc = $results
                            ->aggregate([
                                [
                                    '$match' => [
                                        '$and' => [
                                            [
                                                "subsidiary" => "CFAO MOTORS RDC",
                                                "level" => "Junior",
                                                "typeR" => "Technicien - Manager",
                                                "type" => "Declaratif",
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => 0,
                                        "total" => ['$sum' => '$total'],
                                        "score" => ['$sum' => '$score'],
                                    ],
                                ],
                                [
                                    '$project' => [
                                        "_id" => 0,
                                        "percentage" => [
                                            '$multiply' => [
                                                ['$divide' => ['$score', '$total']],
                                                100,
                                            ],
                                        ],
                                    ],
                                ],
                            ]
                        )->toArray();
                        
                        $resultFacSeRdc = $results
                            ->aggregate([
                                [
                                    '$match' => [
                                        '$and' => [
                                            [
                                                "subsidiary" => "CFAO MOTORS RDC",
                                                "level" => "Senior",
                                                "type" => "Factuel",
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => 0,
                                        "total" => ['$sum' => '$total'],
                                        "score" => ['$sum' => '$score'],
                                    ],
                                ],
                                [
                                    '$project' => [
                                        "_id" => 0,
                                        "percentage" => [
                                            '$multiply' => [
                                                ['$divide' => ['$score', '$total']],
                                                100,
                                            ],
                                        ],
                                    ],
                                ],
                            ])->toArray();
                        
                        $resultDeclaSeRdc = $results
                            ->aggregate([
                                [
                                    '$match' => [
                                        '$and' => [
                                            [
                                                "subsidiary" => "CFAO MOTORS RDC",
                                                "level" => "Senior",
                                                "typeR" => "Technicien - Manager",
                                                "type" => "Declaratif",
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => 0,
                                        "total" => ['$sum' => '$total'],
                                        "score" => ['$sum' => '$score'],
                                    ],
                                ],
                                [
                                    '$project' => [
                                        "_id" => 0,
                                        "percentage" => [
                                            '$multiply' => [
                                                ['$divide' => ['$score', '$total']],
                                                100,
                                            ],
                                        ],
                                    ],
                                ],
                            ])->toArray();
                        
                        $resultFacExRdc = $results
                            ->aggregate([
                                [
                                    '$match' => [
                                        '$and' => [
                                            [
                                                "subsidiary" => "CFAO MOTORS RDC",
                                                "level" => "Expert",
                                                "type" => "Factuel",
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => 0,
                                        "total" => ['$sum' => '$total'],
                                        "score" => ['$sum' => '$score'],
                                    ],
                                ],
                                [
                                    '$project' => [
                                        "_id" => 0,
                                        "percentage" => [
                                            '$multiply' => [
                                                ['$divide' => ['$score', '$total']],
                                                100,
                                            ],
                                        ],
                                    ],
                                ],
                            ])->toArray();
                        
                        $resultDeclaExRdc = $results
                            ->aggregate([
                                [
                                    '$match' => [
                                        '$and' => [
                                            [
                                                "subsidiary" => "CFAO MOTORS RDC",
                                                "level" => "Expert",
                                                "typeR" => "Technicien - Manager",
                                                "type" => "Declaratif",
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => 0,
                                        "total" => ['$sum' => '$total'],
                                        "score" => ['$sum' => '$score'],
                                    ],
                                ],
                                [
                                    '$project' => [
                                        "_id" => 0,
                                        "percentage" => [
                                            '$multiply' => [
                                                ['$divide' => ['$score', '$total']],
                                                100,
                                            ],
                                        ],
                                    ],
                                ],
                            ])->toArray();
                          
                          $resultFacJuSe = $results
                                  ->aggregate([
                                      [
                                          '$match' => [
                                              '$and' => [
                                                  [
                                                      "subsidiary" => "CFAO MOTORS SENEGAL",
                                                      "level" => "Junior",
                                                      "type" => "Factuel",
                                                  ],
                                              ],
                                          ],
                                      ],
                                      [
                                          '$group' => [
                                              "_id" => 0,
                                              "total" => ['$sum' => '$total'],
                                              "score" => ['$sum' => '$score'],
                                          ],
                                      ],
                                      [
                                          '$project' => [
                                              "_id" => 0,
                                              "percentage" => [
                                                  '$multiply' => [
                                                      ['$divide' => ['$score', '$total']],
                                                      100,
                                                  ],
                                              ],
                                          ],
                                      ],
                                  ]
                              )->toArray();
                              
                              $resultDeclaJuSe = $results
                                  ->aggregate([
                                      [
                                          '$match' => [
                                              '$and' => [
                                                  [
                                                      "subsidiary" => "CFAO MOTORS SENEGAL",
                                                      "level" => "Junior",
                                                      "typeR" => "Technicien - Manager",
                                                      "type" => "Declaratif",
                                                  ],
                                              ],
                                          ],
                                      ],
                                      [
                                          '$group' => [
                                              "_id" => 0,
                                              "total" => ['$sum' => '$total'],
                                              "score" => ['$sum' => '$score'],
                                          ],
                                      ],
                                      [
                                          '$project' => [
                                              "_id" => 0,
                                              "percentage" => [
                                                  '$multiply' => [
                                                      ['$divide' => ['$score', '$total']],
                                                      100,
                                                  ],
                                              ],
                                          ],
                                      ],
                                  ]
                              )->toArray();
                              
                              $resultFacSeSe = $results
                                  ->aggregate([
                                      [
                                          '$match' => [
                                              '$and' => [
                                                  [
                                                      "subsidiary" => "CFAO MOTORS SENEGAL",
                                                      "level" => "Senior",
                                                      "type" => "Factuel",
                                                  ],
                                              ],
                                          ],
                                      ],
                                      [
                                          '$group' => [
                                              "_id" => 0,
                                              "total" => ['$sum' => '$total'],
                                              "score" => ['$sum' => '$score'],
                                          ],
                                      ],
                                      [
                                          '$project' => [
                                              "_id" => 0,
                                              "percentage" => [
                                                  '$multiply' => [
                                                      ['$divide' => ['$score', '$total']],
                                                      100,
                                                  ],
                                              ],
                                          ],
                                      ],
                                  ])->toArray();
                              
                              $resultDeclaSeSe = $results
                                  ->aggregate([
                                      [
                                          '$match' => [
                                              '$and' => [
                                                  [
                                                      "subsidiary" => "CFAO MOTORS SENEGAL",
                                                      "level" => "Senior",
                                                      "typeR" => "Technicien - Manager",
                                                      "type" => "Declaratif",
                                                  ],
                                              ],
                                          ],
                                      ],
                                      [
                                          '$group' => [
                                              "_id" => 0,
                                              "total" => ['$sum' => '$total'],
                                              "score" => ['$sum' => '$score'],
                                          ],
                                      ],
                                      [
                                          '$project' => [
                                              "_id" => 0,
                                              "percentage" => [
                                                  '$multiply' => [
                                                      ['$divide' => ['$score', '$total']],
                                                      100,
                                                  ],
                                              ],
                                          ],
                                      ],
                                  ])->toArray();
                              
                              $resultFacExSe = $results
                                  ->aggregate([
                                      [
                                          '$match' => [
                                              '$and' => [
                                                  [
                                                      "subsidiary" => "CFAO MOTORS SENEGAL",
                                                      "level" => "Expert",
                                                      "type" => "Factuel",
                                                  ],
                                              ],
                                          ],
                                      ],
                                      [
                                          '$group' => [
                                              "_id" => 0,
                                              "total" => ['$sum' => '$total'],
                                              "score" => ['$sum' => '$score'],
                                          ],
                                      ],
                                      [
                                          '$project' => [
                                              "_id" => 0,
                                              "percentage" => [
                                                  '$multiply' => [
                                                      ['$divide' => ['$score', '$total']],
                                                      100,
                                                  ],
                                              ],
                                          ],
                                      ],
                                  ])->toArray();
                              
                              $resultDeclaExSe = $results
                                  ->aggregate([
                                      [
                                          '$match' => [
                                              '$and' => [
                                                  [
                                                      "subsidiary" => "CFAO MOTORS SENEGAL",
                                                      "level" => "Expert",
                                                      "typeR" => "Technicien - Manager",
                                                      "type" => "Declaratif",
                                                  ],
                                              ],
                                          ],
                                      ],
                                      [
                                          '$group' => [
                                              "_id" => 0,
                                              "total" => ['$sum' => '$total'],
                                              "score" => ['$sum' => '$score'],
                                          ],
                                      ],
                                      [
                                          '$project' => [
                                              "_id" => 0,
                                              "percentage" => [
                                                  '$multiply' => [
                                                      ['$divide' => ['$score', '$total']],
                                                      100,
                                                  ],
                                              ],
                                          ],
                                      ],
                                  ])->toArray();

    $percentageFacJuBu = $resultFacJuBu[0]['percentage'] ?? 0;
    $percentageFacJuCa = $resultFacJuCa[0]['percentage'] ?? 0;
    $percentageFacJuCo = $resultFacJuCo[0]['percentage'] ?? 0;
    $percentageFacJuGa = $resultFacJuGa[0]['percentage'] ?? 0;
    $percentageFacJuMali = $resultFacJuMali[0]['percentage'] ?? 0;
    $percentageFacJuRca = $resultFacJuRca[0]['percentage'] ?? 0;
    $percentageFacJuRdc = $resultFacJuRdc[0]['percentage'] ?? 0;
    $percentageFacJuSe = $resultFacJuSe[0]['percentage'] ?? 0;

    $percentageFacSeBu = $resultFacSeBu[0]['percentage'] ?? 0;
    $percentageFacSeCa = $resultFacSeCa[0]['percentage'] ?? 0;
    $percentageFacSeCo = $resultFacSeCo[0]['percentage'] ?? 0;
    $percentageFacSeGa = $resultFacSeGa[0]['percentage'] ?? 0;
    $percentageFacSeMali = $resultFacSeMali[0]['percentage'] ?? 0;
    $percentageFacSeRca = $resultFacSeRca[0]['percentage'] ?? 0;
    $percentageFacSeRdc = $resultFacSeRdc[0]['percentage'] ?? 0;
    $percentageFacSeSe = $resultFacSeSe[0]['percentage'] ?? 0;

    $percentageFacExBu = $resultFacExBu[0]['percentage'] ?? 0;
    $percentageFacExCa = $resultFacExCa[0]['percentage'] ?? 0;
    $percentageFacExCo = $resultFacExCo[0]['percentage'] ?? 0;
    $percentageFacExGa = $resultFacExGa[0]['percentage'] ?? 0;
    $percentageFacExMali = $resultFacExMali[0]['percentage'] ?? 0;
    $percentageFacExRca = $resultFacExRca[0]['percentage'] ?? 0;
    $percentageFacExRdc = $resultFacExRdc[0]['percentage'] ?? 0;
    $percentageFacExSe = $resultFacExSe[0]['percentage'] ?? 0;

    $percentageDeclaJuBu = $resultDeclaJuBu[0]['percentage'] ?? 0;
    $percentageDeclaJuCa = $resultDeclaJuCa[0]['percentage'] ?? 0;
    $percentageDeclaJuCo = $resultDeclaJuCo[0]['percentage'] ?? 0;
    $percentageDeclaJuGa = $resultDeclaJuGa[0]['percentage'] ?? 0;
    $percentageDeclaJuMali = $resultDeclaJuMali[0]['percentage'] ?? 0;
    $percentageDeclaJuRca = $resultDeclaJuRca[0]['percentage'] ?? 0;
    $percentageDeclaJuRdc = $resultDeclaJuRdc[0]['percentage'] ?? 0;
    $percentageDeclaJuSe = $resultDeclaJuSe[0]['percentage'] ?? 0;

    $percentageDeclaSeBu = $resultDeclaSeBu[0]['percentage'] ?? 0;
    $percentageDeclaSeCa = $resultDeclaSeCa[0]['percentage'] ?? 0;
    $percentageDeclaSeCo = $resultDeclaSeCo[0]['percentage'] ?? 0;
    $percentageDeclaSeGa = $resultDeclaSeGa[0]['percentage'] ?? 0;
    $percentageDeclaSeMali = $resultDeclaSeMali[0]['percentage'] ?? 0;
    $percentageDeclaSeRca = $resultDeclaSeRca[0]['percentage'] ?? 0;
    $percentageDeclaSeRdc = $resultDeclaSeRdc[0]['percentage'] ?? 0;
    $percentageDeclaSeSe = $resultDeclaSeSe[0]['percentage'] ?? 0;

    $percentageDeclaExBu = $resultDeclaExBu[0]['percentage'] ?? 0;
    $percentageDeclaExCa = $resultDeclaExCa[0]['percentage'] ?? 0;
    $percentageDeclaExCo = $resultDeclaExCo[0]['percentage'] ?? 0;
    $percentageDeclaExGa = $resultDeclaExGa[0]['percentage'] ?? 0;
    $percentageDeclaExMali = $resultDeclaExMali[0]['percentage'] ?? 0;
    $percentageDeclaExRca = $resultDeclaExRca[0]['percentage'] ?? 0;
    $percentageDeclaExRdc = $resultDeclaExRdc[0]['percentage'] ?? 0;
    $percentageDeclaExSe = $resultDeclaExSe[0]['percentage'] ?? 0;

    $resultBu = ((($percentageFacJuBu + $percentageDeclaJuBu) / 2) + (($percentageFacSeBu + $percentageDeclaSeBu) / 2) + (($percentageFacExBu + $percentageDeclaExBu) / 2)) / 3;
    $resultCa = ((($percentageFacJuCa + $percentageDeclaJuCa) / 2) + (($percentageFacSeCa + $percentageDeclaSeCa) / 2) + (($percentageFacExCa + $percentageDeclaExCa) / 2)) / 3;
    $resultCo = ((($percentageFacJuCo + $percentageDeclaJuCo) / 2) + (($percentageFacSeCo + $percentageDeclaSeCo) / 2) + (($percentageFacExCo + $percentageDeclaExCo) / 2)) / 3;
    $resultGa = ((($percentageFacJuGa + $percentageDeclaJuGa) / 2) + (($percentageFacSeGa + $percentageDeclaSeGa) / 2) + (($percentageFacExGa + $percentageDeclaExGa) / 2)) / 3;
    $resultMali = ((($percentageFacJuMali + $percentageDeclaJuMali) / 2) + (($percentageFacSeMali + $percentageDeclaSeMali) / 2) + (($percentageFacExMali + $percentageDeclaExMali) / 2)) / 3;
    $resultRca = ((($percentageFacJuRca + $percentageDeclaJuRca) / 2) + (($percentageFacSeRca + $percentageDeclaSeRca) / 2) + (($percentageFacExRca + $percentageDeclaExRca) / 2)) / 3;
    $resultRdc = ((($percentageFacJuRdc + $percentageDeclaJuRdc) / 2) + (($percentageFacSeRdc + $percentageDeclaSeRdc) / 2) + (($percentageFacExRdc + $percentageDeclaExRdc) / 2)) / 3;
    $resultSe = ((($percentageFacJuSe + $percentageDeclaJuSe) / 2) + (($percentageFacSeSe + $percentageDeclaSeSe) / 2) + (($percentageFacExSe + $percentageDeclaExSe) / 2)) / 3;

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
    $testTotalJu = [];
    $countSavoirsJu = [];
    $countMaSavFaisJu = [];
    $countTechSavFaisJu = [];
    $testsSe = [];
    $testTotalSe = [];
    $countSavoirsSe = [];
    $countMaSavFaisSe = [];
    $countTechSavFaisSe = [];
    $testsEx = [];
    $testTotalEx = [];
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
        if (isset($allocateFacJu) && isset($allocateDeclaJu)) {
            $testTotalJu[] = $technician;
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
        if (isset($allocateFacSe) && isset($allocateDeclaSe)) {
            $testTotalSe[] = $technician;
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
        if (isset($allocateFacEx) && isset($allocateDeclaEx)) {
            $testTotalEx[] = $technician;
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


<style>
/* Hide dropdown content by default */
.dropdown-content {
    display: none;
    margin-top: 20px; /* Adjust as needed */
}

/* Style the toggle button */
.dropdown-toggle {
    background-color:#039FFE ;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    border-radius: 8px;
}

.dropdown-toggle i {
    margin-left: 10px;
    transition: transform 0.3s ease;
}

.dropdown-toggle.open i {
    transform: rotate(180deg);
}

/* Optional: Style for better visibility */
.title-and-cards-container {
    margin-bottom: 20px; /* Adjust as needed */
}
/* Container for the card */
.responsive-card {
    max-width: 100%;
    margin: 0 auto;
    padding: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    background-color: #fff;
}

/* Card body */
.responsive-card-body {
    display: flex;
    align-items: center;
    padding: 1rem;
}

/* Card body inner */
.responsive-card-body-inner {
    width: 100%;
    padding: 0;
}

/* Card header */
.responsive-card-header {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 1rem;
}

/* Card title */
.responsive-card-title {
    margin: 0;
    font-size: 1.5rem;
    line-height: 1.2;
}

/* Responsive adjustments for card header */
@media (max-width: 768px) {
    .responsive-card-header {
        padding: 0.5rem;
    }

    .responsive-card-title {
        font-size: 1.25rem;
    }
}

/* Chart container */
.responsive-chart-container {
    width: 100%;
    position: relative; /* Make sure canvas is positioned correctly */
}

/* Canvas styling */
.responsive-chart-container canvas {
    width: 100% !important; /* Make canvas responsive */
    height: auto !important; /* Maintain aspect ratio */
}

/* Responsive adjustments for canvas */
@media (max-width: 768px) {
    .responsive-card-body {
        padding: 0.5rem;
    }

    .responsive-card-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 576px) {
    .responsive-card-title {
        font-size: 1rem;
    }
}

.title-and-cards-container {
    display: flex;
    align-items: center;
    /* Align items vertically in the center */
    justify-content: space-between;
    /* Space between title, line, and cards */
    padding: 10px;
    /* Optional: adds padding around the container */
}

.title-container {
    flex: 1;
    /* Allow title container to take up space */
}

.main-title {
    font-size: 18px;
    /* Adjust font size as needed */
    font-weight: 600;
    /* Bold title */
    text-align: left;
    /* Align text to the left */
    margin-left: 25px;
}

.dynamic-card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    /* Center cards horizontally */
    flex: 3;
    /* Allow card container to take up more space */
}

.dynamic-card-container .card {
    width: 200px;
    height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    border-radius: 8px;
    position: relative;
    /* for potential future use */
    /* Remove any other styles that might conflict with your existing cards */
}

.card-title {
    margin-bottom: 10px;
    text-align: center;
    font-size: 15px;
    font-weight: 600;
}

.card-canvas {
    width: 100%;
    /* Ensure canvas uses full width */
    height: 80%;
    /* Adjust height of the canvas for the doughnut chart */
    margin-bottom: 10px;
    /* Increased margin from the title */
}

.card-top-title {
    margin-top: 10px;
    /* Space between the top title and the chart */
    text-align: center;
    font-size: 14px;
    font-weight: bolder;
}

.card-secondary-top-title {
    margin-bottom: 5px;
    /* Space between the secondary top title and the chart */
    text-align: center;
    font-size: 12px;
    /* Adjust font size if needed */
    font-weight: bold;
    /* Slightly lighter weight for the Pourcentage complété : */
}

.plus-sign {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem; /* Large size for visibility */
    color: #000; /* Adjust color if needed */
    position: relative; /* Allows movement relative to its normal position */
    top: 50px; /* Moves the plus sign down by 100px */
    transition: transform 0.3s ease, color 0.3s ease; /* Smooth transitions for interactivity */
}

/* Optional: Hover effect for a modern touch */
.plus-sign:hover {
    transform: scale(1.1); /* Slightly enlarges on hover */
    color: #007bff; /* Change color on hover for better visibility */
}
</style>

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
                <!--begin::Title-->
                <div style="margin-top: 55px; margin-bottom : 25px">
                    <div>
                        <h6 class="text-dark fw-bold my-1 fs-2">
                            <?php echo $result_mesure_competence_groupe ?>
                        </h6>
                    </div>
                </div>
                <!--end::Title-->
                <!-- begin::Row -->
                <div>
                    <div id="chartMoyen" class="row">
                        <!-- Dynamic cards will be appended here -->
                    </div>
                    <div style="display: flex; justify-content: center; margin-top: -30px; transform: scale(0.75);">
                        <fieldset style="display: flex; gap: 20px;">
                            <!-- Group 1 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c1' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_0_60 ?></h4>
                            </div>

                            <!-- Group 2 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c2' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_60_80 ?></h4>
                            </div>

                            <!-- Group 3 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c3' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_80_100 ?></h4>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <!-- end::Row -->
                <!--begin::Title-->
                <div style="margin-top: 55px; margin-bottom : 25px">
                    <div>
                        <h6 class="text-dark fw-bold my-1 fs-2">
                            <?php echo $etat_avanacement_test_realises_groupe ?>
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
                <!-- begin::Row -->
                <div class="title-and-cards-container">
                    <div class="title-container">
                        <h4 class="main-title text-center fs-1" style="margin-left: -10px"><?php echo "Tests" ?></h4>
                        <span class="plus-sign">=</span>
                        <h4 class="main-title" style="margin-top: 150px"><?php echo "QCM ".$connaissances. " des techniciens de la filiale" ?></h4>
                        <span class="plus-sign">+</span>
                    </div>
                    <div id="chartCon" class="dynamic-card-container"></div>
                </div>
                <!-- end::Row -->
                <!-- begin::Row -->
                <div class="title-and-cards-container">
                    <div class="title-container">
                        <h4 class="main-title" style="margin-top: 250px !important; margin-bottom: 10px"><?php echo "QCM ".$tache_pro_tech ?></h4>
                        <span class="plus-sign">+</span>
                    </div>
                    <div id="chartTech" class="dynamic-card-container"></div>
                </div>
                <!-- end::Row -->
                <!-- begin::Row -->
                <div class="title-and-cards-container">
                    <div class="title-container">
                        <h4 class="main-title"><?php echo "QCM ".$tache_pro_manager ?></h4>
                    </div>
                    <div id="chartMan" class="dynamic-card-container"></div>
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
                                                class="card-label fw-bolder text-gray-800 fs-2"><?php echo $result_mesure_competence_filiale_groupe ?></span>
                                        </h3>
                                        <!--end::Heading-->
                                    </div>
                                    <!--end::Header-->
                                    <div  class="responsive-chart-container">
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
                <!--begin::Title-->
                <div style="margin-top: 55px; margin-bottom : 25px">
                    <div>
                        <h6 class="text-dark fw-bold my-1 fs-2">
                            <?php echo $result_mesure_competence_filiale ?>
                        </h6>
                    </div>
                </div>
                <!--end::Title-->
                <!-- begin::Row -->
                <div>
                    <div id="chartMoyenFiliale" class="row">
                        <!-- Dynamic cards will be appended here -->
                    </div>
                    <div style="display: flex; justify-content: center; margin-top: -30px; transform: scale(0.75);">
                        <fieldset style="display: flex; gap: 20px;">
                            <!-- Group 1 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c1' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_0_60 ?></h4>
                            </div>

                            <!-- Group 2 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c2' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_60_80 ?></h4>
                            </div>

                            <!-- Group 3 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c3' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_80_100 ?></h4>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <!-- endr::Row -->
                <!--begin::Title-->
                <div style="margin-top: 55px; margin-bottom : 25px">
                    <div>
                        <h6 class="text-dark fw-bold my-1 fs-2">
                            <?php echo $etat_avanacement_test_filiale ?>
                        </h6>
                    </div>
                </div>
                <!--end::Title-->
                <!-- begin::Row -->
                <div>
                    <div id="chartTestFiliale" class="row">
                        <!-- Dynamic cards will be appended here -->
                    </div>
                </div>
                <!-- endr::Row -->
                <!-- Dropdown Toggle Button -->
                <div class="dropdown-container">
                    <button class="dropdown-toggle">Plus de détails sur les tests</button>
                    
                    <!-- Hidden Content -->
                    <div class="dropdown-content">
                        <!-- Begin::Row -->
                        <div class="title-and-cards-container">
                            <div class="title-container">
                                <h4 class="main-title text-center fs-1" style="margin-left: 20px !important; color: #4303ec;"><?php echo "1 Test" ?></h4>
                                <span class="plus-sign" style="margin-left: 20px !important;">=</span>
                                <h4 class="main-title" style="margin-top: 150px; text-align: center; position: relative; top: -30px !important; color: #039FFE;">
                                    <?php echo "QCM ".$connaissances. " du technicien"; ?>
                                    <i class="fa fa-arrow-right" style="margin-left: 10px;"></i>
                                </h4>
                                <span class="plus-sign" style="margin-left: 10px !important;">+</span>
                            </div>
                            <div id="chartFilialeCon" class="dynamic-card-container"></div>
                        </div>
                        <!-- End::Row -->
                        
                        <!-- Begin::Row -->
                        <div class="title-and-cards-container">
                            <div class="title-container">
                                <h4 class="main-title" style="margin-top: 250px !important; position: relative; top: -40px !important; text-align: center; color: #82CDFF;">
                                    <?php echo "QCM ".$tache_pro_tech ?>
                                    <i class="fa fa-arrow-right" style="margin-left: 10px;"></i>
                                </h4>
                                <span class="plus-sign" style="margin-left: 10px !important;">+</span>
                            </div>
                            <div id="chartFilialeTech" class="dynamic-card-container"></div>
                        </div>
                        <!-- End::Row -->
                        
                        <!-- Begin::Row -->
                        <div class="title-and-cards-container">
                            <div class="title-container">
                                <h4 class="main-title" style="text-align: center; color: #BBE4FF;">
                                    <?php echo "QCM ".$tache_pro_manager ?>
                                    <i class="fa fa-arrow-right" style="margin-left: 10px;"></i>
                                </h4>
                            </div>
                            <div id="chartFilialeMan" class="dynamic-card-container"></div>
                        </div>
                        <!-- End::Row -->
                    </div>
                </div>
                <!-- Dropdown Toggle Button -->
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

                    <!--begin::Title-->
                    <div style="margin-top: 55px; margin-bottom : 25px">
                        <div>
                            <h6 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $result_mesure_competence_groupe ?>
                            </h6>
                        </div>
                    </div>
                    <!--end::Title-->
                    <!-- begin::Row -->
                    <div>
                        <div id="chartMoyen" class="row">
                            <!-- Dynamic cards will be appended here -->
                        </div>
                        <div style="display: flex; justify-content: center; margin-top: -30px; transform: scale(0.75);">
                            <fieldset style="display: flex; gap: 20px;">
                                <!-- Group 1 -->
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <canvas id='c1' width="75" height="37.5"></canvas>
                                    <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_0_60 ?></h4>
                                </div>

                                <!-- Group 2 -->
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <canvas id='c2' width="75" height="37.5"></canvas>
                                    <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_60_80 ?></h4>
                                </div>

                                <!-- Group 3 -->
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <canvas id='c3' width="75" height="37.5"></canvas>
                                    <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_80_100 ?></h4>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <!-- endr::Row -->
                    <!--begin::Title-->
                    <div style="margin-top: 55px; margin-bottom : 25px">
                        <div>
                            <h6 class="text-dark fw-bold my-1 fs-2">
                                <?php echo $etat_avanacement_test_realises_groupe ?>
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
                    <!-- Dropdown Toggle Button -->
                    <div class="dropdown-container">
                        <button class="dropdown-toggle">Plus de détails sur les tests</button>
                        
                        <!-- Hidden Content -->
                        <div class="dropdown-content">
                            <!-- Begin::Row -->
                            <div class="title-and-cards-container">
                                <div class="title-container">
                                    <h4 class="main-title text-center fs-1" style="margin-left: 20px !important; color: #4303ec;"><?php echo "1 Test" ?></h4>
                                    <span class="plus-sign" style="margin-left: 20px !important;">=</span>
                                    <h4 class="main-title" style="margin-top: 150px; text-align: center; position: relative; top: -30px !important; color: #039FFE;">
                                        <?php echo "QCM ".$connaissances. " du technicien"; ?>
                                        <i class="fa fa-arrow-right" style="margin-left: 10px;"></i>
                                    </h4>
                                    <span class="plus-sign" style="margin-left: 10px !important;">+</span>
                                </div>
                                <div id="chartCon" class="dynamic-card-container"></div>
                            </div>
                            <!-- End::Row -->
                            
                            <!-- Begin::Row -->
                            <div class="title-and-cards-container">
                                <div class="title-container">
                                    <h4 class="main-title" style="margin-top: 250px !important; position: relative; top: -40px !important; text-align: center; color: #82CDFF;">
                                        <?php echo "QCM ".$tache_pro_tech ?>
                                        <i class="fa fa-arrow-right" style="margin-left: 10px;"></i>
                                    </h4>
                                    <span class="plus-sign" style="margin-left: 10px !important;">+</span>
                                </div>
                                <div id="chartTech" class="dynamic-card-container"></div>
                            </div>
                            <!-- End::Row -->
                            
                            <!-- Begin::Row -->
                            <div class="title-and-cards-container">
                                <div class="title-container">
                                    <h4 class="main-title" style="text-align: center; color: #BBE4FF;">
                                        <?php echo "QCM ".$tache_pro_manager ?>
                                        <i class="fa fa-arrow-right" style="margin-left: 10px;"></i>
                                    </h4>
                                </div>
                                <div id="chartMan" class="dynamic-card-container"></div>
                            </div>
                            <!-- End::Row -->
                        </div>
                    </div>
                    <!-- Dropdown Toggle Button -->
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
                                                    class="card-label fw-bolder text-gray-800 fs-2"><?php echo $result_mesure_competence_filiale_groupe ?></span>
                                            </h3>
                                            <!--end::Heading-->
                                        </div>
                                        <!--end::Header-->
                                        <div  class="responsive-chart-container">
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
const ctxC = document.getElementById('chart');
const data = {
    labels: ['BURKINA FASO', 'CAMEROUN', "CÔTE D'IVOIRE", 'GABON', 'MALI', 'RCA', 'RDC', 'SENEGAL'],
    datasets: [{
        type: 'bar',
        label: 'Résultat Général',
        data: [<?php echo ceil($resultBu) ?>, <?php echo ceil($resultCa) ?>, <?php echo ceil($resultCo) ?>, <?php echo ceil($resultGa) ?>, <?php echo ceil($resultMali) ?>, <?php echo ceil($resultRca) ?>, <?php echo ceil($resultRdc) ?>, <?php echo ceil($resultSe) ?>],
        borderColor: 'rgba(0, 0, 0, 0)', // Transparent border
        backgroundColor: [
            '#003f5c', // Dark Blue
            '#2f4b7c', // Blue
            '#665191', // Purple-Blue
            '#a05195', // Purple-Pink
            '#d45087', // Pink
            '#f95d6a', // Coral
            '#ff7c43', // Orange
            '#ffa600'  // Yellow
        ],
        datalabels: {
            formatter: function(value, context) {
                const label = context.chart.data.labels[context.dataIndex];
                return 'Résultat ' + label;
            },
            color: '#000',
            font: {
                weight: 'bold'
            },
            anchor: 'end',
            align: 'top',
            offset: 4
        }
    }]
};

const chart = new Chart(ctxC, {
    type: 'bar',
    data: data,
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    generateLabels: function(chart) {
                        const labels = chart.data.labels;
                        const datasets = chart.data.datasets[0];
                        return labels.map((label, index) => ({
                            text: `Résultat ${label}`,
                            fillStyle: datasets.backgroundColor[index],
                            hidden: !chart.isDatasetVisible(0),
                            strokeStyle: 'transparent',
                            lineWidth: 0,
                            datasetIndex: 0,
                            index: index
                        }));
                    },
                    font: {
                        size: 10
                    }
                },
                onClick: function(e, legendItem, legend) {
                    const index = legendItem.index;
                    const meta = legend.chart.getDatasetMeta(0);
                    const dataset = meta.data[index];
                    
                    // Toggle visibility
                    dataset.hidden = !dataset.hidden;
                    legend.chart.update();
                }
            },
            datalabels: {
                display: true,
                color: '#000',
                font: {
                    weight: 'bold'
                }
            }
        }
    }
});

let canvas1 = document.getElementById('c1');
let ctx1 = canvas1.getContext('2d');
ctx1.fillStyle = '#f9945e'; //Nuance de bleu
ctx1.fillRect(50, 25, 200, 100);

let canvas2 = document.getElementById('c2');
let ctx2 = canvas2.getContext('2d');
ctx2.fillStyle = '#f9f75e'; //Nuance de bleu
ctx2.fillRect(50, 25, 200, 100);

let canvas3 = document.getElementById('c3');
let ctx3 = canvas3.getContext('2d');
ctx3.fillStyle = '#6cf95e'; //Nuance de bleu
ctx3.fillRect(50, 25, 200, 100);


if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> <= 60) {
    var colorJu = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> < 80) {
    if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> > 60) {
        var colorJu = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> > 80) {
    var colorJu = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> <= 60) {
    var colorSe = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> < 80) {
    if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> > 60) {
        var colorSe = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> > 80) {
    var colorSe = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> <= 60) {
    var colorEx = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> < 80) {
    if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> > 60) {
        var colorEx = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> > 80) {
    var colorEx = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}

if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> <=
    60) {
    var color = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> <
    80) {
    if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> >
        60) {
        var color = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> >
    80) {
    var color = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}

document.addEventListener('DOMContentLoaded', function() {
    // Data for each chart
    const chartData = [{
            title: 'QCM Junior',
            total: <?php echo count($countUsers) ?> * 3,
            completed: <?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) ?>, // QCM réalisés
            data: [<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) ?>,
                <?php echo (count($countUsers) * 3) - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu)) ?>
            ], // QCM réalisés vs. QCM à réaliser
            labels: [
                '<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) ?> QCM réalisés',
                '<?php echo (count($countUsers) * 3) - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu)) ?> QCM restants à réaliser'
            ],
            backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'QCM Senior',
            total: <?php echo count($countUsersSe) + count($countUsersEx) ?> * 3,
            completed: <?php echo count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) ?>, // QCM réalisés
            data: [<?php echo count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) ?>,
                <?php echo ((count($countUsersSe) + count($countUsersEx)) * 3) - (count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe)) ?>
            ], // QCM réalisés vs. QCM à réaliser
            labels: [
                '<?php echo count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) ?> QCM réalisés',
                '<?php echo ((count($countUsersSe) + count($countUsersEx)) * 3) - (count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe)) ?> QCM restants à réaliser'
            ],
            backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'QCM Expert',
            total: <?php echo count($countUsersEx) ?> * 3,
            completed: <?php echo count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>, // QCM réalisés
            data: [<?php echo count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>,
                <?php echo (count($countUsersEx) * 3) - (count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?>
            ], // QCM réalisés vs. QCM à réaliser
            labels: [
                '<?php echo count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?> QCM réalisés',
                '<?php echo (count($countUsersEx) * 3) - (count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?> QCM restants à réaliser'
            ],
            backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Total : 03 Niveaux',
            total: <?php echo count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2) ?> *
                3,
            completed: <?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>, // QCM réalisés
            data: [<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?>,
                <?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) * 3 - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?>
            ], // QCM réalisés vs. QCM à réaliser
            labels: [
                '<?php echo count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx) ?> QCM réalisés',
                '<?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) * 3 - (count($countSavoirJu) + count($countTechSavFaiJu) + count($countMaSavFaiJu) + count($countSavoirSe) + count($countTechSavFaiSe) + count($countMaSavFaiSe) + count($countSavoirEx) + count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?> QCM restants à réaliser'
            ],
            backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
        }
    ];

    const container = document.getElementById('chartContainer');

    // Loop through the data to create and append cards
    chartData.forEach((data, index) => {
        // Calculate the completed percentage
        const completedPercentage = Math.round((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <h5>Total des QCM à réaliser: ${data.total}</h5>
                        <h5><strong>${completedPercentage}%</strong> des QCM completés</h5>
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
                        labels: {
                            // Customize legend labels to include numbers
                            generateLabels: function(chart) {
                                const data = chart.data;
                                return data.labels.map((label, i) => ({
                                    text: `${label}`,
                                    fillStyle: data.datasets[0].backgroundColor[
                                        i],
                                    strokeStyle: data.datasets[0].borderColor[
                                        i],
                                    lineWidth: data.datasets[0].borderWidth,
                                    hidden: false
                                }));
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a +
                                b, 0);
                            let percentage = Math.round((value / sum) * 100);
                            // Round up to the nearest whole number
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
                                const value = tooltipItem.raw;
                                const total = tooltipItem.chart.data.datasets[0].data
                                    .reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                            }
                        }
                    }
                }
            }
        });
    });
});

    document.addEventListener('DOMContentLoaded', () => {
        // Data for each chart
        const chartDatas = [{
                title: 'QCM Junior',
                total: <?php echo count($countUsers) ?>,
                completed: <?php echo count($countSavoirJu) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirJu) ?>,
                    <?php echo (count($countUsers)) - (count($countSavoirJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirJu) ?> QCM réalisés',
                    '<?php echo (count($countUsers)) - (count($countSavoirJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Senior',
                total: <?php echo count($countUsersSe) + count($countUsersEx) ?>,
                completed: <?php echo count($countSavoirSe) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirSe) ?>,
                    <?php echo ((count($countUsersSe) + count($countUsersEx))) - (count($countSavoirSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirSe) ?> QCM réalisés',
                    '<?php echo ((count($countUsersSe) + count($countUsersEx))) - (count($countSavoirSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Expert',
                total: <?php echo count($countUsersEx) ?>,
                completed: <?php echo count($countSavoirEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirEx) ?>,
                    <?php echo (count($countUsersEx)) - (count($countSavoirEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirEx) ?> QCM réalisés',
                    '<?php echo (count($countUsersEx)) - (count($countSavoirEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2) ?>,
                completed: <?php echo count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx) ?>,
                    <?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) - (count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx) ?> QCM réalisés',
                    '<?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) - (count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartCon');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            const completedPercentage = Math.round((chartDatas[i].completed / chartDatas[i].total) * 100);

            const card = document.createElement('div');
            card.classList.add('card');

            // Create and append the primary top title
            const topTitle = document.createElement('div');
            topTitle.classList.add('card-top-title');
            topTitle.textContent = `Total QCM à réaliser : ${chartDatas[i].total}`;
            card.appendChild(topTitle);

            // Create and append the secondary top title
            const secondaryTopTitle = document.createElement('div');
            secondaryTopTitle.classList.add('card-secondary-top-title');
            secondaryTopTitle.textContent = `Pourcentage complété : ${completedPercentage}%`;
            card.appendChild(secondaryTopTitle);

            // Create and append the canvas container
            const canvasContainer = document.createElement('div');
            canvasContainer.classList.add('card-canvas');

            const canvas = document.createElement('canvas');
            canvasContainer.appendChild(canvas);
            card.appendChild(canvasContainer);

            // Create and append the chart title
            const title = document.createElement('div');
            title.classList.add('card-title');
            title.textContent = chartDatas[i].title;
            card.appendChild(title);

            cardContainer.appendChild(card);

            // Initialize the Chart.js doughnut chart
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: chartDatas[i].labels, // Customize these labels
                    datasets: [{
                        data: chartDatas[i].data, // Customize these values
                        backgroundColor: chartDatas[i].backgroundColor, // Customize these colors
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                // Customize legend labels to include numbers
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: `${label}`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].borderColor[i],
                                        lineWidth: data.datasets[0].borderWidth,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
                                return percentage + '%';
                            },
                            color: '#fff',
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = tooltipItem.raw;
                                    const total = tooltipItem.chart.data.datasets[0].data.reduce((a,
                                        b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                                }
                            }
                        }
                    }
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Data for each chart
        const chartData = [{
                title: 'QCM Junior',
                total: <?php echo count($countUsers) ?>,
                completed: <?php echo count($countMaSavFaiJu) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiJu) ?>,
                    <?php echo (count($countUsers)) - (count($countMaSavFaiJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countMaSavFaiJu) ?> QCM réalisés',
                    '<?php echo (count($countUsers)) - (count($countMaSavFaiJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Senior',
                total: <?php echo count($countUsersSe) + count($countUsersEx) ?>,
                completed: <?php echo count($countMaSavFaiSe) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiSe) ?>,
                    <?php echo ((count($countUsersSe) + count($countUsersEx))) - (count($countMaSavFaiSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countMaSavFaiSe) ?> QCM réalisés',
                    '<?php echo (count($countUsersSe) + count($countUsersEx)) - (count($countMaSavFaiSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Expert',
                total: <?php echo count($countUsersEx) ?>,
                completed: <?php echo count($countMaSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiEx) ?>,
                    <?php echo (count($countUsersEx)) - (count($countMaSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countTechSavFaiEx) + count($countMaSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($countUsersEx) * 3) - (count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2) ?>,
                completed: <?php echo count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx) ?>,
                    <?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) - (count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) - (count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartMan');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            const completedPercentage = Math.round((chartData[i].completed / chartData[i].total) * 100);

            const card = document.createElement('div');
            card.classList.add('card');

            // Create and append the primary top title
            const topTitle = document.createElement('div');
            topTitle.classList.add('card-top-title');
            topTitle.textContent = `Total QCM à réaliser : ${chartData[i].total}`;
            card.appendChild(topTitle);

            // Create and append the secondary top title
            const secondaryTopTitle = document.createElement('div');
            secondaryTopTitle.classList.add('card-secondary-top-title');
            secondaryTopTitle.textContent = `Pourcentage complété : ${completedPercentage}%`;
            card.appendChild(secondaryTopTitle);

            // Create and append the canvas container
            const canvasContainer = document.createElement('div');
            canvasContainer.classList.add('card-canvas');

            const canvas = document.createElement('canvas');
            canvasContainer.appendChild(canvas);
            card.appendChild(canvasContainer);

            // Create and append the chart title
            const title = document.createElement('div');
            title.classList.add('card-title');
            title.textContent = chartData[i].title;
            card.appendChild(title);

            cardContainer.appendChild(card);

            // Initialize the Chart.js doughnut chart
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: chartData[i].labels, // Customize these labels
                    datasets: [{
                        data: chartData[i].data, // Customize these values
                        backgroundColor: chartData[i].backgroundColor, // Customize these colors
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                // Customize legend labels to include numbers
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: `${label}`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].borderColor[i],
                                        lineWidth: data.datasets[0].borderWidth,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
                                return percentage + '%';
                            },
                            color: '#fff',
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = tooltipItem.raw;
                                    const total = tooltipItem.chart.data.datasets[0].data.reduce((a,
                                        b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                                }
                            }
                        }
                    }
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Data for each chart
        const chartDatas = [{
                title: 'QCM Junior',
                total: <?php echo count($countUsers) ?>,
                completed: <?php echo count($countTechSavFaiJu) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiJu) ?>,
                    <?php echo (count($countUsers)) - (count($countTechSavFaiJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaiJu) ?> QCM réalisés',
                    '<?php echo (count($countUsers)) - (count($countTechSavFaiJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Senior',
                total: <?php echo count($countUsersSe) + count($countUsersEx) ?>,
                completed: <?php echo count($countTechSavFaiSe) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiSe) ?>,
                    <?php echo ((count($countUsersSe) + count($countUsersEx))) - (count($countTechSavFaiSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaiSe) ?> QCM réalisés',
                    '<?php echo ((count($countUsersSe) + count($countUsersEx))) - (count($countTechSavFaiSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Expert',
                total: <?php echo count($countUsersEx) ?>,
                completed: <?php echo count($countTechSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiEx) ?>,
                    <?php echo (count($countUsersEx)) - (count($countTechSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($countUsersEx)) - (count($countTechSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2) ?>,
                completed: <?php echo count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx) ?>,
                    <?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) - (count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($countUsers) + count($countUsersSe)  + (count($countUsersEx) * 2)) - (count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartTech');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            const completedPercentage = Math.round((chartDatas[i].completed / chartDatas[i].total) * 100);

            const card = document.createElement('div');
            card.classList.add('card');

            // Create and append the primary top title
            const topTitle = document.createElement('div');
            topTitle.classList.add('card-top-title');
            topTitle.textContent = `Total QCM à réaliser : ${chartDatas[i].total}`;
            card.appendChild(topTitle);

            // Create and append the secondary top title
            const secondaryTopTitle = document.createElement('div');
            secondaryTopTitle.classList.add('card-secondary-top-title');
            secondaryTopTitle.textContent = `Pourcentage complété : ${completedPercentage}%`;
            card.appendChild(secondaryTopTitle);

            // Create and append the canvas container
            const canvasContainer = document.createElement('div');
            canvasContainer.classList.add('card-canvas');

            const canvas = document.createElement('canvas');
            canvasContainer.appendChild(canvas);
            card.appendChild(canvasContainer);

            // Create and append the chart title
            const title = document.createElement('div');
            title.classList.add('card-title');
            title.textContent = chartDatas[i].title;
            card.appendChild(title);

            cardContainer.appendChild(card);

            // Initialize the Chart.js doughnut chart
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: chartDatas[i].labels, // Customize these labels
                    datasets: [{
                        data: chartDatas[i].data, // Customize these values
                        backgroundColor: chartDatas[i].backgroundColor, // Customize these colors
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                // Customize legend labels to include numbers
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: `${label}`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].borderColor[i],
                                        lineWidth: data.datasets[0].borderWidth,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
                                return percentage + '%';
                            },
                            color: '#fff',
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = tooltipItem.raw;
                                    const total = tooltipItem.chart.data.datasets[0].data.reduce((a,
                                        b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                                }
                            }
                        }
                    }
                }
            });
        }
    });

document.addEventListener('DOMContentLoaded', function() {
    // Data for each chart
    const chartData = [{
            title: 'Test Junior',
            total: <?php echo count($testsTotalJu) ?>,
            completed: <?php echo count($testsUserJu) ?>, // Test réalisés
            data: [<?php echo count($testsUserJu) ?>,
                <?php echo (count($testsTotalJu) - count($testsUserJu)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['Tests réalisés', 'Tests restants à réaliser'],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Test Senior',
            total: <?php echo count($testsTotalSe) ?>,
            completed: <?php echo count($testsUserSe) ?>, // Test réalisés
            data: [<?php echo count($testsUserSe) ?>,
                <?php echo (count($testsTotalSe) - count($testsUserSe)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['Tests réalisés', 'Tests restants à réaliser'],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Test Expert',
            total: <?php echo count($testsTotalEx) ?>,
            completed: <?php echo count($testsUserEx) ?>, // Test réalisés
            data: [<?php echo count($testsUserEx) ?>,
                <?php echo (count($testsTotalEx) - count($testsUserEx)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['Tests réalisés', 'Tests restants à réaliser'],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Total : 03 Niveaux',
            total: <?php echo count($testsTotalJu) + count($testsTotalSe) + count($testsTotalEx) ?>,
            completed: <?php echo count($testsUserJu) + count($testsUserSe) + count($testsUserEx) ?>, // Test réalisés
            data: [<?php echo count($testsUserJu) + count($testsUserSe) + count($testsUserEx) ?>,
                <?php echo (count($testsTotalJu) + count($testsTotalSe) + count($testsTotalEx)) - (count($testsUserJu) + count($testsUserSe) + count($testsUserEx)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['Tests réalisés', 'Tests restants à réaliser'],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        }
    ];

    const container = document.getElementById('chartTest');

    // Loop through the data to create and append cards
    chartData.forEach((data, index) => {
        // Calculate the completed percentage
        const completedPercentage = Math.round((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <h5>Total des Tests à réaliser: ${data.total}</h5>
                        <h5><strong>${completedPercentage}%</strong> des tests réalisés</h5>
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
                        labels: {
                            // Customize legend labels to include numbers
                            generateLabels: function(chart) {
                                const data = chart.data;
                                return data.labels.map((label, i) => ({
                                    text: `${label}: ${data.datasets[0].data[i]}`,
                                    fillStyle: data.datasets[0].backgroundColor[
                                        i],
                                    strokeStyle: data.datasets[0].borderColor[
                                        i],
                                    lineWidth: data.datasets[0].borderWidth,
                                    hidden: false
                                }));
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a +
                                b, 0);
                            let percentage = Math.round((value / sum) * 100);
                            // Round up to the nearest whole number
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
                                const value = tooltipItem.raw || 0;
                                const dataset = tooltipItem.dataset.data;
                                let sum = dataset.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
                                return `Nombre: ${value}, Pourcentage: ${percentage}%`;
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
    const chartDataM = [{
            title: 'Résultat Niveau Junior',
            total: 100,
            completed: <?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>,
                100 -
                <?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: colorJu
        },
        {
            title: 'Résultat Niveau Senior',
            total: 100,
            completed: <?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>,
                100 -
                <?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: colorSe
        },
        {
            title: 'Résultat Niveau Expert',
            total: 100,
            completed: <?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>,
                100 -
                <?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: colorEx
        },
        {
            title: 'Total : 03 Niveaux',
            total: 100,
            completed: <?php echo round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>, // Moyenne des compétences acquises
            data: [<?php echo round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>,
                <?php echo 100 - round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: ['<?php echo round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>% des compétences acquises',
                '<?php echo 100 - round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>% des compétences à acquérir'
            ],
            backgroundColor: color
        }
    ];

    const containerM = document.getElementById('chartMoyen');

    // Loop through the data to create and append cards
    chartDataM.forEach((data, index) => {
        // Calculate the completed percentage
        const completedPercentage = Math.round((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <canvas id="doughnutChart${index}" width="200" height="200"></canvas>
                        <h5 class="mt-2">${data.title}</h5>
                    </div>
                </div>
            </div>
        `;

        // Append the card to the container
        containerM.insertAdjacentHTML('beforeend', cardHtml);

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
                            let percentage = Math.round((value / sum) *
                                100
                            ); // Round up to the nearest whole number
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
                                let percentage = Math.round((tooltipItem.raw / 100) * 100);
                                return `Compétences acquises: ${percentage}%`;
                            }
                        }
                    }
                }
            }
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for each chart
        const chartData = [{
                title: 'QCM Junior',
                total: <?php echo count($technicians) ?> * 3,
                completed: <?php echo count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) ?>,
                    <?php echo (count($technicians) * 3) - (count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) ?> QCM réalisés',
                    '<?php echo (count($technicians) * 3) - (count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Senior',
                total: <?php echo count($techniciansSe) + count($techniciansEx) ?> * 3,
                completed: <?php echo count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) ?>,
                    <?php echo ((count($techniciansSe) + count($techniciansEx)) * 3) - (count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) ?> QCM réalisés',
                    '<?php echo ((count($techniciansSe) + count($techniciansEx)) * 3) - (count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Expert',
                total: <?php echo count($techniciansEx) ?> * 3,
                completed: <?php echo count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx) ?>,
                    <?php echo (count($techniciansEx) * 3) - (count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx) ?> QCM réalisés',
                    '<?php echo (count($techniciansEx) * 3) - (count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2) ?> *
                    3,
                completed: <?php echo count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) + count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) + count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) + count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) + count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx) ?>,
                    <?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) * 3 - (count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) + count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) + count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) + count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) + count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx) ?> QCM réalisés',
                    '<?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) * 3 - (count($countSavoirsJu) + count($countTechSavFaisJu) + count($countMaSavFaisJu) + count($countSavoirsSe) + count($countTechSavFaisSe) + count($countMaSavFaisSe) + count($countSavoirsEx) + count($countTechSavFaisEx) + count($countMaSavFaisEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#3751f9', '#D3D3D3'] // Blue and Lightgrey
            }
        ];
    
        const container = document.getElementById('chartFilialeContainer');
    
        // Loop through the data to create and append cards
        chartData.forEach((data, index) => {
            // Calculate the completed percentage
            const completedPercentage = Math.round((data.completed / data.total) * 100);
    
            // Create the card element
            const cardHtml = `
                <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                            <h5>Total des QCM à réaliser: ${data.total}</h5>
                            <h5><strong>${completedPercentage}%</strong> des QCM completés</h5>
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
                            labels: {
                                // Customize legend labels to include numbers
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: `${label}`,
                                        fillStyle: data.datasets[0].backgroundColor[
                                            i],
                                        strokeStyle: data.datasets[0].borderColor[
                                            i],
                                        lineWidth: data.datasets[0].borderWidth,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a +
                                    b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
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
                                    const value = tooltipItem.raw;
                                    const total = tooltipItem.chart.data.datasets[0].data
                                        .reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `Nombre: ${value},\nPourcentage: ${percentage}%`;
    
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
            total: <?php echo count($testTotalJu) ?>,
            completed: <?php echo count($testsJu) ?>, // Test réalisés
            data: [<?php echo count($testsJu) ?>,
                <?php echo (count($testTotalJu) - count($testsJu)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['<?php echo count($testsJu) ?> Tests réalisés',
                '<?php echo (count($testTotalJu)) - (count($testsJu)) ?> Tests restants à réaliser'
            ],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Test Senior',
            total: <?php echo count($testTotalSe) ?>,
            completed: <?php echo count($testsSe) ?>, // Test réalisés
            data: [<?php echo count($testsSe) ?>,
                <?php echo (count($testTotalSe) - count($testsSe)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['<?php echo count($testsSe) ?> Tests réalisés',
                '<?php echo (count($testTotalSe)) - (count($testsSe)) ?> Tests restants à réaliser'
            ],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Test Expert',
            total: <?php echo count($testTotalEx) ?>,
            completed: <?php echo count($testsEx) ?>, // Test réalisés
            data: [<?php echo count($testsEx) ?>,
                <?php echo (count($testTotalEx) - count($testsEx)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['<?php echo count($testsEx) ?> Tests réalisés',
                '<?php echo (count($testTotalEx)) - (count($testsEx)) ?> Tests restants à réaliser'
            ],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        },
        {
            title: 'Total : 03 Niveaux',
            total: <?php echo count($testTotalJu) + count($testTotalSe) + count($testTotalEx) ?>,
            completed: <?php echo count($testsJu) + count($testsSe) + count($testsEx) ?>, // Test réalisés
            data: [<?php echo count($testsJu) + count($testsSe) + count($testsEx) ?>,
                <?php echo (count($testTotalJu) + count($testTotalSe) + count($testTotalEx)) - (count($testsJu) + count($testsSe) + count($testsEx)) ?>
            ], // Test réalisés vs. Test à réaliser
            labels: ['<?php echo count($testsJu) + count($testsSe) + count($testsEx) ?> Tests réalisés',
                '<?php echo (count($testTotalJu) + count($testTotalSe) + count($testTotalEx)) - (count($testsJu) + count($testsSe) + count($testsEx)) ?> Tests restants à réaliser'
            ],
            backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
        }
    ];

    const container = document.getElementById('chartTestFiliale');

    // Loop through the data to create and append cards
    chartData.forEach((data, index) => {
        // Calculate the completed percentage
        const completedPercentage = Math.round((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <h5>Total des Tests à réaliser: ${data.total}</h5>
                        <h5>Pourcentage complété: ${completedPercentage}%</h5>
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
                        labels: {
                            // Customize legend labels to include numbers
                            generateLabels: function(chart) {
                                const data = chart.data;
                                return data.labels.map((label, i) => ({
                                    text: `${label}`,
                                    fillStyle: data.datasets[0].backgroundColor[
                                        i],
                                    strokeStyle: data.datasets[0].borderColor[
                                        i],
                                    lineWidth: data.datasets[0].borderWidth,
                                    hidden: false
                                }));
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a +
                                b, 0);
                            let percentage = Math.round((value / sum) * 100);
                            // Round up to the nearest whole number
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
                                const value = tooltipItem.raw;
                                const total = tooltipItem.chart.data.datasets[0].data
                                    .reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                            }
                        }
                    }
                }
            }
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Data for each chart
        const chartDatas = [{
                title: 'QCM Junior',
                total: <?php echo count($technicians) ?> ,
                completed: <?php echo count($countSavoirsJu) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsJu) ?>,
                    <?php echo (count($technicians)) - (count($countSavoirsJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirsJu) ?> QCM réalisés',
                    '<?php echo (count($technicians)) - (count($countSavoirsJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Senior',
                total: <?php echo count($techniciansSe) + count($techniciansEx) ?>,
                completed: <?php echo count($countSavoirsSe) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsSe) ?>,
                    <?php echo (count($techniciansSe) + count($techniciansEx)) - (count($countSavoirsSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirsSe) ?> QCM réalisés',
                    '<?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countSavoirsSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Expert',
                total: <?php echo count($techniciansEx) ?>,
                completed: <?php echo count($countSavoirsEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsEx) ?>,
                    <?php echo (count($techniciansEx)) - (count($countSavoirsEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaisEx) ?> QCM réalisés',
                    '<?php echo (count($techniciansEx)) - (count($countTechSavFaisEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2) ?>,
                completed: <?php echo count($countSavoirsJu) + count($countSavoirsSe) + count($countSavoirsEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirsJu) + count($countSavoirsSe) + count($countSavoirsEx) ?>,
                    <?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countSavoirsJu) + count($countSavoirsSe) + count($countSavoirsEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countSavoirsJu) + count($countSavoirsSe) + count($countSavoirsEx) ?> QCM réalisés',
                    '<?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countSavoirsJu) + count($countSavoirsSe) + count($countSavoirsEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartFilialeCon');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            const completedPercentage = Math.round((chartDatas[i].completed / chartDatas[i].total) * 100);

            const card = document.createElement('div');
            card.classList.add('card');

            // Create and append the primary top title
            const topTitle = document.createElement('div');
            topTitle.classList.add('card-top-title');
            topTitle.textContent = `Total QCM à réaliser : ${chartDatas[i].total}`;
            card.appendChild(topTitle);

            // Create and append the secondary top title
            const secondaryTopTitle = document.createElement('div');
            secondaryTopTitle.classList.add('card-secondary-top-title');
            secondaryTopTitle.textContent = `Pourcentage complété : ${completedPercentage}%`;
            card.appendChild(secondaryTopTitle);

            // Create and append the canvas container
            const canvasContainer = document.createElement('div');
            canvasContainer.classList.add('card-canvas');

            const canvas = document.createElement('canvas');
            canvasContainer.appendChild(canvas);
            card.appendChild(canvasContainer);

            // Create and append the chart title
            const title = document.createElement('div');
            title.classList.add('card-title');
            title.textContent = chartDatas[i].title;
            card.appendChild(title);

            cardContainer.appendChild(card);

            // Initialize the Chart.js doughnut chart
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: chartDatas[i].labels, // Customize these labels
                    datasets: [{
                        data: chartDatas[i].data, // Customize these values
                        backgroundColor: chartDatas[i].backgroundColor, // Customize these colors
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                // Customize legend labels to include numbers
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: `${label}`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].borderColor[i],
                                        lineWidth: data.datasets[0].borderWidth,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
                                return percentage + '%';
                            },
                            color: '#fff',
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = tooltipItem.raw;
                                    const total = tooltipItem.chart.data.datasets[0].data.reduce((a,
                                        b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                                }
                            }
                        }
                    }
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Data for each chart
        const chartData = [{
                title: 'QCM Junior',
                total: <?php echo count($technicians) ?>,
                completed: <?php echo count($countMaSavFaisJu) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaisJu) ?>,
                    <?php echo (count($technicians)) - (count($countMaSavFaisJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countMaSavFaisJu) ?> QCM réalisés',
                    '<?php echo (count($technicians)) - (count($countMaSavFaisJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB ', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Senior',
                total: <?php echo count($techniciansSe) + count($techniciansEx) ?>,
                completed: <?php echo count($countMaSavFaisSe) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaisSe) ?>,
                    <?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countMaSavFaisSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countMaSavFaisSe) ?> QCM réalisés',
                    '<?php echo (count($techniciansSe) + count($techniciansEx)) - (count($countMaSavFaisSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB ', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Expert',
                total: <?php echo count($techniciansEx) ?>,
                completed: <?php echo count($countMaSavFaisEx) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaisEx) ?>,
                    <?php echo (count($techniciansEx)) - (count($countMaSavFaisEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countMaSavFaisEx) ?> QCM réalisés',
                    '<?php echo (count($techniciansEx)) - (count($countMaSavFaisEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB ', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2) ?>,
                completed: <?php echo count($countMaSavFaisJu) + count($countMaSavFaisSe) + count($countMaSavFaisEx) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaisJu) + count($countMaSavFaisSe) + count($countMaSavFaisEx) ?>,
                    <?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countMaSavFaisJu) + count($countMaSavFaisSe) + count($countMaSavFaisEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countMaSavFaisJu) + count($countMaSavFaisSe) + count($countMaSavFaisEx) ?> QCM réalisés',
                    '<?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countMaSavFaisJu) + count($countMaSavFaisSe) + count($countMaSavFaisEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB ', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartFilialeMan');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            const completedPercentage = Math.round((chartData[i].completed / chartData[i].total) * 100);

            const card = document.createElement('div');
            card.classList.add('card');

            // Create and append the primary top title
            const topTitle = document.createElement('div');
            topTitle.classList.add('card-top-title');
            topTitle.textContent = `Total QCM à réaliser : ${chartData[i].total}`;
            card.appendChild(topTitle);

            // Create and append the secondary top title
            const secondaryTopTitle = document.createElement('div');
            secondaryTopTitle.classList.add('card-secondary-top-title');
            secondaryTopTitle.textContent = `Pourcentage complété : ${completedPercentage}%`;
            card.appendChild(secondaryTopTitle);

            // Create and append the canvas container
            const canvasContainer = document.createElement('div');
            canvasContainer.classList.add('card-canvas');

            const canvas = document.createElement('canvas');
            canvasContainer.appendChild(canvas);
            card.appendChild(canvasContainer);

            // Create and append the chart title
            const title = document.createElement('div');
            title.classList.add('card-title');
            title.textContent = chartData[i].title;
            card.appendChild(title);

            cardContainer.appendChild(card);

            // Initialize the Chart.js doughnut chart
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: chartData[i].labels, // Customize these labels
                    datasets: [{
                        data: chartData[i].data, // Customize these values
                        backgroundColor: chartData[i].backgroundColor, // Customize these colors
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                // Customize legend labels to include numbers
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: `${label}`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].borderColor[i],
                                        lineWidth: data.datasets[0].borderWidth,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
                                return percentage + '%';
                            },
                            color: '#fff',
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = tooltipItem.raw;
                                    const total = tooltipItem.chart.data.datasets[0].data.reduce((a,
                                        b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                                }
                            }
                        }
                    }
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Data for each chart
        const chartDatas = [{
                title: 'QCM Junior',
                total: <?php echo count($technicians) ?>,
                completed: <?php echo count($countTechSavFaisJu) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaisJu) ?>,
                    <?php echo (count($technicians)) - (count($countTechSavFaisJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaisJu) ?> QCM réalisés',
                    '<?php echo (count($technicians)) - (count($countTechSavFaisJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Senior',
                total: <?php echo count($techniciansSe) + count($techniciansEx) ?>,
                completed: <?php echo count($countTechSavFaisSe) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaisSe) ?>,
                    <?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countTechSavFaisSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaisSe) ?> QCM réalisés',
                    '<?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countTechSavFaisSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Expert',
                total: <?php echo count($techniciansEx) ?>,
                completed: <?php echo count($countTechSavFaisEx) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaisEx) ?>,
                    <?php echo (count($techniciansEx)) - (count($countTechSavFaisEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaisEx) ?> QCM réalisés',
                    '<?php echo (count($techniciansEx)) - (count($countTechSavFaisEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2) ?>,
                completed: <?php echo count($countTechSavFaisJu) + count($countTechSavFaisSe) + count($countTechSavFaisEx) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaisJu) + count($countTechSavFaisSe) + count($countTechSavFaisEx) ?>,
                    <?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countTechSavFaisJu) + count($countTechSavFaisSe) + count($countTechSavFaisEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countTechSavFaisJu) + count($countTechSavFaisSe) + count($countTechSavFaisEx) ?> QCM réalisés',
                    '<?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countTechSavFaisJu) + count($countTechSavFaisSe) + count($countTechSavFaisEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartFilialeTech');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            const completedPercentage = Math.round((chartDatas[i].completed / chartDatas[i].total) * 100);

            const card = document.createElement('div');
            card.classList.add('card');

            // Create and append the primary top title
            const topTitle = document.createElement('div');
            topTitle.classList.add('card-top-title');
            topTitle.textContent = `Total QCM à réaliser : ${chartDatas[i].total}`;
            card.appendChild(topTitle);

            // Create and append the secondary top title
            const secondaryTopTitle = document.createElement('div');
            secondaryTopTitle.classList.add('card-secondary-top-title');
            secondaryTopTitle.textContent = `Pourcentage complété : ${completedPercentage}%`;
            card.appendChild(secondaryTopTitle);

            // Create and append the canvas container
            const canvasContainer = document.createElement('div');
            canvasContainer.classList.add('card-canvas');

            const canvas = document.createElement('canvas');
            canvasContainer.appendChild(canvas);
            card.appendChild(canvasContainer);

            // Create and append the chart title
            const title = document.createElement('div');
            title.classList.add('card-title');
            title.textContent = chartDatas[i].title;
            card.appendChild(title);

            cardContainer.appendChild(card);

            // Initialize the Chart.js doughnut chart
            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: chartDatas[i].labels, // Customize these labels
                    datasets: [{
                        data: chartDatas[i].data, // Customize these values
                        backgroundColor: chartDatas[i].backgroundColor, // Customize these colors
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                // Customize legend labels to include numbers
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: `${label}`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].borderColor[i],
                                        lineWidth: data.datasets[0].borderWidth,
                                        hidden: false
                                    }));
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / sum) * 100);
                                // Round up to the nearest whole number
                                return percentage + '%';
                            },
                            color: '#fff',
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const value = tooltipItem.raw;
                                    const total = tooltipItem.chart.data.datasets[0].data.reduce((a,
                                        b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `Nombre: ${value},\nPourcentage: ${percentage}%`;

                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<script>
if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> <= 60) {
    var colorJu = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> < 80) {
    if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> > 60) {
        var colorJu = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?> > 80) {
    var colorJu = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> <= 60) {
    var colorSe = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> < 80) {
    if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> > 60) {
        var colorSe = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?> > 80) {
    var colorSe = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> <= 60) {
    var colorEx = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> < 80) {
    if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> > 60) {
        var colorEx = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?> > 80) {
    var colorEx = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}

if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> <=
    60) {
    var color = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> <
    80) {
    if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> >
        60) {
        var color = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo (round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) + round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) + round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2)) / 3 ?> >
    80) {
    var color = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
document.addEventListener('DOMContentLoaded', function() {
    // Data for each chart
    const chartDataM = [{
            title: 'Résultat Niveau Junior',
            total: 100,
            completed: <?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>,
                100 -
                <?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: colorJu
        },
        {
            title: 'Résultat Niveau Senior',
            total: 100,
            completed: <?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>,
                100 -
                <?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: colorSe
        },
        {
            title: 'Résultat Niveau Expert',
            total: 100,
            completed: <?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>,
                100 -
                <?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: colorEx
        },
        {
            title: 'Total : 03 Niveaux',
            total: 100,
            completed: <?php echo round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>, // Moyenne des compétences acquises
            data: [<?php echo round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>,
                <?php echo 100 - round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: ['<?php echo round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>% des compétences acquises',
                '<?php echo 100 - round((($resultFacJu[0]["percentage"] + $resultDeclaJu[0]["percentage"]) / 2 + ($resultFacSe[0]["percentage"] + $resultDeclaSe[0]["percentage"]) / 2 + ($resultFacEx[0]["percentage"] + $resultDeclaEx[0]["percentage"]) / 2) / 3); ?>% des compétences à acquérir'
            ],
            backgroundColor: color
        }
    ];

    const containerM = document.getElementById('chartMoyenFiliale');

    // Loop through the data to create and append cards
    chartDataM.forEach((data, index) => {
        // Calculate the completed percentage
        const completedPercentage = Math.round((data.completed / data.total) * 100);

        // Create the card element
        const cardHtml = `
            <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                        <canvas id="doughnutChart${index}" width="200" height="200"></canvas>
                        <h5 class="mt-2">${data.title}</h5>
                    </div>
                </div>
            </div>
        `;

        // Append the card to the container
        containerM.insertAdjacentHTML('beforeend', cardHtml);

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
                            let percentage = Math.round((value / sum) *
                                100
                            ); // Round up to the nearest whole number
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
                                let percentage = Math.round((tooltipItem.raw / 100) * 100);
                                return `Compétences acquises: ${percentage}%`;
                            }
                        }
                    }
                }
            }
        });
    });
});
</script>
