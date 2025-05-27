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

    $i = 4;

    if($_SESSION["profile"] == "Super Admin") {
        $techniciansBam = [];
        $techsBam = $users->find([
            '$and' => [
                [
                    "agency" => "Bamako",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsBam as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansBam, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansBam, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuBam = [];
        $techsJuBam = $users->find([
            '$and' => [
                [
                    "agency" => "Bamako",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuBam as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuBam, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuBam, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeBam = [];
        $techsSeBam = $users->find([
            '$and' => [
                [
                    "agency" => "Bamako",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeBam as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeBam, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeBam, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExBam = [];
        $techsExBam = $users->find([
            '$and' => [
                [
                    "agency" => "Bamako",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExBam as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExBam, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExBam, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuBam = [];
        $countSavoirsJuBam = [];
        $countMaSavFaisJuBam = [];
        $countTechSavFaisJuBam = [];
        $testsSeBam = [];
        $countSavoirsSeBam = [];
        $countMaSavFaisSeBam = [];
        $countTechSavFaisSeBam = [];
        $testsExBam = [];
        $countSavoirsExBam = [];
        $countMaSavFaisExBam = [];
        $countTechSavFaisExBam = [];
        foreach ($techniciansBam as $technician) { 
            $allocateFacJuBam = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuBam = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuBam) && $allocateFacJuBam['active'] == true) {
                $countSavoirsJuBam[] = $allocateFacJuBam;
            }
            if (isset($allocateDeclaJuBam) && $allocateDeclaJuBam['activeManager'] == true) {
                $countMaSavFaisJuBam[] = $allocateDeclaJuBam;
            }
            if (isset($allocateDeclaJuBam) && $allocateDeclaJuBam['active'] == true) {
                $countTechSavFaisJuBam[] = $allocateDeclaJuBam;
            }
            if (isset($allocateFacJuBam) && isset($allocateDeclaJuBam) && $allocateFacJuBam['active'] == true && $allocateDeclaJuBam['active'] == true && $allocateDeclaJuBam['activeManager'] == true) {
                $testsJuBam[] = $technician;
            }
            $allocateFacSeBam = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeBam = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeBam) && $allocateFacSeBam['active'] == true) {
                $countSavoirsSeBam[] = $allocateFacSeBam;
            }
            if (isset($allocateDeclaSeBam) && $allocateDeclaSeBam['activeManager'] == true) {
                $countMaSavFaisSeBam[] = $allocateDeclaSeBam;
            }
            if (isset($allocateDeclaSeBam) && $allocateDeclaSeBam['active'] == true) {
                $countTechSavFaisSeBam[] = $allocateDeclaSeBam;
            }
            if (isset($allocateFacSeBam) && isset($allocateDeclaSeBam) && $allocateFacSeBam['active'] == true && $allocateDeclaSeBam['active'] == true && $allocateDeclaSeBam['activeManager'] == true) {
                $testsSeBam[] = $technician;
            }
            $allocateFacExBam = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExBam = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExBam) && $allocateFacExBam['active'] == true) {
                $countSavoirsExBam[] = $allocateFacExBam;
            }
            if (isset($allocateDeclaExBam) && $allocateDeclaExBam['activeManager'] == true) {
                $countMaSavFaisExBam[] = $allocateDeclaExBam;
            }
            if (isset($allocateDeclaExBam) && $allocateDeclaExBam['active'] == true) {
                $countTechSavFaisExBam[] = $allocateDeclaExBam;
            }
            if (isset($allocateFacExBam) && isset($allocateDeclaExBam) && $allocateFacExBam['active'] == true && $allocateDeclaExBam['active'] == true && $allocateDeclaExBam['activeManager'] == true) {
                $testsExBam[] = $technician;
            }
        }

        $techniciansBan = [];
        $techsBan = $users->find([
            '$and' => [
                [
                    "agency" => "Bangui",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsBan as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansBan, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansBan, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuBan = [];
        $techsJuBan = $users->find([
            '$and' => [
                [
                    "agency" => "Bangui",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuBan as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuBan, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuBan, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeBan = [];
        $techsSeBan = $users->find([
            '$and' => [
                [
                    "agency" => "Bangui",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeBan as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeBan, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeBan, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExBan = [];
        $techsExBan = $users->find([
            '$and' => [
                [
                    "agency" => "Bangui",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExBan as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExBan, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExBan, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuBan = [];
        $countSavoirsJuBan = [];
        $countMaSavFaisJuBan = [];
        $countTechSavFaisJuBan = [];
        $testsSeBan = [];
        $countSavoirsSeBan = [];
        $countMaSavFaisSeBan = [];
        $countTechSavFaisSeBan = [];
        $testsExBan = [];
        $countSavoirsExBan = [];
        $countMaSavFaisExBan = [];
        $countTechSavFaisExBan = [];
        foreach ($techniciansBan as $technician) { 
            $allocateFacJuBan = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuBan = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuBan) && $allocateFacJuBan['active'] == true) {
                $countSavoirsJuBan[] = $allocateFacJuBan;
            }
            if (isset($allocateDeclaJuBan) && $allocateDeclaJuBan['activeManager'] == true) {
                $countMaSavFaisJuBan[] = $allocateDeclaJuBan;
            }
            if (isset($allocateDeclaJuBan) && $allocateDeclaJuBan['active'] == true) {
                $countTechSavFaisJuBan[] = $allocateDeclaJuBan;
            }
            if (isset($allocateFacJuBan) && isset($allocateDeclaJuBan) && $allocateFacJuBan['active'] == true && $allocateDeclaJuBan['active'] == true && $allocateDeclaJuBan['activeManager'] == true) {
                $testsJuBan[] = $technician;
            }
            $allocateFacSeBan = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeBan = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeBan) && $allocateFacSeBan['active'] == true) {
                $countSavoirsSeBan[] = $allocateFacSeBan;
            }
            if (isset($allocateDeclaSeBan) && $allocateDeclaSeBan['activeManager'] == true) {
                $countMaSavFaisSeBan[] = $allocateDeclaSeBan;
            }
            if (isset($allocateDeclaSeBan) && $allocateDeclaSeBan['active'] == true) {
                $countTechSavFaisSeBan[] = $allocateDeclaSeBan;
            }
            if (isset($allocateFacSeBan) && isset($allocateDeclaSeBan) && $allocateFacSeBan['active'] == true && $allocateDeclaSeBan['active'] == true && $allocateDeclaSeBan['activeManager'] == true) {
                $testsSeBan[] = $technician;
            }
            $allocateFacExBan = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExBan = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExBan) && $allocateFacExBan['active'] == true) {
                $countSavoirsExBan[] = $allocateFacExBan;
            }
            if (isset($allocateDeclaExBan) && $allocateDeclaExBan['activeManager'] == true) {
                $countMaSavFaisExBan[] = $allocateDeclaExBan;
            }
            if (isset($allocateDeclaExBan) && $allocateDeclaExBan['active'] == true) {
                $countTechSavFaisExBan[] = $allocateDeclaExBan;
            }
            if (isset($allocateFacExBan) && isset($allocateDeclaExBan) && $allocateFacExBan['active'] == true && $allocateDeclaExBan['active'] == true && $allocateDeclaExBan['activeManager'] == true) {
                $testsExBan[] = $technician;
            }
        }
        
        $techniciansBer = [];
        $techsBer = $users->find([
            '$and' => [
                [
                    "agency" => "Bertoua",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsBer as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansBer, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansBer, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuBer = [];
        $techsJuBer = $users->find([
            '$and' => [
                [
                    "agency" => "Bertoua",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuBer as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuBer, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuBer, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeBer = [];
        $techsSeBer = $users->find([
            '$and' => [
                [
                    "agency" => "Bertoua",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeBer as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeBer, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeBer, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExBer = [];
        $techsExBer = $users->find([
            '$and' => [
                [
                    "agency" => "Bertoua",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExBer as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExBer, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExBer, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuBer = [];
        $countSavoirsJuBer = [];
        $countMaSavFaisJuBer = [];
        $countTechSavFaisJuBer = [];
        $testsSeBer = [];
        $countSavoirsSeBer = [];
        $countMaSavFaisSeBer = [];
        $countTechSavFaisSeBer = [];
        $testsExBer = [];
        $countSavoirsExBer = [];
        $countMaSavFaisExBer = [];
        $countTechSavFaisExBer = [];
        foreach ($techniciansBer as $technician) { 
            $allocateFacJuBer = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuBer = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuBer) && $allocateFacJuBer['active'] == true) {
                $countSavoirsJuBer[] = $allocateFacJuBer;
            }
            if (isset($allocateDeclaJuBer) && $allocateDeclaJuBer['activeManager'] == true) {
                $countMaSavFaisJuBer[] = $allocateDeclaJuBer;
            }
            if (isset($allocateDeclaJuBer) && $allocateDeclaJuBer['active'] == true) {
                $countTechSavFaisJuBer[] = $allocateDeclaJuBer;
            }
            if (isset($allocateFacJuBer) && isset($allocateDeclaJuBer) && $allocateFacJuBer['active'] == true && $allocateDeclaJuBer['active'] == true && $allocateDeclaJuBer['activeManager'] == true) {
                $testsJuBer[] = $technician;
            }
            $allocateFacSeBer = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeBer = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeBer) && $allocateFacSeBer['active'] == true) {
                $countSavoirsSeBer[] = $allocateFacSeBer;
            }
            if (isset($allocateDeclaSeBer) && $allocateDeclaSeBer['activeManager'] == true) {
                $countMaSavFaisSeBer[] = $allocateDeclaSeBer;
            }
            if (isset($allocateDeclaSeBer) && $allocateDeclaSeBer['active'] == true) {
                $countTechSavFaisSeBer[] = $allocateDeclaSeBer;
            }
            if (isset($allocateFacSeBer) && isset($allocateDeclaSeBer) && $allocateFacSeBer['active'] == true && $allocateDeclaSeBer['active'] == true && $allocateDeclaSeBer['activeManager'] == true) {
                $testsSeBer[] = $technician;
            }
            $allocateFacExBer = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExBer = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExBer) && $allocateFacExBer['active'] == true) {
                $countSavoirsExBer[] = $allocateFacExBer;
            }
            if (isset($allocateDeclaExBer) && $allocateDeclaExBer['activeManager'] == true) {
                $countMaSavFaisExBer[] = $allocateDeclaExBer;
            }
            if (isset($allocateDeclaExBer) && $allocateDeclaExBer['active'] == true) {
                $countTechSavFaisExBer[] = $allocateDeclaExBer;
            }
            if (isset($allocateFacExBer) && isset($allocateDeclaExBer) && $allocateFacExBer['active'] == true && $allocateDeclaExBer['active'] == true && $allocateDeclaExBer['activeManager'] == true) {
                $testsExBer[] = $technician;
            }
        }
        
        $techniciansDa = [];
        $techsDa = $users->find([
            '$and' => [
                [
                    "agency" => "Dakar",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsDa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansDa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansDa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuDa = [];
        $techsJuDa = $users->find([
            '$and' => [
                [
                    "agency" => "Dakar",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuDa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuDa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuDa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeDa = [];
        $techsSeDa = $users->find([
            '$and' => [
                [
                    "agency" => "Dakar",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeDa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeDa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeDa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExDa = [];
        $techsExDa = $users->find([
            '$and' => [
                [
                    "agency" => "Dakar",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExDa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExDa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExDa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuDa = [];
        $countSavoirsJuDa = [];
        $countMaSavFaisJuDa = [];
        $countTechSavFaisJuDa = [];
        $testsSeDa = [];
        $countSavoirsSeDa = [];
        $countMaSavFaisSeDa = [];
        $countTechSavFaisSeDa = [];
        $testsExDa = [];
        $countSavoirsExDa = [];
        $countMaSavFaisExDa = [];
        $countTechSavFaisExDa = [];
        foreach ($techniciansDa as $technician) { 
            $allocateFacJuDa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuDa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuDa) && $allocateFacJuDa['active'] == true) {
                $countSavoirsJuDa[] = $allocateFacJuDa;
            }
            if (isset($allocateDeclaJuDa) && $allocateDeclaJuDa['activeManager'] == true) {
                $countMaSavFaisJuDa[] = $allocateDeclaJuDa;
            }
            if (isset($allocateDeclaJuDa) && $allocateDeclaJuDa['active'] == true) {
                $countTechSavFaisJuDa[] = $allocateDeclaJuDa;
            }
            if (isset($allocateFacJuDa) && isset($allocateDeclaJuDa) && $allocateFacJuDa['active'] == true && $allocateDeclaJuDa['active'] == true && $allocateDeclaJuDa['activeManager'] == true) {
                $testsJuDa[] = $technician;
            }
            $allocateFacSeDa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeDa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeDa) && $allocateFacSeDa['active'] == true) {
                $countSavoirsSeDa[] = $allocateFacSeDa;
            }
            if (isset($allocateDeclaSeDa) && $allocateDeclaSeDa['activeManager'] == true) {
                $countMaSavFaisSeDa[] = $allocateDeclaSeDa;
            }
            if (isset($allocateDeclaSeDa) && $allocateDeclaSeDa['active'] == true) {
                $countTechSavFaisSeDa[] = $allocateDeclaSeDa;
            }
            if (isset($allocateFacSeDa) && isset($allocateDeclaSeDa) && $allocateFacSeDa['active'] == true && $allocateDeclaSeDa['active'] == true && $allocateDeclaSeDa['activeManager'] == true) {
                $testsSeDa[] = $technician;
            }
            $allocateFacExDa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExDa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExDa) && $allocateFacExDa['active'] == true) {
                $countSavoirsExDa[] = $allocateFacExDa;
            }
            if (isset($allocateDeclaExDa) && $allocateDeclaExDa['activeManager'] == true) {
                $countMaSavFaisExDa[] = $allocateDeclaExDa;
            }
            if (isset($allocateDeclaExDa) && $allocateDeclaExDa['active'] == true) {
                $countTechSavFaisExDa[] = $allocateDeclaExDa;
            }
            if (isset($allocateFacExDa) && isset($allocateDeclaExDa) && $allocateFacExDa['active'] == true && $allocateDeclaExDa['active'] == true && $allocateDeclaExDa['activeManager'] == true) {
                $testsExDa[] = $technician;
            }
        }
        
        $techniciansDo = [];
        $techsDo = $users->find([
            '$and' => [
                [
                    "agency" => "Douala",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsDo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansDo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansDo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuDo = [];
        $techsJuDo = $users->find([
            '$and' => [
                [
                    "agency" => "Douala",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuDo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuDo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuDo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeDo = [];
        $techsSeDo = $users->find([
            '$and' => [
                [
                    "agency" => "Douala",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeDo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeDo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeDo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExDo = [];
        $techsExDo = $users->find([
            '$and' => [
                [
                    "agency" => "Douala",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExDo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExDo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExDo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuDo = [];
        $countSavoirsJuDo = [];
        $countMaSavFaisJuDo = [];
        $countTechSavFaisJuDo = [];
        $testsSeDo = [];
        $countSavoirsSeDo = [];
        $countMaSavFaisSeDo = [];
        $countTechSavFaisSeDo = [];
        $testsExDo = [];
        $countSavoirsExDo = [];
        $countMaSavFaisExDo = [];
        $countTechSavFaisExDo = [];
        foreach ($techniciansDo as $technician) { 
            $allocateFacJuDo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuDo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuDo) && $allocateFacJuDo['active'] == true) {
                $countSavoirsJuDo[] = $allocateFacJuDo;
            }
            if (isset($allocateDeclaJuDo) && $allocateDeclaJuDo['activeManager'] == true) {
                $countMaSavFaisJuDo[] = $allocateDeclaJuDo;
            }
            if (isset($allocateDeclaJuDo) && $allocateDeclaJuDo['active'] == true) {
                $countTechSavFaisJuDo[] = $allocateDeclaJuDo;
            }
            if (isset($allocateFacJuDo) && isset($allocateDeclaJuDo) && $allocateFacJuDo['active'] == true && $allocateDeclaJuDo['active'] == true && $allocateDeclaJuDo['activeManager'] == true) {
                $testsJuDo[] = $technician;
            }
            $allocateFacSeDo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeDo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeDo) && $allocateFacSeDo['active'] == true) {
                $countSavoirsSeDo[] = $allocateFacSeDo;
            }
            if (isset($allocateDeclaSeDo) && $allocateDeclaSeDo['activeManager'] == true) {
                $countMaSavFaisSeDo[] = $allocateDeclaSeDo;
            }
            if (isset($allocateDeclaSeDo) && $allocateDeclaSeDo['active'] == true) {
                $countTechSavFaisSeDo[] = $allocateDeclaSeDo;
            }
            if (isset($allocateFacSeDo) && isset($allocateDeclaSeDo) && $allocateFacSeDo['active'] == true && $allocateDeclaSeDo['active'] == true && $allocateDeclaSeDo['activeManager'] == true) {
                $testsSeDo[] = $technician;
            }
            $allocateFacExDo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExDo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExDo) && $allocateFacExDo['active'] == true) {
                $countSavoirsExDo[] = $allocateFacExDo;
            }
            if (isset($allocateDeclaExDo) && $allocateDeclaExDo['activeManager'] == true) {
                $countMaSavFaisExDo[] = $allocateDeclaExDo;
            }
            if (isset($allocateDeclaExDo) && $allocateDeclaExDo['active'] == true) {
                $countTechSavFaisExDo[] = $allocateDeclaExDo;
            }
            if (isset($allocateFacExDo) && isset($allocateDeclaExDo) && $allocateFacExDo['active'] == true && $allocateDeclaExDo['active'] == true && $allocateDeclaExDo['activeManager'] == true) {
                $testsExDo[] = $technician;
            }
        }
        
        $techniciansGa = [];
        $techsGa = $users->find([
            '$and' => [
                [
                    "agency" => "Garoua",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsGa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansGa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansGa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuGa = [];
        $techsJuGa = $users->find([
            '$and' => [
                [
                    "agency" => "Garoua",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuGa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuGa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuGa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeGa = [];
        $techsSeGa = $users->find([
            '$and' => [
                [
                    "agency" => "Garoua",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeGa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeGa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeGa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExGa = [];
        $techsExGa = $users->find([
            '$and' => [
                [
                    "agency" => "Garoua",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExGa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExGa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExGa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuGa = [];
        $countSavoirsJuGa = [];
        $countMaSavFaisJuGa = [];
        $countTechSavFaisJuGa = [];
        $testsSeGa = [];
        $countSavoirsSeGa = [];
        $countMaSavFaisSeGa = [];
        $countTechSavFaisSeGa = [];
        $testsExGa = [];
        $countSavoirsExGa = [];
        $countMaSavFaisExGa = [];
        $countTechSavFaisExGa = [];
        foreach ($techniciansGa as $technician) { 
            $allocateFacJuGa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuGa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuGa) && $allocateFacJuGa['active'] == true) {
                $countSavoirsJuGa[] = $allocateFacJuGa;
            }
            if (isset($allocateDeclaJuGa) && $allocateDeclaJuGa['activeManager'] == true) {
                $countMaSavFaisJuGa[] = $allocateDeclaJuGa;
            }
            if (isset($allocateDeclaJuGa) && $allocateDeclaJuGa['active'] == true) {
                $countTechSavFaisJuGa[] = $allocateDeclaJuGa;
            }
            if (isset($allocateFacJuGa) && isset($allocateDeclaJuGa) && $allocateFacJuGa['active'] == true && $allocateDeclaJuGa['active'] == true && $allocateDeclaJuGa['activeManager'] == true) {
                $testsJuGa[] = $technician;
            }
            $allocateFacSeGa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeGa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeGa) && $allocateFacSeGa['active'] == true) {
                $countSavoirsSeGa[] = $allocateFacSeGa;
            }
            if (isset($allocateDeclaSeGa) && $allocateDeclaSeGa['activeManager'] == true) {
                $countMaSavFaisSeGa[] = $allocateDeclaSeGa;
            }
            if (isset($allocateDeclaSeGa) && $allocateDeclaSeGa['active'] == true) {
                $countTechSavFaisSeGa[] = $allocateDeclaSeGa;
            }
            if (isset($allocateFacSeGa) && isset($allocateDeclaSeGa) && $allocateFacSeGa['active'] == true && $allocateDeclaSeGa['active'] == true && $allocateDeclaSeGa['activeManager'] == true) {
                $testsSeGa[] = $technician;
            }
            $allocateFacExGa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExGa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExGa) && $allocateFacExGa['active'] == true) {
                $countSavoirsExGa[] = $allocateFacExGa;
            }
            if (isset($allocateDeclaExGa) && $allocateDeclaExGa['activeManager'] == true) {
                $countMaSavFaisExGa[] = $allocateDeclaExGa;
            }
            if (isset($allocateDeclaExGa) && $allocateDeclaExGa['active'] == true) {
                $countTechSavFaisExGa[] = $allocateDeclaExGa;
            }
            if (isset($allocateFacExGa) && isset($allocateDeclaExGa) && $allocateFacExGa['active'] == true && $allocateDeclaExGa['active'] == true && $allocateDeclaExGa['activeManager'] == true) {
                $testsExGa[] = $technician;
            }
        }
        
        $techniciansKi = [];
        $techsKi = $users->find([
            '$and' => [
                [
                    "agency" => "Kinshasa",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsKi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansKi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansKi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuKi = [];
        $techsJuKi = $users->find([
            '$and' => [
                [
                    "agency" => "Kinshasa",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuKi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuKi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuKi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeKi = [];
        $techsSeKi = $users->find([
            '$and' => [
                [
                    "agency" => "Kinshasa",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeKi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeKi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeKi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExKi = [];
        $techsExKi = $users->find([
            '$and' => [
                [
                    "agency" => "Kinshasa",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExKi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExKi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExKi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuKi = [];
        $countSavoirsJuKi = [];
        $countMaSavFaisJuKi = [];
        $countTechSavFaisJuKi = [];
        $testsSeKi = [];
        $countSavoirsSeKi = [];
        $countMaSavFaisSeKi = [];
        $countTechSavFaisSeKi = [];
        $testsExKi = [];
        $countSavoirsExKi = [];
        $countMaSavFaisExKi = [];
        $countTechSavFaisExKi = [];
        foreach ($techniciansKi as $technician) { 
            $allocateFacJuKi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuKi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuKi) && $allocateFacJuKi['active'] == true) {
                $countSavoirsJuKi[] = $allocateFacJuKi;
            }
            if (isset($allocateDeclaJuKi) && $allocateDeclaJuKi['activeManager'] == true) {
                $countMaSavFaisJuKi[] = $allocateDeclaJuKi;
            }
            if (isset($allocateDeclaJuKi) && $allocateDeclaJuKi['active'] == true) {
                $countTechSavFaisJuKi[] = $allocateDeclaJuKi;
            }
            if (isset($allocateFacJuKi) && isset($allocateDeclaJuKi) && $allocateFacJuKi['active'] == true && $allocateDeclaJuKi['active'] == true && $allocateDeclaJuKi['activeManager'] == true) {
                $testsJuKi[] = $technician;
            }
            $allocateFacSeKi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeKi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeKi) && $allocateFacSeKi['active'] == true) {
                $countSavoirsSeKi[] = $allocateFacSeKi;
            }
            if (isset($allocateDeclaSeKi) && $allocateDeclaSeKi['activeManager'] == true) {
                $countMaSavFaisSeKi[] = $allocateDeclaSeKi;
            }
            if (isset($allocateDeclaSeKi) && $allocateDeclaSeKi['active'] == true) {
                $countTechSavFaisSeKi[] = $allocateDeclaSeKi;
            }
            if (isset($allocateFacSeKi) && isset($allocateDeclaSeKi) && $allocateFacSeKi['active'] == true && $allocateDeclaSeKi['active'] == true && $allocateDeclaSeKi['activeManager'] == true) {
                $testsSeKi[] = $technician;
            }
            $allocateFacExKi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExKi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExKi) && $allocateFacExKi['active'] == true) {
                $countSavoirsExKi[] = $allocateFacExKi;
            }
            if (isset($allocateDeclaExKi) && $allocateDeclaExKi['activeManager'] == true) {
                $countMaSavFaisExKi[] = $allocateDeclaExKi;
            }
            if (isset($allocateDeclaExKi) && $allocateDeclaExKi['active'] == true) {
                $countTechSavFaisExKi[] = $allocateDeclaExKi;
            }
            if (isset($allocateFacExKi) && isset($allocateDeclaExKi) && $allocateFacExKi['active'] == true && $allocateDeclaExKi['active'] == true && $allocateDeclaExKi['activeManager'] == true) {
                $testsExKi[] = $technician;
            }
        }
        
        $techniciansKo = [];
        $techsKo = $users->find([
            '$and' => [
                [
                    "agency" => "Kolwezi",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsKo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansKo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansKo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuKo = [];
        $techsJuKo = $users->find([
            '$and' => [
                [
                    "agency" => "Kolwezi",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuKo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuKo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuKo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeKo = [];
        $techsSeKo = $users->find([
            '$and' => [
                [
                    "agency" => "Kolwezi",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeKo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeKo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeKo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExKo = [];
        $techsExKo = $users->find([
            '$and' => [
                [
                    "agency" => "Kolwezi",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExKo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExKo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExKo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuKo = [];
        $countSavoirsJuKo = [];
        $countMaSavFaisJuKo = [];
        $countTechSavFaisJuKo = [];
        $testsSeKo = [];
        $countSavoirsSeKo = [];
        $countMaSavFaisSeKo = [];
        $countTechSavFaisSeKo = [];
        $testsExKo = [];
        $countSavoirsExKo = [];
        $countMaSavFaisExKo = [];
        $countTechSavFaisExKo = [];
        foreach ($techniciansKo as $technician) { 
            $allocateFacJuKo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuKo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuKo) && $allocateFacJuKo['active'] == true) {
                $countSavoirsJuKo[] = $allocateFacJuKo;
            }
            if (isset($allocateDeclaJuKo) && $allocateDeclaJuKo['activeManager'] == true) {
                $countMaSavFaisJuKo[] = $allocateDeclaJuKo;
            }
            if (isset($allocateDeclaJuKo) && $allocateDeclaJuKo['active'] == true) {
                $countTechSavFaisJuKo[] = $allocateDeclaJuKo;
            }
            if (isset($allocateFacJuKo) && isset($allocateDeclaJuKo) && $allocateFacJuKo['active'] == true && $allocateDeclaJuKo['active'] == true && $allocateDeclaJuKo['activeManager'] == true) {
                $testsJuKo[] = $technician;
            }
            $allocateFacSeKo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeKo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeKo) && $allocateFacSeKo['active'] == true) {
                $countSavoirsSeKo[] = $allocateFacSeKo;
            }
            if (isset($allocateDeclaSeKo) && $allocateDeclaSeKo['activeManager'] == true) {
                $countMaSavFaisSeKo[] = $allocateDeclaSeKo;
            }
            if (isset($allocateDeclaSeKo) && $allocateDeclaSeKo['active'] == true) {
                $countTechSavFaisSeKo[] = $allocateDeclaSeKo;
            }
            if (isset($allocateFacSeKo) && isset($allocateDeclaSeKo) && $allocateFacSeKo['active'] == true && $allocateDeclaSeKo['active'] == true && $allocateDeclaSeKo['activeManager'] == true) {
                $testsSeKo[] = $technician;
            }
            $allocateFacExKo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExKo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExKo) && $allocateFacExKo['active'] == true) {
                $countSavoirsExKo[] = $allocateFacExKo;
            }
            if (isset($allocateDeclaExKo) && $allocateDeclaExKo['activeManager'] == true) {
                $countMaSavFaisExKo[] = $allocateDeclaExKo;
            }
            if (isset($allocateDeclaExKo) && $allocateDeclaExKo['active'] == true) {
                $countTechSavFaisExKo[] = $allocateDeclaExKo;
            }
            if (isset($allocateFacExKo) && isset($allocateDeclaExKo) && $allocateFacExKo['active'] == true && $allocateDeclaExKo['active'] == true && $allocateDeclaExKo['activeManager'] == true) {
                $testsExKo[] = $technician;
            }
        }
        
        $techniciansLi = [];
        $techsLi = $users->find([
            '$and' => [
                [
                    "agency" => "Libreville",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsLi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansLi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansLi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuLi = [];
        $techsJuLi = $users->find([
            '$and' => [
                [
                    "agency" => "Libreville",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuLi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuLi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuLi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeLi = [];
        $techsSeLi = $users->find([
            '$and' => [
                [
                    "agency" => "Libreville",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeLi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeLi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeLi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExLi = [];
        $techsExLi = $users->find([
            '$and' => [
                [
                    "agency" => "Libreville",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExLi as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExLi, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExLi, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuLi = [];
        $countSavoirsJuLi = [];
        $countMaSavFaisJuLi = [];
        $countTechSavFaisJuLi = [];
        $testsSeLi = [];
        $countSavoirsSeLi = [];
        $countMaSavFaisSeLi = [];
        $countTechSavFaisSeLi = [];
        $testsExLi = [];
        $countSavoirsExLi = [];
        $countMaSavFaisExLi = [];
        $countTechSavFaisExLi = [];
        foreach ($techniciansLi as $technician) { 
            $allocateFacJuLi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuLi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuLi) && $allocateFacJuLi['active'] == true) {
                $countSavoirsJuLi[] = $allocateFacJuLi;
            }
            if (isset($allocateDeclaJuLi) && $allocateDeclaJuLi['activeManager'] == true) {
                $countMaSavFaisJuLi[] = $allocateDeclaJuLi;
            }
            if (isset($allocateDeclaJuLi) && $allocateDeclaJuLi['active'] == true) {
                $countTechSavFaisJuLi[] = $allocateDeclaJuLi;
            }
            if (isset($allocateFacJuLi) && isset($allocateDeclaJuLi) && $allocateFacJuLi['active'] == true && $allocateDeclaJuLi['active'] == true && $allocateDeclaJuLi['activeManager'] == true) {
                $testsJuLi[] = $technician;
            }
            $allocateFacSeLi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeLi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeLi) && $allocateFacSeLi['active'] == true) {
                $countSavoirsSeLi[] = $allocateFacSeLi;
            }
            if (isset($allocateDeclaSeLi) && $allocateDeclaSeLi['activeManager'] == true) {
                $countMaSavFaisSeLi[] = $allocateDeclaSeLi;
            }
            if (isset($allocateDeclaSeLi) && $allocateDeclaSeLi['active'] == true) {
                $countTechSavFaisSeLi[] = $allocateDeclaSeLi;
            }
            if (isset($allocateFacSeLi) && isset($allocateDeclaSeLi) && $allocateFacSeLi['active'] == true && $allocateDeclaSeLi['active'] == true && $allocateDeclaSeLi['activeManager'] == true) {
                $testsSeLi[] = $technician;
            }
            $allocateFacExLi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExLi = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExLi) && $allocateFacExLi['active'] == true) {
                $countSavoirsExLi[] = $allocateFacExLi;
            }
            if (isset($allocateDeclaExLi) && $allocateDeclaExLi['activeManager'] == true) {
                $countMaSavFaisExLi[] = $allocateDeclaExLi;
            }
            if (isset($allocateDeclaExLi) && $allocateDeclaExLi['active'] == true) {
                $countTechSavFaisExLi[] = $allocateDeclaExLi;
            }
            if (isset($allocateFacExLi) && isset($allocateDeclaExLi) && $allocateFacExLi['active'] == true && $allocateDeclaExLi['active'] == true && $allocateDeclaExLi['activeManager'] == true) {
                $testsExLi[] = $technician;
            }
        }
        
        $techniciansLu = [];
        $techsLu = $users->find([
            '$and' => [
                [
                    "agency" => "Lubumbashi",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsLu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansLu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansLu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuLu = [];
        $techsJuLu = $users->find([
            '$and' => [
                [
                    "agency" => "Lubumbashi",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuLu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuLu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuLu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeLu = [];
        $techsSeLu = $users->find([
            '$and' => [
                [
                    "agency" => "Lubumbashi",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeLu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeLu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeLu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExLu = [];
        $techsExLu = $users->find([
            '$and' => [
                [
                    "agency" => "Lubumbashi",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExLu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExLu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExLu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuLu = [];
        $countSavoirsJuLu = [];
        $countMaSavFaisJuLu = [];
        $countTechSavFaisJuLu = [];
        $testsSeLu = [];
        $countSavoirsSeLu = [];
        $countMaSavFaisSeLu = [];
        $countTechSavFaisSeLu = [];
        $testsExLu = [];
        $countSavoirsExLu = [];
        $countMaSavFaisExLu = [];
        $countTechSavFaisExLu = [];
        foreach ($techniciansLu as $technician) { 
            $allocateFacJuLu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuLu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuLu) && $allocateFacJuLu['active'] == true) {
                $countSavoirsJuLu[] = $allocateFacJuLu;
            }
            if (isset($allocateDeclaJuLu) && $allocateDeclaJuLu['activeManager'] == true) {
                $countMaSavFaisJuLu[] = $allocateDeclaJuLu;
            }
            if (isset($allocateDeclaJuLu) && $allocateDeclaJuLu['active'] == true) {
                $countTechSavFaisJuLu[] = $allocateDeclaJuLu;
            }
            if (isset($allocateFacJuLu) && isset($allocateDeclaJuLu) && $allocateFacJuLu['active'] == true && $allocateDeclaJuLu['active'] == true && $allocateDeclaJuLu['activeManager'] == true) {
                $testsJuLu[] = $technician;
            }
            $allocateFacSeLu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeLu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeLu) && $allocateFacSeLu['active'] == true) {
                $countSavoirsSeLu[] = $allocateFacSeLu;
            }
            if (isset($allocateDeclaSeLu) && $allocateDeclaSeLu['activeManager'] == true) {
                $countMaSavFaisSeLu[] = $allocateDeclaSeLu;
            }
            if (isset($allocateDeclaSeLu) && $allocateDeclaSeLu['active'] == true) {
                $countTechSavFaisSeLu[] = $allocateDeclaSeLu;
            }
            if (isset($allocateFacSeLu) && isset($allocateDeclaSeLu) && $allocateFacSeLu['active'] == true && $allocateDeclaSeLu['active'] == true && $allocateDeclaSeLu['activeManager'] == true) {
                $testsSeLu[] = $technician;
            }
            $allocateFacExLu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExLu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExLu) && $allocateFacExLu['active'] == true) {
                $countSavoirsExLu[] = $allocateFacExLu;
            }
            if (isset($allocateDeclaExLu) && $allocateDeclaExLu['activeManager'] == true) {
                $countMaSavFaisExLu[] = $allocateDeclaExLu;
            }
            if (isset($allocateDeclaExLu) && $allocateDeclaExLu['active'] == true) {
                $countTechSavFaisExLu[] = $allocateDeclaExLu;
            }
            if (isset($allocateFacExLu) && isset($allocateDeclaExLu) && $allocateFacExLu['active'] == true && $allocateDeclaExLu['active'] == true && $allocateDeclaExLu['activeManager'] == true) {
                $testsExLu[] = $technician;
            }
        }
        
        $techniciansNg = [];
        $techsNg = $users->find([
            '$and' => [
                [
                    "agency" => "Ngaoundere",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsNg as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansNg, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansNg, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuNg = [];
        $techsJuNg = $users->find([
            '$and' => [
                [
                    "agency" => "Ngaoundere",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuNg as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuNg, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuNg, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeNg = [];
        $techsSeNg = $users->find([
            '$and' => [
                [
                    "agency" => "Ngaoundere",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeNg as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeNg, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeNg, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExNg = [];
        $techsExNg = $users->find([
            '$and' => [
                [
                    "agency" => "Ngaoundere",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExNg as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExNg, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExNg, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuNg = [];
        $countSavoirsJuNg = [];
        $countMaSavFaisJuNg = [];
        $countTechSavFaisJuNg = [];
        $testsSeNg = [];
        $countSavoirsSeNg = [];
        $countMaSavFaisSeNg = [];
        $countTechSavFaisSeNg = [];
        $testsExNg = [];
        $countSavoirsExNg = [];
        $countMaSavFaisExNg = [];
        $countTechSavFaisExNg = [];
        foreach ($techniciansNg as $technician) { 
            $allocateFacJuNg = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuNg = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuNg) && $allocateFacJuNg['active'] == true) {
                $countSavoirsJuNg[] = $allocateFacJuNg;
            }
            if (isset($allocateDeclaJuNg) && $allocateDeclaJuNg['activeManager'] == true) {
                $countMaSavFaisJuNg[] = $allocateDeclaJuNg;
            }
            if (isset($allocateDeclaJuNg) && $allocateDeclaJuNg['active'] == true) {
                $countTechSavFaisJuNg[] = $allocateDeclaJuNg;
            }
            if (isset($allocateFacJuNg) && isset($allocateDeclaJuNg) && $allocateFacJuNg['active'] == true && $allocateDeclaJuNg['active'] == true && $allocateDeclaJuNg['activeManager'] == true) {
                $testsJuNg[] = $technician;
            }
            $allocateFacSeNg = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeNg = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeNg) && $allocateFacSeNg['active'] == true) {
                $countSavoirsSeNg[] = $allocateFacSeNg;
            }
            if (isset($allocateDeclaSeNg) && $allocateDeclaSeNg['activeManager'] == true) {
                $countMaSavFaisSeNg[] = $allocateDeclaSeNg;
            }
            if (isset($allocateDeclaSeNg) && $allocateDeclaSeNg['active'] == true) {
                $countTechSavFaisSeNg[] = $allocateDeclaSeNg;
            }
            if (isset($allocateFacSeNg) && isset($allocateDeclaSeNg) && $allocateFacSeNg['active'] == true && $allocateDeclaSeNg['active'] == true && $allocateDeclaSeNg['activeManager'] == true) {
                $testsSeNg[] = $technician;
            }
            $allocateFacExNg = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExNg = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExNg) && $allocateFacExNg['active'] == true) {
                $countSavoirsExNg[] = $allocateFacExNg;
            }
            if (isset($allocateDeclaExNg) && $allocateDeclaExNg['activeManager'] == true) {
                $countMaSavFaisExNg[] = $allocateDeclaExNg;
            }
            if (isset($allocateDeclaExNg) && $allocateDeclaExNg['active'] == true) {
                $countTechSavFaisExNg[] = $allocateDeclaExNg;
            }
            if (isset($allocateFacExNg) && isset($allocateDeclaExNg) && $allocateFacExNg['active'] == true && $allocateDeclaExNg['active'] == true && $allocateDeclaExNg['activeManager'] == true) {
                $testsExNg[] = $technician;
            }
        }
        
        $techniciansOu = [];
        $techsOu = $users->find([
            '$and' => [
                [
                    "agency" => "Ouaga",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsOu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansOu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansOu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuOu = [];
        $techsJuOu = $users->find([
            '$and' => [
                [
                    "agency" => "Ouaga",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuOu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuOu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuOu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeOu = [];
        $techsSeOu = $users->find([
            '$and' => [
                [
                    "agency" => "Ouaga",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeOu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeOu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeOu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExOu = [];
        $techsExOu = $users->find([
            '$and' => [
                [
                    "agency" => "Ouaga",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExOu as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExOu, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExOu, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuOu = [];
        $countSavoirsJuOu = [];
        $countMaSavFaisJuOu = [];
        $countTechSavFaisJuOu = [];
        $testsSeOu = [];
        $countSavoirsSeOu = [];
        $countMaSavFaisSeOu = [];
        $countTechSavFaisSeOu = [];
        $testsExOu = [];
        $countSavoirsExOu = [];
        $countMaSavFaisExOu = [];
        $countTechSavFaisExOu = [];
        foreach ($techniciansOu as $technician) { 
            $allocateFacJuOu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuOu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuOu) && $allocateFacJuOu['active'] == true) {
                $countSavoirsJuOu[] = $allocateFacJuOu;
            }
            if (isset($allocateDeclaJuOu) && $allocateDeclaJuOu['activeManager'] == true) {
                $countMaSavFaisJuOu[] = $allocateDeclaJuOu;
            }
            if (isset($allocateDeclaJuOu) && $allocateDeclaJuOu['active'] == true) {
                $countTechSavFaisJuOu[] = $allocateDeclaJuOu;
            }
            if (isset($allocateFacJuOu) && isset($allocateDeclaJuOu) && $allocateFacJuOu['active'] == true && $allocateDeclaJuOu['active'] == true && $allocateDeclaJuOu['activeManager'] == true) {
                $testsJuOu[] = $technician;
            }
            $allocateFacSeOu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeOu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeOu) && $allocateFacSeOu['active'] == true) {
                $countSavoirsSeOu[] = $allocateFacSeOu;
            }
            if (isset($allocateDeclaSeOu) && $allocateDeclaSeOu['activeManager'] == true) {
                $countMaSavFaisSeOu[] = $allocateDeclaSeOu;
            }
            if (isset($allocateDeclaSeOu) && $allocateDeclaSeOu['active'] == true) {
                $countTechSavFaisSeOu[] = $allocateDeclaSeOu;
            }
            if (isset($allocateFacSeOu) && isset($allocateDeclaSeOu) && $allocateFacSeOu['active'] == true && $allocateDeclaSeOu['active'] == true && $allocateDeclaSeOu['activeManager'] == true) {
                $testsSeOu[] = $technician;
            }
            $allocateFacExOu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExOu = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExOu) && $allocateFacExOu['active'] == true) {
                $countSavoirsExOu[] = $allocateFacExOu;
            }
            if (isset($allocateDeclaExOu) && $allocateDeclaExOu['activeManager'] == true) {
                $countMaSavFaisExOu[] = $allocateDeclaExOu;
            }
            if (isset($allocateDeclaExOu) && $allocateDeclaExOu['active'] == true) {
                $countTechSavFaisExOu[] = $allocateDeclaExOu;
            }
            if (isset($allocateFacExOu) && isset($allocateDeclaExOu) && $allocateFacExOu['active'] == true && $allocateDeclaExOu['active'] == true && $allocateDeclaExOu['activeManager'] == true) {
                $testsExOu[] = $technician;
            }
        }
        
        $techniciansPo = [];
        $techsPo = $users->find([
            '$and' => [
                [
                    "agency" => "Pointe Noire",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsPo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansPo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansPo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuPo = [];
        $techsJuPo = $users->find([
            '$and' => [
                [
                    "agency" => "Pointe Noire",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuPo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuPo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuPo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSePo = [];
        $techsSePo = $users->find([
            '$and' => [
                [
                    "agency" => "Pointe Noire",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSePo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSePo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSePo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExPo = [];
        $techsExPo = $users->find([
            '$and' => [
                [
                    "agency" => "Pointe Noire",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExPo as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExPo, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExPo, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuPo = [];
        $countSavoirsJuPo = [];
        $countMaSavFaisJuPo = [];
        $countTechSavFaisJuPo = [];
        $testsSePo = [];
        $countSavoirsSePo = [];
        $countMaSavFaisSePo = [];
        $countTechSavFaisSePo = [];
        $testsExPo = [];
        $countSavoirsExPo = [];
        $countMaSavFaisExPo = [];
        $countTechSavFaisExPo = [];
        foreach ($techniciansPo as $technician) { 
            $allocateFacJuPo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuPo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuPo) && $allocateFacJuPo['active'] == true) {
                $countSavoirsJuPo[] = $allocateFacJuPo;
            }
            if (isset($allocateDeclaJuPo) && $allocateDeclaJuPo['activeManager'] == true) {
                $countMaSavFaisJuPo[] = $allocateDeclaJuPo;
            }
            if (isset($allocateDeclaJuPo) && $allocateDeclaJuPo['active'] == true) {
                $countTechSavFaisJuPo[] = $allocateDeclaJuPo;
            }
            if (isset($allocateFacJuPo) && isset($allocateDeclaJuPo) && $allocateFacJuPo['active'] == true && $allocateDeclaJuPo['active'] == true && $allocateDeclaJuPo['activeManager'] == true) {
                $testsJuPo[] = $technician;
            }
            $allocateFacSePo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSePo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSePo) && $allocateFacSePo['active'] == true) {
                $countSavoirsSePo[] = $allocateFacSePo;
            }
            if (isset($allocateDeclaSePo) && $allocateDeclaSePo['activeManager'] == true) {
                $countMaSavFaisSePo[] = $allocateDeclaSePo;
            }
            if (isset($allocateDeclaSePo) && $allocateDeclaSePo['active'] == true) {
                $countTechSavFaisSePo[] = $allocateDeclaSePo;
            }
            if (isset($allocateFacSePo) && isset($allocateDeclaSePo) && $allocateFacSePo['active'] == true && $allocateDeclaSePo['active'] == true && $allocateDeclaSePo['activeManager'] == true) {
                $testsSePo[] = $technician;
            }
            $allocateFacExPo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExPo = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExPo) && $allocateFacExPo['active'] == true) {
                $countSavoirsExPo[] = $allocateFacExPo;
            }
            if (isset($allocateDeclaExPo) && $allocateDeclaExPo['activeManager'] == true) {
                $countMaSavFaisExPo[] = $allocateDeclaExPo;
            }
            if (isset($allocateDeclaExPo) && $allocateDeclaExPo['active'] == true) {
                $countTechSavFaisExPo[] = $allocateDeclaExPo;
            }
            if (isset($allocateFacExPo) && isset($allocateDeclaExPo) && $allocateFacExPo['active'] == true && $allocateDeclaExPo['active'] == true && $allocateDeclaExPo['activeManager'] == true) {
                $testsExPo[] = $technician;
            }
        }
        
        $techniciansVr = [];
        $techsVr = $users->find([
            '$and' => [
                [
                    "agency" => "Vridi - Equip",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsVr as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansVr, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansVr, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuVr = [];
        $techsJuVr = $users->find([
            '$and' => [
                [
                    "agency" => "Vridi - Equip",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuVr as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuVr, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuVr, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeVr = [];
        $techsSeVr = $users->find([
            '$and' => [
                [
                    "agency" => "Vridi - Equip",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeVr as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeVr, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeVr, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExVr = [];
        $techsExVr = $users->find([
            '$and' => [
                [
                    "agency" => "Vridi - Equip",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExVr as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExVr, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExVr, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuVr = [];
        $countSavoirsJuVr = [];
        $countMaSavFaisJuVr = [];
        $countTechSavFaisJuVr = [];
        $testsSeVr = [];
        $countSavoirsSeVr = [];
        $countMaSavFaisSeVr = [];
        $countTechSavFaisSeVr = [];
        $testsExVr = [];
        $countSavoirsExVr = [];
        $countMaSavFaisExVr = [];
        $countTechSavFaisExVr = [];
        foreach ($techniciansVr as $technician) { 
            $allocateFacJuVr = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuVr = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuVr) && $allocateFacJuVr['active'] == true) {
                $countSavoirsJuVr[] = $allocateFacJuVr;
            }
            if (isset($allocateDeclaJuVr) && $allocateDeclaJuVr['activeManager'] == true) {
                $countMaSavFaisJuVr[] = $allocateDeclaJuVr;
            }
            if (isset($allocateDeclaJuVr) && $allocateDeclaJuVr['active'] == true) {
                $countTechSavFaisJuVr[] = $allocateDeclaJuVr;
            }
            if (isset($allocateFacJuVr) && isset($allocateDeclaJuVr) && $allocateFacJuVr['active'] == true && $allocateDeclaJuVr['active'] == true && $allocateDeclaJuVr['activeManager'] == true) {
                $testsJuVr[] = $technician;
            }
            $allocateFacSeVr = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeVr = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeVr) && $allocateFacSeVr['active'] == true) {
                $countSavoirsSeVr[] = $allocateFacSeVr;
            }
            if (isset($allocateDeclaSeVr) && $allocateDeclaSeVr['activeManager'] == true) {
                $countMaSavFaisSeVr[] = $allocateDeclaSeVr;
            }
            if (isset($allocateDeclaSeVr) && $allocateDeclaSeVr['active'] == true) {
                $countTechSavFaisSeVr[] = $allocateDeclaSeVr;
            }
            if (isset($allocateFacSeVr) && isset($allocateDeclaSeVr) && $allocateFacSeVr['active'] == true && $allocateDeclaSeVr['active'] == true && $allocateDeclaSeVr['activeManager'] == true) {
                $testsSeVr[] = $technician;
            }
            $allocateFacExVr = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExVr = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExVr) && $allocateFacExVr['active'] == true) {
                $countSavoirsExVr[] = $allocateFacExVr;
            }
            if (isset($allocateDeclaExVr) && $allocateDeclaExVr['activeManager'] == true) {
                $countMaSavFaisExVr[] = $allocateDeclaExVr;
            }
            if (isset($allocateDeclaExVr) && $allocateDeclaExVr['active'] == true) {
                $countTechSavFaisExVr[] = $allocateDeclaExVr;
            }
            if (isset($allocateFacExVr) && isset($allocateDeclaExVr) && $allocateFacExVr['active'] == true && $allocateDeclaExVr['active'] == true && $allocateDeclaExVr['activeManager'] == true) {
                $testsExVr[] = $technician;
            }
        }
        
        $techniciansYa = [];
        $techsYa = $users->find([
            '$and' => [
                [
                    "agency" => "Yaoundé",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsYa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansYa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansYa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansJuYa = [];
        $techsJuYa = $users->find([
            '$and' => [
                [
                    "agency" => "Yaoundé",
                    "level" => "Junior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsJuYa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansJuYa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansJuYa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansSeYa = [];
        $techsSeYa = $users->find([
            '$and' => [
                [
                    "agency" => "Yaoundé",
                    "level" => "Senior",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsSeYa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansSeYa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansSeYa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
        $techniciansExYa = [];
        $techsExYa = $users->find([
            '$and' => [
                [
                    "agency" => "Yaoundé",
                    "level" => "Expert",
                    "active" => true,
                ],
            ],
        ])->toArray();
        foreach ($techsExYa as $techn) {
            if ($techn["profile"] == "Technicien") {
                array_push($techniciansExYa, new MongoDB\BSON\ObjectId($techn['_id']));
            } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
                array_push($techniciansExYa, new MongoDB\BSON\ObjectId($techn['_id']));
            }
        }
    
        
        $testsJuYa = [];
        $countSavoirsJuYa = [];
        $countMaSavFaisJuYa = [];
        $countTechSavFaisJuYa = [];
        $testsSeYa = [];
        $countSavoirsSeYa = [];
        $countMaSavFaisSeYa = [];
        $countTechSavFaisSeYa = [];
        $testsExYa = [];
        $countSavoirsExYa = [];
        $countMaSavFaisExYa = [];
        $countTechSavFaisExYa = [];
        foreach ($techniciansYa as $technician) { 
            $allocateFacJuYa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Junior",
                    ],
                ],
            ]);
            $allocateDeclaJuYa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Junior",
                    ],
                ],
            ]);
            if (isset($allocateFacJuYa) && $allocateFacJuYa['active'] == true) {
                $countSavoirsJuYa[] = $allocateFacJuYa;
            }
            if (isset($allocateDeclaJuYa) && $allocateDeclaJuYa['activeManager'] == true) {
                $countMaSavFaisJuYa[] = $allocateDeclaJuYa;
            }
            if (isset($allocateDeclaJuYa) && $allocateDeclaJuYa['active'] == true) {
                $countTechSavFaisJuYa[] = $allocateDeclaJuYa;
            }
            if (isset($allocateFacJuYa) && isset($allocateDeclaJuYa) && $allocateFacJuYa['active'] == true && $allocateDeclaJuYa['active'] == true && $allocateDeclaJuYa['activeManager'] == true) {
                $testsJuYa[] = $technician;
            }
            $allocateFacSeYa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Senior",
                    ],
                ],
            ]);
            $allocateDeclaSeYa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Senior",
                    ],
                ],
            ]);
            if (isset($allocateFacSeYa) && $allocateFacSeYa['active'] == true) {
                $countSavoirsSeYa[] = $allocateFacSeYa;
            }
            if (isset($allocateDeclaSeYa) && $allocateDeclaSeYa['activeManager'] == true) {
                $countMaSavFaisSeYa[] = $allocateDeclaSeYa;
            }
            if (isset($allocateDeclaSeYa) && $allocateDeclaSeYa['active'] == true) {
                $countTechSavFaisSeYa[] = $allocateDeclaSeYa;
            }
            if (isset($allocateFacSeYa) && isset($allocateDeclaSeYa) && $allocateFacSeYa['active'] == true && $allocateDeclaSeYa['active'] == true && $allocateDeclaSeYa['activeManager'] == true) {
                $testsSeYa[] = $technician;
            }
            $allocateFacExYa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Factuel",
                        "level" => "Expert",
                    ],
                ],
            ]);
            $allocateDeclaExYa = $allocations->findOne([
                '$and' => [
                    [
                        "user" => new MongoDB\BSON\ObjectId($technician),
                        "type" => "Declaratif",
                        "level" => "Expert",
                    ],
                ],
            ]);
            if (isset($allocateFacExYa) && $allocateFacExYa['active'] == true) {
                $countSavoirsExYa[] = $allocateFacExYa;
            }
            if (isset($allocateDeclaExYa) && $allocateDeclaExYa['activeManager'] == true) {
                $countMaSavFaisExYa[] = $allocateDeclaExYa;
            }
            if (isset($allocateDeclaExYa) && $allocateDeclaExYa['active'] == true) {
                $countTechSavFaisExYa[] = $allocateDeclaExYa;
            }
            if (isset($allocateFacExYa) && isset($allocateDeclaExYa) && $allocateFacExYa['active'] == true && $allocateDeclaExYa['active'] == true && $allocateDeclaExYa['activeManager'] == true) {
                $testsExYa[] = $technician;
            }
        }
    } else {
        if ($_GET['agency']) {
            $agency = $_GET['agency'];
        }
        
        $technicians = [];
        $techs = $users->find([
            '$and' => [
                [
                    "subsidiary" => $_SESSION["subsidiary"],
                    "agency" => $agency,
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
                    "agency" => $agency,
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
                    "agency" => $agency,
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
                    "agency" => $agency,
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
        

        $resultsFacScoreJuTj = [];
        $resultsDeclaScoreJuTj = [];
        $resultsFacTotalJuTj = [];
        $resultsDeclaTotalJuTj = [];

        foreach ($techniciansJu as $tech) {
            $alloFacJuTj = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaJuTj = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            if ($alloFacJuTj && $alloDeclaJuTj) {
                $resultFacJuTj = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacJuTj)) {
                    array_push($resultsFacScoreJuTj, $resultFacJuTj['score']);
                    array_push($resultsFacTotalJuTj, $resultFacJuTj['total']);
                }
                $resultDeclaJuTj = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaJuTj)) {
                    array_push($resultsDeclaScoreJuTj, $resultDeclaJuTj['score']);
                    array_push($resultsDeclaTotalJuTj, $resultDeclaJuTj['total']);
                }
            }
        }
        $totalResultFacJuTj = 0;
        $scoreResultFacJuTj = 0;
        for ($i = 0; $i < count($resultsFacTotalJuTj); $i++) {
            $scoreResultFacJuTj += ( int)$resultsFacScoreJuTj[$i];
            $totalResultFacJuTj += ( int)$resultsFacTotalJuTj[$i];
        }
        if($totalResultFacJuTj == 0) {
            $avgFacJuTj = ($scoreResultFacJuTj * 100 / 1);
        } else {
            $avgFacJuTj = ($scoreResultFacJuTj * 100 / $totalResultFacJuTj);
        }
        $percentageFacJuTj = $avgFacJuTj;
        $totalResultDeclaJuTj = 0;
        $scoreResultDeclaJuTj = 0;
        for ($i = 0; $i < count($resultsDeclaTotalJuTj); $i++) {
            $scoreResultDeclaJuTj += ( int)$resultsDeclaScoreJuTj[$i];
            $totalResultDeclaJuTj += ( int)$resultsDeclaTotalJuTj[$i];
        }
        if($totalResultDeclaJuTj == 0) {
            $avgDeclaJuTj = ($scoreResultDeclaJuTj * 100 / 1);
        } else {
            $avgDeclaJuTj = ($scoreResultDeclaJuTj * 100 / $totalResultDeclaJuTj);
        }
        $percentageDeclaJuTj = $avgDeclaJuTj;
        
        $resultsFacScoreJuTs = [];
        $resultsDeclaScoreJuTs = [];
        $resultsFacScoreSeTs = [];
        $resultsDeclaScoreSeTs = [];
        $resultsFacTotalJuTs = [];
        $resultsDeclaTotalJuTs = [];
        $resultsFacTotalSeTs = [];
        $resultsDeclaTotalSeTs = [];

        foreach ($techniciansSe as $tech) {
            $alloFacJuTs = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaJuTs = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            $alloFacSeTs = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Senior',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaSeTs = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Senior',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            if ($alloFacJuTs && $alloDeclaJuTs) {
                $resultFacJuTs = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacJuTs)) {
                    array_push($resultsFacScoreJuTs, $resultFacJuTs['score']);
                    array_push($resultsFacTotalJuTs, $resultFacJuTs['total']);
                }
                $resultDeclaJuTs = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaJuTs)) {
                    array_push($resultsDeclaScoreJuTs, $resultDeclaJuTs['score']);
                    array_push($resultsDeclaTotalJuTs, $resultDeclaJuTs['total']);
                }
            }
            if ($alloFacSeTs && $alloDeclaSeTs) {
                $resultFacSeTs = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Senior",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacSeTs)) {
                    array_push($resultsFacScoreSeTs, $resultFacSeTs['score']);
                    array_push($resultsFacTotalSeTs, $resultFacSeTs['total']);
                }
                $resultDeclaSeTs = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Senior",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaSeTs)) {
                    array_push($resultsDeclaScoreSeTs, $resultDeclaSeTs['score']);
                    array_push($resultsDeclaTotalSeTs, $resultDeclaSeTs['total']);
                }
            }
        }
        $totalResultFacJuTs = 0;
        $scoreResultFacJuTs = 0;
        for ($i = 0; $i < count($resultsFacTotalJuTs); $i++) {
            $scoreResultFacJuTs += ( int)$resultsFacScoreJuTs[$i];
            $totalResultFacJuTs += ( int)$resultsFacTotalJuTs[$i];
        }
        if($totalResultFacJuTs == 0) {
            $avgFacJuTs = ($scoreResultFacJuTs * 100 / 1);
        } else {
            $avgFacJuTs = ($scoreResultFacJuTs * 100 / $totalResultFacJuTs);
        }
        $percentageFacJuTs = $avgFacJuTs;
        $totalResultDeclaJuTs = 0;
        $scoreResultDeclaJuTs = 0;
        for ($i = 0; $i < count($resultsDeclaTotalJuTs); $i++) {
            $scoreResultDeclaJuTs += ( int)$resultsDeclaScoreJuTs[$i];
            $totalResultDeclaJuTs += ( int)$resultsDeclaTotalJuTs[$i];
        }
        if($totalResultDeclaJuTs == 0) {
            $avgDeclaJuTs = ($scoreResultDeclaJuTs * 100 / 1);
        } else {
            $avgDeclaJuTs = ($scoreResultDeclaJuTs * 100 / $totalResultDeclaJuTs);
        }
        $percentageDeclaJuTs = $avgDeclaJuTs;
        $totalResultFacSeTs = 0;
        $scoreResultFacSeTs = 0;
        for ($i = 0; $i < count($resultsFacTotalSeTs); $i++) {
            $scoreResultFacSeTs += ( int)$resultsFacScoreSeTs[$i];
            $totalResultFacSeTs += ( int)$resultsFacTotalSeTs[$i];
        }
        if($totalResultFacSeTs == 0) {
            $avgFacSeTs = ($scoreResultFacSeTs * 100 / 1);
            $percentageFacSeTs = 0;
        } else {
            $avgFacSeTs = ($scoreResultFacSeTs * 100 / $totalResultFacSeTs);
        }
        $percentageFacSeTs = $avgFacSeTs;
        $totalResultDeclaSeTs = 0;
        $scoreResultDeclaSeTs = 0;
        for ($i = 0; $i < count($resultsDeclaTotalSeTs); $i++) {
            $scoreResultDeclaSeTs += ( int)$resultsDeclaScoreSeTs[$i];
            $totalResultDeclaSeTs += ( int)$resultsDeclaTotalSeTs[$i];
        }
        if($totalResultDeclaSeTs == 0) {
            $avgDeclaSeTs = ($scoreResultDeclaSeTs * 100 / 1);
            $percentageDeclaSeTs = 0;
        } else {
            $avgDeclaSeTs = ($scoreResultDeclaSeTs * 100 / $totalResultDeclaSeTs);
        }
        $percentageDeclaSeTs = $avgDeclaSeTs;
        
        $resultsFacScoreJuTx = [];
        $resultsDeclaScoreJuTx = [];
        $resultsFacTotalJuTx = [];
        $resultsDeclaTotalJuTx = [];
        $resultsFacScoreSeTx = [];
        $resultsDeclaScoreSeTx = [];
        $resultsFacTotalSeTx = [];
        $resultsDeclaTotalSeTx = [];

        foreach ($techniciansEx as $tech) {
            $alloFacJuTx = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaJuTx = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            $alloFacSeTx = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Senior',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaSeTx = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Senior',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            if ($alloFacJuTx && $alloDeclaJuTx) {
                $resultFacJuTx = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacJuTx)) {
                    array_push($resultsFacScoreJuTx, $resultFacJuTx['score']);
                    array_push($resultsFacTotalJuTx, $resultFacJuTx['total']);
                }
                $resultDeclaJuTx = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaJuTx)) {
                    array_push($resultsDeclaScoreJuTx, $resultDeclaJuTx['score']);
                    array_push($resultsDeclaTotalJuTx, $resultDeclaJuTx['total']);
                }
            }
            if ($alloFacSeTx && $alloDeclaSeTx) {
                $resultFacSeTx = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Senior",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacSeTx)) {
                    array_push($resultsFacScoreSeTx, $resultFacSeTx['score']);
                    array_push($resultsFacTotalSeTx, $resultFacSeTx['total']);
                }
                $resultDeclaSeTx = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Senior",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaSeTx)) {
                    array_push($resultsDeclaScoreSeTx, $resultDeclaSeTx['score']);
                    array_push($resultsDeclaTotalSeTx, $resultDeclaSeTx['total']);
                }
            }
        }
        $totalResultFacJuTx = 0;
        $scoreResultFacJuTx = 0;
        for ($i = 0; $i < count($resultsFacTotalJuTx); $i++) {
            $scoreResultFacJuTx += ( int)$resultsFacScoreJuTx[$i];
            $totalResultFacJuTx += ( int)$resultsFacTotalJuTx[$i];
        }
        if($totalResultFacJuTx == 0) {
            $avgFacJuTx = ($scoreResultFacJuTx * 100 / 1);
        } else {
            $avgFacJuTx = ($scoreResultFacJuTx * 100 / $totalResultFacJuTx);
        }
        $percentageFacJuTx = $avgFacJuTx;
        $totalResultDeclaJuTx = 0;
        $scoreResultDeclaJuTx = 0;
        for ($i = 0; $i < count($resultsDeclaTotalJuTx); $i++) {
            $scoreResultDeclaJuTx += ( int)$resultsDeclaScoreJuTx[$i];
            $totalResultDeclaJuTx += ( int)$resultsDeclaTotalJuTx[$i];
        }
        if($totalResultDeclaJuTx == 0) {
            $avgDeclaJuTx = ($scoreResultDeclaJuTx * 100 / 1);
        } else {
            $avgDeclaJuTx = ($scoreResultDeclaJuTx * 100 / $totalResultDeclaJuTx);
        }
        $percentageDeclaJuTx = $avgDeclaJuTx;
        $totalResultFacSeTx = 0;
        $scoreResultFacSeTx = 0;
        for ($i = 0; $i < count($resultsFacTotalSeTx); $i++) {
            $scoreResultFacSeTx += ( int)$resultsFacScoreSeTx[$i];
            $totalResultFacSeTx += ( int)$resultsFacTotalSeTx[$i];
        }
        if($totalResultFacSeTx == 0) {
            $avgFacSeTx = ($scoreResultFacSeTx * 100 / 1);
        } else {
            $avgFacSeTx = ($scoreResultFacSeTx * 100 / $totalResultFacSeTx);
        }
        $percentageFacSeTx = $avgFacSeTx;
        $totalResultDeclaSeTx = 0;
        $scoreResultDeclaSeTx = 0;
        for ($i = 0; $i < count($resultsDeclaTotalSeTx); $i++) {
            $scoreResultDeclaSeTx += ( int)$resultsDeclaScoreSeTx[$i];
            $totalResultDeclaSeTx += ( int)$resultsDeclaTotalSeTx[$i];
        }
        if($totalResultDeclaSeTx == 0) {
            $avgDeclaSeTx = ($scoreResultDeclaSeTx * 100 / 1);
        } else {
            $avgDeclaSeTx = ($scoreResultDeclaSeTx * 100 / $totalResultDeclaSeTx);
        }
        $percentageDeclaSeTx = $avgDeclaSeTx;

        $resultsFacScoreJu = [];
        $resultsDeclaScoreJu = [];
        $resultsFacScoreSe = [];
        $resultsDeclaScoreSe = [];
        $resultsFacScoreEx = [];
        $resultsDeclaScoreEx = [];
        $resultsFacTotalJu = [];
        $resultsDeclaTotalJu = [];
        $resultsFacTotalSe = [];
        $resultsDeclaTotalSe = [];
        $resultsFacTotalEx = [];
        $resultsDeclaTotalEx = [];

        foreach ($technicians as $tech) {
            $alloFacJu = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaJu = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Junior',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            $alloFacSe = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Senior',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaSe = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Senior',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            $alloFacEx = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Expert',
                        'type' => 'Factuel',
                        'active' => true
                    ]
                ]
            ]);
            $alloDeclaEx = $allocations->findOne([
                '$and' => [
                    [
                        'user' =>  new MongoDB\BSON\ObjectId($tech),
                        'level' => 'Expert',
                        'type' => 'Declaratif',
                        'activeManager' => true,
                        'active' => true
                    ]
                ]
            ]);
            if ($alloFacJu && $alloDeclaJu) {
                $resultFacJu = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacJu)) {
                    array_push($resultsFacScoreJu, $resultFacJu['score']);
                    array_push($resultsFacTotalJu, $resultFacJu['total']);
                }
                $resultDeclaJu = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Junior",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaJu)) {
                    array_push($resultsDeclaScoreJu, $resultDeclaJu['score']);
                    array_push($resultsDeclaTotalJu, $resultDeclaJu['total']);
                }
            }
            if ($alloFacSe && $alloDeclaSe) {
                $resultFacSe = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Senior",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacSe)) {
                    array_push($resultsFacScoreSe, $resultFacSe['score']);
                    array_push($resultsFacTotalSe, $resultFacSe['total']);
                }
                $resultDeclaSe = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Senior",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaSe)) {
                    array_push($resultsDeclaScoreSe, $resultDeclaSe['score']);
                    array_push($resultsDeclaTotalSe, $resultDeclaSe['total']);
                }
            }
            if ($alloFacEx && $alloDeclaEx) {
                $resultFacEx = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Expert",
                            'typeR' => 'Technicien',
                            "type" => "Factuel",
                        ],
                    ],
                ]);
                if (isset($resultFacEx)) {
                    array_push($resultsFacScoreEx, $resultFacEx['score']);
                    array_push($resultsFacTotalEx, $resultFacEx['total']);
                }
                $resultDeclaEx = $results->findOne([
                    '$and' => [
                        [
                            "user" => new MongoDB\BSON\ObjectId($tech),
                            "level" => "Expert",
                            "typeR" => "Technicien - Manager",
                            "type" => "Declaratif",
                        ],
                    ],
                ]);
                if (isset($resultDeclaEx)) {
                    array_push($resultsDeclaScoreEx, $resultDeclaEx['score']);
                    array_push($resultsDeclaTotalEx, $resultDeclaEx['total']);
                }
            }
        }
        $totalResultFacJu = 0;
        $scoreResultFacJu = 0;
        for ($i = 0; $i < count($resultsFacTotalJu); $i++) {
            $scoreResultFacJu += ( int)$resultsFacScoreJu[$i];
            $totalResultFacJu += ( int)$resultsFacTotalJu[$i];
        }
        if($totalResultFacJu == 0) {
            $avgFacJu = ($scoreResultFacJu * 100 / 1);
        } else {
            $avgFacJu = ($scoreResultFacJu * 100 / $totalResultFacJu);
        }
        $percentageFacJu = $avgFacJu;
        $totalResultDeclaJu = 0;
        $scoreResultDeclaJu = 0;
        for ($i = 0; $i < count($resultsDeclaTotalJu); $i++) {
            $scoreResultDeclaJu += ( int)$resultsDeclaScoreJu[$i];
            $totalResultDeclaJu += ( int)$resultsDeclaTotalJu[$i];
        }
        if($totalResultDeclaJu == 0) {
            $avgDeclaJu = ($scoreResultDeclaJu * 100 / 1);
        } else {
            $avgDeclaJu = ($scoreResultDeclaJu * 100 / $totalResultDeclaJu);
        }
        $percentageDeclaJu = $avgDeclaJu;
        $totalResultFacSe = 0;
        $scoreResultFacSe = 0;
        for ($i = 0; $i < count($resultsFacTotalSe); $i++) {
            $scoreResultFacSe += ( int)$resultsFacScoreSe[$i];
            $totalResultFacSe += ( int)$resultsFacTotalSe[$i];
        }
        if($totalResultFacSe == 0) {
            $avgFacSe = ($scoreResultFacSe * 100 / 1);
            $percentageFacSe = 0;
        } else {
            $avgFacSe = ($scoreResultFacSe * 100 / $totalResultFacSe);
        }
        $percentageFacSe = $avgFacSe;
        $totalResultDeclaSe = 0;
        $scoreResultDeclaSe = 0;
        for ($i = 0; $i < count($resultsDeclaTotalSe); $i++) {
            $scoreResultDeclaSe += ( int)$resultsDeclaScoreSe[$i];
            $totalResultDeclaSe += ( int)$resultsDeclaTotalSe[$i];
        }
        if($totalResultDeclaSe == 0) {
            $avgDeclaSe = ($scoreResultDeclaSe * 100 / 1);
            $percentageDeclaSe = 0;
        } else {
            $avgDeclaSe = ($scoreResultDeclaSe * 100 / $totalResultDeclaSe);
        }
        $percentageDeclaSe = $avgDeclaSe;
        $totalResultFacEx = 0;
        $scoreResultFacEx = 0;
        for ($i = 0; $i < count($resultsFacTotalEx); $i++) {
            $scoreResultFacEx += ( int)$resultsFacScoreEx[$i];
            $totalResultFacEx += ( int)$resultsFacTotalEx[$i];
        }
        if($totalResultFacEx == 0) {
            $avgFacEx = ($scoreResultFacEx * 100 / 1);
        } else {
            $avgFacEx = ($scoreResultFacEx * 100 / $totalResultFacEx);
        }
        $percentageFacEx = $avgFacEx;
        $totalResultDeclaEx = 0;
        $scoreResultDeclaEx = 0;
        for ($i = 0; $i < count($resultsDeclaTotalEx); $i++) {
            $scoreResultDeclaEx += ( int)$resultsDeclaScoreEx[$i];
            $totalResultDeclaEx += ( int)$resultsDeclaTotalEx[$i];
        }
        if($totalResultDeclaEx == 0) {
            $avgDeclaEx = ($scoreResultDeclaEx * 100 / 1);
        } else {
            $avgDeclaEx = ($scoreResultDeclaEx * 100 / $totalResultDeclaEx);
        }
        $percentageDeclaEx = $avgDeclaEx;
        
        $testsUserJu = [];
        $countSavoirJu = [];
        $countMaSavFaiJu = [];
        $countTechSavFaiJu = [];
        $testsUserSe = [];
        $countSavoirSe = [];
        $countMaSavFaiSe = [];
        $countTechSavFaiSe = [];
        $testsUserEx = [];
        $countSavoirEx = [];
        $countMaSavFaiEx = [];
        $countTechSavFaiEx = [];
        $testsTotalJu = [];
        $testsTotalSe = [];
        $testsTotalEx = [];
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
                $countSavoirJu[] = $allocateFacJu;
            }
            if (isset($allocateDeclaJu) && $allocateDeclaJu['activeManager'] == true) {
                $countMaSavFaiJu[] = $allocateDeclaJu;
            }
            if (isset($allocateDeclaJu) && $allocateDeclaJu['active'] == true) {
                $countTechSavFaiJu[] = $allocateDeclaJu;
            }
            if (isset($allocateFacJu) && isset($allocateDeclaJu) && $allocateFacJu['active'] == true && $allocateDeclaJu['active'] == true && $allocateDeclaJu['activeManager'] == true) {
                $testsUserJu[] = $technician;
            }
            if (isset($allocateFacJu) && isset($allocateDeclaJu)) {
                $testsTotalJu[] = $technician;
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
                $countSavoirSe[] = $allocateFacSe;
            }
            if (isset($allocateDeclaSe) && $allocateDeclaSe['activeManager'] == true) {
                $countMaSavFaiSe[] = $allocateDeclaSe;
            }
            if (isset($allocateDeclaSe) && $allocateDeclaSe['active'] == true) {
                $countTechSavFaiSe[] = $allocateDeclaSe;
            }
            if (isset($allocateFacSe) && isset($allocateDeclaSe) && $allocateFacSe['active'] == true && $allocateDeclaSe['active'] == true && $allocateDeclaSe['activeManager'] == true) {
                $testsUserSe[] = $technician;
            }
            if (isset($allocateFacSe) && isset($allocateDeclaSe)) {
                $testsTotalSe[] = $technician;
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
                $countSavoirEx[] = $allocateFacEx;
            }
            if (isset($allocateDeclaEx) && $allocateDeclaEx['activeManager'] == true) {
                $countMaSavFaiEx[] = $allocateDeclaEx;
            }
            if (isset($allocateDeclaEx) && $allocateDeclaEx['active'] == true) {
                $countTechSavFaiEx[] = $allocateDeclaEx;
            }
            if (isset($allocateFacEx) && isset($allocateDeclaEx) && $allocateFacEx['active'] == true && $allocateDeclaEx['active'] == true && $allocateDeclaEx['activeManager'] == true) {
                $testsUserEx[] = $technician;
            }
            if (isset($allocateFacEx) && isset($allocateDeclaEx)) {
                $testsTotalEx[] = $technician;
            }
        }    
    }
    ?>
<?php include "./partials/header.php"; ?>

<style>
/* Hide dropdown content by default */
.dropdown-content2 {
    display: none;
    margin-top: 15px;
    /* Reduced margin for a tighter look */
    padding: 5px;
    /* Add some padding for better spacing */
    background-color: #f9f9f9;
    /* Light background for dropdown content */
    border-radius: 8px;
    /* Rounded corners for dropdown content */
    transition: max-height 0.3s ease, opacity 0.3s ease;
    /* Smooth transitions */
    opacity: 0;
    max-height: 0;
    overflow: hidden;
}

/* Style the toggle button */
.dropdown-toggle2 {
    background-color: #fff;
    /* Button background */
    color: gray;
    /* Button text color */
    padding: 10px 15px !important;
    /* Reduced padding for a more compact button */
    cursor: pointer;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    /* Smooth transitions */
    border: none;
    /* No border */
}

/* Style for the icon next to the button text */
.dropdown-toggle2 i {
    margin-left: 10px;
    /* More space between text and icon */
    font-size: 16px;
    /* Proper icon size */
    transition: transform 0.3s ease;
    /* Smooth rotation transition */
}

/* Rotate icon when the dropdown is open */
.dropdown-toggle2.open i {
    transform: rotate(180deg);
}

/* Button hover effect */
.dropdown-toggle2:hover {
    background-color: #f1f1f1;
    /* Slightly darker background on hover */
    color: #333;
    /* Slightly darker text color on hover for better contrast */
}

/* Optional: Style for better visibility */
.title-and-cards-container2 {
    margin-bottom: 25px;
    /* Adjust as needed */
}


/* Hide dropdown content by default */
.dropdown-content {
    display: none;
    margin-top: 25px;
    /* Adjust as needed */
    transition: opacity 0.3s ease, max-height 0.3s ease;
    /* Smooth transition for dropdown visibility */
}

/* Style the toggle button */
.dropdown-toggle {
    background-color: #fff;
    color: white;
    border: none;
    padding: 10px 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: background-color 0.3s ease, color 0.3s ease;
    /* Smooth transition for background and text color */

}

.dropdown-toggle i {
    margin-left: 5px;
    font-size: 14px;
    /* Set a proper size for the icon */
    transition: transform 0.3s ease;
    /* Smooth rotation transition */
}


/* Ensure no extra content or pseudo-elements */
.dropdown-toggle::before,
.dropdown-toggle::after {
    content: none;
    /* Ensure no extra content or pseudo-elements */
}

.dropdown-toggle.open i {
    transform: rotate(180deg);
}

/* Optional: Style for better visibility */
.title-and-cards-container {
    margin-bottom: 25px;
    /* Adjust as needed */
}

.dropdown-toggle:hover {
    background-color: #f0f0f0;
    /* Slightly darker background on hover */
    color: #333;
    /* Slightly darker text color on hover for better contrast */
}

/* Optional: Style for better visibility */
.title-and-cards-container {
    margin-bottom: 20px;
    /* Adjust as needed */
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
    position: relative;
    /* Make sure canvas is positioned correctly */
}

/* Canvas styling */
.responsive-chart-container canvas {
    width: 100% !important;
    /* Make canvas responsive */
    height: auto !important;
    /* Maintain aspect ratio */
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
    gap: 8px;
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
    font-size: 3rem;
    /* Large size for visibility */
    color: #000;
    /* Adjust color if needed */
    position: relative;
    /* Allows movement relative to its normal position */
    top: 50px;
    /* Moves the plus sign down by 100px */
    transition: transform 0.3s ease, color 0.3s ease;
    /* Smooth transitions for interactivity */
}

/* Optional: Hover effect for a modern touch */
.plus-sign:hover {
    transform: scale(1.1);
    /* Slightly enlarges on hover */
    color: #007bff;
    /* Change color on hover for better visibility */
}
</style>
<!--begin::Title-->
<?php if ( $_SESSION["profile"] == "Super Admin") { ?>
    <h1 class="text-dark fw-bold my-1 fs-2">
        <title><?php echo $etat_avanacement_qcm_agences ?>| CFAO Mobility Academy</title> 
    </h1>
<?php } else { ?>
    <h1 class="text-dark fw-bold my-1 fs-2">
        <title><?php echo $etat_avanacement_agence ?> <?php echo $_GET['agency'] ?> | CFAO Mobility Academy</title> 
    </h1>
<?php } ?>
<!--end::Title-->
<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
            <?php if ( $_SESSION["profile"] == "Super Admin") { ?>
                <h1 class="text-dark fw-bold my-1 fs-2">
                    <?php echo $etat_avanacement_qcm_agences ?> 
                </h1>
            <?php } else { ?>
                <h1 class="text-dark fw-bold my-1 fs-2">
                    <?php echo $etat_avanacement_agence ?> <?php echo $_GET['agency'] ?> 
                </h1>
            <?php } ?>
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
            <?php if ( $_SESSION["profile"] == "Admin" || $_SESSION["profile"] == "Directeur Filiale") { ?>
            <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    <!--begin::Toolbar-->
                    <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                            <!--begin::Info-->
                            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                                <!--begin::Title-->
                                <h1 class="text-dark fw-bold my-1 fs-2">
                                    <?php echo $effectif_agence ?>
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
                            <?php echo $technicienss ?> <?php echo $agence ?> <?php echo $global ?></div>
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
                <!-- Dropdown Container -->
                <div class="dropdown-container2">
                    <button class="dropdown-toggle2" style="color: black">
                        Plus de détails sur les Résultats
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <!-- Dropdown Content (Initially hidden) -->
                    <div class="dropdown-content2" style="display: none;">
                        <div class="row">
                            <!-- Card 1 -->
                            <div class="col-xl-3">
                                <div class="card" style="height: 430px; width: 300px; margin-bottom: 25px;">
                                    <center>
                                        <h5 class="mt-2" style="align-text: center; margin-top: 18px !important;">
                                            Résultats Niveau Junior</h5>
                                    </center>
                                    <div id="chart_junior_filiale"
                                        style="height: 100%; width: 100%; margin-top: 10px; margin-left: -4px;"></div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="col-xl-3">
                                <div class="card" style="height: 430px; width: 300px; margin-bottom: 25px;">
                                    <center>
                                        <h5 class="mt-2" style="align-text: center; margin-top: 18px !important;">
                                            Résultats Niveau Senior</h5>
                                    </center>
                                    <div id="chart_senior_filiale"
                                        style="height: 100%; width: 100%; margin-top: 10px; margin-left: -4px;"></div>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="col-xl-3">
                                <div class="card" style="height: 430px; width: 300px; margin-bottom: 25px;">
                                    <center>
                                        <h5 class="mt-2" style="align-text: center; margin-top: 18px !important;">
                                            Résultats Niveau Expert</h5>
                                    </center>
                                    <div id="chart_expert_filiale"
                                        style="height: 100%; width: 100%; margin-top: 10px; margin-left: -4px;"></div>
                                </div>
                            </div>

                            <!-- Card 4 -->
                            <div class="col-xl-3">
                                <div class="card" style="height: 430px; width: 300px; margin-bottom: 25px;">
                                    <center>
                                        <h5 class="mt-2" style="align-text: center; margin-top: 18px !important;">Total
                                            : 03 niveaux</h5>
                                    </center>
                                    <div id="chart_total_filiale"
                                        style="height: 100%; width: 100%; margin-top: 10px; margin-left: -4px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <button class="dropdown-toggle" style="color: black">Plus de détails sur les tests
                    <i class="fas fa-chevron-down"></i></button>
                    
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
            </div>
            <?php } ?>
            <!--end:Row-->
            <?php if ($_SESSION["profile"] == "Super Admin") { ?>
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
                <div class="w-150px me-3" style="margin-top: 10px; margin-bottom: 20px;" id="etat">
                <span class="fw-bolder"  style="margin-bottom: 10px;"> Selectionner le pays</span>
                    <!--begin::Select2-->
                    <select id="select"
                        class="form-select form-select-solid"
                        data-control="select2"
                        data-hide-search="true"
                        data-placeholder="Pays"
                        data-kt-ecommerce-order-filter="etat">
                        <option></option>
                        <option value="tous">Tous</option>
                        <option value="BURKINA">Burkina Faso</option>
                        <option value="CAMEROUN">Cameroun</option>
                        <option value="CONGO">Congo</option>
                        <option value="RCI">Côte d'Ivoire</option>
                        <option value="GABON">Gabon</option>
                        <option value="MALI">Mali</option>
                        <option value="RCA">RCA</option>
                        <option value="RDC">RDC</option>
                        <option value="SENEGAL">Sénégal</option>
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
                                class="table align-middle table-bordered  table-row-dashed fs-6 gy-4 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr class="text-start text-black fw-bold fs-7 text-uppeGase gs-0">
                                        <th class="min-w-150px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;"><?php echo $agence ?>
                                        </th>
                                        <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $level ?>
                                        </th>
                                        <th class="min-w-100px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="2"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 100px;"><?php echo $nbre_qcm_effectue ?>
                                        </th>
                                        <th class="min-w-200px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="2"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 150.516px;"><?php echo $qcm_realises ?>
                                        </th>
                                        <!-- <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $taux_evolution_qcm ?>
                                        </th> -->
                                        <th class="min-w-200px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="2"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;"><?php echo $qcm_techs_realises ?>
                                        </th>
                                        <!-- <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $taux_evolution_tache_tech ?>
                                        </th> -->
                                        <th class="min-w-200px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="2"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;"><?php echo $qcm_manager_realises ?>
                                        </th>
                                        <!-- <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $taux_evolution_tache_manager ?>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <tr class="odd" etat="<?php echo $mali ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                BAMAKO
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuBam) + count($countTechSavFaisJuBam) + count($countMaSavFaisJuBam) ?> / <?php echo count($techniciansBam) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountBam = count($techniciansBam) * 3;
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsJuBam) + count($countTechSavFaisJuBam) + count($countMaSavFaisJuBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuBam) ?> / <?php echo count($techniciansBam) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = count($techniciansBam);
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsJuBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuBam) ?> / <?php echo count($techniciansBam) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = count($techniciansBam);
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countTechSavFaisJuBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuBam) ?> / <?php echo count($techniciansBam) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = count($techniciansBam);
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countMaSavFaisJuBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $mali ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeBam) + count($countTechSavFaisSeBam) + count($countMaSavFaisSeBam) ?> / <?php echo (count($techniciansSeBam) + count($techniciansExBam)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountBam = (count($techniciansSeBam) + count($techniciansExBam)) * 3;
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsSeBam) + count($countTechSavFaisSeBam) + count($countMaSavFaisSeBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeBam) ?> / <?php echo (count($techniciansSeBam) + count($techniciansExBam)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = (count($techniciansSeBam) + count($techniciansExBam));
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsSeBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeBam) ?> / <?php echo (count($techniciansSeBam) + count($techniciansExBam)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = (count($techniciansSeBam) + count($techniciansExBam));
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countTechSavFaisSeBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeBam) ?> / <?php echo (count($techniciansSeBam) + count($techniciansExBam)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = (count($techniciansSeBam) + count($techniciansExBam));
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countMaSavFaisSeBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $mali ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExBam) + count($countTechSavFaisExBam) + count($countMaSavFaisExBam) ?> / <?php echo count($techniciansExBam) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountBam = count($techniciansExBam) * 3;
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsExBam) + count($countTechSavFaisExBam) + count($countMaSavFaisExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExBam) ?> / <?php echo count($techniciansExBam) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = count($techniciansExBam);
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExBam) ?> / <?php echo count($techniciansExBam) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = count($techniciansExBam);
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countTechSavFaisExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExBam) ?> / <?php echo count($techniciansExBam) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBam = count($techniciansExBam);
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countMaSavFaisExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $mali ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuBam) + count($countTechSavFaisJuBam) + count($countMaSavFaisJuBam) + count($countSavoirsSeBam) + count($countTechSavFaisSeBam) + count($countMaSavFaisSeBam) + count($countSavoirsExBam) + count($countTechSavFaisExBam) + count($countMaSavFaisExBam) ?> / <?php echo (count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBam = (count($techniciansBam) + count($techniciansSeBam)+ (count($techniciansExBam) * 2)) * 3 ;
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsJuBam) + count($countTechSavFaisJuBam) + count($countMaSavFaisJuBam) + count($countSavoirsSeBam) + count($countTechSavFaisSeBam) + count($countMaSavFaisSeBam) + count($countSavoirsExBam) + count($countTechSavFaisExBam) + count($countMaSavFaisExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuBam) + count($countSavoirsSeBam) + count($countSavoirsExBam)) ?> / <?php echo (count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBam = (count($techniciansBam) + count($techniciansSeBam)+ (count($techniciansExBam) * 2));
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countSavoirsJuBam) + count($countSavoirsSeBam) + count($countSavoirsExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuBam) + count($countTechSavFaisSeBam) + count($countTechSavFaisExBam)) ?> / <?php echo (count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBam = (count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2));
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countTechSavFaisJuBam) + count($countTechSavFaisSeBam) + count($countTechSavFaisExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuBam) + count($countMaSavFaisSeBam) + count($countMaSavFaisExBam)) ?> / <?php echo (count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBam = (count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2));
                                            if ($technicianCountBam > 0) {
                                                $percentageBam = ceil((count($countMaSavFaisJuBam) + count($countMaSavFaisSeBam) + count($countMaSavFaisExBam)) * 100 / $technicianCountBam);
                                            } else {
                                                $percentageBam = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBam . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $centrafrique ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                BANGUI
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuBan) + count($countTechSavFaisJuBan) + count($countMaSavFaisJuBan) ?> / <?php echo count($techniciansBan) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountBan = count($techniciansBan) * 3;
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsJuBan) + count($countTechSavFaisJuBan) + count($countMaSavFaisJuBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuBan) ?> / <?php echo count($techniciansBan) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBan = count($techniciansBan);
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsJuBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuBan) ?> / <?php echo count($techniciansBan) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBan = count($techniciansBan);
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countTechSavFaisJuBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuBan) ?> / <?php echo count($techniciansBan) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBan = count($techniciansBan);
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countMaSavFaisJuBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $centrafrique ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeBan) + count($countTechSavFaisSeBan) + count($countMaSavFaisSeBan) ?> / <?php echo (count($techniciansSeBan) + count($techniciansExBan)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeBan = count($techniciansSeBan);
                                            $technicianCountExBan = count($techniciansExBan);
                                            $totalTechnicianCountBan = ($technicianCountSeBan + $technicianCountExBan) * 3;
                                    
                                            if ($totalTechnicianCountBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsSeBan) + count($countTechSavFaisSeBan) + count($countMaSavFaisSeBan)) * 100 / ($totalTechnicianCountBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeBan) ?> / <?php echo (count($techniciansSeBan) + count($techniciansExBan)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBan = count($techniciansSeBan);
                                            $technicianCountExBan = count($techniciansExBan);
                                            $totalTechnicianCountBan = $technicianCountSeBan + $technicianCountExBan;
                                    
                                            if ($totalTechnicianCountBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsSeBan)) * 100 / ($totalTechnicianCountBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeBan) ?> / <?php echo (count($techniciansSeBan) + count($techniciansExBan)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBan = count($techniciansSeBan);
                                            $technicianCountExBan = count($techniciansExBan);
                                            $totalTechnicianCountBan = $technicianCountSeBan + $technicianCountExBan;
                                    
                                            if ($totalTechnicianCountBan > 0) {
                                                $percentageBan = ceil((count($countTechSavFaisSeBan)) * 100 / ($totalTechnicianCountBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeBan) ?> / <?php echo (count($techniciansSeBan) + count($techniciansExBan)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBan = count($techniciansSeBan);
                                            $technicianCountExBan = count($techniciansExBan);
                                            $totalTechnicianCountBan = $technicianCountSeBan + $technicianCountExBan;
                                    
                                            if ($totalTechnicianCountBan > 0) {
                                                $percentageBan = ceil((count($countMaSavFaisSeBan)) * 100 / ($totalTechnicianCountBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $centrafrique ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExBan) + count($countTechSavFaisExBan) + count($countMaSavFaisExBan) ?> / <?php echo count($techniciansExBan) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExBan = count($techniciansExBan) * 3;
                                    
                                            if ($technicianCountExBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsExBan) + count($countTechSavFaisExBan) + count($countMaSavFaisExBan)) * 100 / ($technicianCountExBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExBan) ?> / <?php echo count($techniciansExBan) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBan = count($techniciansExBan);
                                    
                                            if ($technicianCountExBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsExBan)) * 100 / ($technicianCountExBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExBan) ?> / <?php echo count($techniciansExBan) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBan = count($techniciansExBan);
                                    
                                            if ($technicianCountExBan > 0) {
                                                $percentageBan = ceil((count($countTechSavFaisExBan)) * 100 / ($technicianCountExBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExBan) ?> / <?php echo count($techniciansExBan) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBan = count($techniciansExBan);
                                    
                                            if ($technicianCountExBan > 0) {
                                                $percentageBan = ceil((count($countMaSavFaisExBan)) * 100 / ($technicianCountExBan));
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $centrafrique ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuBan) + count($countTechSavFaisJuBan) + count($countMaSavFaisJuBan) + count($countSavoirsSeBan) + count($countTechSavFaisSeBan) + count($countMaSavFaisSeBan) + count($countSavoirsExBan) + count($countTechSavFaisExBan) + count($countMaSavFaisExBan) ?> / <?php echo (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBan = (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) * 3;
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsJuBan) + count($countTechSavFaisJuBan) + count($countMaSavFaisJuBan) + count($countSavoirsSeBan) + count($countTechSavFaisSeBan) + count($countMaSavFaisSeBan) + count($countSavoirsExBan) + count($countTechSavFaisExBan) + count($countMaSavFaisExBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuBan) + count($countSavoirsSeBan) + count($countSavoirsExBan)) ?> / <?php echo (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBan = (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2));
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countSavoirsJuBan) + count($countSavoirsSeBan) + count($countSavoirsExBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuBan) + count($countTechSavFaisSeBan)+ count($countTechSavFaisExBan)) ?> / <?php echo (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBan = (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2));
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countTechSavFaisJuBan) + count($countTechSavFaisSeBan) + count($countTechSavFaisExBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuBan) + count($countMaSavFaisSeBan) + count($countMaSavFaisExBan)) ?> / <?php echo (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBan = (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2));
                                            if ($technicianCountBan > 0) {
                                                $percentageBan = ceil((count($countMaSavFaisJuBan) + count($countMaSavFaisSeBan) + count($countMaSavFaisExBan)) * 100 / $technicianCountBan);
                                            } else {
                                                $percentageBan = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBan . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                BERTOUA
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuBer) + count($countTechSavFaisJuBer) + count($countMaSavFaisJuBer) ?> / <?php echo count($techniciansBer) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountBer = count($techniciansBer);
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil(count(($countSavoirsJuBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuBer) ?> / <?php echo count($techniciansBer) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBer = count($techniciansBer);
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil(count(($countSavoirsJuBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuBer) ?> / <?php echo count($techniciansBer) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBer = count($techniciansBer);
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil(count(($countTechSavFaisJuBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuBer) ?> / <?php echo count($techniciansBer) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBer = count($techniciansBer);
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil(count(($countMaSavFaisJuBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeBer) + count($countTechSavFaisSeBer) + count($countMaSavFaisSeBer) ?> / <?php echo (count($techniciansSeBer) + count($techniciansExBer)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeBer = count($techniciansSeBer);
                                            $technicianCountExBer = count($techniciansExBer);
                                            $totalTechnicianCountBer = ($technicianCountSeBer + $technicianCountExBer) * 3;
                                    
                                            if ($totalTechnicianCountBer > 0) {
                                                $percentageBer = ceil((count($countSavoirsSeBer) + count($countTechSavFaisSeBer) + count($countMaSavFaisSeBer)) * 100 / ($totalTechnicianCountBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeBer) ?> / <?php echo (count($techniciansSeBer) + count($techniciansExBer))?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBer = count($techniciansSeBer);
                                            $technicianCountExBer = count($techniciansExBer);
                                            $totalTechnicianCountBer = $technicianCountSeBer + $technicianCountExBer;
                                    
                                            if ($totalTechnicianCountBer > 0) {
                                                $percentageBer = ceil((count($countSavoirsSeBer)) * 100 / ($totalTechnicianCountBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeBer) ?> / <?php echo (count($techniciansSeBer) + count($techniciansExBer))?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBer = count($techniciansSeBer);
                                            $technicianCountExBer = count($techniciansExBer);
                                            $totalTechnicianCountBer = $technicianCountSeBer + $technicianCountExBer;
                                    
                                            if ($totalTechnicianCountBer > 0) {
                                                $percentageBer = ceil((count($countTechSavFaisSeBer)) * 100 / ($totalTechnicianCountBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeBer) ?> / <?php echo (count($techniciansSeBer) + count($techniciansExBer))?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBer = count($techniciansSeBer);
                                            $technicianCountExBer = count($techniciansExBer);
                                            $totalTechnicianCountBer = $technicianCountSeBer + $technicianCountExBer;
                                    
                                            if ($totalTechnicianCountBer > 0) {
                                                $percentageBer = ceil((count($countMaSavFaisSeBer)) * 100 / ($totalTechnicianCountBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExBer) + count($countTechSavFaisExBer) + count($countMaSavFaisExBer) ?> / <?php echo count($techniciansExBer) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExBer = count($techniciansExBer);
                                    
                                            if ($technicianCountExBer > 0) {
                                                $percentageBer = ceil((count($countSavoirsExBer)) * 100 / ($technicianCountExBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExBer) ?> / <?php echo count($techniciansExBer) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBer = count($techniciansExBer);
                                    
                                            if ($technicianCountExBer > 0) {
                                                $percentageBer = ceil((count($countSavoirsExBer)) * 100 / ($technicianCountExBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExBer) ?> / <?php echo count($techniciansExBer) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBer = count($techniciansExBer);
                                    
                                            if ($technicianCountExBer > 0) {
                                                $percentageBer = ceil((count($countTechSavFaisExBer)) * 100 / ($technicianCountExBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExBer) ?> / <?php echo count($techniciansExBer) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBer = count($techniciansExBer);
                                    
                                            if ($technicianCountExBer > 0) {
                                                $percentageBer = ceil((count($countMaSavFaisExBer)) * 100 / ($technicianCountExBer));
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuBer) + count($countTechSavFaisJuBer) + count($countMaSavFaisJuBer) + count($countSavoirsSeBer) + count($countTechSavFaisSeBer) + count($countMaSavFaisSeBer) + count($countSavoirsExBer) + count($countTechSavFaisExBer) + count($countMaSavFaisExBer) ?> / <?php echo (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBer = (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) * 3;
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil((count($countSavoirsJuBer) + count($countTechSavFaisJuBer) + count($countMaSavFaisJuBer) + count($countSavoirsSeBer) + count($countTechSavFaisSeBer) + count($countMaSavFaisSeBer) + count($countSavoirsExBer) + count($countTechSavFaisExBer) + count($countMaSavFaisExBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuBer) + count($countSavoirsSeBer) + count($countSavoirsExBer)) ?> / <?php echo (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBer = (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2));
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil((count($countSavoirsJuBer) + count($countSavoirsSeBer) + count($countSavoirsExBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countTechSavFaisJuBer) + count($countTechSavFaisSeBer) + count($countTechSavFaisExBer) ?> / <?php echo (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBer = (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2));
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil((count($countTechSavFaisJuBer) + count($countTechSavFaisSeBer) + count($countTechSavFaisExBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuBer) + count($countMaSavFaisSeBer) + count($countMaSavFaisExBer)) ?> / <?php echo (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBer = (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2));
                                            if ($technicianCountBer > 0) {
                                                $percentageBer = ceil((count($countMaSavFaisJuBer) + count($countMaSavFaisSeBer) + count($countMaSavFaisExBer)) * 100 / $technicianCountBer);
                                            } else {
                                                $percentageBer = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBer . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $senegal ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                DAKAR
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuDa) + count($countTechSavFaisJuDa) + count($countMaSavFaisJuDa) ?> / <?php echo count($techniciansDa) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountDa = count($techniciansDa) * 3;
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsJuDa) + count($countTechSavFaisJuDa) + count($countMaSavFaisJuDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuDa) ?> / <?php echo count($techniciansDa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountDa = count($techniciansDa);
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsJuDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuDa) ?> / <?php echo count($techniciansDa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountDa = count($techniciansDa);
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countTechSavFaisJuDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuDa) ?> / <?php echo count($techniciansDa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountDa = count($techniciansDa);
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countMaSavFaisJuDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $senegal ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeDa) + count($countTechSavFaisSeDa) + count($countMaSavFaisSeDa) ?> / <?php echo (count($techniciansSeDa) + count($techniciansExDa)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeDa = count($techniciansSeDa);
                                            $technicianCountExDa = count($techniciansExDa);
                                            $totalTechnicianCountDa = ($technicianCountSeDa + $technicianCountExDa) * 3;
                                    
                                            if ($totalTechnicianCountDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsSeDa) + count($countTechSavFaisSeDa) + count($countMaSavFaisSeDa)) * 100 / ($totalTechnicianCountDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeDa) ?> / <?php echo (count($techniciansSeDa) + count($techniciansExDa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeDa = count($techniciansSeDa);
                                            $technicianCountExDa = count($techniciansExDa);
                                            $totalTechnicianCountDa = $technicianCountSeDa + $technicianCountExDa;
                                    
                                            if ($totalTechnicianCountDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsSeDa)) * 100 / ($totalTechnicianCountDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeDa) ?> / <?php echo (count($techniciansSeDa) + count($techniciansExDa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeDa = count($techniciansSeDa);
                                            $technicianCountExDa = count($techniciansExDa);
                                            $totalTechnicianCountDa = $technicianCountSeDa + $technicianCountExDa;
                                    
                                            if ($totalTechnicianCountDa > 0) {
                                                $percentageDa = ceil((count($countTechSavFaisSeDa)) * 100 / ($totalTechnicianCountDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeDa) ?> / <?php echo (count($techniciansSeDa) + count($techniciansExDa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeDa = count($techniciansSeDa);
                                            $technicianCountExDa = count($techniciansExDa);
                                            $totalTechnicianCountDa = $technicianCountSeDa + $technicianCountExDa;
                                    
                                            if ($totalTechnicianCountDa > 0) {
                                                $percentageDa = ceil((count($countMaSavFaisSeDa)) * 100 / ($totalTechnicianCountDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $senegal ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExDa) + count($countTechSavFaisExDa) + count($countMaSavFaisExDa) ?> / <?php echo count($techniciansExDa) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExDa = count($techniciansExDa) * 3;
                                    
                                            if ($technicianCountExDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsExDa) + count($countTechSavFaisExDa) + count($countMaSavFaisExDa)) * 100 / ($technicianCountExDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExDa) ?> / <?php echo count($techniciansExDa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExDa = count($techniciansExDa);
                                    
                                            if ($technicianCountExDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsExDa)) * 100 / ($technicianCountExDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExDa) ?> / <?php echo count($techniciansExDa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExDa = count($techniciansExDa);
                                    
                                            if ($technicianCountExDa > 0) {
                                                $percentageDa = ceil((count($countTechSavFaisExDa)) * 100 / ($technicianCountExDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExDa) ?> / <?php echo count($techniciansExDa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExDa = count($techniciansExDa);
                                    
                                            if ($technicianCountExDa > 0) {
                                                $percentageDa = ceil((count($countMaSavFaisExDa)) * 100 / ($technicianCountExDa));
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $senegal ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuDa) + count($countTechSavFaisJuDa) + count($countMaSavFaisJuDa) + count($countSavoirsSeDa) + count($countTechSavFaisSeDa) + count($countMaSavFaisSeDa) + count($countSavoirsExDa) + count($countTechSavFaisExDa) + count($countMaSavFaisExDa) ?> / <?php echo (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDa = (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) * 3;
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsJuDa) + count($countTechSavFaisJuDa) + count($countMaSavFaisJuDa) + count($countSavoirsSeDa) + count($countTechSavFaisSeDa) + count($countMaSavFaisSeDa) + count($countSavoirsExDa) + count($countTechSavFaisExDa) + count($countMaSavFaisExDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuDa) + count($countSavoirsSeDa) + count($countSavoirsExDa)) ?> / <?php echo (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2))?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDa = (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2));
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countSavoirsJuDa) + count($countSavoirsSeDa) + count($countSavoirsExDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuDa) + count($countTechSavFaisSeDa) + count($countTechSavFaisExDa)) ?> / <?php echo (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2))?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDa = (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2));
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countTechSavFaisJuDa) + count($countTechSavFaisSeDa) + count($countTechSavFaisExDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuDa) + count($countMaSavFaisSeDa) + count($countMaSavFaisExDa)) ?> / <?php echo (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2))?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDa = (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2));
                                            if ($technicianCountDa > 0) {
                                                $percentageDa = ceil((count($countMaSavFaisJuDa) + count($countMaSavFaisSeDa) + count($countMaSavFaisExDa)) * 100 / $technicianCountDa);
                                            } else {
                                                $percentageDa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                DOUALA
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuDo) + count($countTechSavFaisJuDo) + count($countMaSavFaisJuDo) ?> / <?php echo count($techniciansDo) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountDo = count($techniciansDo) * 3;
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsJuDo) + count($countTechSavFaisJuDo) + count($countMaSavFaisJuDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuDo) ?> / <?php echo count($techniciansDo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountDo = count($techniciansDo);
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsJuDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuDo) ?> / <?php echo count($techniciansDo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountDo = count($techniciansDo);
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countTechSavFaisJuDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuDo) ?> / <?php echo count($techniciansDo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountDo = count($techniciansDo);
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countMaSavFaisJuDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeDo) + count($countTechSavFaisSeDo) + count($countMaSavFaisSeDo) ?> / <?php echo (count($techniciansSeDo) + count($techniciansExDo)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeDo = count($techniciansSeDo);
                                            $technicianCountExDo = count($techniciansExDo);
                                            $totalTechnicianCountDo = ($technicianCountSeDo + $technicianCountExDo) * 3;
                                    
                                            if ($totalTechnicianCountDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsSeDo) + count($countTechSavFaisSeDo) + count($countMaSavFaisSeDo)) * 100 / ($totalTechnicianCountDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeDo) ?> / <?php echo (count($techniciansSeDo) + count($techniciansExDo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeDo = count($techniciansSeDo);
                                            $technicianCountExDo = count($techniciansExDo);
                                            $totalTechnicianCountDo = $technicianCountSeDo + $technicianCountExDo;
                                    
                                            if ($totalTechnicianCountDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsSeDo)) * 100 / ($totalTechnicianCountDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeDo) ?> / <?php echo (count($techniciansSeDo) + count($techniciansExDo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeDo = count($techniciansSeDo);
                                            $technicianCountExDo = count($techniciansExDo);
                                            $totalTechnicianCountDo = $technicianCountSeDo + $technicianCountExDo;
                                    
                                            if ($totalTechnicianCountDo > 0) {
                                                $percentageDo = ceil((count($countTechSavFaisSeDo)) * 100 / ($totalTechnicianCountDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeDo) ?> / <?php echo (count($techniciansSeDo) + count($techniciansExDo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeDo = count($techniciansSeDo);
                                            $technicianCountExDo = count($techniciansExDo);
                                            $totalTechnicianCountDo = $technicianCountSeDo + $technicianCountExDo;
                                    
                                            if ($totalTechnicianCountDo > 0) {
                                                $percentageDo = ceil((count($countMaSavFaisSeDo)) * 100 / ($totalTechnicianCountDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExDo) + count($countTechSavFaisExDo) + count($countMaSavFaisExDo) ?> / <?php echo count($techniciansExDo) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExDo = count($techniciansExDo) * 3;
                                    
                                            if ($technicianCountExDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsExDo) + count($countTechSavFaisExDo) + count($countMaSavFaisExDo)) * 100 / ($technicianCountExDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExDo) ?> / <?php echo count($techniciansExDo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExDo = count($techniciansExDo);
                                    
                                            if ($technicianCountExDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsExDo)) * 100 / ($technicianCountExDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExDo) ?> / <?php echo count($techniciansExDo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExDo = count($techniciansExDo);
                                    
                                            if ($technicianCountExDo > 0) {
                                                $percentageDo = ceil((count($countTechSavFaisExDo)) * 100 / ($technicianCountExDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExDo) ?> / <?php echo count($techniciansExDo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExDo = count($techniciansExDo);
                                    
                                            if ($technicianCountExDo > 0) {
                                                $percentageDo = ceil((count($countMaSavFaisExDo)) * 100 / ($technicianCountExDo));
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuDo) + count($countTechSavFaisJuDo) + count($countMaSavFaisJuDo) + count($countSavoirsSeDo) + count($countTechSavFaisSeDo) + count($countMaSavFaisSeDo) + count($countSavoirsExDo) + count($countTechSavFaisExDo) + count($countMaSavFaisExDo) ?> / <?php echo (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDo = (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) * 3;
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsJuDo) + count($countTechSavFaisJuDo) + count($countMaSavFaisJuDo) + count($countSavoirsSeDo) + count($countTechSavFaisSeDo) + count($countMaSavFaisSeDo) + count($countSavoirsExDo) + count($countTechSavFaisExDo) + count($countMaSavFaisExDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuDo) + count($countSavoirsSeDo) + count($countSavoirsExDo)) ?> / <?php echo (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDo = (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2));
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countSavoirsJuDo) + count($countSavoirsSeDo) + count($countSavoirsExDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuDo) + count($countTechSavFaisSeDo) + count($countTechSavFaisExDo)) ?> / <?php echo (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDo = (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2));
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countTechSavFaisJuDo) + count($countTechSavFaisSeDo) + count($countTechSavFaisExDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuDo) + count($countMaSavFaisSeDo) + count($countMaSavFaisExDo)) ?> / <?php echo (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountDo = (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2));
                                            if ($technicianCountDo > 0) {
                                                $percentageDo = ceil((count($countMaSavFaisJuDo) + count($countMaSavFaisSeDo) + count($countMaSavFaisExDo)) * 100 / $technicianCountDo);
                                            } else {
                                                $percentageDo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageDo . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                GAROUA
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuGa) + count($countTechSavFaisJuGa) + count($countMaSavFaisJuGa) ?> / <?php echo count($techniciansGa) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountGa = count($techniciansGa) * 3;
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsJuGa) + count($countTechSavFaisJuGa) + count($countMaSavFaisJuGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuGa) ?> / <?php echo count($techniciansGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountGa = count($techniciansGa);
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsJuGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuGa) ?> / <?php echo count($techniciansGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountGa = count($techniciansGa);
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countTechSavFaisJuGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuGa) ?> / <?php echo count($techniciansGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountGa = count($techniciansGa);
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countMaSavFaisJuGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeGa) + count($countTechSavFaisSeGa) + count($countMaSavFaisSeGa) ?> / <?php echo (count($techniciansSeGa) + count($techniciansExGa)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeGa = count($techniciansSeGa);
                                            $technicianCountExGa = count($techniciansExGa);
                                            $totalTechnicianCountGa = ($technicianCountSeGa + $technicianCountExGa) * 3;
                                    
                                            if ($totalTechnicianCountGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsSeGa) + count($countTechSavFaisSeGa) + count($countMaSavFaisSeGa)) * 100 / ($totalTechnicianCountGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeGa) ?> / <?php echo (count($techniciansSeGa) + count($techniciansExGa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeGa = count($techniciansSeGa);
                                            $technicianCountExGa = count($techniciansExGa);
                                            $totalTechnicianCountGa = $technicianCountSeGa + $technicianCountExGa;
                                    
                                            if ($totalTechnicianCountGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsSeGa)) * 100 / ($totalTechnicianCountGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeGa) ?> / <?php echo (count($techniciansSeGa) + count($techniciansExGa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeGa = count($techniciansSeGa);
                                            $technicianCountExGa = count($techniciansExGa);
                                            $totalTechnicianCountGa = $technicianCountSeGa + $technicianCountExGa;
                                    
                                            if ($totalTechnicianCountGa > 0) {
                                                $percentageGa = ceil((count($countTechSavFaisSeGa)) * 100 / ($totalTechnicianCountGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeGa) ?> / <?php echo (count($techniciansSeGa) + count($techniciansExGa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeGa = count($techniciansSeGa);
                                            $technicianCountExGa = count($techniciansExGa);
                                            $totalTechnicianCountGa = $technicianCountSeGa + $technicianCountExGa;
                                    
                                            if ($totalTechnicianCountGa > 0) {
                                                $percentageGa = ceil((count($countMaSavFaisSeGa)) * 100 / ($totalTechnicianCountGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExGa) + count($countTechSavFaisExGa) + count($countMaSavFaisExGa) ?> / <?php echo count($techniciansExGa) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExGa = count($techniciansExGa) * 3;
                                    
                                            if ($technicianCountExGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsExGa) + count($countTechSavFaisExGa) + count($countMaSavFaisExGa)) * 100 / ($technicianCountExGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExGa) ?> / <?php echo count($techniciansExGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExGa = count($techniciansExGa);
                                    
                                            if ($technicianCountExGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsExGa)) * 100 / ($technicianCountExGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExGa) ?> / <?php echo count($techniciansExGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExGa = count($techniciansExGa);
                                    
                                            if ($technicianCountExGa > 0) {
                                                $percentageGa = ceil((count($countTechSavFaisExGa)) * 100 / ($technicianCountExGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExGa) ?> / <?php echo count($techniciansExGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExGa = count($techniciansExGa);
                                    
                                            if ($technicianCountExGa > 0) {
                                                $percentageGa = ceil((count($countMaSavFaisExGa)) * 100 / ($technicianCountExGa));
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuGa) + count($countTechSavFaisJuGa) + count($countMaSavFaisJuGa) + count($countSavoirsSeGa) + count($countTechSavFaisSeGa) + count($countMaSavFaisSeGa) + count($countSavoirsExGa) + count($countTechSavFaisExGa) + count($countMaSavFaisExGa) ?> / <?php echo (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountGa = (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) * 3;
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) ?> / <?php echo (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountGa = (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2));
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsJuGa) + count($countTechSavFaisJuGa) + count($countMaSavFaisJuGa) + count($countSavoirsSeGa) + count($countTechSavFaisSeGa) + count($countMaSavFaisSeGa) + count($countSavoirsExGa) + count($countTechSavFaisExGa) + count($countMaSavFaisExGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) ?> / <?php echo (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountGa = (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2));
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) ?> / <?php echo (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountGa = (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2));
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                KINSHASA
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuKi) + count($countTechSavFaisJuKi) + count($countMaSavFaisJuKi) ?> / <?php echo count($techniciansKi) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountKi = count($techniciansKi) * 3;
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsJuKi) + count($countTechSavFaisJuKi) + count($countMaSavFaisJuKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuKi) ?> / <?php echo count($techniciansKi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountKi = count($techniciansKi);
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsJuKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuKi) ?> / <?php echo count($techniciansKi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountKi = count($techniciansKi);
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countTechSavFaisJuKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuKi) ?> / <?php echo count($techniciansKi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountKi = count($techniciansKi);
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countMaSavFaisJuKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeKi) + count($countTechSavFaisSeKi) + count($countMaSavFaisSeKi) ?> / <?php echo (count($techniciansSeKi) + count($techniciansExKi)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeKi = count($techniciansSeKi);
                                            $technicianCountExKi = count($techniciansExKi);
                                            $totalTechnicianCountKi = ($technicianCountSeKi + $technicianCountExKi) * 3;
                                    
                                            if ($totalTechnicianCountKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsSeKi) + count($countTechSavFaisSeKi) + count($countMaSavFaisSeKi)) * 100 / ($totalTechnicianCountKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeKi) ?> / <?php echo (count($techniciansSeKi) + count($techniciansExKi)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeKi = count($techniciansSeKi);
                                            $technicianCountExKi = count($techniciansExKi);
                                            $totalTechnicianCountKi = $technicianCountSeKi + $technicianCountExKi;
                                    
                                            if ($totalTechnicianCountKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsSeKi)) * 100 / ($totalTechnicianCountKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeKi) ?> / <?php echo (count($techniciansSeKi) + count($techniciansExKi)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeKi = count($techniciansSeKi);
                                            $technicianCountExKi = count($techniciansExKi);
                                            $totalTechnicianCountKi = $technicianCountSeKi + $technicianCountExKi;
                                    
                                            if ($totalTechnicianCountKi > 0) {
                                                $percentageKi = ceil((count($countTechSavFaisSeKi)) * 100 / ($totalTechnicianCountKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeKi) ?> / <?php echo (count($techniciansSeKi) + count($techniciansExKi)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeKi = count($techniciansSeKi);
                                            $technicianCountExKi = count($techniciansExKi);
                                            $totalTechnicianCountKi = $technicianCountSeKi + $technicianCountExKi;
                                    
                                            if ($totalTechnicianCountKi > 0) {
                                                $percentageKi = ceil((count($countMaSavFaisSeKi)) * 100 / ($totalTechnicianCountKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExKi) + count($countTechSavFaisExKi) + count($countMaSavFaisExKi) ?> / <?php echo count($techniciansExKi) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExKi = count($techniciansExKi) * 3;
                                    
                                            if ($technicianCountExKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsExKi) + count($countTechSavFaisExKi) + count($countMaSavFaisExKi)) * 100 / ($technicianCountExKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExKi) ?> / <?php echo count($techniciansExKi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExKi = count($techniciansExKi);
                                    
                                            if ($technicianCountExKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsExKi)) * 100 / ($technicianCountExKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExKi) ?> / <?php echo count($techniciansExKi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExKi = count($techniciansExKi);
                                    
                                            if ($technicianCountExKi > 0) {
                                                $percentageKi = ceil((count($countTechSavFaisExKi)) * 100 / ($technicianCountExKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExKi) ?> / <?php echo count($techniciansExKi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExKi = count($techniciansExKi);
                                    
                                            if ($technicianCountExKi > 0) {
                                                $percentageKi = ceil((count($countMaSavFaisExKi)) * 100 / ($technicianCountExKi));
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuKi) + count($countTechSavFaisJuKi) + count($countMaSavFaisJuKi) + count($countSavoirsSeKi) + count($countTechSavFaisSeKi) + count($countMaSavFaisSeKi) + count($countSavoirsExKi) + count($countTechSavFaisExKi) + count($countMaSavFaisExKi) ?> / <?php echo (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKi = (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) * 3;
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsJuKi) + count($countTechSavFaisJuKi) + count($countMaSavFaisJuKi) + count($countSavoirsSeKi) + count($countTechSavFaisSeKi) + count($countMaSavFaisSeKi) + count($countSavoirsExKi) + count($countTechSavFaisExKi) + count($countMaSavFaisExKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuKi) + count($countSavoirsSeKi) + count($countSavoirsExKi)) ?> / <?php echo (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKi = (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2));
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countSavoirsJuKi) + count($countSavoirsSeKi) + count($countSavoirsExKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuKi) + count($countTechSavFaisSeKi) + count($countTechSavFaisExKi)) ?> / <?php echo (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKi = (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2));
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countTechSavFaisJuKi) + count($countTechSavFaisSeKi) + count($countTechSavFaisExKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuKi) + count($countMaSavFaisSeKi) + count($countMaSavFaisExKi)) ?> / <?php echo (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKi = (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2));
                                            if ($technicianCountKi > 0) {
                                                $percentageKi = ceil((count($countMaSavFaisJuKi) + count($countMaSavFaisSeKi) + count($countMaSavFaisExKi)) * 100 / $technicianCountKi);
                                            } else {
                                                $percentageKi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKi . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                KOLWEZI
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuKo) + count($countTechSavFaisJuKo) + count($countMaSavFaisJuKo) ?> / <?php echo count($techniciansKo) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountKo = count($techniciansKo) * 3;
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsJuKo) + count($countTechSavFaisJuKo) + count($countMaSavFaisJuKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuKo) ?> / <?php echo count($techniciansKo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountKo = count($techniciansKo);
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsJuKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuKo) ?> / <?php echo count($techniciansKo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountKo = count($techniciansKo);
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countTechSavFaisJuKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuKo) ?> / <?php echo count($techniciansKo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountKo = count($techniciansKo);
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countMaSavFaisJuKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeKo) + count($countTechSavFaisSeKo) + count($countMaSavFaisSeKo) ?> / <?php echo (count($techniciansSeKo) + count($techniciansExKo)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeKo = count($techniciansSeKo);
                                            $technicianCountExKo = count($techniciansExKo);
                                            $totalTechnicianCountKo = ($technicianCountSeKo + $technicianCountExKo) * 3;
                                    
                                            if ($totalTechnicianCountKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsSeKo) + count($countTechSavFaisSeKo) + count($countMaSavFaisSeKo)) * 100 / ($totalTechnicianCountKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeKo) ?> / <?php echo (count($techniciansSeKo) + count($techniciansExKo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeKo = count($techniciansSeKo);
                                            $technicianCountExKo = count($techniciansExKo);
                                            $totalTechnicianCountKo = $technicianCountSeKo + $technicianCountExKo;
                                    
                                            if ($totalTechnicianCountKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsSeKo)) * 100 / ($totalTechnicianCountKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeKo) ?> / <?php echo (count($techniciansSeKo) + count($techniciansExKo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeKo = count($techniciansSeKo);
                                            $technicianCountExKo = count($techniciansExKo);
                                            $totalTechnicianCountKo = $technicianCountSeKo + $technicianCountExKo;
                                    
                                            if ($totalTechnicianCountKo > 0) {
                                                $percentageKo = ceil((count($countTechSavFaisSeKo)) * 100 / ($totalTechnicianCountKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeKo) ?> / <?php echo (count($techniciansSeKo) + count($techniciansExKo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeKo = count($techniciansSeKo);
                                            $technicianCountExKo = count($techniciansExKo);
                                            $totalTechnicianCountKo = $technicianCountSeKo + $technicianCountExKo;
                                    
                                            if ($totalTechnicianCountKo > 0) {
                                                $percentageKo = ceil((count($countMaSavFaisSeKo)) * 100 / ($totalTechnicianCountKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExKo) + count($countTechSavFaisExKo) + count($countMaSavFaisExKo) ?> / <?php echo count($techniciansExKo) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExKo = count($techniciansExKo) * 3;
                                    
                                            if ($technicianCountExKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsExKo) + count($countTechSavFaisExKo) + count($countMaSavFaisExKo)) * 100 / ($technicianCountExKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExKo) ?> / <?php echo count($techniciansExKo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExKo = count($techniciansExKo);
                                    
                                            if ($technicianCountExKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsExKo)) * 100 / ($technicianCountExKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExKo) ?> / <?php echo count($techniciansExKo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExKo = count($techniciansExKo);
                                    
                                            if ($technicianCountExKo > 0) {
                                                $percentageKo = ceil((count($countTechSavFaisExKo)) * 100 / ($technicianCountExKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExKo) ?> / <?php echo count($techniciansExKo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExKo = count($techniciansExKo);
                                    
                                            if ($technicianCountExKo > 0) {
                                                $percentageKo = ceil((count($countMaSavFaisExKo)) * 100 / ($technicianCountExKo));
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuKo) + count($countTechSavFaisJuKo) + count($countMaSavFaisJuKo) + count($countSavoirsSeKo) + count($countTechSavFaisSeKo) + count($countMaSavFaisSeKo) + count($countSavoirsExKo) + count($countTechSavFaisExKo) + count($countMaSavFaisExKo) ?> / <?php echo (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKo = (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) * 3;
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsJuKo) + count($countTechSavFaisJuKo) + count($countMaSavFaisJuKo) + count($countSavoirsSeKo) + count($countTechSavFaisSeKo) + count($countMaSavFaisSeKo) + count($countSavoirsExKo) + count($countTechSavFaisExKo) + count($countMaSavFaisExKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuKo) + count($countSavoirsSeKo) + count($countSavoirsExKo)) ?> / <?php echo (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKo = (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2));
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countSavoirsJuKo) + count($countSavoirsSeKo) + count($countSavoirsExKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuKo) + count($countTechSavFaisSeKo) + count($countTechSavFaisExKo)) ?> / <?php echo (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKo = (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2));
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countTechSavFaisJuKo) + count($countTechSavFaisSeKo) + count($countTechSavFaisExKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuKo) + count($countMaSavFaisSeKo) + count($countMaSavFaisExKo)) ?> / <?php echo (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountKo = (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2));
                                            if ($technicianCountKo > 0) {
                                                $percentageKo = ceil((count($countMaSavFaisJuKo) + count($countMaSavFaisSeKo) + count($countMaSavFaisExKo)) * 100 / $technicianCountKo);
                                            } else {
                                                $percentageKo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageKo . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $gabon ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                LIBREVILLE
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuLi) + count($countTechSavFaisJuLi) + count($countMaSavFaisJuLi) ?> / <?php echo count($techniciansLi) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountLi = count($techniciansLi) * 3;
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsJuLi) + count($countTechSavFaisJuLi) + count($countMaSavFaisJuLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuLi) ?> / <?php echo count($techniciansLi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountLi = count($techniciansLi);
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsJuLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuLi) ?> / <?php echo count($techniciansLi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountLi = count($techniciansLi);
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countTechSavFaisJuLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuLi) ?> / <?php echo count($techniciansLi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountLi = count($techniciansLi);
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countMaSavFaisJuLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $gabon ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeLi) + count($countTechSavFaisSeLi) + count($countMaSavFaisSeLi) ?> / <?php echo (count($techniciansSeLi) + count($techniciansExLi)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeLi = count($techniciansSeLi);
                                            $technicianCountExLi = count($techniciansExLi);
                                            $totalTechnicianCountLi = ($technicianCountSeLi + $technicianCountExLi) * 3;
                                    
                                            if ($totalTechnicianCountLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsSeLi) + count($countTechSavFaisSeLi) + count($countMaSavFaisSeLi)) * 100 / ($totalTechnicianCountLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeLi) ?> / <?php echo (count($techniciansSeLi) + count($techniciansExLi)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeLi = count($techniciansSeLi);
                                            $technicianCountExLi = count($techniciansExLi);
                                            $totalTechnicianCountLi = $technicianCountSeLi + $technicianCountExLi;
                                    
                                            if ($totalTechnicianCountLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsSeLi)) * 100 / ($totalTechnicianCountLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeLi) ?> / <?php echo (count($techniciansSeLi) + count($techniciansExLi)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeLi = count($techniciansSeLi);
                                            $technicianCountExLi = count($techniciansExLi);
                                            $totalTechnicianCountLi = $technicianCountSeLi + $technicianCountExLi;
                                    
                                            if ($totalTechnicianCountLi > 0) {
                                                $percentageLi = ceil((count($countTechSavFaisSeLi)) * 100 / ($totalTechnicianCountLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeLi) ?> / <?php echo (count($techniciansSeLi) + count($techniciansExLi)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeLi = count($techniciansSeLi);
                                            $technicianCountExLi = count($techniciansExLi);
                                            $totalTechnicianCountLi = $technicianCountSeLi + $technicianCountExLi;
                                    
                                            if ($totalTechnicianCountLi > 0) {
                                                $percentageLi = ceil((count($countMaSavFaisSeLi)) * 100 / ($totalTechnicianCountLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $gabon ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExLi) + count($countTechSavFaisExLi) + count($countMaSavFaisExLi) ?> / <?php echo count($techniciansExLi) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExLi = count($techniciansExLi) * 3;
                                    
                                            if ($technicianCountExLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsExLi) + count($countTechSavFaisExLi) + count($countMaSavFaisExLi)) * 100 / ($technicianCountExLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExLi) ?> / <?php echo count($techniciansExLi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExLi = count($techniciansExLi);
                                    
                                            if ($technicianCountExLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsExLi)) * 100 / ($technicianCountExLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExLi) ?> / <?php echo count($techniciansExLi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExLi = count($techniciansExLi);
                                    
                                            if ($technicianCountExLi > 0) {
                                                $percentageLi = ceil((count($countTechSavFaisExLi)) * 100 / ($technicianCountExLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExLi) ?> / <?php echo count($techniciansExLi) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExLi = count($techniciansExLi);
                                    
                                            if ($technicianCountExLi > 0) {
                                                $percentageLi = ceil((count($countMaSavFaisExLi)) * 100 / ($technicianCountExLi));
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $gabon ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuLi) + count($countTechSavFaisJuLi) + count($countMaSavFaisJuLi) + count($countSavoirsSeLi) + count($countTechSavFaisSeLi) + count($countMaSavFaisSeLi) + count($countSavoirsExLi) + count($countTechSavFaisExLi) + count($countMaSavFaisExLi) ?> / <?php echo (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLi = (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) * 3;
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsJuLi) + count($countTechSavFaisJuLi) + count($countMaSavFaisJuLi) + count($countSavoirsSeLi) + count($countTechSavFaisSeLi) + count($countMaSavFaisSeLi) + count($countSavoirsExLi) + count($countTechSavFaisExLi) + count($countMaSavFaisExLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuLi) + count($countSavoirsSeLi) + count($countSavoirsExLi)) ?> / <?php echo (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLi = (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2));
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countSavoirsJuLi) + count($countSavoirsSeLi) + count($countSavoirsExLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuLi) + count($countTechSavFaisSeLi) + count($countTechSavFaisExLi)) ?> / <?php echo (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLi = (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2));
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countTechSavFaisJuLi) + count($countTechSavFaisSeLi) + count($countTechSavFaisExLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuLi) + count($countMaSavFaisSeLi) + count($countMaSavFaisExLi)) ?> / <?php echo (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLi = (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2));
                                            if ($technicianCountLi > 0) {
                                                $percentageLi = ceil((count($countMaSavFaisJuLi) + count($countMaSavFaisSeLi) + count($countMaSavFaisExLi)) * 100 / $technicianCountLi);
                                            } else {
                                                $percentageLi = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLi . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                LUBUMBASHI
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuLu) + count($countTechSavFaisJuLu) + count($countMaSavFaisJuLu) ?> / <?php echo count($techniciansLu) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountLu = count($techniciansLu) * 3;
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsJuLu) + count($countTechSavFaisJuLu) + count($countMaSavFaisJuLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuLu) ?> / <?php echo count($techniciansLu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountLu = count($techniciansLu);
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsJuLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuLu) ?> / <?php echo count($techniciansLu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountLu = count($techniciansLu);
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countTechSavFaisJuLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuLu) ?> / <?php echo count($techniciansLu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountLu = count($techniciansLu);
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countMaSavFaisJuLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeLu) + count($countTechSavFaisSeLu) + count($countMaSavFaisSeLu) ?> / <?php echo (count($techniciansSeLu) + count($techniciansExLu)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeLu = count($techniciansSeLu);
                                            $technicianCountExLu = count($techniciansExLu);
                                            $totalTechnicianCountLu = ($technicianCountSeLu + $technicianCountExLu) * 3;
                                    
                                            if ($totalTechnicianCountLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsSeLu) + count($countTechSavFaisSeLu) + count($countMaSavFaisSeLu)) * 100 / ($totalTechnicianCountLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeLu) ?> / <?php echo (count($techniciansSeLu) + count($techniciansExLu)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeLu = count($techniciansSeLu);
                                            $technicianCountExLu = count($techniciansExLu);
                                            $totalTechnicianCountLu = $technicianCountSeLu + $technicianCountExLu;
                                    
                                            if ($totalTechnicianCountLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsSeLu)) * 100 / ($totalTechnicianCountLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeLu) ?> / <?php echo (count($techniciansSeLu) + count($techniciansExLu)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeLu = count($techniciansSeLu);
                                            $technicianCountExLu = count($techniciansExLu);
                                            $totalTechnicianCountLu = $technicianCountSeLu + $technicianCountExLu;
                                    
                                            if ($totalTechnicianCountLu > 0) {
                                                $percentageLu = ceil((count($countTechSavFaisSeLu)) * 100 / ($totalTechnicianCountLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeLu) ?> / <?php echo (count($techniciansSeLu) + count($techniciansExLu)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeLu = count($techniciansSeLu);
                                            $technicianCountExLu = count($techniciansExLu);
                                            $totalTechnicianCountLu = $technicianCountSeLu + $technicianCountExLu;
                                    
                                            if ($totalTechnicianCountLu > 0) {
                                                $percentageLu = ceil((count($countMaSavFaisSeLu)) * 100 / ($totalTechnicianCountLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExLu) + count($countTechSavFaisExLu) + count($countMaSavFaisExLu) ?> / <?php echo count($techniciansExLu) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExLu = count($techniciansExLu) * 3;
                                    
                                            if ($technicianCountExLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsExLu) + count($countTechSavFaisExLu) + count($countMaSavFaisExLu)) * 100 / ($technicianCountExLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExLu) ?> / <?php echo count($techniciansExLu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExLu = count($techniciansExLu);
                                    
                                            if ($technicianCountExLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsExLu)) * 100 / ($technicianCountExLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExLu) ?> / <?php echo count($techniciansExLu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExLu = count($techniciansExLu);
                                    
                                            if ($technicianCountExLu > 0) {
                                                $percentageLu = ceil((count($countTechSavFaisExLu)) * 100 / ($technicianCountExLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExLu) ?> / <?php echo count($techniciansExLu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExLu = count($techniciansExLu);
                                    
                                            if ($technicianCountExLu > 0) {
                                                $percentageLu = ceil((count($countMaSavFaisExLu)) * 100 / ($technicianCountExLu));
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $rdc ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuLu) + count($countTechSavFaisJuLu) + count($countMaSavFaisJuLu) + count($countSavoirsSeLu) + count($countTechSavFaisSeLu) + count($countMaSavFaisSeLu) + count($countSavoirsExLu) + count($countTechSavFaisExLu) + count($countMaSavFaisExLu) ?> / <?php echo (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLu = (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) * 3;
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsJuLu) + count($countTechSavFaisJuLu) + count($countMaSavFaisJuLu) + count($countSavoirsSeLu) + count($countTechSavFaisSeLu) + count($countMaSavFaisSeLu) + count($countSavoirsExLu) + count($countTechSavFaisExLu) + count($countMaSavFaisExLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuLu) + count($countSavoirsSeLu) + count($countSavoirsExLu)) ?> / <?php echo (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLu = (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2));
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countSavoirsJuLu) + count($countSavoirsSeLu) + count($countSavoirsExLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuLu) + count($countTechSavFaisSeLu) + count($countTechSavFaisExLu)) ?> / <?php echo (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLu = (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2));
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countTechSavFaisJuLu) + count($countTechSavFaisSeLu) + count($countTechSavFaisExLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuLu) + count($countMaSavFaisSeLu) + count($countMaSavFaisExLu)) ?> / <?php echo (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountLu = (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2));
                                            if ($technicianCountLu > 0) {
                                                $percentageLu = ceil((count($countMaSavFaisJuLu) + count($countMaSavFaisSeLu) + count($countMaSavFaisExLu)) * 100 / $technicianCountLu);
                                            } else {
                                                $percentageLu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageLu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                NGAOUNDERE
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuNg) + count($countTechSavFaisJuNg) + count($countMaSavFaisJuNg) ?> / <?php echo count($techniciansNg) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountNg = count($techniciansNg) * 3;
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsJuNg) + count($countTechSavFaisJuNg) + count($countMaSavFaisJuNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuNg) ?> / <?php echo count($techniciansNg) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountNg = count($techniciansNg);
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsJuNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuNg) ?> / <?php echo count($techniciansNg) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountNg = count($techniciansNg);
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countTechSavFaisJuNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuNg) ?> / <?php echo count($techniciansNg) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountNg = count($techniciansNg);
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countMaSavFaisJuNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeNg) + count($countTechSavFaisSeNg) + count($countMaSavFaisSeNg) ?> / <?php echo (count($techniciansSeNg) + count($techniciansExNg)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeNg = count($techniciansSeNg);
                                            $technicianCountExNg = count($techniciansExNg);
                                            $totalTechnicianCountNg = ($technicianCountSeNg + $technicianCountExNg) * 3;
                                    
                                            if ($totalTechnicianCountNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsSeNg) + count($countTechSavFaisSeNg) + count($countMaSavFaisSeNg)) * 100 / ($totalTechnicianCountNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeNg) ?> / <?php echo (count($techniciansSeNg) + count($techniciansExNg)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeNg = count($techniciansSeNg);
                                            $technicianCountExNg = count($techniciansExNg);
                                            $totalTechnicianCountNg = $technicianCountSeNg + $technicianCountExNg;
                                    
                                            if ($totalTechnicianCountNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsSeNg)) * 100 / ($totalTechnicianCountNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeNg) ?> / <?php echo (count($techniciansSeNg) + count($techniciansExNg)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeNg = count($techniciansSeNg);
                                            $technicianCountExNg = count($techniciansExNg);
                                            $totalTechnicianCountNg = $technicianCountSeNg + $technicianCountExNg;
                                    
                                            if ($totalTechnicianCountNg > 0) {
                                                $percentageNg = ceil((count($countTechSavFaisSeNg)) * 100 / ($totalTechnicianCountNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeNg) ?> / <?php echo (count($techniciansSeNg) + count($techniciansExNg)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeNg = count($techniciansSeNg);
                                            $technicianCountExNg = count($techniciansExNg);
                                            $totalTechnicianCountNg = $technicianCountSeNg + $technicianCountExNg;
                                    
                                            if ($totalTechnicianCountNg > 0) {
                                                $percentageNg = ceil((count($countMaSavFaisSeNg)) * 100 / ($totalTechnicianCountNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExNg) + count($countTechSavFaisExNg) + count($countMaSavFaisExNg) ?> / <?php echo count($techniciansExNg) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExNg = count($techniciansExNg) * 3;
                                    
                                            if ($technicianCountExNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsExNg) + count($countTechSavFaisExNg) + count($countMaSavFaisExNg)) * 100 / ($technicianCountExNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExNg) ?> / <?php echo count($techniciansExNg) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExNg = count($techniciansExNg);
                                    
                                            if ($technicianCountExNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsExNg)) * 100 / ($technicianCountExNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExNg) ?> / <?php echo count($techniciansExNg) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExNg = count($techniciansExNg);
                                    
                                            if ($technicianCountExNg > 0) {
                                                $percentageNg = ceil((count($countTechSavFaisExNg)) * 100 / ($technicianCountExNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExNg) ?> / <?php echo count($techniciansExNg) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExNg = count($techniciansExNg);
                                    
                                            if ($technicianCountExNg > 0) {
                                                $percentageNg = ceil((count($countMaSavFaisExNg)) * 100 / ($technicianCountExNg));
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuNg) + count($countTechSavFaisJuNg) + count($countMaSavFaisJuNg) + count($countSavoirsSeNg) + count($countTechSavFaisSeNg) + count($countMaSavFaisSeNg) + count($countSavoirsExNg) + count($countTechSavFaisExNg) + count($countMaSavFaisExNg) ?> / <?php echo (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountNg = (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) * 3;
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsJuNg) + count($countTechSavFaisJuNg) + count($countMaSavFaisJuNg) + count($countSavoirsSeNg) + count($countTechSavFaisSeNg) + count($countMaSavFaisSeNg) + count($countSavoirsExNg) + count($countTechSavFaisExNg) + count($countMaSavFaisExNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuNg) + count($countSavoirsSeNg) + count($countSavoirsExNg)) ?> / <?php echo (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountNg = (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2));
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countSavoirsJuNg) + count($countSavoirsSeNg) + count($countSavoirsExNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuNg) + count($countTechSavFaisSeNg) + count($countTechSavFaisExNg)) ?> / <?php echo (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountNg = (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2));
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countTechSavFaisJuNg) + count($countTechSavFaisSeNg) + count($countTechSavFaisExNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuNg) + count($countMaSavFaisSeNg) + count($countMaSavFaisExNg)) ?> / <?php echo (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountNg = (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2));
                                            if ($technicianCountNg > 0) {
                                                $percentageNg = ceil((count($countMaSavFaisJuNg) + count($countMaSavFaisSeNg) + count($countMaSavFaisExNg)) * 100 / $technicianCountNg);
                                            } else {
                                                $percentageNg = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageNg . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="BURKINA">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                OUAGA
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuOu) + count($countTechSavFaisJuOu) + count($countMaSavFaisJuOu) ?> / <?php echo count($techniciansOu) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountOu = count($techniciansOu) * 3;
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsJuOu) + count($countTechSavFaisJuOu) + count($countMaSavFaisJuOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuOu) ?> / <?php echo count($techniciansOu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountOu = count($techniciansOu);
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsJuOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuOu) ?> / <?php echo count($techniciansOu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountOu = count($techniciansOu);
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countTechSavFaisJuOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuOu) ?> / <?php echo count($techniciansOu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountOu = count($techniciansOu);
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countMaSavFaisJuOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="BURKINA">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeOu) + count($countTechSavFaisSeOu) + count($countMaSavFaisSeOu) ?> / <?php echo (count($techniciansSeOu) + count($techniciansExOu)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeOu = count($techniciansSeOu);
                                            $technicianCountExOu = count($techniciansExOu);
                                            $totalTechnicianCountOu = ($technicianCountSeOu + $technicianCountExOu) * 3;
                                    
                                            if ($totalTechnicianCountOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsSeOu) + count($countTechSavFaisSeOu) + count($countMaSavFaisSeOu)) * 100 / ($totalTechnicianCountOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeOu) ?> / <?php echo (count($techniciansSeOu) + count($techniciansExOu)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeOu = count($techniciansSeOu);
                                            $technicianCountExOu = count($techniciansExOu);
                                            $totalTechnicianCountOu = $technicianCountSeOu + $technicianCountExOu;
                                    
                                            if ($totalTechnicianCountOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsSeOu)) * 100 / ($totalTechnicianCountOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeOu) ?> / <?php echo (count($techniciansSeOu) + count($techniciansExOu)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeOu = count($techniciansSeOu);
                                            $technicianCountExOu = count($techniciansExOu);
                                            $totalTechnicianCountOu = $technicianCountSeOu + $technicianCountExOu;
                                    
                                            if ($totalTechnicianCountOu > 0) {
                                                $percentageOu = ceil((count($countTechSavFaisSeOu)) * 100 / ($totalTechnicianCountOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeOu) ?> / <?php echo (count($techniciansSeOu) + count($techniciansExOu)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeOu = count($techniciansSeOu);
                                            $technicianCountExOu = count($techniciansExOu);
                                            $totalTechnicianCountOu = $technicianCountSeOu + $technicianCountExOu;
                                    
                                            if ($totalTechnicianCountOu > 0) {
                                                $percentageOu = ceil((count($countMaSavFaisSeOu)) * 100 / ($totalTechnicianCountOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="BURKINA">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExOu) + count($countTechSavFaisExOu) + count($countMaSavFaisExOu) ?> / <?php echo count($techniciansExOu) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExOu = count($techniciansExOu) *3;
                                    
                                            if ($technicianCountExOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsExOu) + count($countTechSavFaisExOu) + count($countMaSavFaisExOu)) * 100 / ($technicianCountExOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExOu) ?> / <?php echo count($techniciansExOu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExOu = count($techniciansExOu);
                                    
                                            if ($technicianCountExOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsExOu)) * 100 / ($technicianCountExOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExOu) ?> / <?php echo count($techniciansExOu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExOu = count($techniciansExOu);
                                    
                                            if ($technicianCountExOu > 0) {
                                                $percentageOu = ceil((count($countTechSavFaisExOu)) * 100 / ($technicianCountExOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExOu) ?> / <?php echo count($techniciansExOu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExOu = count($techniciansExOu);
                                    
                                            if ($technicianCountExOu > 0) {
                                                $percentageOu = ceil((count($countMaSavFaisExOu)) * 100 / ($technicianCountExOu));
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="BURKINA">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuOu) + count($countTechSavFaisJuOu) + count($countMaSavFaisJuOu) + count($countSavoirsSeOu) + count($countTechSavFaisSeOu) + count($countMaSavFaisSeOu) + count($countSavoirsExOu) + count($countTechSavFaisExOu) + count($countMaSavFaisExOu) ?> / <?php echo (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountOu = (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) * 3;
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsJuOu) + count($countTechSavFaisJuOu) + count($countMaSavFaisJuOu) + count($countSavoirsSeOu) + count($countTechSavFaisSeOu) + count($countMaSavFaisSeOu) + count($countSavoirsExOu) + count($countTechSavFaisExOu) + count($countMaSavFaisExOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuOu) + count($countSavoirsSeOu) + count($countSavoirsExOu)) ?> / <?php echo (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountOu = (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2));
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countSavoirsJuOu) + count($countSavoirsSeOu) + count($countSavoirsExOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuOu) + count($countTechSavFaisSeOu) + count($countTechSavFaisExOu)) ?> / <?php echo (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountOu = (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2));
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countTechSavFaisJuOu) + count($countTechSavFaisSeOu) + count($countTechSavFaisExOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuOu) + count($countMaSavFaisSeOu) + count($countMaSavFaisExOu)) ?> / <?php echo (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountOu = (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2));
                                            if ($technicianCountOu > 0) {
                                                $percentageOu = ceil((count($countMaSavFaisJuOu) + count($countMaSavFaisSeOu) + count($countMaSavFaisExOu)) * 100 / $technicianCountOu);
                                            } else {
                                                $percentageOu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageOu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $congo ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                POINTE NOIRE
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuPo) + count($countTechSavFaisJuPo) + count($countMaSavFaisJuPo) ?> / <?php echo count($techniciansPo) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountPo = count($techniciansPo) * 3;
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsJuPo) + count($countTechSavFaisJuPo) + count($countMaSavFaisJuPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuPo) ?> / <?php echo count($techniciansPo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountPo = count($techniciansPo);
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsJuPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuPo) ?> / <?php echo count($techniciansPo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountPo = count($techniciansPo);
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countTechSavFaisJuPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuPo) ?> / <?php echo count($techniciansPo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountPo = count($techniciansPo);
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countMaSavFaisJuPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $congo ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSePo) + count($countTechSavFaisSePo) + count($countMaSavFaisSePo) ?> / <?php echo (count($techniciansSePo) + count($techniciansExPo)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSePo = count($techniciansSePo);
                                            $technicianCountExPo = count($techniciansExPo);
                                            $totalTechnicianCountPo = ($technicianCountSePo + $technicianCountExPo) * 3;
                                    
                                            if ($totalTechnicianCountPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsSePo) + count($countTechSavFaisSePo) + count($countMaSavFaisSePo)) * 100 / ($totalTechnicianCountPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSePo) ?> / <?php echo (count($techniciansSePo) + count($techniciansExPo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSePo = count($techniciansSePo);
                                            $technicianCountExPo = count($techniciansExPo);
                                            $totalTechnicianCountPo = $technicianCountSePo + $technicianCountExPo;
                                    
                                            if ($totalTechnicianCountPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsSePo)) * 100 / ($totalTechnicianCountPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSePo) ?> / <?php echo (count($techniciansSePo) + count($techniciansExPo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSePo = count($techniciansSePo);
                                            $technicianCountExPo = count($techniciansExPo);
                                            $totalTechnicianCountPo = $technicianCountSePo + $technicianCountExPo;
                                    
                                            if ($totalTechnicianCountPo > 0) {
                                                $percentagePo = ceil((count($countTechSavFaisSePo)) * 100 / ($totalTechnicianCountPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSePo) ?> / <?php echo (count($techniciansSePo) + count($techniciansExPo)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSePo = count($techniciansSePo);
                                            $technicianCountExPo = count($techniciansExPo);
                                            $totalTechnicianCountPo = $technicianCountSePo + $technicianCountExPo;
                                    
                                            if ($totalTechnicianCountPo > 0) {
                                                $percentagePo = ceil((count($countMaSavFaisSePo)) * 100 / ($totalTechnicianCountPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $congo ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExPo) + count($countTechSavFaisExPo) + count($countMaSavFaisExPo) ?> / <?php echo count($techniciansExPo) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExPo = count($techniciansExPo) * 3;
                                    
                                            if ($technicianCountExPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsExPo) + count($countTechSavFaisExPo) + count($countMaSavFaisExPo)) * 100 / ($technicianCountExPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExPo) ?> / <?php echo count($techniciansExPo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExPo = count($techniciansExPo);
                                    
                                            if ($technicianCountExPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsExPo)) * 100 / ($technicianCountExPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExPo) ?> / <?php echo count($techniciansExPo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExPo = count($techniciansExPo);
                                    
                                            if ($technicianCountExPo > 0) {
                                                $percentagePo = ceil((count($countTechSavFaisExPo)) * 100 / ($technicianCountExPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExPo) ?> / <?php echo count($techniciansExPo) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExPo = count($techniciansExPo);
                                    
                                            if ($technicianCountExPo > 0) {
                                                $percentagePo = ceil((count($countMaSavFaisExPo)) * 100 / ($technicianCountExPo));
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $congo ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuPo) + count($countTechSavFaisJuPo) + count($countMaSavFaisJuPo) + count($countSavoirsSePo) + count($countTechSavFaisSePo) + count($countMaSavFaisSePo) + count($countSavoirsExPo) + count($countTechSavFaisExPo) + count($countMaSavFaisExPo) ?> / <?php echo (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountPo = (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) * 3;
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsJuPo) + count($countTechSavFaisJuPo) + count($countMaSavFaisJuPo) + count($countSavoirsSePo) + count($countTechSavFaisSePo) + count($countMaSavFaisSePo) + count($countSavoirsExPo) + count($countTechSavFaisExPo) + count($countMaSavFaisExPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuPo) + count($countSavoirsSePo) + count($countSavoirsExPo)) ?> / <?php echo (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountPo = (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2));
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countSavoirsJuPo) + count($countSavoirsSePo) + count($countSavoirsExPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuPo) + count($countTechSavFaisSePo) + count($countTechSavFaisExPo)) ?> / <?php echo (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountPo = (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2));
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countTechSavFaisJuPo) + count($countTechSavFaisSePo) + count($countTechSavFaisExPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuPo) + count($countMaSavFaisSePo) + count($countMaSavFaisExPo)) ?> / <?php echo (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountPo = (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2));
                                            if ($technicianCountPo > 0) {
                                                $percentagePo = ceil((count($countMaSavFaisJuPo) + count($countMaSavFaisSePo) + count($countMaSavFaisExPo)) * 100 / $technicianCountPo);
                                            } else {
                                                $percentagePo = 0; // or any other appropriate value or message
                                            }
                                            echo $percentagePo . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="RCI">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                VRIDI - EQUIP
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuVr) + count($countTechSavFaisJuVr) + count($countMaSavFaisJuVr) ?> / <?php echo count($techniciansVr) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountVr = count($techniciansVr) * 3;
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsJuVr) + count($countTechSavFaisJuVr) + count($countMaSavFaisJuVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuVr) ?> / <?php echo count($techniciansVr) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountVr = count($techniciansVr);
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsJuVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuVr) ?> / <?php echo count($techniciansVr) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountVr = count($techniciansVr);
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countTechSavFaisJuVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuVr) ?> / <?php echo count($techniciansVr) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountVr = count($techniciansVr);
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countMaSavFaisJuVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="RCI">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeVr) + count($countTechSavFaisSeVr) + count($countMaSavFaisSeVr) ?> / <?php echo (count($techniciansSeVr) + count($techniciansExVr)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeVr = count($techniciansSeVr);
                                            $technicianCountExVr = count($techniciansExVr);
                                            $totalTechnicianCountVr = ($technicianCountSeVr + $technicianCountExVr) * 3;
                                    
                                            if ($totalTechnicianCountVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsSeVr) + count($countTechSavFaisSeVr) + count($countMaSavFaisSeVr)) * 100 / ($totalTechnicianCountVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeVr) ?> / <?php echo (count($techniciansSeVr) + count($techniciansExVr)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeVr = count($techniciansSeVr);
                                            $technicianCountExVr = count($techniciansExVr);
                                            $totalTechnicianCountVr = $technicianCountSeVr + $technicianCountExVr;
                                    
                                            if ($totalTechnicianCountVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsSeVr)) * 100 / ($totalTechnicianCountVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeVr) ?> / <?php echo (count($techniciansSeVr) + count($techniciansExVr)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeVr = count($techniciansSeVr);
                                            $technicianCountExVr = count($techniciansExVr);
                                            $totalTechnicianCountVr = $technicianCountSeVr + $technicianCountExVr;
                                    
                                            if ($totalTechnicianCountVr > 0) {
                                                $percentageVr = ceil((count($countTechSavFaisSeVr)) * 100 / ($totalTechnicianCountVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeVr) ?> / <?php echo (count($techniciansSeVr) + count($techniciansExVr)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeVr = count($techniciansSeVr);
                                            $technicianCountExVr = count($techniciansExVr);
                                            $totalTechnicianCountVr = $technicianCountSeVr + $technicianCountExVr;
                                    
                                            if ($totalTechnicianCountVr > 0) {
                                                $percentageVr = ceil((count($countMaSavFaisSeVr)) * 100 / ($totalTechnicianCountVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="RCI">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExVr) + count($countTechSavFaisExVr) + count($countMaSavFaisExVr) ?> / <?php echo count($techniciansExVr) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExVr = count($techniciansExVr) * 3;
                                    
                                            if ($technicianCountExVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsExVr) + count($countTechSavFaisExVr) + count($countMaSavFaisExVr)) * 100 / ($technicianCountExVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExVr) ?> / <?php echo count($techniciansExVr) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExVr = count($techniciansExVr);
                                    
                                            if ($technicianCountExVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsExVr)) * 100 / ($technicianCountExVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExVr) ?> / <?php echo count($techniciansExVr) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExVr = count($techniciansExVr);
                                    
                                            if ($technicianCountExVr > 0) {
                                                $percentageVr = ceil((count($countTechSavFaisExVr)) * 100 / ($technicianCountExVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExVr) ?> / <?php echo count($techniciansExVr) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExVr = count($techniciansExVr);
                                    
                                            if ($technicianCountExVr > 0) {
                                                $percentageVr = ceil((count($countMaSavFaisExVr)) * 100 / ($technicianCountExVr));
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="RCI">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuVr) + count($countTechSavFaisJuVr) + count($countMaSavFaisJuVr) + count($countSavoirsSeVr) + count($countTechSavFaisSeVr) + count($countMaSavFaisSeVr) + count($countSavoirsExVr) + count($countTechSavFaisExVr) + count($countMaSavFaisExVr) ?> / <?php echo (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountVr = (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) * 3;
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsJuVr) + count($countTechSavFaisJuVr) + count($countMaSavFaisJuVr) + count($countSavoirsSeVr) + count($countTechSavFaisSeVr) + count($countMaSavFaisSeVr) + count($countSavoirsExVr) + count($countTechSavFaisExVr) + count($countMaSavFaisExVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuVr) + count($countSavoirsSeVr) + count($countSavoirsExVr)) ?> / <?php echo (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountVr = (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2));
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countSavoirsJuVr) + count($countSavoirsSeVr) + count($countSavoirsExVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuVr) + count($countTechSavFaisSeVr) + count($countTechSavFaisExVr)) ?> / <?php echo (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountVr = (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2));
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countTechSavFaisJuVr) + count($countTechSavFaisSeVr) + count($countTechSavFaisExVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuVr) + count($countMaSavFaisSeVr) + count($countMaSavFaisExVr)) ?> / <?php echo (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountVr = (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2));
                                            if ($technicianCountVr > 0) {
                                                $percentageVr = ceil((count($countMaSavFaisJuVr) + count($countMaSavFaisSeVr) + count($countMaSavFaisExVr)) * 100 / $technicianCountVr);
                                            } else {
                                                $percentageVr = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageVr . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                YAOUNDE
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsJuYa) + count($countTechSavFaisJuYa) + count($countMaSavFaisJuYa) ?> / <?php echo count($techniciansYa) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php $technicianCountYa = count($techniciansYa) * 3;
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsJuYa) + count($countTechSavFaisJuYa) + count($countMaSavFaisJuYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuYa) ?> / <?php echo count($techniciansYa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountYa = count($techniciansYa);
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsJuYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuYa) ?> / <?php echo count($techniciansYa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountYa = count($techniciansYa);
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countTechSavFaisJuYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuYa) ?> / <?php echo count($techniciansYa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountYa = count($techniciansYa);
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countMaSavFaisJuYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsSeYa) + count($countTechSavFaisSeYa) + count($countMaSavFaisSeYa) ?> / <?php echo (count($techniciansSeYa) + count($techniciansExYa)) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountSeYa = count($techniciansSeYa);
                                            $technicianCountExYa = count($techniciansExYa);
                                            $totalTechnicianCountYa = ($technicianCountSeYa + $technicianCountExYa) * 3;
                                    
                                            if ($totalTechnicianCountYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsSeYa) + count($countTechSavFaisSeYa) + count($countMaSavFaisSeYa)) * 100 / ($totalTechnicianCountYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeYa) ?> / <?php echo (count($techniciansSeYa) + count($techniciansExYa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeYa = count($techniciansSeYa);
                                            $technicianCountExYa = count($techniciansExYa);
                                            $totalTechnicianCountYa = $technicianCountSeYa + $technicianCountExYa;
                                    
                                            if ($totalTechnicianCountYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsSeYa)) * 100 / ($totalTechnicianCountYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeYa) ?> / <?php echo (count($techniciansSeYa) + count($techniciansExYa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeYa = count($techniciansSeYa);
                                            $technicianCountExYa = count($techniciansExYa);
                                            $totalTechnicianCountYa = $technicianCountSeYa + $technicianCountExYa;
                                    
                                            if ($totalTechnicianCountYa > 0) {
                                                $percentageYa = ceil((count($countTechSavFaisSeYa)) * 100 / ($totalTechnicianCountYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeYa) ?> / <?php echo (count($techniciansSeYa) + count($techniciansExYa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeYa = count($techniciansSeYa);
                                            $technicianCountExYa = count($techniciansExYa);
                                            $totalTechnicianCountYa = $technicianCountSeYa + $technicianCountExYa;
                                    
                                            if ($totalTechnicianCountYa > 0) {
                                                $percentageYa = ceil((count($countMaSavFaisSeYa)) * 100 / ($totalTechnicianCountYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php echo count($countSavoirsExYa) + count($countTechSavFaisExYa) + count($countMaSavFaisExYa) ?> / <?php echo count($techniciansExYa) * 3 ?>
                                        </td>
                                        <td class="text-center" style="background-color: #edf2f7;">
                                            <?php
                                            $technicianCountExYa = count($techniciansExYa) * 3;
                                    
                                            if ($technicianCountExYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsExYa) + count($countTechSavFaisExYa) + count($countMaSavFaisExYa)) * 100 / ($technicianCountExYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExYa) ?> / <?php echo count($techniciansExYa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExYa = count($techniciansExYa);
                                    
                                            if ($technicianCountExYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsExYa)) * 100 / ($technicianCountExYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExYa) ?> / <?php echo count($techniciansExYa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExYa = count($techniciansExYa);
                                    
                                            if ($technicianCountExYa > 0) {
                                                $percentageYa = ceil((count($countTechSavFaisExYa)) * 100 / ($technicianCountExYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExYa) ?> / <?php echo count($techniciansExYa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExYa = count($techniciansExYa);
                                    
                                            if ($technicianCountExYa > 0) {
                                                $percentageYa = ceil((count($countMaSavFaisExYa)) * 100 / ($technicianCountExYa));
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                        <?php echo count($countSavoirsJuYa) + count($countTechSavFaisJuYa) + count($countMaSavFaisJuYa) + count($countSavoirsSeYa) + count($countTechSavFaisSeYa) + count($countMaSavFaisSeYa) + count($countSavoirsExYa) + count($countTechSavFaisExYa) + count($countMaSavFaisExYa) ?> / <?php echo (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2)) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountYa = (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2)) * 3;
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsJuYa) + count($countTechSavFaisJuYa) + count($countMaSavFaisJuYa) + count($countSavoirsSeYa) + count($countTechSavFaisSeYa) + count($countMaSavFaisSeYa) + count($countSavoirsExYa) + count($countTechSavFaisExYa) + count($countMaSavFaisExYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuYa) + count($countSavoirsSeYa) + count($countSavoirsExYa)) ?> / <?php echo (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountYa = (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2));
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countSavoirsJuYa) + count($countSavoirsSeYa) + count($countSavoirsExYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuYa) + count($countTechSavFaisSeYa) + count($countTechSavFaisExYa)) ?> / <?php echo (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountYa = (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2));
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countTechSavFaisJuYa) + count($countTechSavFaisSeYa) + count($countTechSavFaisExYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuYa) + count($countMaSavFaisSeYa) + count($countMaSavFaisExYa)) ?> / <?php echo (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountYa = (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2));
                                            if ($technicianCountYa > 0) {
                                                $percentageYa = ceil((count($countMaSavFaisJuYa) + count($countMaSavFaisSeYa) + count($countMaSavFaisExYa)) * 100 / $technicianCountYa);
                                            } else {
                                                $percentageYa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageYa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="<?php echo $cameroun ?>">
                                        <th class=" text-center" colspan="10">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class=" text-center fw-bolder" style="background: #edf2f7;" colspan="2">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $result ?>
                                            </a>
                                        </th>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php echo ((count($countSavoirsJuBam) + count($countSavoirsSeBam) + count($countSavoirsExBam)) + (count($countSavoirsJuBan) + count($countSavoirsSeBan) + count($countSavoirsExBan)) + (count($countSavoirsJuBer) + count($countSavoirsSeBer) + count($countSavoirsExBer)) + (count($countSavoirsJuDa) + count($countSavoirsSeDa) + count($countSavoirsExDa)) + (count($countSavoirsJuDo) + count($countSavoirsSeDo) + count($countSavoirsExDo)) + (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) + (count($countSavoirsJuKi) + count($countSavoirsSeKi) + count($countSavoirsExKi)) + (count($countSavoirsJuYa) + count($countSavoirsSeYa) + count($countSavoirsExYa)) + (count($countSavoirsJuKo) + count($countSavoirsSeKo) + count($countSavoirsExKo)) + (count($countSavoirsJuLi) + count($countSavoirsSeLi) + count($countSavoirsExLi)) + (count($countSavoirsJuLu) + count($countSavoirsSeLu) + count($countSavoirsExLu)) + (count($countSavoirsJuNg) + count($countSavoirsSeNg) + count($countSavoirsExNg)) + (count($countSavoirsJuOu) + count($countSavoirsSeOu) + count($countSavoirsExOu)) + (count($countSavoirsJuPo) + count($countSavoirsSePo) + count($countSavoirsExPo)) + (count($countSavoirsJuVr) + count($countSavoirsSeVr) + count($countSavoirsExVr))) + ((count($countTechSavFaisJuBam) + count($countTechSavFaisSeBam) + count($countTechSavFaisExBam)) + (count($countTechSavFaisJuBan) + count($countTechSavFaisSeBan) + count($countTechSavFaisExBan)) + (count($countTechSavFaisJuBer) + count($countTechSavFaisSeBer) + count($countTechSavFaisExBer)) + (count($countTechSavFaisJuDa) + count($countTechSavFaisSeDa) + count($countTechSavFaisExDa)) + (count($countTechSavFaisJuDo) + count($countTechSavFaisSeDo) + count($countTechSavFaisExDo)) + (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) + (count($countTechSavFaisJuKi) + count($countTechSavFaisSeKi) + count($countTechSavFaisExKi)) + (count($countTechSavFaisJuYa) + count($countTechSavFaisSeYa) + count($countTechSavFaisExYa)) + (count($countTechSavFaisJuKo) + count($countTechSavFaisSeKo) + count($countTechSavFaisExKo)) + (count($countTechSavFaisJuLi) + count($countTechSavFaisSeLi) + count($countTechSavFaisExLi)) + (count($countTechSavFaisJuLu) + count($countTechSavFaisSeLu) + count($countTechSavFaisExLu)) + (count($countTechSavFaisJuNg) + count($countTechSavFaisSeNg) + count($countTechSavFaisExNg)) + (count($countTechSavFaisJuOu) + count($countTechSavFaisSeOu) + count($countTechSavFaisExOu)) + (count($countTechSavFaisJuPo) + count($countTechSavFaisSePo) + count($countTechSavFaisExPo)) + (count($countTechSavFaisJuVr) + count($countTechSavFaisSeVr) + count($countTechSavFaisExVr)))  +  ((count($countMaSavFaisJuBam) + count($countMaSavFaisSeBam) + count($countMaSavFaisExBam)) + (count($countMaSavFaisJuBan) + count($countMaSavFaisSeBan) + count($countMaSavFaisExBan)) + (count($countMaSavFaisJuBer) + count($countMaSavFaisSeBer) + count($countMaSavFaisExBer)) + (count($countMaSavFaisJuDa) + count($countMaSavFaisSeDa) + count($countMaSavFaisExDa)) + (count($countMaSavFaisJuDo) + count($countMaSavFaisSeDo) + count($countMaSavFaisExDo)) + (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) + (count($countMaSavFaisJuKi) + count($countMaSavFaisSeKi) + count($countMaSavFaisExKi)) + (count($countMaSavFaisJuYa) + count($countMaSavFaisSeYa) + count($countMaSavFaisExYa)) + (count($countMaSavFaisJuKo) + count($countMaSavFaisSeKo) + count($countMaSavFaisExKo)) + (count($countMaSavFaisJuLi) + count($countMaSavFaisSeLi) + count($countMaSavFaisExLi)) + (count($countMaSavFaisJuLu) + count($countMaSavFaisSeLu) + count($countMaSavFaisExLu)) + (count($countMaSavFaisJuNg) + count($countMaSavFaisSeNg) + count($countMaSavFaisExNg)) + (count($countMaSavFaisJuOu) + count($countMaSavFaisSeOu) + count($countMaSavFaisExOu)) + (count($countMaSavFaisJuPo) + count($countMaSavFaisSePo) + count($countMaSavFaisExPo)) + (count($countMaSavFaisJuVr) + count($countMaSavFaisSeVr) + count($countMaSavFaisExVr))) ?> / <?php echo ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2))) * 3 ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php $technicianCount = ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2)))) * 3;
                                            if ($technicianCount > 0) {
                                                $percentage = ceil((((count($countSavoirsJuBam) + count($countSavoirsSeBam) + count($countSavoirsExBam)) + (count($countSavoirsJuBan) + count($countSavoirsSeBan) + count($countSavoirsExBan)) + (count($countSavoirsJuBer) + count($countSavoirsSeBer) + count($countSavoirsExBer)) + (count($countSavoirsJuDa) + count($countSavoirsSeDa) + count($countSavoirsExDa)) + (count($countSavoirsJuDo) + count($countSavoirsSeDo) + count($countSavoirsExDo)) + (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) + (count($countSavoirsJuKi) + count($countSavoirsSeKi) + count($countSavoirsExKi)) + (count($countSavoirsJuYa) + count($countSavoirsSeYa) + count($countSavoirsExYa)) + (count($countSavoirsJuKo) + count($countSavoirsSeKo) + count($countSavoirsExKo)) + (count($countSavoirsJuLi) + count($countSavoirsSeLi) + count($countSavoirsExLi)) + (count($countSavoirsJuLu) + count($countSavoirsSeLu) + count($countSavoirsExLu)) + (count($countSavoirsJuNg) + count($countSavoirsSeNg) + count($countSavoirsExNg)) + (count($countSavoirsJuOu) + count($countSavoirsSeOu) + count($countSavoirsExOu)) + (count($countSavoirsJuPo) + count($countSavoirsSePo) + count($countSavoirsExPo)) + (count($countSavoirsJuVr) + count($countSavoirsSeVr) + count($countSavoirsExVr))) + ((count($countTechSavFaisJuBam) + count($countTechSavFaisSeBam) + count($countTechSavFaisExBam)) + (count($countTechSavFaisJuBan) + count($countTechSavFaisSeBan) + count($countTechSavFaisExBan)) + (count($countTechSavFaisJuBer) + count($countTechSavFaisSeBer) + count($countTechSavFaisExBer)) + (count($countTechSavFaisJuDa) + count($countTechSavFaisSeDa) + count($countTechSavFaisExDa)) + (count($countTechSavFaisJuDo) + count($countTechSavFaisSeDo) + count($countTechSavFaisExDo)) + (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) + (count($countTechSavFaisJuKi) + count($countTechSavFaisSeKi) + count($countTechSavFaisExKi)) + (count($countTechSavFaisJuYa) + count($countTechSavFaisSeYa) + count($countTechSavFaisExYa)) + (count($countTechSavFaisJuKo) + count($countTechSavFaisSeKo) + count($countTechSavFaisExKo)) + (count($countTechSavFaisJuLi) + count($countTechSavFaisSeLi) + count($countTechSavFaisExLi)) + (count($countTechSavFaisJuLu) + count($countTechSavFaisSeLu) + count($countTechSavFaisExLu)) + (count($countTechSavFaisJuNg) + count($countTechSavFaisSeNg) + count($countTechSavFaisExNg)) + (count($countTechSavFaisJuOu) + count($countTechSavFaisSeOu) + count($countTechSavFaisExOu)) + (count($countTechSavFaisJuPo) + count($countTechSavFaisSePo) + count($countTechSavFaisExPo)) + (count($countTechSavFaisJuVr) + count($countTechSavFaisSeVr) + count($countTechSavFaisExVr)))  +  ((count($countMaSavFaisJuBam) + count($countMaSavFaisSeBam) + count($countMaSavFaisExBam)) + (count($countMaSavFaisJuBan) + count($countMaSavFaisSeBan) + count($countMaSavFaisExBan)) + (count($countMaSavFaisJuBer) + count($countMaSavFaisSeBer) + count($countMaSavFaisExBer)) + (count($countMaSavFaisJuDa) + count($countMaSavFaisSeDa) + count($countMaSavFaisExDa)) + (count($countMaSavFaisJuDo) + count($countMaSavFaisSeDo) + count($countMaSavFaisExDo)) + (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) + (count($countMaSavFaisJuKi) + count($countMaSavFaisSeKi) + count($countMaSavFaisExKi)) + (count($countMaSavFaisJuYa) + count($countMaSavFaisSeYa) + count($countMaSavFaisExYa)) + (count($countMaSavFaisJuKo) + count($countMaSavFaisSeKo) + count($countMaSavFaisExKo)) + (count($countMaSavFaisJuLi) + count($countMaSavFaisSeLi) + count($countMaSavFaisExLi)) + (count($countMaSavFaisJuLu) + count($countMaSavFaisSeLu) + count($countMaSavFaisExLu)) + (count($countMaSavFaisJuNg) + count($countMaSavFaisSeNg) + count($countMaSavFaisExNg)) + (count($countMaSavFaisJuOu) + count($countMaSavFaisSeOu) + count($countMaSavFaisExOu)) + (count($countMaSavFaisJuPo) + count($countMaSavFaisSePo) + count($countMaSavFaisExPo)) + (count($countMaSavFaisJuVr) + count($countMaSavFaisSeVr) + count($countMaSavFaisExVr)))) * 100 / $technicianCount);
                                            } else {
                                                $percentage = 0; // or any other appropriate value or message
                                            }
                                            echo $percentage . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php echo ((count($countSavoirsJuBam) + count($countSavoirsSeBam) + count($countSavoirsExBam)) + (count($countSavoirsJuBan) + count($countSavoirsSeBan) + count($countSavoirsExBan)) + (count($countSavoirsJuBer) + count($countSavoirsSeBer) + count($countSavoirsExBer)) + (count($countSavoirsJuDa) + count($countSavoirsSeDa) + count($countSavoirsExDa)) + (count($countSavoirsJuDo) + count($countSavoirsSeDo) + count($countSavoirsExDo)) + (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) + (count($countSavoirsJuKi) + count($countSavoirsSeKi) + count($countSavoirsExKi)) + (count($countSavoirsJuYa) + count($countSavoirsSeYa) + count($countSavoirsExYa)) + (count($countSavoirsJuKo) + count($countSavoirsSeKo) + count($countSavoirsExKo)) + (count($countSavoirsJuLi) + count($countSavoirsSeLi) + count($countSavoirsExLi)) + (count($countSavoirsJuLu) + count($countSavoirsSeLu) + count($countSavoirsExLu)) + (count($countSavoirsJuNg) + count($countSavoirsSeNg) + count($countSavoirsExNg)) + (count($countSavoirsJuOu) + count($countSavoirsSeOu) + count($countSavoirsExOu)) + (count($countSavoirsJuPo) + count($countSavoirsSePo) + count($countSavoirsExPo)) + (count($countSavoirsJuVr) + count($countSavoirsSeVr) + count($countSavoirsExVr))) ?> / <?php echo ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2))) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php $technicianCount = ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2))));
                                            if ($technicianCount > 0) {
                                                $percentage = ceil(((count($countSavoirsJuBam) + count($countSavoirsSeBam) + count($countSavoirsExBam)) + (count($countSavoirsJuBan) + count($countSavoirsSeBan) + count($countSavoirsExBan)) + (count($countSavoirsJuBer) + count($countSavoirsSeBer) + count($countSavoirsExBer)) + (count($countSavoirsJuDa) + count($countSavoirsSeDa) + count($countSavoirsExDa)) + (count($countSavoirsJuDo) + count($countSavoirsSeDo) + count($countSavoirsExDo)) + (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) + (count($countSavoirsJuKi) + count($countSavoirsSeKi) + count($countSavoirsExKi)) + (count($countSavoirsJuYa) + count($countSavoirsSeYa) + count($countSavoirsExYa)) + (count($countSavoirsJuKo) + count($countSavoirsSeKo) + count($countSavoirsExKo)) + (count($countSavoirsJuLi) + count($countSavoirsSeLi) + count($countSavoirsExLi)) + (count($countSavoirsJuLu) + count($countSavoirsSeLu) + count($countSavoirsExLu)) + (count($countSavoirsJuNg) + count($countSavoirsSeNg) + count($countSavoirsExNg)) + (count($countSavoirsJuOu) + count($countSavoirsSeOu) + count($countSavoirsExOu)) + (count($countSavoirsJuPo) + count($countSavoirsSePo) + count($countSavoirsExPo)) + (count($countSavoirsJuVr) + count($countSavoirsSeVr) + count($countSavoirsExVr))) * 100 / $technicianCount);
                                            } else {
                                                $percentage = 0; // or any other appropriate value or message
                                            }
                                            echo $percentage . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php echo ((count($countTechSavFaisJuBam) + count($countTechSavFaisSeBam) + count($countTechSavFaisExBam)) + (count($countTechSavFaisJuBan) + count($countTechSavFaisSeBan) + count($countTechSavFaisExBan)) + (count($countTechSavFaisJuBer) + count($countTechSavFaisSeBer) + count($countTechSavFaisExBer)) + (count($countTechSavFaisJuDa) + count($countTechSavFaisSeDa) + count($countTechSavFaisExDa)) + (count($countTechSavFaisJuDo) + count($countTechSavFaisSeDo) + count($countTechSavFaisExDo)) + (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) + (count($countTechSavFaisJuKi) + count($countTechSavFaisSeKi) + count($countTechSavFaisExKi)) + (count($countTechSavFaisJuYa) + count($countTechSavFaisSeYa) + count($countTechSavFaisExYa)) + (count($countTechSavFaisJuKo) + count($countTechSavFaisSeKo) + count($countTechSavFaisExKo)) + (count($countTechSavFaisJuLi) + count($countTechSavFaisSeLi) + count($countTechSavFaisExLi)) + (count($countTechSavFaisJuLu) + count($countTechSavFaisSeLu) + count($countTechSavFaisExLu)) + (count($countTechSavFaisJuNg) + count($countTechSavFaisSeNg) + count($countTechSavFaisExNg)) + (count($countTechSavFaisJuOu) + count($countTechSavFaisSeOu) + count($countTechSavFaisExOu)) + (count($countTechSavFaisJuPo) + count($countTechSavFaisSePo) + count($countTechSavFaisExPo)) + (count($countTechSavFaisJuVr) + count($countTechSavFaisSeVr) + count($countTechSavFaisExVr))) ?> / <?php echo ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2))) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php $technicianCount = ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2))));
                                            if ($technicianCount > 0) {
                                                $percentage = ceil(((count($countTechSavFaisJuBam) + count($countTechSavFaisSeBam) + count($countTechSavFaisExBam)) + (count($countTechSavFaisJuBan) + count($countTechSavFaisSeBan) + count($countTechSavFaisExBan)) + (count($countTechSavFaisJuBer) + count($countTechSavFaisSeBer) + count($countTechSavFaisExBer)) + (count($countTechSavFaisJuDa) + count($countTechSavFaisSeDa) + count($countTechSavFaisExDa)) + (count($countTechSavFaisJuDo) + count($countTechSavFaisSeDo) + count($countTechSavFaisExDo)) + (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) + (count($countTechSavFaisJuKi) + count($countTechSavFaisSeKi) + count($countTechSavFaisExKi)) + (count($countTechSavFaisJuYa) + count($countTechSavFaisSeYa) + count($countTechSavFaisExYa)) + (count($countTechSavFaisJuKo) + count($countTechSavFaisSeKo) + count($countTechSavFaisExKo)) + (count($countTechSavFaisJuLi) + count($countTechSavFaisSeLi) + count($countTechSavFaisExLi)) + (count($countTechSavFaisJuLu) + count($countTechSavFaisSeLu) + count($countTechSavFaisExLu)) + (count($countTechSavFaisJuNg) + count($countTechSavFaisSeNg) + count($countTechSavFaisExNg)) + (count($countTechSavFaisJuOu) + count($countTechSavFaisSeOu) + count($countTechSavFaisExOu)) + (count($countTechSavFaisJuPo) + count($countTechSavFaisSePo) + count($countTechSavFaisExPo)) + (count($countTechSavFaisJuVr) + count($countTechSavFaisSeVr) + count($countTechSavFaisExVr))) * 100 / $technicianCount);
                                            } else {
                                                $percentage = 0; // or any other appropriate value or message
                                            }
                                            echo $percentage . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php echo ((count($countMaSavFaisJuBam) + count($countMaSavFaisSeBam) + count($countMaSavFaisExBam)) + (count($countMaSavFaisJuBan) + count($countMaSavFaisSeBan) + count($countMaSavFaisExBan)) + (count($countMaSavFaisJuBer) + count($countMaSavFaisSeBer) + count($countMaSavFaisExBer)) + (count($countMaSavFaisJuDa) + count($countMaSavFaisSeDa) + count($countMaSavFaisExDa)) + (count($countMaSavFaisJuDo) + count($countMaSavFaisSeDo) + count($countMaSavFaisExDo)) + (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) + (count($countMaSavFaisJuKi) + count($countMaSavFaisSeKi) + count($countMaSavFaisExKi)) + (count($countMaSavFaisJuYa) + count($countMaSavFaisSeYa) + count($countMaSavFaisExYa)) + (count($countMaSavFaisJuKo) + count($countMaSavFaisSeKo) + count($countMaSavFaisExKo)) + (count($countMaSavFaisJuLi) + count($countMaSavFaisSeLi) + count($countMaSavFaisExLi)) + (count($countMaSavFaisJuLu) + count($countMaSavFaisSeLu) + count($countMaSavFaisExLu)) + (count($countMaSavFaisJuNg) + count($countMaSavFaisSeNg) + count($countMaSavFaisExNg)) + (count($countMaSavFaisJuOu) + count($countMaSavFaisSeOu) + count($countMaSavFaisExOu)) + (count($countMaSavFaisJuPo) + count($countMaSavFaisSePo) + count($countMaSavFaisExPo)) + (count($countMaSavFaisJuVr) + count($countMaSavFaisSeVr) + count($countMaSavFaisExVr))) ?> / <?php echo ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2)) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2))) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background: #edf2f7;">
                                            <?php $technicianCount = ((count($techniciansBam) + count($techniciansSeBam) + (count($techniciansExBam) * 2)) + (count($techniciansBan) + count($techniciansSeBan) + (count($techniciansExBan) * 2)) + (count($techniciansBer) + count($techniciansSeBer) + (count($techniciansExBer) * 2)) + (count($techniciansDa) + count($techniciansSeDa) + (count($techniciansExDa) * 2)) + (count($techniciansDo) + count($techniciansSeDo) + (count($techniciansExDo) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansKi) + count($techniciansSeKi) + (count($techniciansExKi) * 2)) + (count($techniciansKo) + count($techniciansSeKo) + (count($techniciansExKo) * 2)) + (count($techniciansLi) + count($techniciansSeLi) + (count($techniciansExLi) * 2)) + (count($techniciansLu) + count($techniciansSeLu) + (count($techniciansExLu) * 2)) + (count($techniciansNg) + count($techniciansSeNg) + (count($techniciansExNg) * 2)) + (count($techniciansOu) + count($techniciansSeOu) + (count($techniciansExOu) * 2)) + (count($techniciansPo) + count($techniciansSePo) + (count($techniciansExPo) * 2)) + (count($techniciansVr) + count($techniciansSeVr) + (count($techniciansExVr) * 2) + (count($techniciansYa) + count($techniciansSeYa) + (count($techniciansExYa) * 2))));
                                            if ($technicianCount > 0) {
                                                $percentage = ceil(((count($countMaSavFaisJuBam) + count($countMaSavFaisSeBam) + count($countMaSavFaisExBam)) + (count($countMaSavFaisJuBan) + count($countMaSavFaisSeBan) + count($countMaSavFaisExBan)) + (count($countMaSavFaisJuBer) + count($countMaSavFaisSeBer) + count($countMaSavFaisExBer)) + (count($countMaSavFaisJuDa) + count($countMaSavFaisSeDa) + count($countMaSavFaisExDa)) + (count($countMaSavFaisJuDo) + count($countMaSavFaisSeDo) + count($countMaSavFaisExDo)) + (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) + (count($countMaSavFaisJuKi) + count($countMaSavFaisSeKi) + count($countMaSavFaisExKi)) + (count($countMaSavFaisJuYa) + count($countMaSavFaisSeYa) + count($countMaSavFaisExYa)) + (count($countMaSavFaisJuKo) + count($countMaSavFaisSeKo) + count($countMaSavFaisExKo)) + (count($countMaSavFaisJuLi) + count($countMaSavFaisSeLi) + count($countMaSavFaisExLi)) + (count($countMaSavFaisJuLu) + count($countMaSavFaisSeLu) + count($countMaSavFaisExLu)) + (count($countMaSavFaisJuNg) + count($countMaSavFaisSeNg) + count($countMaSavFaisExNg)) + (count($countMaSavFaisJuOu) + count($countMaSavFaisSeOu) + count($countMaSavFaisExOu)) + (count($countMaSavFaisJuPo) + count($countMaSavFaisSePo) + count($countMaSavFaisExPo)) + (count($countMaSavFaisJuVr) + count($countMaSavFaisSeVr) + count($countMaSavFaisExVr))) * 100 / $technicianCount);
                                            } else {
                                                $percentage = 0; // or any other appropriate value or message
                                            }
                                            echo $percentage . '%'; ?>
                                        </td>
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
                    <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span class="path2"></span></i>
                    <?php echo $excel ?>
                </button>
                <!--end::Export-->
            </div>
            <!--end::Export dropdown-->
            <?php } ?>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    </div>
    <!--end:Row-->

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
                name: `StateAgency.xlsx`
            })
        });
    });
    $(document).ready(function() {
        $('.dropdown-toggle').click(function() {
            var $dropdownContent = $('.dropdown-content');
            var isVisible = $dropdownContent.is(':visible');
            
            $dropdownContent.slideToggle();
            $(this).toggleClass('open', !isVisible);
        });
    });
    
    // Script for toggling the dropdown content
    document.querySelector('.dropdown-toggle2').addEventListener('click', function() {
        const dropdownContent = document.querySelector('.dropdown-content2');
        const toggleButton = this;
    
        if (dropdownContent.style.display === 'none' || dropdownContent.style.display === '') {
            dropdownContent.style.display = 'block';
            dropdownContent.style.opacity = '1';
            dropdownContent.style.maxHeight = dropdownContent.scrollHeight + 'px'; // Smoothly expand
            toggleButton.classList.add('open'); // Add class for rotating icon
        } else {
            dropdownContent.style.opacity = '0';
            dropdownContent.style.maxHeight = '0'; // Smoothly collapse
            setTimeout(() => {
                dropdownContent.style.display = 'none';
            }, 300); // Delay hiding to allow the transition to complete
            toggleButton.classList.remove('open'); // Remove class for rotating icon
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

    if (<?php echo round(($percentageFacJu + $percentageDeclaJu) / 2) ?> <= 60) {
    var colorJu = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($percentageFacJu + $percentageDeclaJu) / 2) ?> < 80) {
    if (<?php echo round(($percentageFacJu + $percentageDeclaJu) / 2) ?> > 60) {
        var colorJu = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($percentageFacJu + $percentageDeclaJu) / 2) ?> > 80) {
    var colorJu = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($percentageFacSe + $percentageDeclaSe) / 2) ?> <= 60) {
    var colorSe = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($percentageFacSe + $percentageDeclaSe) / 2) ?> < 80) {
    if (<?php echo round(($percentageFacSe + $percentageDeclaSe) / 2) ?> > 60) {
        var colorSe = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($percentageFacSe + $percentageDeclaSe) / 2) ?> > 80) {
    var colorSe = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?> <= 60) {
    var colorEx = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?> < 80) {
    if (<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?> > 60) {
        var colorEx = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?> > 80) {
    var colorEx = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}

if (<?php echo (round(($percentageFacJu + $percentageDeclaJu) / 2) + round(($percentageFacSe + $percentageDeclaSe) / 2) + round(($percentageFacEx + $percentageDeclaEx) / 2)) / 3 ?> <=
    60) {
    var color = ['#f9945e', '#D3D3D3'] // Blue and Lightgrey
}
if (<?php echo (round(($percentageFacJu + $percentageDeclaJu) / 2) + round(($percentageFacSe + $percentageDeclaSe) / 2) + round(($percentageFacEx + $percentageDeclaEx) / 2)) / 3 ?> <
    80) {
    if (<?php echo (round(($percentageFacJu + $percentageDeclaJu) / 2) + round(($percentageFacSe + $percentageDeclaSe) / 2) + round(($percentageFacEx + $percentageDeclaEx) / 2)) / 3 ?> >
        60) {
        var color = ['#f9f75e', '#D3D3D3'] // Blue and Lightgrey
    }
}
if (<?php echo (round(($percentageFacJu + $percentageDeclaJu) / 2) + round(($percentageFacSe + $percentageDeclaSe) / 2) + round(($percentageFacEx + $percentageDeclaEx) / 2)) / 3 ?> >
    80) {
    var color = ['#6cf95e', '#D3D3D3'] // Blue and Lightgrey
}
    
    document.addEventListener('DOMContentLoaded', () => {
        // Data for each chart
        const chartDatas = [{
                title: 'QCM Niveau Junior',
                total: <?php echo count($technicians) ?>,
                completed: <?php echo count($countSavoirJu) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirJu) ?>,
                    <?php echo (count($technicians)) - (count($countSavoirJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirJu) ?> QCM réalisés',
                    '<?php echo (count($technicians)) - (count($countSavoirJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Niveau Senior',
                total: <?php echo count($techniciansSe) + count($techniciansEx) ?>,
                completed: <?php echo count($countSavoirSe) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirSe) ?>,
                    <?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countSavoirSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirSe) ?> QCM réalisés',
                    '<?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countSavoirSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Niveau Expert',
                total: <?php echo count($techniciansEx) ?>,
                completed: <?php echo count($countSavoirEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirEx) ?>,
                    <?php echo (count($techniciansEx)) - (count($countSavoirEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countSavoirEx) ?> QCM réalisés',
                    '<?php echo (count($techniciansEx)) - (count($countSavoirEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2) ?>,
                completed: <?php echo count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx) ?>, // QCM réalisés
                data: [<?php echo count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx) ?>,
                    <?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx) ?> QCM réalisés',
                    '<?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countSavoirJu) + count($countSavoirSe) + count($countSavoirEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#039FFE', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartCon');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            if(chartDatas[i].total == 0) {
                var completedPercentage = 0;
            } else {
                var completedPercentage = Math.round((chartDatas[i].completed / chartDatas[i].total) * 100);
            }

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
                title: 'QCM Niveau Junior',
                total: <?php echo count($technicians) ?>,
                completed: <?php echo count($countMaSavFaiJu) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiJu) ?>,
                    <?php echo (count($technicians)) - (count($countMaSavFaiJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countMaSavFaiJu) ?> QCM réalisés',
                    '<?php echo (count($technicians)) - (count($countMaSavFaiJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Niveau Senior',
                total: <?php echo count($techniciansSe) + count($techniciansEx) ?>,
                completed: <?php echo count($countMaSavFaiSe) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiSe) ?>,
                    <?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countMaSavFaiSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countMaSavFaiSe) ?> QCM réalisés',
                    '<?php echo (count($techniciansSe) + count($techniciansEx)) - (count($countMaSavFaiSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Niveau Expert',
                total: <?php echo count($techniciansEx) ?>,
                completed: <?php echo count($countMaSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiEx) ?>,
                    <?php echo (count($techniciansEx)) - (count($countMaSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countTechSavFaiEx) + count($countMaSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($techniciansEx) * 3) - (count($countTechSavFaiEx) + count($countMaSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2) ?>,
                completed: <?php echo count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx) ?>,
                    <?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countMaSavFaiJu) + count($countMaSavFaiSe) + count($countMaSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#C9E7FB', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartMan');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            if(chartData[i].total == 0) {
                var completedPercentage = 0;
            } else {
                var completedPercentage = Math.round((chartData[i].completed / chartData[i].total) * 100);
            }

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
                title: 'QCM Niveau Junior',
                total: <?php echo count($technicians) ?>,
                completed: <?php echo count($countTechSavFaiJu) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiJu) ?>,
                    <?php echo (count($technicians)) - (count($countTechSavFaiJu)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaiJu) ?> QCM réalisés',
                    '<?php echo (count($technicians)) - (count($countTechSavFaiJu)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Niveau Senior',
                total: <?php echo count($techniciansSe) + count($techniciansEx) ?>,
                completed: <?php echo count($countTechSavFaiSe) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiSe) ?>,
                    <?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countTechSavFaiSe)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaiSe) ?> QCM réalisés',
                    '<?php echo ((count($techniciansSe) + count($techniciansEx))) - (count($countTechSavFaiSe)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'QCM Niveau Expert',
                total: <?php echo count($techniciansEx) ?>,
                completed: <?php echo count($countTechSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiEx) ?>,
                    <?php echo (count($techniciansEx)) - (count($countTechSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: ['<?php echo count($countTechSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($techniciansEx)) - (count($countTechSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Total : 03 Niveaux',
                total: <?php echo count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2) ?>,
                completed: <?php echo count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx) ?>, // QCM réalisés
                data: [<?php echo count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx) ?>,
                    <?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx)) ?>
                ], // QCM réalisés vs. QCM à réaliser
                labels: [
                    '<?php echo count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx) ?> QCM réalisés',
                    '<?php echo (count($technicians) + count($techniciansSe)  + (count($techniciansEx) * 2)) - (count($countTechSavFaiJu) + count($countTechSavFaiSe) + count($countTechSavFaiEx)) ?> QCM restants à réaliser'
                ],
                backgroundColor: ['#82CDFF', '#D3D3D3'] // Blue and Lightgrey
            }
        ];

        const cardContainer = document.getElementById('chartTech');
        const numberOfCards = 4;

        for (let i = 0; i < numberOfCards; i++) {
            // Calculate the completed percentage
            if(chartDatas[i].total == 0) {
                var completedPercentage = 0;
            } else {
                var completedPercentage = Math.round((chartDatas[i].completed / chartDatas[i].total) * 100);
            }

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
                title: 'Test Niveau Junior',
                total: <?php echo count($testsTotalJu) ?>,
                completed: <?php echo count($testsUserJu) ?>, // Test réalisés
                data: [<?php echo count($testsUserJu) ?>,
                    <?php echo (count($testsTotalJu) - count($testsUserJu)) ?>
                ], // Test réalisés vs. Test à réaliser
                labels: ['Tests réalisés', 'Tests restants à réaliser'],
                backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Test Niveau Senior',
                total: <?php echo count($testsTotalSe) ?>,
                completed: <?php echo count($testsUserSe) ?>, // Test réalisés
                data: [<?php echo count($testsUserSe) ?>,
                    <?php echo (count($testsTotalSe) - count($testsUserSe)) ?>
                ], // Test réalisés vs. Test à réaliser
                labels: ['Tests réalisés', 'Tests restants à réaliser'],
                backgroundColor: ['#4303ec', '#D3D3D3'] // Blue and Lightgrey
            },
            {
                title: 'Test Niveau Expert',
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
            if(data.total == 0) {
                var completedPercentage = 0;
            } else {
                var completedPercentage = Math.round((data.completed / data.total) * 100);
            }
    
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
    // Define color ranges for percentage completion
    const getColorForCompletion = (percentage) => {
        if (percentage >= 80) return '#6CF95D'; // Green
        if (percentage >= 60) return '#FAF75A'; // Yellow
        return '#FB9258'; // Orange
    };

    // Determine the background color for the donut chart
    const getBackgroundColor = (percentage) => {
        if (percentage === 0) return ['#FFFFFF']; // All white if 0%
        return [
            getColorForCompletion(percentage), // Color for the completed part
            '#DCDCDC' // Grey color for the remaining part
        ];
    };

    
    // Data for each chart
    const chartDataM = [
        {
            title: 'Résultat <?php echo count($techniciansJu) ?> Techniciens Niveau Junior',
            total: 100,
            completed: <?php echo round(($percentageFacJuTj + $percentageDeclaJuTj) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($percentageFacJuTj + $percentageDeclaJuTj) / 2) ?>,
                100 - <?php echo round(($percentageFacJuTj + $percentageDeclaJuTj) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($percentageFacJuTj + $percentageDeclaJuTj) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($percentageFacJuTj + $percentageDeclaJuTj) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: getBackgroundColor(<?php echo round(($percentageFacJuTj + $percentageDeclaJuTj) / 2) ?>)
        },
        {
            title: 'Résultat <?php echo count($techniciansSe) ?> Techniciens Niveau Senior',
            total: 100,
            completed: <?php echo round(($percentageFacSeTs + $percentageDeclaSeTs) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($percentageFacSeTs + $percentageDeclaSeTs) / 2) ?>,
                100 - <?php echo round(($percentageFacSeTs + $percentageDeclaSeTs) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($percentageFacSeTs + $percentageDeclaSeTs) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($percentageFacSeTs + $percentageDeclaSeTs) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: getBackgroundColor(<?php echo round(($percentageFacSeTs + $percentageDeclaSeTs) / 2) ?>)
        },
        {
            title: 'Résultat <?php echo count($techniciansEx) ?> Techniciens Niveau Expert',
            total: 100,
            completed: <?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?>, // Moyenne des compétences acquises
            data: [<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?>,
                100 - <?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?>
            ], // Moyenne des compétences acquises vs. Moyenne des compétences à acquérir
            labels: [
                '<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?>% des compétences acquises',
                '<?php echo 100 - round(($percentageFacEx + $percentageDeclaEx) / 2) ?>% des compétences à acquérir'
            ],
            backgroundColor: getBackgroundColor(<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?>)
        }
    ];

    // Calculate the average for "Total : 03 Niveaux" based on non-zero values
    const validData = chartDataM.filter(chart => chart.completed > 0);
    const averageCompleted = validData.length > 0 ?
        Math.round(validData.reduce((acc, chart) => acc + chart.completed, 0) / validData.length) :
        0;
    const averageData = [averageCompleted, 100 - averageCompleted];

    // Determine color based on average completion percentage
    const totalColor = getColorForCompletion(averageCompleted);
    const totalBackgroundColor = getBackgroundColor(averageCompleted);

    chartDataM.push({
        title: 'Total : 03 Niveaux',
        total: 100,
        completed: averageCompleted,
        data: averageData,
        labels: [
            `${averageCompleted}% des compétences acquises`,
            `${100 - averageCompleted}% des compétences à acquérir`
        ],
        backgroundColor: totalBackgroundColor
    });

    const containerM = document.getElementById('chartMoyen');

    // Loop through the data to create and append cards
    chartDataM.forEach((data, index) => {
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
                    borderWidth: 0 // Remove the border
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
                            let percentage = Math.round((value / sum) * 100); // Round up to the nearest whole number
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
// Fixed list of cities for the x-axis labels
var cityLabelJu = ['<?php echo count($techniciansJu) ?> Techniciens Junior', '<?php echo count($techniciansSe) ?> Techniciens Senior', '<?php echo count($techniciansEx) ?> Techniciens Expert'];
var cityLabelSe = ['<?php echo count($techniciansSe) ?> Techniciens Senior', '<?php echo count($techniciansEx) ?> Techniciens Expert', ''];
var cityLabelEx = ['<?php echo count($techniciansEx) ?> Techniciens Expert', '', ''];
var cityLabels = ['Total Niveau Junior', 'Total Niveau Senior', 'Total Niveau Expert'];

// Sample test data for Junior, Senior, and Expert levels
var juniorScores = [<?php echo round(($percentageFacJuTj + $percentageDeclaJuTj) / 2)?>, <?php echo round(($percentageFacJuTs + $percentageDeclaJuTs) / 2)?>, <?php echo round(($percentageFacJuTx + $percentageDeclaJuTx) / 2)?>]; // Replace with actual junior data
var seniorScores = [<?php echo round(($percentageFacSeTs + $percentageDeclaSeTs) / 2)?>, <?php echo round(($percentageFacSeTx + $percentageDeclaSeTx) / 2)?>, 0];  // Replace with actual senior data
var expertScores = [<?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?>, 0, 0]; // Replace with actual expert data
var averageScores = [<?php echo round(($percentageFacJu + $percentageDeclaJu) / 2) ?>, <?php echo round(($percentageFacSe + $percentageDeclaSe) / 2) ?>, <?php echo round(($percentageFacEx + $percentageDeclaEx) / 2) ?>]; // Replace with actual expert data

// Calculate the total (average) score across the three levels for each city
// var averageScores = cityLabels.map((_, index) => {
//     // Create an array of non-zero scores for the current city
//     var nonZeroScores = [juniorScores[index], seniorScores[index], expertScores[index]].filter(score => score > 0);

//     // Calculate the mean only if there are non-zero scores
//     if (nonZeroScores.length > 0) {
//         var meanScore = nonZeroScores.reduce((sum, score) => sum + score, 0) / nonZeroScores.length;
//         return Math.round(Math.min(meanScore, 100)); // Cap values at 100
//     } else {
//         return 0; // If all scores are zero, return 0
//     }
// });


// Function to determine bar color based on score value
function determineColor(score) {
    if (score < 60) {
        return '#F9945E'; // Orange for scores <= 60
    } else if (score <= 80) {
        return '#F8F75F'; // Yellow for scores between 61-80 
    } else {
        return '#63FE5A'; // Green for scores > 80
    }
}

// Function to create the chart for a specific data set and container
function renderChart(chartId, data, labels) {
    var chartContainer = document.querySelector("#" + chartId);
    if (!chartContainer) {
        console.error("Chart container with ID " + chartId + " not found.");
        return;
    }

    var colors = data.map(value => determineColor(value)); // Apply dynamic colors based on score

    var chartOptions = {
        chart: {
            type: 'bar',
            height: 350,
            width: 300
        },
        series: [{
            name: "Taux d'acquisition des compétences",
            data: data
        }],
        xaxis: {
            categories: labels // Use city names for x-axis labels
        },
        plotOptions: {
            bar: {
                distributed: true, // Ensure each bar gets a different color
                horizontal: false,
                borderRadius: 4 // Rounded bar edges
            }
        },
        colors: colors, // Dynamic colors
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                return value + '%'; // Display percentage with each value
            },
            style: {
                colors: ['#333'] // Set text color to white for percentage labels
            }
        },
        tooltip: {
            enabled: true // Enable tooltips to show values on hover
        },
        yaxis: {
            max: 100 // Limit y-axis to 100
        }
    };

    var chart = new ApexCharts(chartContainer, chartOptions);
    chart.render().catch(function(error) {
        console.error("Error rendering chart:", error);
    });
}

// Initialize all charts for the different levels and the total score
function initializeCharts() {
    renderChart('chart_junior_filiale', juniorScores, cityLabelJu);
    renderChart('chart_senior_filiale', seniorScores, cityLabelSe);
    renderChart('chart_expert_filiale', expertScores, cityLabelEx);
    renderChart('chart_total_filiale', averageScores, cityLabels);
}

// Ensure the charts are initialized after the DOM fully loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

// Ensure the charts are initialized after the DOM fully loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});
</script>
    <?php } ?>
<?php include "./partials/footer.php"; ?>