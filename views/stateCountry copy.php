<?php
session_start();
include_once "language.php";

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
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;
    $allocations = $academy->allocations;

    if ($_SESSION['profile'] == 'Super Admin') {
        $i = 4;
    } else {
        $i = 3;
    }
        
    $techniciansBu = [];
    $techsBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuBu = [];
    $techsJuBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeBu = [];
    $techsSeBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExBu = [];
    $techsExBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuBu = [];
    $countSavoirsJuBu = [];
    $countMaSavFaisJuBu = [];
    $countTechSavFaisJuBu = [];
    $testsSeBu = [];
    $countSavoirsSeBu = [];
    $countMaSavFaisSeBu = [];
    $countTechSavFaisSeBu = [];
    $testsExBu = [];
    $countSavoirsExBu = [];
    $countMaSavFaisExBu = [];
    $countTechSavFaisExBu = [];
    foreach ($techniciansBu as $technician) { 
        $allocateFacJuBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuBu) && $allocateFacJuBu['active'] == true) {
            $countSavoirsJuBu[] = $allocateFacJuBu;
        }
        if (isset($allocateDeclaJuBu) && $allocateDeclaJuBu['activeManager'] == true) {
            $countMaSavFaisJuBu[] = $allocateDeclaJuBu;
        }
        if (isset($allocateDeclaJuBu) && $allocateDeclaJuBu['active'] == true) {
            $countTechSavFaisJuBu[] = $allocateDeclaJuBu;
        }
        if (isset($allocateFacJuBu) && isset($allocateDeclaJuBu) && $allocateFacJuBu['active'] == true && $allocateDeclaJuBu['active'] == true && $allocateDeclaJuBu['activeManager'] == true) {
            $testsJuBu[] = $technician;
        }
        $allocateFacSeBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeBu) && $allocateFacSeBu['active'] == true) {
            $countSavoirsSeBu[] = $allocateFacSeBu;
        }
        if (isset($allocateDeclaSeBu) && $allocateDeclaSeBu['activeManager'] == true) {
            $countMaSavFaisSeBu[] = $allocateDeclaSeBu;
        }
        if (isset($allocateDeclaSeBu) && $allocateDeclaSeBu['active'] == true) {
            $countTechSavFaisSeBu[] = $allocateDeclaSeBu;
        }
        if (isset($allocateFacSeBu) && isset($allocateDeclaSeBu) && $allocateFacSeBu['active'] == true && $allocateDeclaSeBu['active'] == true && $allocateDeclaSeBu['activeManager'] == true) {
            $testsSeBu[] = $technician;
        }
        $allocateFacExBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExBu) && $allocateFacExBu['active'] == true) {
            $countSavoirsExBu[] = $allocateFacExBu;
        }
        if (isset($allocateDeclaExBu) && $allocateDeclaExBu['activeManager'] == true) {
            $countMaSavFaisExBu[] = $allocateDeclaExBu;
        }
        if (isset($allocateDeclaExBu) && $allocateDeclaExBu['active'] == true) {
            $countTechSavFaisExBu[] = $allocateDeclaExBu;
        }
        if (isset($allocateFacExBu) && isset($allocateDeclaExBu) && $allocateFacExBu['active'] == true && $allocateDeclaExBu['active'] == true && $allocateDeclaExBu['activeManager'] == true) {
            $testsExBu[] = $technician;
        }
    }   
           
    $techniciansCa = [];
    $techsCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuCa = [];
    $techsJuCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeCa = [];
    $techsSeCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExCa = [];
    $techsExCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuCa = [];
    $countSavoirsJuCa = [];
    $countMaSavFaisJuCa = [];
    $countTechSavFaisJuCa = [];
    $testsSeCa = [];
    $countSavoirsSeCa = [];
    $countMaSavFaisSeCa = [];
    $countTechSavFaisSeCa = [];
    $testsExCa = [];
    $countSavoirsExCa = [];
    $countMaSavFaisExCa = [];
    $countTechSavFaisExCa = [];
    foreach ($techniciansCa as $technician) { 
        $allocateFacJuCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuCa) && $allocateFacJuCa['active'] == true) {
            $countSavoirsJuCa[] = $allocateFacJuCa;
        }
        if (isset($allocateDeclaJuCa) && $allocateDeclaJuCa['activeManager'] == true) {
            $countMaSavFaisJuCa[] = $allocateDeclaJuCa;
        }
        if (isset($allocateDeclaJuCa) && $allocateDeclaJuCa['active'] == true) {
            $countTechSavFaisJuCa[] = $allocateDeclaJuCa;
        }
        if (isset($allocateFacJuCa) && isset($allocateDeclaJuCa) && $allocateFacJuCa['active'] == true && $allocateDeclaJuCa['active'] == true && $allocateDeclaJuCa['activeManager'] == true) {
            $testsJuCa[] = $technician;
        }
        $allocateFacSeCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeCa) && $allocateFacSeCa['active'] == true) {
            $countSavoirsSeCa[] = $allocateFacSeCa;
        }
        if (isset($allocateDeclaSeCa) && $allocateDeclaSeCa['activeManager'] == true) {
            $countMaSavFaisSeCa[] = $allocateDeclaSeCa;
        }
        if (isset($allocateDeclaSeCa) && $allocateDeclaSeCa['active'] == true) {
            $countTechSavFaisSeCa[] = $allocateDeclaSeCa;
        }
        if (isset($allocateFacSeCa) && isset($allocateDeclaSeCa) && $allocateFacSeCa['active'] == true && $allocateDeclaSeCa['active'] == true && $allocateDeclaSeCa['activeManager'] == true) {
            $testsSeCa[] = $technician;
        }
        $allocateFacExCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExCa) && $allocateFacExCa['active'] == true) {
            $countSavoirsExCa[] = $allocateFacExCa;
        }
        if (isset($allocateDeclaExCa) && $allocateDeclaExCa['activeManager'] == true) {
            $countMaSavFaisExCa[] = $allocateDeclaExCa;
        }
        if (isset($allocateDeclaExCa) && $allocateDeclaExCa['active'] == true) {
            $countTechSavFaisExCa[] = $allocateDeclaExCa;
        }
        if (isset($allocateFacExCa) && isset($allocateDeclaExCa) && $allocateFacExCa['active'] == true && $allocateDeclaExCa['active'] == true && $allocateDeclaExCa['activeManager'] == true) {
            $testsExCa[] = $technician;
        }
    }
        
    $techniciansRci = [];
    $techsRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuRci = [];
    $techsJuRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeRci = [];
    $techsSeRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExRci = [];
    $techsExRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuRci = [];
    $countSavoirsJuRci = [];
    $countMaSavFaisJuRci = [];
    $countTechSavFaisJuRci = [];
    $testsSeRci = [];
    $countSavoirsSeRci = [];
    $countMaSavFaisSeRci = [];
    $countTechSavFaisSeRci = [];
    $testsExRci = [];
    $countSavoirsExRci = [];
    $countMaSavFaisExRci = [];
    $countTechSavFaisExRci = [];
    foreach ($techniciansRci as $technician) { 
        $allocateFacJuRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuRci) && $allocateFacJuRci['active'] == true) {
            $countSavoirsJuRci[] = $allocateFacJuRci;
        }
        if (isset($allocateDeclaJuRci) && $allocateDeclaJuRci['activeManager'] == true) {
            $countMaSavFaisJuRci[] = $allocateDeclaJuRci;
        }
        if (isset($allocateDeclaJuRci) && $allocateDeclaJuRci['active'] == true) {
            $countTechSavFaisJuRci[] = $allocateDeclaJuRci;
        }
        if (isset($allocateFacJuRci) && isset($allocateDeclaJuRci) && $allocateFacJuRci['active'] == true && $allocateDeclaJuRci['active'] == true && $allocateDeclaJuRci['activeManager'] == true) {
            $testsJuRci[] = $technician;
        }
        $allocateFacSeRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeRci) && $allocateFacSeRci['active'] == true) {
            $countSavoirsSeRci[] = $allocateFacSeRci;
        }
        if (isset($allocateDeclaSeRci) && $allocateDeclaSeRci['activeManager'] == true) {
            $countMaSavFaisSeRci[] = $allocateDeclaSeRci;
        }
        if (isset($allocateDeclaSeRci) && $allocateDeclaSeRci['active'] == true) {
            $countTechSavFaisSeRci[] = $allocateDeclaSeRci;
        }
        if (isset($allocateFacSeRci) && isset($allocateDeclaSeRci) && $allocateFacSeRci['active'] == true && $allocateDeclaSeRci['active'] == true && $allocateDeclaSeRci['activeManager'] == true) {
            $testsSeRci[] = $technician;
        }
        $allocateFacExRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExRci) && $allocateFacExRci['active'] == true) {
            $countSavoirsExRci[] = $allocateFacExRci;
        }
        if (isset($allocateDeclaExRci) && $allocateDeclaExRci['activeManager'] == true) {
            $countMaSavFaisExRci[] = $allocateDeclaExRci;
        }
        if (isset($allocateDeclaExRci) && $allocateDeclaExRci['active'] == true) {
            $countTechSavFaisExRci[] = $allocateDeclaExRci;
        }
        if (isset($allocateFacExRci) && isset($allocateDeclaExRci) && $allocateFacExRci['active'] == true && $allocateDeclaExRci['active'] == true && $allocateDeclaExRci['activeManager'] == true) {
            $testsExRci[] = $technician;
        }
    }
        
    $techniciansGa = [];
    $techsGa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS GABON",
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
                "subsidiary" => "CFAO MOTORS GABON",
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
                "subsidiary" => "CFAO MOTORS GABON",
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
                "subsidiary" => "CFAO MOTORS GABON",
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
        
    $techniciansMali = [];
    $techsMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuMali = [];
    $techsJuMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeMali = [];
    $techsSeMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExMali = [];
    $techsExMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuMali = [];
    $countSavoirsJuMali = [];
    $countMaSavFaisJuMali = [];
    $countTechSavFaisJuMali = [];
    $testsSeMali = [];
    $countSavoirsSeMali = [];
    $countMaSavFaisSeMali = [];
    $countTechSavFaisSeMali = [];
    $testsExMali = [];
    $countSavoirsExMali = [];
    $countMaSavFaisExMali = [];
    $countTechSavFaisExMali = [];
    foreach ($techniciansMali as $technician) { 
        $allocateFacJuMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuMali) && $allocateFacJuMali['active'] == true) {
            $countSavoirsJuMali[] = $allocateFacJuMali;
        }
        if (isset($allocateDeclaJuMali) && $allocateDeclaJuMali['activeManager'] == true) {
            $countMaSavFaisJuMali[] = $allocateDeclaJuMali;
        }
        if (isset($allocateDeclaJuMali) && $allocateDeclaJuMali['active'] == true) {
            $countTechSavFaisJuMali[] = $allocateDeclaJuMali;
        }
        if (isset($allocateFacJuMali) && isset($allocateDeclaJuMali) && $allocateFacJuMali['active'] == true && $allocateDeclaJuMali['active'] == true && $allocateDeclaJuMali['activeManager'] == true) {
            $testsJuMali[] = $technician;
        }
        $allocateFacSeMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeMali) && $allocateFacSeMali['active'] == true) {
            $countSavoirsSeMali[] = $allocateFacSeMali;
        }
        if (isset($allocateDeclaSeMali) && $allocateDeclaSeMali['activeManager'] == true) {
            $countMaSavFaisSeMali[] = $allocateDeclaSeMali;
        }
        if (isset($allocateDeclaSeMali) && $allocateDeclaSeMali['active'] == true) {
            $countTechSavFaisSeMali[] = $allocateDeclaSeMali;
        }
        if (isset($allocateFacSeMali) && isset($allocateDeclaSeMali) && $allocateFacSeMali['active'] == true && $allocateDeclaSeMali['active'] == true && $allocateDeclaSeMali['activeManager'] == true) {
            $testsSeMali[] = $technician;
        }
        $allocateFacExMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExMali) && $allocateFacExMali['active'] == true) {
            $countSavoirsExMali[] = $allocateFacExMali;
        }
        if (isset($allocateDeclaExMali) && $allocateDeclaExMali['activeManager'] == true) {
            $countMaSavFaisExMali[] = $allocateDeclaExMali;
        }
        if (isset($allocateDeclaExMali) && $allocateDeclaExMali['active'] == true) {
            $countTechSavFaisExMali[] = $allocateDeclaExMali;
        }
        if (isset($allocateFacExMali) && isset($allocateDeclaExMali) && $allocateFacExMali['active'] == true && $allocateDeclaExMali['active'] == true && $allocateDeclaExMali['activeManager'] == true) {
            $testsExMali[] = $technician;
        }
    }
        
    $techniciansRca = [];
    $techsRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuRca = [];
    $techsJuRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeRca = [];
    $techsSeRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExRca = [];
    $techsExRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuRca = [];
    $countSavoirsJuRca = [];
    $countMaSavFaisJuRca = [];
    $countTechSavFaisJuRca = [];
    $testsSeRca = [];
    $countSavoirsSeRca = [];
    $countMaSavFaisSeRca = [];
    $countTechSavFaisSeRca = [];
    $testsExRca = [];
    $countSavoirsExRca = [];
    $countMaSavFaisExRca = [];
    $countTechSavFaisExRca = [];
    foreach ($techniciansRca as $technician) { 
        $allocateFacJuRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuRca) && $allocateFacJuRca['active'] == true) {
            $countSavoirsJuRca[] = $allocateFacJuRca;
        }
        if (isset($allocateDeclaJuRca) && $allocateDeclaJuRca['activeManager'] == true) {
            $countMaSavFaisJuRca[] = $allocateDeclaJuRca;
        }
        if (isset($allocateDeclaJuRca) && $allocateDeclaJuRca['active'] == true) {
            $countTechSavFaisJuRca[] = $allocateDeclaJuRca;
        }
        if (isset($allocateFacJuRca) && isset($allocateDeclaJuRca) && $allocateFacJuRca['active'] == true && $allocateDeclaJuRca['active'] == true && $allocateDeclaJuRca['activeManager'] == true) {
            $testsJuRca[] = $technician;
        }
        $allocateFacSeRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeRca) && $allocateFacSeRca['active'] == true) {
            $countSavoirsSeRca[] = $allocateFacSeRca;
        }
        if (isset($allocateDeclaSeRca) && $allocateDeclaSeRca['activeManager'] == true) {
            $countMaSavFaisSeRca[] = $allocateDeclaSeRca;
        }
        if (isset($allocateDeclaSeRca) && $allocateDeclaSeRca['active'] == true) {
            $countTechSavFaisSeRca[] = $allocateDeclaSeRca;
        }
        if (isset($allocateFacSeRca) && isset($allocateDeclaSeRca) && $allocateFacSeRca['active'] == true && $allocateDeclaSeRca['active'] == true && $allocateDeclaSeRca['activeManager'] == true) {
            $testsSeRca[] = $technician;
        }
        $allocateFacExRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExRca) && $allocateFacExRca['active'] == true) {
            $countSavoirsExRca[] = $allocateFacExRca;
        }
        if (isset($allocateDeclaExRca) && $allocateDeclaExRca['activeManager'] == true) {
            $countMaSavFaisExRca[] = $allocateDeclaExRca;
        }
        if (isset($allocateDeclaExRca) && $allocateDeclaExRca['active'] == true) {
            $countTechSavFaisExRca[] = $allocateDeclaExRca;
        }
        if (isset($allocateFacExRca) && isset($allocateDeclaExRca) && $allocateFacExRca['active'] == true && $allocateDeclaExRca['active'] == true && $allocateDeclaExRca['activeManager'] == true) {
            $testsExRca[] = $technician;
        }
    }
        
    $techniciansRdc = [];
    $techsRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuRdc = [];
    $techsJuRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeRdc = [];
    $techsSeRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExRdc = [];
    $techsExRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuRdc = [];
    $countSavoirsJuRdc = [];
    $countMaSavFaisJuRdc = [];
    $countTechSavFaisJuRdc = [];
    $testsSeRdc = [];
    $countSavoirsSeRdc = [];
    $countMaSavFaisSeRdc = [];
    $countTechSavFaisSeRdc = [];
    $testsExRdc = [];
    $countSavoirsExRdc = [];
    $countMaSavFaisExRdc = [];
    $countTechSavFaisExRdc = [];
    foreach ($techniciansRdc as $technician) { 
        $allocateFacJuRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuRdc) && $allocateFacJuRdc['active'] == true) {
            $countSavoirsJuRdc[] = $allocateFacJuRdc;
        }
        if (isset($allocateDeclaJuRdc) && $allocateDeclaJuRdc['activeManager'] == true) {
            $countMaSavFaisJuRdc[] = $allocateDeclaJuRdc;
        }
        if (isset($allocateDeclaJuRdc) && $allocateDeclaJuRdc['active'] == true) {
            $countTechSavFaisJuRdc[] = $allocateDeclaJuRdc;
        }
        if (isset($allocateFacJuRdc) && isset($allocateDeclaJuRdc) && $allocateFacJuRdc['active'] == true && $allocateDeclaJuRdc['active'] == true && $allocateDeclaJuRdc['activeManager'] == true) {
            $testsJuRdc[] = $technician;
        }
        $allocateFacSeRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeRdc) && $allocateFacSeRdc['active'] == true) {
            $countSavoirsSeRdc[] = $allocateFacSeRdc;
        }
        if (isset($allocateDeclaSeRdc) && $allocateDeclaSeRdc['activeManager'] == true) {
            $countMaSavFaisSeRdc[] = $allocateDeclaSeRdc;
        }
        if (isset($allocateDeclaSeRdc) && $allocateDeclaSeRdc['active'] == true) {
            $countTechSavFaisSeRdc[] = $allocateDeclaSeRdc;
        }
        if (isset($allocateFacSeRdc) && isset($allocateDeclaSeRdc) && $allocateFacSeRdc['active'] == true && $allocateDeclaSeRdc['active'] == true && $allocateDeclaSeRdc['activeManager'] == true) {
            $testsSeRdc[] = $technician;
        }
        $allocateFacExRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExRdc) && $allocateFacExRdc['active'] == true) {
            $countSavoirsExRdc[] = $allocateFacExRdc;
        }
        if (isset($allocateDeclaExRdc) && $allocateDeclaExRdc['activeManager'] == true) {
            $countMaSavFaisExRdc[] = $allocateDeclaExRdc;
        }
        if (isset($allocateDeclaExRdc) && $allocateDeclaExRdc['active'] == true) {
            $countTechSavFaisExRdc[] = $allocateDeclaExRdc;
        }
        if (isset($allocateFacExRdc) && isset($allocateDeclaExRdc) && $allocateFacExRdc['active'] == true && $allocateDeclaExRdc['active'] == true && $allocateDeclaExRdc['activeManager'] == true) {
            $testsExRdc[] = $technician;
        }
    }
        
    $techniciansSe = [];
    $techsSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
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
    $techniciansJuSe = [];
    $techsJuSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeSe = [];
    $techsSeSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExSe = [];
    $techsExSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuSe = [];
    $countSavoirsJuSe = [];
    $countMaSavFaisJuSe = [];
    $countTechSavFaisJuSe = [];
    $testsSeSe = [];
    $countSavoirsSeSe = [];
    $countMaSavFaisSeSe = [];
    $countTechSavFaisSeSe = [];
    $testsExSe = [];
    $countSavoirsExSe = [];
    $countMaSavFaisExSe = [];
    $countTechSavFaisExSe = [];
    foreach ($techniciansSe as $technician) { 
        $allocateFacJuSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuSe) && $allocateFacJuSe['active'] == true) {
            $countSavoirsJuSe[] = $allocateFacJuSe;
        }
        if (isset($allocateDeclaJuSe) && $allocateDeclaJuSe['activeManager'] == true) {
            $countMaSavFaisJuSe[] = $allocateDeclaJuSe;
        }
        if (isset($allocateDeclaJuSe) && $allocateDeclaJuSe['active'] == true) {
            $countTechSavFaisJuSe[] = $allocateDeclaJuSe;
        }
        if (isset($allocateFacJuSe) && isset($allocateDeclaJuSe) && $allocateFacJuSe['active'] == true && $allocateDeclaJuSe['active'] == true && $allocateDeclaJuSe['activeManager'] == true) {
            $testsJuSe[] = $technician;
        }
        $allocateFacSeSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeSe) && $allocateFacSeSe['active'] == true) {
            $countSavoirsSeSe[] = $allocateFacSeSe;
        }
        if (isset($allocateDeclaSeSe) && $allocateDeclaSeSe['activeManager'] == true) {
            $countMaSavFaisSeSe[] = $allocateDeclaSeSe;
        }
        if (isset($allocateDeclaSeSe) && $allocateDeclaSeSe['active'] == true) {
            $countTechSavFaisSeSe[] = $allocateDeclaSeSe;
        }
        if (isset($allocateFacSeSe) && isset($allocateDeclaSeSe) && $allocateFacSeSe['active'] == true && $allocateDeclaSeSe['active'] == true && $allocateDeclaSeSe['activeManager'] == true) {
            $testsSeSe[] = $technician;
        }
        $allocateFacExSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExSe) && $allocateFacExSe['active'] == true) {
            $countSavoirsExSe[] = $allocateFacExSe;
        }
        if (isset($allocateDeclaExSe) && $allocateDeclaExSe['activeManager'] == true) {
            $countMaSavFaisExSe[] = $allocateDeclaExSe;
        }
        if (isset($allocateDeclaExSe) && $allocateDeclaExSe['active'] == true) {
            $countTechSavFaisExSe[] = $allocateDeclaExSe;
        }
        if (isset($allocateFacExSe) && isset($allocateDeclaExSe) && $allocateFacExSe['active'] == true && $allocateDeclaExSe['active'] == true && $allocateDeclaExSe['activeManager'] == true) {
            $testsExSe[] = $technician;
        }
    }   
    ?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $etat_avanacement_country ?> | CFAO Mobility Academy</title>
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
                    <?php echo $etat_avanacement_country ?> </h1>
                <!--end::Title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <!-- <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="<?php echo $recherche?>">
                    </div> -->
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Info-->
            <!--begin::Actions-->
            <!-- <div class="d-flex align-items-center flex-nowrap text-nowrap py-1">
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="users" title="Cliquez ici pour voir la liste des techniciens"
                        data-bs-toggle="modal" class="btn btn-primary">
                        Liste techniciens
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="questions" title="Cliquez ici pour voir la liste des questions"
                        data-bs-toggle="modal" class="btn btn-primary">
                        Liste questionnaires
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="edit" title="Cliquez ici pour modifier le questionnaire"
                        data-bs-toggle="modal" class="btn btn-primary">
                        Modifier
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center" style="margin-left: 10px;">
                    <button type="button" id="delete" title="Cliquez ici pour supprimer le questionnaire"
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
                                class="table align-middle table-bordered  table-row-dashed fs-6 gy-4 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-150px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;"><?php echo $subsidiary ?> CFAO (<?php echo $pays ?>)
                                        </th>
                                        <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $level ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin') { ?>
                                        <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $nbre_test_effectue ?>
                                        </th>
                                        <?php } ?>
                                        <th class="min-w-200px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 150.516px;"><?php echo $qcm_realises ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin') { ?>
                                        <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $taux_evolution_qcm ?>
                                        </th>
                                        <?php } ?>
                                        <th class="min-w-200px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;"><?php echo $qcm_techs_realises ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin') { ?>
                                        <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $taux_evolution_tache_tech ?>
                                        </th>
                                        <?php } ?>
                                        <th class="min-w-200px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;"><?php echo $qcm_manager_realises ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin') { ?>
                                        <th class="min-w-0px sorting text-center" tabindex="0" aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px;"><?php echo $taux_evolution_tache_manager ?>
                                        </th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                <?php if ($_SESSION['profile'] == 'Directeur Groupe') { ?>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $burkina ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countSavoirsJuBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countTechSavFaisJuBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countMaSavFaisJuBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBu = count($techniciansSeBu);
                                            $technicianCountExBu = count($techniciansExBu);
                                            $totalTechnicianCountBu = $technicianCountSeBu + $technicianCountExBu;
                                    
                                            if ($totalTechnicianCountBu > 0) {
                                                $percentageBu = ceil((count($countSavoirsSeBu)) * 100 / ($totalTechnicianCountBu));
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBu = count($techniciansSeBu);
                                            $technicianCountExBu = count($techniciansExBu);
                                            $totalTechnicianCountBu = $technicianCountSeBu + $technicianCountExBu;
                                    
                                            if ($totalTechnicianCountBu > 0) {
                                                $percentageBu = ceil((count($countTechSavFaisSeBu)) * 100 / ($totalTechnicianCountBu));
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeBu = count($techniciansSeBu);
                                            $technicianCountExBu = count($techniciansExBu);
                                            $totalTechnicianCountBu = $technicianCountSeBu + $technicianCountExBu;
                                    
                                            if ($totalTechnicianCountBu > 0) {
                                                $percentageBu = ceil((count($countMaSavFaisSeBu)) * 100 / ($totalTechnicianCountBu));
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBu = count($techniciansExBu);
                                    
                                            if ($technicianCountExBu > 0) {
                                                $percentageBu = ceil((count($countSavoirsExBu)) * 100 / ($technicianCountExBu));
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBu = count($techniciansExBu);
                                    
                                            if ($technicianCountExBu > 0) {
                                                $percentageBu = ceil((count($countTechSavFaisExBu)) * 100 / ($technicianCountExBu));
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExBu = count($techniciansExBu);
                                    
                                            if ($technicianCountExBu > 0) {
                                                $percentageBu = ceil((count($countMaSavFaisExBu)) * 100 / ($technicianCountExBu));
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $cameroun ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountCa = count($techniciansCa);
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countSavoirsJuCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountCa = count($techniciansCa);
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countTechSavFaisJuCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountCa = count($techniciansCa);
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countMaSavFaisJuCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeCa = count($techniciansSeCa);
                                            $technicianCountExCa = count($techniciansExCa);
                                            $totalTechnicianCountCa = $technicianCountSeCa + $technicianCountExCa;
                                    
                                            if ($totalTechnicianCountCa > 0) {
                                                $percentageCa = ceil((count($countSavoirsSeCa)) * 100 / ($totalTechnicianCountCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeCa = count($techniciansSeCa);
                                            $technicianCountExCa = count($techniciansExCa);
                                            $totalTechnicianCountCa = $technicianCountSeCa + $technicianCountExCa;
                                    
                                            if ($totalTechnicianCountCa > 0) {
                                                $percentageCa = ceil((count($countTechSavFaisSeCa)) * 100 / ($totalTechnicianCountCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeCa = count($techniciansSeCa);
                                            $technicianCountExCa = count($techniciansExCa);
                                            $totalTechnicianCountCa = $technicianCountSeCa + $technicianCountExCa;
                                    
                                            if ($totalTechnicianCountCa > 0) {
                                                $percentageCa = ceil((count($countMaSavFaisSeCa)) * 100 / ($totalTechnicianCountCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExCa = count($techniciansExCa);
                                    
                                            if ($technicianCountExCa > 0) {
                                                $percentageCa = ceil((count($countSavoirsExCa)) * 100 / ($technicianCountExCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExCa = count($techniciansExCa);
                                    
                                            if ($technicianCountExCa > 0) {
                                                $percentageCa = ceil((count($countTechSavFaisExCa)) * 100 / ($technicianCountExCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExCa = count($techniciansExCa);
                                    
                                            if ($technicianCountExCa > 0) {
                                                $percentageCa = ceil((count($countMaSavFaisExCa)) * 100 / ($technicianCountExCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $cote_divoire ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRci = count($techniciansRci);
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil(count(($countSavoirsJuRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRci = count($techniciansRci);
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil(count(($countTechSavFaisJuRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRci = count($techniciansRci);
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil(count(($countMaSavFaisJuRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRci = count($techniciansSeRci);
                                            $technicianCountExRci = count($techniciansExRci);
                                            $totalTechnicianCountRci = $technicianCountSeRci + $technicianCountExRci;
                                    
                                            if ($totalTechnicianCountRci > 0) {
                                                $percentageRci = ceil((count($countSavoirsSeRci)) * 100 / ($totalTechnicianCountRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRci = count($techniciansSeRci);
                                            $technicianCountExRci = count($techniciansExRci);
                                            $totalTechnicianCountRci = $technicianCountSeRci + $technicianCountExRci;
                                    
                                            if ($totalTechnicianCountRci > 0) {
                                                $percentageRci = ceil((count($countTechSavFaisSeRci)) * 100 / ($totalTechnicianCountRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRci = count($techniciansSeRci);
                                            $technicianCountExRci = count($techniciansExRci);
                                            $totalTechnicianCountRci = $technicianCountSeRci + $technicianCountExRci;
                                    
                                            if ($totalTechnicianCountRci > 0) {
                                                $percentageRci = ceil((count($countMaSavFaisSeRci)) * 100 / ($totalTechnicianCountRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRci = count($techniciansExRci);
                                    
                                            if ($technicianCountExRci > 0) {
                                                $percentageRci = ceil((count($countSavoirsExRci)) * 100 / ($technicianCountExRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRci = count($techniciansExRci);
                                    
                                            if ($technicianCountExRci > 0) {
                                                $percentageRci = ceil((count($countTechSavFaisExRci)) * 100 / ($technicianCountExRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRci = count($techniciansExRci);
                                    
                                            if ($technicianCountExRci > 0) {
                                                $percentageRci = ceil((count($countMaSavFaisExRci)) * 100 / ($technicianCountExRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $gabon ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
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
                                            <?php $technicianCountGa = count($techniciansGa);
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countTechSavFaisJuGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
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
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
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
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
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
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $mali ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountMali = count($techniciansMali);
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countSavoirsJuMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountMali = count($techniciansMali);
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countTechSavFaisJuMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountMali = count($techniciansMali);
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countMaSavFaisJuMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeMali = count($techniciansSeMali);
                                            $technicianCountExMali = count($techniciansExMali);
                                            $totalTechnicianCountMali = $technicianCountSeMali + $technicianCountExMali;
                                    
                                            if ($totalTechnicianCountMali > 0) {
                                                $percentageMali = ceil((count($countSavoirsSeMali)) * 100 / ($totalTechnicianCountMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeMali = count($techniciansSeMali);
                                            $technicianCountExMali = count($techniciansExMali);
                                            $totalTechnicianCountMali = $technicianCountSeMali + $technicianCountExMali;
                                    
                                            if ($totalTechnicianCountMali > 0) {
                                                $percentageMali = ceil((count($countTechSavFaisSeMali)) * 100 / ($totalTechnicianCountMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeMali = count($techniciansSeMali);
                                            $technicianCountExMali = count($techniciansExMali);
                                            $totalTechnicianCountMali = $technicianCountSeMali + $technicianCountExMali;
                                    
                                            if ($totalTechnicianCountMali > 0) {
                                                $percentageMali = ceil((count($countMaSavFaisSeMali)) * 100 / ($totalTechnicianCountMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExMali = count($techniciansExMali);
                                    
                                            if ($technicianCountExMali > 0) {
                                                $percentageMali = ceil((count($countSavoirsExMali)) * 100 / ($technicianCountExMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExMali = count($techniciansExMali);
                                    
                                            if ($technicianCountExMali > 0) {
                                                $percentageMali = ceil((count($countTechSavFaisExMali)) * 100 / ($technicianCountExMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExMali = count($techniciansExMali);
                                    
                                            if ($technicianCountExMali > 0) {
                                                $percentageMali = ceil((count($countMaSavFaisExMali)) * 100 / ($technicianCountExMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $centrafrique ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRca = count($techniciansRca);
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countSavoirsJuRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRca = count($techniciansRca);
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countTechSavFaisJuRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRca = count($techniciansRca);
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countMaSavFaisJuRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRca = count($techniciansSeRca);
                                            $technicianCountExRca = count($techniciansExRca);
                                            $totalTechnicianCountRca = $technicianCountSeRca + $technicianCountExRca;
                                    
                                            if ($totalTechnicianCountRca > 0) {
                                                $percentageRca = ceil((count($countSavoirsSeRca)) * 100 / ($totalTechnicianCountRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRca = count($techniciansSeRca);
                                            $technicianCountExRca = count($techniciansExRca);
                                            $totalTechnicianCountRca = $technicianCountSeRca + $technicianCountExRca;
                                    
                                            if ($totalTechnicianCountRca > 0) {
                                                $percentageRca = ceil((count($countTechSavFaisSeRca)) * 100 / ($totalTechnicianCountRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRca = count($techniciansSeRca);
                                            $technicianCountExRca = count($techniciansExRca);
                                            $totalTechnicianCountRca = $technicianCountSeRca + $technicianCountExRca;
                                    
                                            if ($totalTechnicianCountRca > 0) {
                                                $percentageRca = ceil((count($countMaSavFaisSeRca)) * 100 / ($totalTechnicianCountRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRca = count($techniciansExRca);
                                    
                                            if ($technicianCountExRca > 0) {
                                                $percentageRca = ceil((count($countSavoirsExRca)) * 100 / ($technicianCountExRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRca = count($techniciansExRca);
                                    
                                            if ($technicianCountExRca > 0) {
                                                $percentageRca = ceil((count($countTechSavFaisExRca)) * 100 / ($technicianCountExRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRca = count($techniciansExRca);
                                    
                                            if ($technicianCountExRca > 0) {
                                                $percentageRca = ceil((count($countMaSavFaisExRca)) * 100 / ($technicianCountExRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $rdc ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRdc = count($techniciansRdc);
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countSavoirsJuRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRdc = count($techniciansRdc);
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countTechSavFaisJuRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRdc = count($techniciansRdc);
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countMaSavFaisJuRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRdc = count($techniciansSeRdc);
                                            $technicianCountExRdc = count($techniciansExRdc);
                                            $totalTechnicianCountRdc = $technicianCountSeRdc + $technicianCountExRdc;
                                    
                                            if ($totalTechnicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countSavoirsSeRdc)) * 100 / ($totalTechnicianCountRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRdc = count($techniciansSeRdc);
                                            $technicianCountExRdc = count($techniciansExRdc);
                                            $totalTechnicianCountRdc = $technicianCountSeRdc + $technicianCountExRdc;
                                    
                                            if ($totalTechnicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countTechSavFaisSeRdc)) * 100 / ($totalTechnicianCountRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRdc = count($techniciansSeRdc);
                                            $technicianCountExRdc = count($techniciansExRdc);
                                            $totalTechnicianCountRdc = $technicianCountSeRdc + $technicianCountExRdc;
                                    
                                            if ($totalTechnicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countMaSavFaisSeRdc)) * 100 / ($totalTechnicianCountRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRdc = count($techniciansExRdc);
                                    
                                            if ($technicianCountExRdc > 0) {
                                                $percentageRdc = ceil((count($countSavoirsExRdc)) * 100 / ($technicianCountExRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRdc = count($techniciansExRdc);
                                    
                                            if ($technicianCountExRdc > 0) {
                                                $percentageRdc = ceil((count($countTechSavFaisExRdc)) * 100 / ($technicianCountExRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRdc = count($techniciansExRdc);
                                    
                                            if ($technicianCountExRdc > 0) {
                                                $percentageRdc = ceil((count($countMaSavFaisExRdc)) * 100 / ($technicianCountExRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $senegal ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountSe = count($techniciansSe);
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countSavoirsJuSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountSe = count($techniciansSe);
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countTechSavFaisJuSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountSe = count($techniciansSe);
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countMaSavFaisJuSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeSe = count($techniciansSeSe);
                                            $technicianCountExSe = count($techniciansExSe);
                                            $totalTechnicianCountSe = $technicianCountSeSe + $technicianCountExSe;
                                    
                                            if ($totalTechnicianCountSe > 0) {
                                                $percentageSe = ceil((count($countSavoirsSeSe)) * 100 / ($totalTechnicianCountSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeSe = count($techniciansSeSe);
                                            $technicianCountExSe = count($techniciansExSe);
                                            $totalTechnicianCountSe = $technicianCountSeSe + $technicianCountExSe;
                                    
                                            if ($totalTechnicianCountSe > 0) {
                                                $percentageSe = ceil((count($countTechSavFaisSeSe)) * 100 / ($totalTechnicianCountSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeSe = count($techniciansSeSe);
                                            $technicianCountExSe = count($techniciansExSe);
                                            $totalTechnicianCountSe = $technicianCountSeSe + $technicianCountExSe;
                                    
                                            if ($totalTechnicianCountSe > 0) {
                                                $percentageSe = ceil((count($countMaSavFaisSeSe)) * 100 / ($totalTechnicianCountSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExSe = count($techniciansExSe);
                                    
                                            if ($technicianCountExSe > 0) {
                                                $percentageSe = ceil((count($countSavoirsExSe)) * 100 / ($technicianCountExSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExSe = count($techniciansExSe);
                                    
                                            if ($technicianCountExSe > 0) {
                                                $percentageSe = ceil((count($countTechSavFaisExSe)) * 100 / ($technicianCountExSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExSe = count($techniciansExSe);
                                    
                                            if ($technicianCountExSe > 0) {
                                                $percentageSe = ceil((count($countMaSavFaisExSe)) * 100 / ($technicianCountExSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php } else { ?>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $burkina ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countSavoirsJuBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countTechSavFaisJuBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countMaSavFaisJuBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeBu) + count($techniciansExBu)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = (count($techniciansSeBu) + count($techniciansExBu));
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countSavoirsSeBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = (count($techniciansSeBu) + count($techniciansExBu));
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countTechSavFaisSeBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = (count($techniciansSeBu) + count($techniciansExBu));
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countMaSavFaisSeBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansExBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countSavoirsExBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansExBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countTechSavFaisExBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExBu) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountBu = count($techniciansExBu);
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countMaSavFaisExBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansBu) + count($techniciansSeBu) + (count($techniciansExBu) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuBu) + count($countSavoirsSeBu) + count($countSavoirsExBu)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBu = (count($techniciansBu) + count($techniciansSeBu)+ (count($techniciansExBu) * 2));
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countSavoirsJuBu) + count($countSavoirsSeBu) + count($countSavoirsExBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuBu) + count($countTechSavFaisSeBu) + count($countTechSavFaisExBu)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBu = (count($techniciansBu) + count($techniciansSeBu) + (count($techniciansExBu) * 2));
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countTechSavFaisJuBu) + count($countTechSavFaisSeBu) + count($countTechSavFaisExBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuBu) + count($countMaSavFaisSeBu) + count($countMaSavFaisExBu)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountBu = (count($techniciansBu) + count($techniciansSeBu) + (count($techniciansExBu) * 2));
                                            if ($technicianCountBu > 0) {
                                                $percentageBu = ceil((count($countMaSavFaisJuBu) + count($countMaSavFaisSeBu) + count($countMaSavFaisExBu)) * 100 / $technicianCountBu);
                                            } else {
                                                $percentageBu = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageBu . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $cameroun ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountCa = count($techniciansCa);
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countSavoirsJuCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountCa = count($techniciansCa);
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countTechSavFaisJuCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountCa = count($techniciansCa);
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countMaSavFaisJuCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeCa) + count($techniciansExCa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeCa = count($techniciansSeCa);
                                            $technicianCountExCa = count($techniciansExCa);
                                            $totalTechnicianCountCa = $technicianCountSeCa + $technicianCountExCa;
                                    
                                            if ($totalTechnicianCountCa > 0) {
                                                $percentageCa = ceil((count($countSavoirsSeCa)) * 100 / ($totalTechnicianCountCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeCa = count($techniciansSeCa);
                                            $technicianCountExCa = count($techniciansExCa);
                                            $totalTechnicianCountCa = $technicianCountSeCa + $technicianCountExCa;
                                    
                                            if ($totalTechnicianCountCa > 0) {
                                                $percentageCa = ceil((count($countTechSavFaisSeCa)) * 100 / ($totalTechnicianCountCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeCa = count($techniciansSeCa);
                                            $technicianCountExCa = count($techniciansExCa);
                                            $totalTechnicianCountCa = $technicianCountSeCa + $technicianCountExCa;
                                    
                                            if ($totalTechnicianCountCa > 0) {
                                                $percentageCa = ceil((count($countMaSavFaisSeCa)) * 100 / ($totalTechnicianCountCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExCa = count($techniciansExCa);
                                    
                                            if ($technicianCountExCa > 0) {
                                                $percentageCa = ceil((count($countSavoirsExCa)) * 100 / ($technicianCountExCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExCa = count($techniciansExCa);
                                    
                                            if ($technicianCountExCa > 0) {
                                                $percentageCa = ceil((count($countTechSavFaisExCa)) * 100 / ($technicianCountExCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExCa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExCa = count($techniciansExCa);
                                    
                                            if ($technicianCountExCa > 0) {
                                                $percentageCa = ceil((count($countMaSavFaisExCa)) * 100 / ($technicianCountExCa));
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuCa) + count($countSavoirsSeCa) + count($countSavoirsExCa)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountCa = (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2));
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countSavoirsJuCa) + count($countSavoirsSeCa) + count($countSavoirsExCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuCa) + count($countTechSavFaisSeCa)+ count($countTechSavFaisExCa)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountCa = (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2));
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countTechSavFaisJuCa) + count($countTechSavFaisSeCa) + count($countTechSavFaisExCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuCa) + count($countMaSavFaisSeCa) + count($countMaSavFaisExCa)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountCa = (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2));
                                            if ($technicianCountCa > 0) {
                                                $percentageCa = ceil((count($countMaSavFaisJuCa) + count($countMaSavFaisSeCa) + count($countMaSavFaisExCa)) * 100 / $technicianCountCa);
                                            } else {
                                                $percentageCa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageCa . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $cote_divoire ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRci = count($techniciansRci);
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil(count(($countSavoirsJuRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRci = count($techniciansRci);
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil(count(($countTechSavFaisJuRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRci = count($techniciansRci);
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil(count(($countMaSavFaisJuRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeRci) + count($techniciansExRci))?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRci = count($techniciansSeRci);
                                            $technicianCountExRci = count($techniciansExRci);
                                            $totalTechnicianCountRci = $technicianCountSeRci + $technicianCountExRci;
                                    
                                            if ($totalTechnicianCountRci > 0) {
                                                $percentageRci = ceil((count($countSavoirsSeRci)) * 100 / ($totalTechnicianCountRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRci = count($techniciansSeRci);
                                            $technicianCountExRci = count($techniciansExRci);
                                            $totalTechnicianCountRci = $technicianCountSeRci + $technicianCountExRci;
                                    
                                            if ($totalTechnicianCountRci > 0) {
                                                $percentageRci = ceil((count($countTechSavFaisSeRci)) * 100 / ($totalTechnicianCountRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRci = count($techniciansSeRci);
                                            $technicianCountExRci = count($techniciansExRci);
                                            $totalTechnicianCountRci = $technicianCountSeRci + $technicianCountExRci;
                                    
                                            if ($totalTechnicianCountRci > 0) {
                                                $percentageRci = ceil((count($countMaSavFaisSeRci)) * 100 / ($totalTechnicianCountRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRci = count($techniciansExRci);
                                    
                                            if ($technicianCountExRci > 0) {
                                                $percentageRci = ceil((count($countSavoirsExRci)) * 100 / ($technicianCountExRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRci = count($techniciansExRci);
                                    
                                            if ($technicianCountExRci > 0) {
                                                $percentageRci = ceil((count($countTechSavFaisExRci)) * 100 / ($technicianCountExRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExRci) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRci = count($techniciansExRci);
                                    
                                            if ($technicianCountExRci > 0) {
                                                $percentageRci = ceil((count($countMaSavFaisExRci)) * 100 / ($technicianCountExRci));
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuRci) + count($countSavoirsSeRci) + count($countSavoirsExRci)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRci = (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2));
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil((count($countSavoirsJuRci) + count($countSavoirsSeRci) + count($countSavoirsExRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo count($countTechSavFaisJuRci) + count($countTechSavFaisSeRci) + count($countTechSavFaisExRci) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRci = (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2));
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil((count($countTechSavFaisJuRci) + count($countTechSavFaisSeRci) + count($countTechSavFaisExRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuRci) + count($countMaSavFaisSeRci) + count($countMaSavFaisExRci)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRci = (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2));
                                            if ($technicianCountRci > 0) {
                                                $percentageRci = ceil((count($countMaSavFaisJuRci) + count($countMaSavFaisSeRci) + count($countMaSavFaisExRci)) * 100 / $technicianCountRci);
                                            } else {
                                                $percentageRci = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRci . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $gabon ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuGa) ?>
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
                                            <?php echo count($countTechSavFaisJuGa) ?>
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
                                            <?php echo count($countMaSavFaisJuGa) ?>
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
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeGa) + count($techniciansExGa)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeGa) ?>
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
                                            <?php echo count($countTechSavFaisSeGa) ?>
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
                                            <?php echo count($countMaSavFaisSeGa) ?>
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
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExGa) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExGa) ?>
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
                                            <?php echo count($countTechSavFaisExGa) ?>
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
                                            <?php echo count($countMaSavFaisExGa) ?>
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
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2))?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountGa = (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2));
                                            if ($technicianCountGa > 0) {
                                                $percentageGa = ceil((count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) * 100 / $technicianCountGa);
                                            } else {
                                                $percentageGa = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageGa . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) ?>
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
                                            <?php echo (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) ?>
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
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $mali ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountMali = count($techniciansMali);
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countSavoirsJuMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountMali = count($techniciansMali);
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countTechSavFaisJuMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountMali = count($techniciansMali);
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countMaSavFaisJuMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeMali) + count($techniciansExMali)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeMali = count($techniciansSeMali);
                                            $technicianCountExMali = count($techniciansExMali);
                                            $totalTechnicianCountMali = $technicianCountSeMali + $technicianCountExMali;
                                    
                                            if ($totalTechnicianCountMali > 0) {
                                                $percentageMali = ceil((count($countSavoirsSeMali)) * 100 / ($totalTechnicianCountMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeMali = count($techniciansSeMali);
                                            $technicianCountExMali = count($techniciansExMali);
                                            $totalTechnicianCountMali = $technicianCountSeMali + $technicianCountExMali;
                                    
                                            if ($totalTechnicianCountMali > 0) {
                                                $percentageMali = ceil((count($countTechSavFaisSeMali)) * 100 / ($totalTechnicianCountMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeMali = count($techniciansSeMali);
                                            $technicianCountExMali = count($techniciansExMali);
                                            $totalTechnicianCountMali = $technicianCountSeMali + $technicianCountExMali;
                                    
                                            if ($totalTechnicianCountMali > 0) {
                                                $percentageMali = ceil((count($countMaSavFaisSeMali)) * 100 / ($totalTechnicianCountMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExMali = count($techniciansExMali);
                                    
                                            if ($technicianCountExMali > 0) {
                                                $percentageMali = ceil((count($countSavoirsExMali)) * 100 / ($technicianCountExMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExMali = count($techniciansExMali);
                                    
                                            if ($technicianCountExMali > 0) {
                                                $percentageMali = ceil((count($countTechSavFaisExMali)) * 100 / ($technicianCountExMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExMali) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExMali = count($techniciansExMali);
                                    
                                            if ($technicianCountExMali > 0) {
                                                $percentageMali = ceil((count($countMaSavFaisExMali)) * 100 / ($technicianCountExMali));
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuMali) + count($countSavoirsSeMali) + count($countSavoirsExMali)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountMali = (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2));
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countSavoirsJuMali) + count($countSavoirsSeMali) + count($countSavoirsExMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuMali) + count($countTechSavFaisSeMali) + count($countTechSavFaisExMali)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountMali = (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2));
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countTechSavFaisJuMali) + count($countTechSavFaisSeMali) + count($countTechSavFaisExMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuMali) + count($countMaSavFaisSeMali) + count($countMaSavFaisExMali)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountMali = (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2));
                                            if ($technicianCountMali > 0) {
                                                $percentageMali = ceil((count($countMaSavFaisJuMali) + count($countMaSavFaisSeMali) + count($countMaSavFaisExMali)) * 100 / $technicianCountMali);
                                            } else {
                                                $percentageMali = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageMali . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $centrafrique ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRca = count($techniciansRca);
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countSavoirsJuRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRca = count($techniciansRca);
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countTechSavFaisJuRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRca = count($techniciansRca);
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countMaSavFaisJuRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeRca) + count($techniciansExRca)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRca = count($techniciansSeRca);
                                            $technicianCountExRca = count($techniciansExRca);
                                            $totalTechnicianCountRca = $technicianCountSeRca + $technicianCountExRca;
                                    
                                            if ($totalTechnicianCountRca > 0) {
                                                $percentageRca = ceil((count($countSavoirsSeRca)) * 100 / ($totalTechnicianCountRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRca = count($techniciansSeRca);
                                            $technicianCountExRca = count($techniciansExRca);
                                            $totalTechnicianCountRca = $technicianCountSeRca + $technicianCountExRca;
                                    
                                            if ($totalTechnicianCountRca > 0) {
                                                $percentageRca = ceil((count($countTechSavFaisSeRca)) * 100 / ($totalTechnicianCountRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRca = count($techniciansSeRca);
                                            $technicianCountExRca = count($techniciansExRca);
                                            $totalTechnicianCountRca = $technicianCountSeRca + $technicianCountExRca;
                                    
                                            if ($totalTechnicianCountRca > 0) {
                                                $percentageRca = ceil((count($countMaSavFaisSeRca)) * 100 / ($totalTechnicianCountRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRca = count($techniciansExRca);
                                    
                                            if ($technicianCountExRca > 0) {
                                                $percentageRca = ceil((count($countSavoirsExRca)) * 100 / ($technicianCountExRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRca = count($techniciansExRca);
                                    
                                            if ($technicianCountExRca > 0) {
                                                $percentageRca = ceil((count($countTechSavFaisExRca)) * 100 / ($technicianCountExRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExRca) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRca = count($techniciansExRca);
                                    
                                            if ($technicianCountExRca > 0) {
                                                $percentageRca = ceil((count($countMaSavFaisExRca)) * 100 / ($technicianCountExRca));
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuRca) + count($countSavoirsSeRca) + count($countSavoirsExRca)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRca = (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2));
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countSavoirsJuRca) + count($countSavoirsSeRca) + count($countSavoirsExRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuRca) + count($countTechSavFaisSeRca) + count($countTechSavFaisExRca)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRca = (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2));
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countTechSavFaisJuRca) + count($countTechSavFaisSeRca) + count($countTechSavFaisExRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuRca) + count($countMaSavFaisSeRca) + count($countMaSavFaisExRca)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRca = (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2));
                                            if ($technicianCountRca > 0) {
                                                $percentageRca = ceil((count($countMaSavFaisJuRca) + count($countMaSavFaisSeRca) + count($countMaSavFaisExRca)) * 100 / $technicianCountRca);
                                            } else {
                                                $percentageRca = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRca . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $rdc ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRdc = count($techniciansRdc);
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countSavoirsJuRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRdc = count($techniciansRdc);
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countTechSavFaisJuRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountRdc = count($techniciansRdc);
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countMaSavFaisJuRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeRdc) + count($techniciansExRdc)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRdc = count($techniciansSeRdc);
                                            $technicianCountExRdc = count($techniciansExRdc);
                                            $totalTechnicianCountRdc = $technicianCountSeRdc + $technicianCountExRdc;
                                    
                                            if ($totalTechnicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countSavoirsSeRdc)) * 100 / ($totalTechnicianCountRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRdc = count($techniciansSeRdc);
                                            $technicianCountExRdc = count($techniciansExRdc);
                                            $totalTechnicianCountRdc = $technicianCountSeRdc + $technicianCountExRdc;
                                    
                                            if ($totalTechnicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countTechSavFaisSeRdc)) * 100 / ($totalTechnicianCountRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeRdc = count($techniciansSeRdc);
                                            $technicianCountExRdc = count($techniciansExRdc);
                                            $totalTechnicianCountRdc = $technicianCountSeRdc + $technicianCountExRdc;
                                    
                                            if ($totalTechnicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countMaSavFaisSeRdc)) * 100 / ($totalTechnicianCountRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRdc = count($techniciansExRdc);
                                    
                                            if ($technicianCountExRdc > 0) {
                                                $percentageRdc = ceil((count($countSavoirsExRdc)) * 100 / ($technicianCountExRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRdc = count($techniciansExRdc);
                                    
                                            if ($technicianCountExRdc > 0) {
                                                $percentageRdc = ceil((count($countTechSavFaisExRdc)) * 100 / ($technicianCountExRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExRdc) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExRdc = count($techniciansExRdc);
                                    
                                            if ($technicianCountExRdc > 0) {
                                                $percentageRdc = ceil((count($countMaSavFaisExRdc)) * 100 / ($technicianCountExRdc));
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuRdc) + count($countSavoirsSeRdc) + count($countSavoirsExRdc)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRdc = (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2));
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countSavoirsJuRdc) + count($countSavoirsSeRdc) + count($countSavoirsExRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuRdc) + count($countTechSavFaisSeRdc) + count($countTechSavFaisExRdc)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRdc = (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2));
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countTechSavFaisJuRdc) + count($countTechSavFaisSeRdc) + count($countTechSavFaisExRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuRdc) + count($countMaSavFaisSeRdc) + count($countMaSavFaisExRdc)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountRdc = (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2));
                                            if ($technicianCountRdc > 0) {
                                                $percentageRdc = ceil((count($countMaSavFaisJuRdc) + count($countMaSavFaisSeRdc) + count($countMaSavFaisExRdc)) * 100 / $technicianCountRdc);
                                            } else {
                                                $percentageRdc = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageRdc . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" rowspan="<?php echo $i ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $senegal ?>
                                            </a>
                                        </th>
                                        <td class="text-center">
                                            <?php echo $junior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsJuSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountSe = count($techniciansSe);
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countSavoirsJuSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisJuSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountSe = count($techniciansSe);
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countTechSavFaisJuSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisJuSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php $technicianCountSe = count($techniciansSe);
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countMaSavFaisJuSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $senior ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo (count($techniciansSeSe) + count($techniciansExSe)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsSeSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeSe = count($techniciansSeSe);
                                            $technicianCountExSe = count($techniciansExSe);
                                            $totalTechnicianCountSe = $technicianCountSeSe + $technicianCountExSe;
                                    
                                            if ($totalTechnicianCountSe > 0) {
                                                $percentageSe = ceil((count($countSavoirsSeSe)) * 100 / ($totalTechnicianCountSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisSeSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeSe = count($techniciansSeSe);
                                            $technicianCountExSe = count($techniciansExSe);
                                            $totalTechnicianCountSe = $technicianCountSeSe + $technicianCountExSe;
                                    
                                            if ($totalTechnicianCountSe > 0) {
                                                $percentageSe = ceil((count($countTechSavFaisSeSe)) * 100 / ($totalTechnicianCountSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisSeSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountSeSe = count($techniciansSeSe);
                                            $technicianCountExSe = count($techniciansExSe);
                                            $totalTechnicianCountSe = $technicianCountSeSe + $technicianCountExSe;
                                    
                                            if ($totalTechnicianCountSe > 0) {
                                                $percentageSe = ceil((count($countMaSavFaisSeSe)) * 100 / ($totalTechnicianCountSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center">
                                            <?php echo $expert ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($techniciansExSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countSavoirsExSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExSe = count($techniciansExSe);
                                    
                                            if ($technicianCountExSe > 0) {
                                                $percentageSe = ceil((count($countSavoirsExSe)) * 100 / ($technicianCountExSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countTechSavFaisExSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExSe = count($techniciansExSe);
                                    
                                            if ($technicianCountExSe > 0) {
                                                $percentageSe = ceil((count($countTechSavFaisExSe)) * 100 / ($technicianCountExSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo count($countMaSavFaisExSe) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $technicianCountExSe = count($techniciansExSe);
                                    
                                            if ($technicianCountExSe > 0) {
                                                $percentageSe = ceil((count($countMaSavFaisExSe)) * 100 / ($technicianCountExSe));
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo $global ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countSavoirsJuSe) + count($countSavoirsSeSe) + count($countSavoirsExSe)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountSe = (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2));
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countSavoirsJuSe) + count($countSavoirsSeSe) + count($countSavoirsExSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countTechSavFaisJuSe) + count($countTechSavFaisSeSe) + count($countTechSavFaisExSe)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountSe = (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2));
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countTechSavFaisJuSe) + count($countTechSavFaisSeSe) + count($countTechSavFaisExSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php echo (count($countMaSavFaisJuSe) + count($countMaSavFaisSeSe) + count($countMaSavFaisExSe)) ?>
                                        </td>
                                        <td class="text-center fw-bolder" style="background-color: #edf2f7;">
                                            <?php $technicianCountSe = (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2));
                                            if ($technicianCountSe > 0) {
                                                $percentageSe = ceil((count($countMaSavFaisJuSe) + count($countMaSavFaisSeSe) + count($countMaSavFaisExSe)) * 100 / $technicianCountSe);
                                            } else {
                                                $percentageSe = 0; // or any other appropriate value or message
                                            }
                                            echo $percentageSe . '%'; ?>
                                        </td>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center" colspan="9">
                                        </th>
                                    </tr>
                                    <tr class="odd" etat="">
                                        <th class=" text-center fw-bolder bg-primary" colspan="2">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer"
                                                class="text-gray-800 text-hover-primary">
                                                <?php echo $result ?>
                                            </a>
                                        </th>
                                        <td class="text-center fw-bolder bg-primary">
                                            <?php echo ((count($techniciansBu) + count($techniciansSeBu) + (count($techniciansExBu) * 2)) + (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2)) + (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2)) + (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2)) + (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2)) + (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2))) ?>
                                        </td>
                                        <td class="text-center fw-bolder bg-primary">
                                            <?php echo ((count($countSavoirsJuBu) + count($countSavoirsSeBu) + count($countSavoirsExBu)) + (count($countSavoirsJuCa) + count($countSavoirsSeCa) + count($countSavoirsExCa)) + (count($countSavoirsJuRci) + count($countSavoirsSeRci) + count($countSavoirsExRci)) + (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) + (count($countSavoirsJuMali) + count($countSavoirsSeMali) + count($countSavoirsExMali)) + (count($countSavoirsJuRca) + count($countSavoirsSeRca) + count($countSavoirsExRca)) + (count($countSavoirsJuRdc) + count($countSavoirsSeRdc) + count($countSavoirsExRdc)) + (count($countSavoirsJuSe) + count($countSavoirsSeSe) + count($countSavoirsExSe))) ?>
                                        </td>
                                        <td class="text-center fw-bolder bg-primary">
                                            <?php $technicianCount = ((count($techniciansBu) + count($techniciansSeBu) + (count($techniciansExBu) * 2)) + (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2)) + (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2)) + (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2)) + (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2)) + (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2)));
                                            if ($technicianCount > 0) {
                                                $percentage = ceil(((count($countSavoirsJuBu) + count($countSavoirsSeBu) + count($countSavoirsExBu)) + (count($countSavoirsJuCa) + count($countSavoirsSeCa) + count($countSavoirsExCa)) + (count($countSavoirsJuRci) + count($countSavoirsSeRci) + count($countSavoirsExRci)) + (count($countSavoirsJuGa) + count($countSavoirsSeGa) + count($countSavoirsExGa)) + (count($countSavoirsJuMali) + count($countSavoirsSeMali) + count($countSavoirsExMali)) + (count($countSavoirsJuRca) + count($countSavoirsSeRca) + count($countSavoirsExRca)) + (count($countSavoirsJuRdc) + count($countSavoirsSeRdc) + count($countSavoirsExRdc)) + (count($countSavoirsJuSe) + count($countSavoirsSeSe) + count($countSavoirsExSe))) * 100 / $technicianCount);
                                            } else {
                                                $percentage = 0; // or any other appropriate value or message
                                            }
                                            echo $percentage . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder bg-primary">
                                            <?php echo ((count($countTechSavFaisJuBu) + count($countTechSavFaisSeBu) + count($countTechSavFaisExBu)) + (count($countTechSavFaisJuCa) + count($countTechSavFaisSeCa) + count($countTechSavFaisExCa)) + (count($countTechSavFaisJuRci) + count($countTechSavFaisSeRci) + count($countTechSavFaisExRci)) + (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) + (count($countTechSavFaisJuMali) + count($countTechSavFaisSeMali) + count($countTechSavFaisExMali)) + (count($countTechSavFaisJuRca) + count($countTechSavFaisSeRca) + count($countTechSavFaisExRca)) + (count($countTechSavFaisJuRdc) + count($countTechSavFaisSeRdc) + count($countTechSavFaisExRdc)) + (count($countTechSavFaisJuSe) + count($countTechSavFaisSeSe) + count($countTechSavFaisExSe))) ?>
                                        </td>
                                        <td class="text-center fw-bolder bg-primary">
                                            <?php $technicianCount = ((count($techniciansBu) + count($techniciansSeBu) + (count($techniciansExBu) * 2)) + (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2)) + (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2)) + (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2)) + (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2)) + (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2)));
                                            if ($technicianCount > 0) {
                                                $percentage = ceil(((count($countTechSavFaisJuBu) + count($countTechSavFaisSeBu) + count($countTechSavFaisExBu)) + (count($countTechSavFaisJuCa) + count($countTechSavFaisSeCa) + count($countTechSavFaisExCa)) + (count($countTechSavFaisJuRci) + count($countTechSavFaisSeRci) + count($countTechSavFaisExRci)) + (count($countTechSavFaisJuGa) + count($countTechSavFaisSeGa) + count($countTechSavFaisExGa)) + (count($countTechSavFaisJuMali) + count($countTechSavFaisSeMali) + count($countTechSavFaisExMali)) + (count($countTechSavFaisJuRca) + count($countTechSavFaisSeRca) + count($countTechSavFaisExRca)) + (count($countTechSavFaisJuRdc) + count($countTechSavFaisSeRdc) + count($countTechSavFaisExRdc)) + (count($countTechSavFaisJuSe) + count($countTechSavFaisSeSe) + count($countTechSavFaisExSe))) * 100 / $technicianCount);
                                            } else {
                                                $percentage = 0; // or any other appropriate value or message
                                            }
                                            echo $percentage . '%'; ?>
                                        </td>
                                        <td class="text-center fw-bolder bg-primary">
                                            <?php echo ((count($countMaSavFaisJuBu) + count($countMaSavFaisSeBu) + count($countMaSavFaisExBu)) + (count($countMaSavFaisJuCa) + count($countMaSavFaisSeCa) + count($countMaSavFaisExCa)) + (count($countMaSavFaisJuRci) + count($countMaSavFaisSeRci) + count($countMaSavFaisExRci)) + (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) + (count($countMaSavFaisJuMali) + count($countMaSavFaisSeMali) + count($countMaSavFaisExMali)) + (count($countMaSavFaisJuRca) + count($countMaSavFaisSeRca) + count($countMaSavFaisExRca)) + (count($countMaSavFaisJuRdc) + count($countMaSavFaisSeRdc) + count($countMaSavFaisExRdc)) + (count($countMaSavFaisJuSe) + count($countMaSavFaisSeSe) + count($countMaSavFaisExSe))) ?>
                                        </td>
                                        <td class="text-center fw-bolder bg-primary">
                                            <?php $technicianCount = ((count($techniciansBu) + count($techniciansSeBu) + (count($techniciansExBu) * 2)) + (count($techniciansCa) + count($techniciansSeCa) + (count($techniciansExCa) * 2)) + (count($techniciansRci) + count($techniciansSeRci) + (count($techniciansExRci) * 2)) + (count($techniciansGa) + count($techniciansSeGa) + (count($techniciansExGa) * 2)) + (count($techniciansMali) + count($techniciansSeMali) + (count($techniciansExMali) * 2)) + (count($techniciansRca) + count($techniciansSeRca) + (count($techniciansExRca) * 2)) + (count($techniciansRdc) + count($techniciansSeRdc) + (count($techniciansExRdc) * 2)) + (count($techniciansSe) + count($techniciansSeSe) + (count($techniciansExSe) * 2)));
                                            if ($technicianCount > 0) {
                                                $percentage = ceil(((count($countMaSavFaisJuBu) + count($countMaSavFaisSeBu) + count($countMaSavFaisExBu)) + (count($countMaSavFaisJuCa) + count($countMaSavFaisSeCa) + count($countMaSavFaisExCa)) + (count($countMaSavFaisJuRci) + count($countMaSavFaisSeRci) + count($countMaSavFaisExRci)) + (count($countMaSavFaisJuGa) + count($countMaSavFaisSeGa) + count($countMaSavFaisExGa)) + (count($countMaSavFaisJuMali) + count($countMaSavFaisSeMali) + count($countMaSavFaisExMali)) + (count($countMaSavFaisJuRca) + count($countMaSavFaisSeRca) + count($countMaSavFaisExRca)) + (count($countMaSavFaisJuRdc) + count($countMaSavFaisSeRdc) + count($countMaSavFaisExRdc)) + (count($countMaSavFaisJuSe) + count($countMaSavFaisSeSe) + count($countMaSavFaisExSe))) * 100 / $technicianCount);
                                            } else {
                                                $percentage = 0; // or any other appropriate value or message
                                            }
                                            echo $percentage. '%'; ?>
                                        </td>
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
            name: `StateCountry.xlsx`
        })
    });
});
</script>
<?php include_once "partials/footer.php"; ?>
<?php
} ?>


<?php
session_start();
include_once "language.php";

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
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;
    $allocations = $academy->allocations;

    if ($_SESSION['profile'] == 'Super Admin') {
        $i = 4;
    } else {
        $i = 3;
    }
        
    $techniciansBu = [];
    $techsBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuBu = [];
    $techsJuBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeBu = [];
    $techsSeBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExBu = [];
    $techsExBu = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS BURKINA",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExBu as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExBu, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExBu, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuBu = [];
    $countSavoirsJuBu = [];
    $countMaSavFaisJuBu = [];
    $countTechSavFaisJuBu = [];
    $testsSeBu = [];
    $countSavoirsSeBu = [];
    $countMaSavFaisSeBu = [];
    $countTechSavFaisSeBu = [];
    $testsExBu = [];
    $countSavoirsExBu = [];
    $countMaSavFaisExBu = [];
    $countTechSavFaisExBu = [];
    foreach ($techniciansBu as $technician) { 
        $allocateFacJuBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuBu) && $allocateFacJuBu['active'] == true) {
            $countSavoirsJuBu[] = $allocateFacJuBu;
        }
        if (isset($allocateDeclaJuBu) && $allocateDeclaJuBu['activeManager'] == true) {
            $countMaSavFaisJuBu[] = $allocateDeclaJuBu;
        }
        if (isset($allocateDeclaJuBu) && $allocateDeclaJuBu['active'] == true) {
            $countTechSavFaisJuBu[] = $allocateDeclaJuBu;
        }
        if (isset($allocateFacJuBu) && isset($allocateDeclaJuBu) && $allocateFacJuBu['active'] == true && $allocateDeclaJuBu['active'] == true && $allocateDeclaJuBu['activeManager'] == true) {
            $testsJuBu[] = $technician;
        }
        $allocateFacSeBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeBu) && $allocateFacSeBu['active'] == true) {
            $countSavoirsSeBu[] = $allocateFacSeBu;
        }
        if (isset($allocateDeclaSeBu) && $allocateDeclaSeBu['activeManager'] == true) {
            $countMaSavFaisSeBu[] = $allocateDeclaSeBu;
        }
        if (isset($allocateDeclaSeBu) && $allocateDeclaSeBu['active'] == true) {
            $countTechSavFaisSeBu[] = $allocateDeclaSeBu;
        }
        if (isset($allocateFacSeBu) && isset($allocateDeclaSeBu) && $allocateFacSeBu['active'] == true && $allocateDeclaSeBu['active'] == true && $allocateDeclaSeBu['activeManager'] == true) {
            $testsSeBu[] = $technician;
        }
        $allocateFacExBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExBu = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExBu) && $allocateFacExBu['active'] == true) {
            $countSavoirsExBu[] = $allocateFacExBu;
        }
        if (isset($allocateDeclaExBu) && $allocateDeclaExBu['activeManager'] == true) {
            $countMaSavFaisExBu[] = $allocateDeclaExBu;
        }
        if (isset($allocateDeclaExBu) && $allocateDeclaExBu['active'] == true) {
            $countTechSavFaisExBu[] = $allocateDeclaExBu;
        }
        if (isset($allocateFacExBu) && isset($allocateDeclaExBu) && $allocateFacExBu['active'] == true && $allocateDeclaExBu['active'] == true && $allocateDeclaExBu['activeManager'] == true) {
            $testsExBu[] = $technician;
        }
    }   
           
    $techniciansCa = [];
    $techsCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuCa = [];
    $techsJuCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeCa = [];
    $techsSeCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExCa = [];
    $techsExCa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CAMEROON MOTORS INDUSTRIES",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExCa as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExCa, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExCa, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuCa = [];
    $countSavoirsJuCa = [];
    $countMaSavFaisJuCa = [];
    $countTechSavFaisJuCa = [];
    $testsSeCa = [];
    $countSavoirsSeCa = [];
    $countMaSavFaisSeCa = [];
    $countTechSavFaisSeCa = [];
    $testsExCa = [];
    $countSavoirsExCa = [];
    $countMaSavFaisExCa = [];
    $countTechSavFaisExCa = [];
    foreach ($techniciansCa as $technician) { 
        $allocateFacJuCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuCa) && $allocateFacJuCa['active'] == true) {
            $countSavoirsJuCa[] = $allocateFacJuCa;
        }
        if (isset($allocateDeclaJuCa) && $allocateDeclaJuCa['activeManager'] == true) {
            $countMaSavFaisJuCa[] = $allocateDeclaJuCa;
        }
        if (isset($allocateDeclaJuCa) && $allocateDeclaJuCa['active'] == true) {
            $countTechSavFaisJuCa[] = $allocateDeclaJuCa;
        }
        if (isset($allocateFacJuCa) && isset($allocateDeclaJuCa) && $allocateFacJuCa['active'] == true && $allocateDeclaJuCa['active'] == true && $allocateDeclaJuCa['activeManager'] == true) {
            $testsJuCa[] = $technician;
        }
        $allocateFacSeCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeCa) && $allocateFacSeCa['active'] == true) {
            $countSavoirsSeCa[] = $allocateFacSeCa;
        }
        if (isset($allocateDeclaSeCa) && $allocateDeclaSeCa['activeManager'] == true) {
            $countMaSavFaisSeCa[] = $allocateDeclaSeCa;
        }
        if (isset($allocateDeclaSeCa) && $allocateDeclaSeCa['active'] == true) {
            $countTechSavFaisSeCa[] = $allocateDeclaSeCa;
        }
        if (isset($allocateFacSeCa) && isset($allocateDeclaSeCa) && $allocateFacSeCa['active'] == true && $allocateDeclaSeCa['active'] == true && $allocateDeclaSeCa['activeManager'] == true) {
            $testsSeCa[] = $technician;
        }
        $allocateFacExCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExCa = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExCa) && $allocateFacExCa['active'] == true) {
            $countSavoirsExCa[] = $allocateFacExCa;
        }
        if (isset($allocateDeclaExCa) && $allocateDeclaExCa['activeManager'] == true) {
            $countMaSavFaisExCa[] = $allocateDeclaExCa;
        }
        if (isset($allocateDeclaExCa) && $allocateDeclaExCa['active'] == true) {
            $countTechSavFaisExCa[] = $allocateDeclaExCa;
        }
        if (isset($allocateFacExCa) && isset($allocateDeclaExCa) && $allocateFacExCa['active'] == true && $allocateDeclaExCa['active'] == true && $allocateDeclaExCa['activeManager'] == true) {
            $testsExCa[] = $technician;
        }
    }
        
    $techniciansRci = [];
    $techsRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuRci = [];
    $techsJuRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeRci = [];
    $techsSeRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExRci = [];
    $techsExRci = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS COTE D'IVOIRE",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExRci as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExRci, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExRci, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuRci = [];
    $countSavoirsJuRci = [];
    $countMaSavFaisJuRci = [];
    $countTechSavFaisJuRci = [];
    $testsSeRci = [];
    $countSavoirsSeRci = [];
    $countMaSavFaisSeRci = [];
    $countTechSavFaisSeRci = [];
    $testsExRci = [];
    $countSavoirsExRci = [];
    $countMaSavFaisExRci = [];
    $countTechSavFaisExRci = [];
    foreach ($techniciansRci as $technician) { 
        $allocateFacJuRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuRci) && $allocateFacJuRci['active'] == true) {
            $countSavoirsJuRci[] = $allocateFacJuRci;
        }
        if (isset($allocateDeclaJuRci) && $allocateDeclaJuRci['activeManager'] == true) {
            $countMaSavFaisJuRci[] = $allocateDeclaJuRci;
        }
        if (isset($allocateDeclaJuRci) && $allocateDeclaJuRci['active'] == true) {
            $countTechSavFaisJuRci[] = $allocateDeclaJuRci;
        }
        if (isset($allocateFacJuRci) && isset($allocateDeclaJuRci) && $allocateFacJuRci['active'] == true && $allocateDeclaJuRci['active'] == true && $allocateDeclaJuRci['activeManager'] == true) {
            $testsJuRci[] = $technician;
        }
        $allocateFacSeRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeRci) && $allocateFacSeRci['active'] == true) {
            $countSavoirsSeRci[] = $allocateFacSeRci;
        }
        if (isset($allocateDeclaSeRci) && $allocateDeclaSeRci['activeManager'] == true) {
            $countMaSavFaisSeRci[] = $allocateDeclaSeRci;
        }
        if (isset($allocateDeclaSeRci) && $allocateDeclaSeRci['active'] == true) {
            $countTechSavFaisSeRci[] = $allocateDeclaSeRci;
        }
        if (isset($allocateFacSeRci) && isset($allocateDeclaSeRci) && $allocateFacSeRci['active'] == true && $allocateDeclaSeRci['active'] == true && $allocateDeclaSeRci['activeManager'] == true) {
            $testsSeRci[] = $technician;
        }
        $allocateFacExRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExRci = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExRci) && $allocateFacExRci['active'] == true) {
            $countSavoirsExRci[] = $allocateFacExRci;
        }
        if (isset($allocateDeclaExRci) && $allocateDeclaExRci['activeManager'] == true) {
            $countMaSavFaisExRci[] = $allocateDeclaExRci;
        }
        if (isset($allocateDeclaExRci) && $allocateDeclaExRci['active'] == true) {
            $countTechSavFaisExRci[] = $allocateDeclaExRci;
        }
        if (isset($allocateFacExRci) && isset($allocateDeclaExRci) && $allocateFacExRci['active'] == true && $allocateDeclaExRci['active'] == true && $allocateDeclaExRci['activeManager'] == true) {
            $testsExRci[] = $technician;
        }
    }
        
    $techniciansGa = [];
    $techsGa = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS GABON",
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
                "subsidiary" => "CFAO MOTORS GABON",
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
                "subsidiary" => "CFAO MOTORS GABON",
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
                "subsidiary" => "CFAO MOTORS GABON",
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
        
    $techniciansMali = [];
    $techsMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuMali = [];
    $techsJuMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeMali = [];
    $techsSeMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExMali = [];
    $techsExMali = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS MALI",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExMali as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExMali, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExMali, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuMali = [];
    $countSavoirsJuMali = [];
    $countMaSavFaisJuMali = [];
    $countTechSavFaisJuMali = [];
    $testsSeMali = [];
    $countSavoirsSeMali = [];
    $countMaSavFaisSeMali = [];
    $countTechSavFaisSeMali = [];
    $testsExMali = [];
    $countSavoirsExMali = [];
    $countMaSavFaisExMali = [];
    $countTechSavFaisExMali = [];
    foreach ($techniciansMali as $technician) { 
        $allocateFacJuMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuMali) && $allocateFacJuMali['active'] == true) {
            $countSavoirsJuMali[] = $allocateFacJuMali;
        }
        if (isset($allocateDeclaJuMali) && $allocateDeclaJuMali['activeManager'] == true) {
            $countMaSavFaisJuMali[] = $allocateDeclaJuMali;
        }
        if (isset($allocateDeclaJuMali) && $allocateDeclaJuMali['active'] == true) {
            $countTechSavFaisJuMali[] = $allocateDeclaJuMali;
        }
        if (isset($allocateFacJuMali) && isset($allocateDeclaJuMali) && $allocateFacJuMali['active'] == true && $allocateDeclaJuMali['active'] == true && $allocateDeclaJuMali['activeManager'] == true) {
            $testsJuMali[] = $technician;
        }
        $allocateFacSeMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeMali) && $allocateFacSeMali['active'] == true) {
            $countSavoirsSeMali[] = $allocateFacSeMali;
        }
        if (isset($allocateDeclaSeMali) && $allocateDeclaSeMali['activeManager'] == true) {
            $countMaSavFaisSeMali[] = $allocateDeclaSeMali;
        }
        if (isset($allocateDeclaSeMali) && $allocateDeclaSeMali['active'] == true) {
            $countTechSavFaisSeMali[] = $allocateDeclaSeMali;
        }
        if (isset($allocateFacSeMali) && isset($allocateDeclaSeMali) && $allocateFacSeMali['active'] == true && $allocateDeclaSeMali['active'] == true && $allocateDeclaSeMali['activeManager'] == true) {
            $testsSeMali[] = $technician;
        }
        $allocateFacExMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExMali = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExMali) && $allocateFacExMali['active'] == true) {
            $countSavoirsExMali[] = $allocateFacExMali;
        }
        if (isset($allocateDeclaExMali) && $allocateDeclaExMali['activeManager'] == true) {
            $countMaSavFaisExMali[] = $allocateDeclaExMali;
        }
        if (isset($allocateDeclaExMali) && $allocateDeclaExMali['active'] == true) {
            $countTechSavFaisExMali[] = $allocateDeclaExMali;
        }
        if (isset($allocateFacExMali) && isset($allocateDeclaExMali) && $allocateFacExMali['active'] == true && $allocateDeclaExMali['active'] == true && $allocateDeclaExMali['activeManager'] == true) {
            $testsExMali[] = $technician;
        }
    }
        
    $techniciansRca = [];
    $techsRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuRca = [];
    $techsJuRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeRca = [];
    $techsSeRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExRca = [];
    $techsExRca = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS CENTRAFRIQUE",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExRca as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExRca, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExRca, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuRca = [];
    $countSavoirsJuRca = [];
    $countMaSavFaisJuRca = [];
    $countTechSavFaisJuRca = [];
    $testsSeRca = [];
    $countSavoirsSeRca = [];
    $countMaSavFaisSeRca = [];
    $countTechSavFaisSeRca = [];
    $testsExRca = [];
    $countSavoirsExRca = [];
    $countMaSavFaisExRca = [];
    $countTechSavFaisExRca = [];
    foreach ($techniciansRca as $technician) { 
        $allocateFacJuRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuRca) && $allocateFacJuRca['active'] == true) {
            $countSavoirsJuRca[] = $allocateFacJuRca;
        }
        if (isset($allocateDeclaJuRca) && $allocateDeclaJuRca['activeManager'] == true) {
            $countMaSavFaisJuRca[] = $allocateDeclaJuRca;
        }
        if (isset($allocateDeclaJuRca) && $allocateDeclaJuRca['active'] == true) {
            $countTechSavFaisJuRca[] = $allocateDeclaJuRca;
        }
        if (isset($allocateFacJuRca) && isset($allocateDeclaJuRca) && $allocateFacJuRca['active'] == true && $allocateDeclaJuRca['active'] == true && $allocateDeclaJuRca['activeManager'] == true) {
            $testsJuRca[] = $technician;
        }
        $allocateFacSeRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeRca) && $allocateFacSeRca['active'] == true) {
            $countSavoirsSeRca[] = $allocateFacSeRca;
        }
        if (isset($allocateDeclaSeRca) && $allocateDeclaSeRca['activeManager'] == true) {
            $countMaSavFaisSeRca[] = $allocateDeclaSeRca;
        }
        if (isset($allocateDeclaSeRca) && $allocateDeclaSeRca['active'] == true) {
            $countTechSavFaisSeRca[] = $allocateDeclaSeRca;
        }
        if (isset($allocateFacSeRca) && isset($allocateDeclaSeRca) && $allocateFacSeRca['active'] == true && $allocateDeclaSeRca['active'] == true && $allocateDeclaSeRca['activeManager'] == true) {
            $testsSeRca[] = $technician;
        }
        $allocateFacExRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExRca = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExRca) && $allocateFacExRca['active'] == true) {
            $countSavoirsExRca[] = $allocateFacExRca;
        }
        if (isset($allocateDeclaExRca) && $allocateDeclaExRca['activeManager'] == true) {
            $countMaSavFaisExRca[] = $allocateDeclaExRca;
        }
        if (isset($allocateDeclaExRca) && $allocateDeclaExRca['active'] == true) {
            $countTechSavFaisExRca[] = $allocateDeclaExRca;
        }
        if (isset($allocateFacExRca) && isset($allocateDeclaExRca) && $allocateFacExRca['active'] == true && $allocateDeclaExRca['active'] == true && $allocateDeclaExRca['activeManager'] == true) {
            $testsExRca[] = $technician;
        }
    }
        
    $techniciansRdc = [];
    $techsRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansJuRdc = [];
    $techsJuRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeRdc = [];
    $techsSeRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExRdc = [];
    $techsExRdc = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS RDC",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExRdc as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExRdc, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuRdc = [];
    $countSavoirsJuRdc = [];
    $countMaSavFaisJuRdc = [];
    $countTechSavFaisJuRdc = [];
    $testsSeRdc = [];
    $countSavoirsSeRdc = [];
    $countMaSavFaisSeRdc = [];
    $countTechSavFaisSeRdc = [];
    $testsExRdc = [];
    $countSavoirsExRdc = [];
    $countMaSavFaisExRdc = [];
    $countTechSavFaisExRdc = [];
    foreach ($techniciansRdc as $technician) { 
        $allocateFacJuRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuRdc) && $allocateFacJuRdc['active'] == true) {
            $countSavoirsJuRdc[] = $allocateFacJuRdc;
        }
        if (isset($allocateDeclaJuRdc) && $allocateDeclaJuRdc['activeManager'] == true) {
            $countMaSavFaisJuRdc[] = $allocateDeclaJuRdc;
        }
        if (isset($allocateDeclaJuRdc) && $allocateDeclaJuRdc['active'] == true) {
            $countTechSavFaisJuRdc[] = $allocateDeclaJuRdc;
        }
        if (isset($allocateFacJuRdc) && isset($allocateDeclaJuRdc) && $allocateFacJuRdc['active'] == true && $allocateDeclaJuRdc['active'] == true && $allocateDeclaJuRdc['activeManager'] == true) {
            $testsJuRdc[] = $technician;
        }
        $allocateFacSeRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeRdc) && $allocateFacSeRdc['active'] == true) {
            $countSavoirsSeRdc[] = $allocateFacSeRdc;
        }
        if (isset($allocateDeclaSeRdc) && $allocateDeclaSeRdc['activeManager'] == true) {
            $countMaSavFaisSeRdc[] = $allocateDeclaSeRdc;
        }
        if (isset($allocateDeclaSeRdc) && $allocateDeclaSeRdc['active'] == true) {
            $countTechSavFaisSeRdc[] = $allocateDeclaSeRdc;
        }
        if (isset($allocateFacSeRdc) && isset($allocateDeclaSeRdc) && $allocateFacSeRdc['active'] == true && $allocateDeclaSeRdc['active'] == true && $allocateDeclaSeRdc['activeManager'] == true) {
            $testsSeRdc[] = $technician;
        }
        $allocateFacExRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExRdc = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExRdc) && $allocateFacExRdc['active'] == true) {
            $countSavoirsExRdc[] = $allocateFacExRdc;
        }
        if (isset($allocateDeclaExRdc) && $allocateDeclaExRdc['activeManager'] == true) {
            $countMaSavFaisExRdc[] = $allocateDeclaExRdc;
        }
        if (isset($allocateDeclaExRdc) && $allocateDeclaExRdc['active'] == true) {
            $countTechSavFaisExRdc[] = $allocateDeclaExRdc;
        }
        if (isset($allocateFacExRdc) && isset($allocateDeclaExRdc) && $allocateFacExRdc['active'] == true && $allocateDeclaExRdc['active'] == true && $allocateDeclaExRdc['activeManager'] == true) {
            $testsExRdc[] = $technician;
        }
    }
        
    $techniciansSe = [];
    $techsSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
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
    $techniciansJuSe = [];
    $techsJuSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
                "level" => "Junior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsJuSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansJuSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansJuSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansSeSe = [];
    $techsSeSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
                "level" => "Senior",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsSeSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansSeSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansSeSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }
    $techniciansExSe = [];
    $techsExSe = $users->find([
        '$and' => [
            [
                "subsidiary" => "CFAO MOTORS SENEGAL",
                "level" => "Expert",
                "active" => true,
            ],
        ],
    ])->toArray();
    foreach ($techsExSe as $techn) {
        if ($techn["profile"] == "Technicien") {
            array_push($techniciansExSe, new MongoDB\BSON\ObjectId($techn['_id']));
        } elseif ($techn["profile"] == "Manager" && $techn["test"] == true) {
            array_push($techniciansExSe, new MongoDB\BSON\ObjectId($techn['_id']));
        }
    }

    
    $testsJuSe = [];
    $countSavoirsJuSe = [];
    $countMaSavFaisJuSe = [];
    $countTechSavFaisJuSe = [];
    $testsSeSe = [];
    $countSavoirsSeSe = [];
    $countMaSavFaisSeSe = [];
    $countTechSavFaisSeSe = [];
    $testsExSe = [];
    $countSavoirsExSe = [];
    $countMaSavFaisExSe = [];
    $countTechSavFaisExSe = [];
    foreach ($techniciansSe as $technician) { 
        $allocateFacJuSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Junior",
                ],
            ],
        ]);
        $allocateDeclaJuSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Junior",
                ],
            ],
        ]);
        if (isset($allocateFacJuSe) && $allocateFacJuSe['active'] == true) {
            $countSavoirsJuSe[] = $allocateFacJuSe;
        }
        if (isset($allocateDeclaJuSe) && $allocateDeclaJuSe['activeManager'] == true) {
            $countMaSavFaisJuSe[] = $allocateDeclaJuSe;
        }
        if (isset($allocateDeclaJuSe) && $allocateDeclaJuSe['active'] == true) {
            $countTechSavFaisJuSe[] = $allocateDeclaJuSe;
        }
        if (isset($allocateFacJuSe) && isset($allocateDeclaJuSe) && $allocateFacJuSe['active'] == true && $allocateDeclaJuSe['active'] == true && $allocateDeclaJuSe['activeManager'] == true) {
            $testsJuSe[] = $technician;
        }
        $allocateFacSeSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Senior",
                ],
            ],
        ]);
        $allocateDeclaSeSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Senior",
                ],
            ],
        ]);
        if (isset($allocateFacSeSe) && $allocateFacSeSe['active'] == true) {
            $countSavoirsSeSe[] = $allocateFacSeSe;
        }
        if (isset($allocateDeclaSeSe) && $allocateDeclaSeSe['activeManager'] == true) {
            $countMaSavFaisSeSe[] = $allocateDeclaSeSe;
        }
        if (isset($allocateDeclaSeSe) && $allocateDeclaSeSe['active'] == true) {
            $countTechSavFaisSeSe[] = $allocateDeclaSeSe;
        }
        if (isset($allocateFacSeSe) && isset($allocateDeclaSeSe) && $allocateFacSeSe['active'] == true && $allocateDeclaSeSe['active'] == true && $allocateDeclaSeSe['activeManager'] == true) {
            $testsSeSe[] = $technician;
        }
        $allocateFacExSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Factuel",
                    "level" => "Expert",
                ],
            ],
        ]);
        $allocateDeclaExSe = $allocations->findOne([
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId($technician),
                    "type" => "Declaratif",
                    "level" => "Expert",
                ],
            ],
        ]);
        if (isset($allocateFacExSe) && $allocateFacExSe['active'] == true) {
            $countSavoirsExSe[] = $allocateFacExSe;
        }
        if (isset($allocateDeclaExSe) && $allocateDeclaExSe['activeManager'] == true) {
            $countMaSavFaisExSe[] = $allocateDeclaExSe;
        }
        if (isset($allocateDeclaExSe) && $allocateDeclaExSe['active'] == true) {
            $countTechSavFaisExSe[] = $allocateDeclaExSe;
        }
        if (isset($allocateFacExSe) && isset($allocateDeclaExSe) && $allocateFacExSe['active'] == true && $allocateDeclaExSe['active'] == true && $allocateDeclaExSe['activeManager'] == true) {
            $testsExSe[] = $technician;
        }
    }   
    ?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $etat_avanacement_country ?> | CFAO Mobility Academy</title>
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
                    <?php echo $etat_avanacement_country ?> </h1>
                <!--end::Title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <!-- <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="<?php echo $recherche?>">
                    </div> -->
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Info-->

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

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table aria-describedby=""
                                class="table align-middle table-bordered  table-row-dashed fs-6 gy-4 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-150px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px; text-align: center;"><?php echo $subsidiary ?> CFAO
                                            (<?php echo $pays ?>)
                                        </th>
                                        <th class="min-w-0px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px; text-align: center;"><?php echo $level ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin' || 'Directeur Groupe') { ?>
                                        <th class="min-w-0px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px; text-align: center;"><?php echo $nbre_test_effectue ?>
                                        </th>
                                        <?php } ?>
                                        <th class="min-w-200px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 150.516px; text-align: center;"><?php echo $qcm_realises ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin' || 'Directeur Groupe') { ?>
                                        <th class="min-w-0px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px; text-align: center;"><?php echo $taux_evolution_qcm ?>
                                        </th>
                                        <?php } ?>
                                        <th class="min-w-200px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px; text-align: center;">
                                            <?php echo $qcm_techs_realises ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin' || 'Directeur Groupe') { ?>
                                        <th class="min-w-0px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px; text-align: center;">
                                            <?php echo $taux_evolution_tache_tech ?>
                                        </th>
                                        <?php } ?>
                                        <th class="min-w-200px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px; text-align: center;">
                                            <?php echo $qcm_manager_realises ?>
                                        </th>
                                        <?php if ($_SESSION['profile'] == 'Super Admin' || 'Directeur Groupe') { ?>
                                        <th class="min-w-0px sorting class ='text-center'" tabindex="0"
                                            aria-controls="kt_customers_table" rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 05px; text-align: center;">
                                            <?php echo $taux_evolution_tache_manager ?>
                                        </th>
                                        <?php } ?>
                                    </tr>
                                </thead>

                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <?php
                                    // Function to calculate percentage
                                    function calculatePercentage($numerator, $denominator) {
                                        return ($denominator > 0) ? ceil(($numerator * 100) / $denominator) . '%' : '0%';
                                    }

                                    // Function to generate a table row
                                    function generateTableRow($locationLabel, $level, $totalTechnicians, $totalSavoirs, $totalTechSavFais, $totalMaSavFais, $isFirstOfLocation, $isGlobal = false) {
                                        $percentageSavoirs = calculatePercentage($totalSavoirs, $totalTechnicians);
                                        $percentageTechSavFais = calculatePercentage($totalTechSavFais, $totalTechnicians);
                                        $percentageMaSavFais = calculatePercentage($totalMaSavFais, $totalTechnicians);

                                        $locationCell = $isFirstOfLocation ? "<td rowspan='4' class='text-center' style='text-align: center; vertical-align: middle; height: 50px;'><strong>{$locationLabel}</strong></td>" : "";
                                        $globalClass = $isGlobal ? "class='global-row'" : "";

                                        return "
                                        <tr {$globalClass} class='odd' etat=''>
                                            {$locationCell}
                                            <td class='text-center'>{$level}</td>
                                            <td class='text-center'>{$totalTechnicians}</td>
                                            <td class='text-center'>{$totalSavoirs}</td>
                                            <td class='text-center'>{$percentageSavoirs}</td>
                                            <td class='text-center'>{$totalTechSavFais}</td>
                                            <td class='text-center'>{$percentageTechSavFais}</td>
                                            <td class='text-center'>{$totalMaSavFais}</td>
                                            <td class='text-center'>{$percentageMaSavFais}</td>
                                        </tr>
                                        ";
                                    }

                                    // Function to generate grand total row
                                    function generateGrandTotalRow($grandTotals) {
                                        return generateTableRow('Total Global', 'Global', $grandTotals['totalTechnicians'], $grandTotals['totalSavoirs'], $grandTotals['totalTechSavFais'], $grandTotals['totalMaSavFais'], false, true);
                                    }

                                    // Prepare data for each section
                                    function prepareData($technicians, $countSavoirs, $countTechSavFais, $countMaSavFais) {
                                        return [
                                            'totalTechnicians' => count($technicians),
                                            'totalSavoirs' => count($countSavoirs),
                                            'totalTechSavFais' => count($countTechSavFais),
                                            'totalMaSavFais' => count($countMaSavFais)
                                        ];
                                    }

                                    // Data for each location
                                    $locations = [
                                        'BURKINA' => [
                                            'Junior' => prepareData($techniciansBu, $countSavoirsJuBu, $countTechSavFaisJuBu, $countMaSavFaisJuBu),
                                            'Senior' => prepareData(array_merge($techniciansSeBu, $techniciansExBu), $countSavoirsSeBu, $countTechSavFaisSeBu, $countMaSavFaisSeBu),
                                            'Expert' => prepareData($techniciansExBu, $countSavoirsExBu, $countTechSavFaisExBu, $countMaSavFaisExBu)
                                        ],
                                        'CAMEROUN' => [
                                            'Junior' => prepareData($techniciansCa, $countSavoirsJuCa, $countTechSavFaisJuCa, $countMaSavFaisJuCa),
                                            'Senior' => prepareData(array_merge($techniciansSeCa, $techniciansExCa), $countSavoirsSeCa, $countTechSavFaisSeCa, $countMaSavFaisSeCa),
                                            'Expert' => prepareData($techniciansExCa, $countSavoirsExCa, $countTechSavFaisExCa, $countMaSavFaisExCa)
                                        ],
                                        "COTE D'IVOIRE" => [
                                            'Junior' => prepareData($techniciansRci, $countSavoirsJuRci, $countTechSavFaisJuRci, $countMaSavFaisJuRci),
                                            'Senior' => prepareData(array_merge($techniciansSeRci, $techniciansExRci), $countSavoirsSeRci, $countTechSavFaisSeRci, $countMaSavFaisSeRci),
                                            'Expert' => prepareData($techniciansExRci, $countSavoirsExRci, $countTechSavFaisExRci, $countMaSavFaisExRci)
                                        ],
                                        'GABON' => [
                                            'Junior' => prepareData($techniciansGa, $countSavoirsJuGa, $countTechSavFaisJuGa, $countMaSavFaisJuGa),
                                            'Senior' => prepareData(array_merge($techniciansSeGa, $techniciansExGa), $countSavoirsSeGa, $countTechSavFaisSeGa, $countMaSavFaisSeGa),
                                            'Expert' => prepareData($techniciansExGa, $countSavoirsExGa, $countTechSavFaisExGa, $countMaSavFaisExGa)
                                        ],
                                        'MALI' => [
                                            'Junior' => prepareData($techniciansMali, $countSavoirsJuMali, $countTechSavFaisJuMali, $countMaSavFaisJuMali),
                                            'Senior' => prepareData(array_merge($techniciansSeMali, $techniciansExMali), $countSavoirsSeMali, $countTechSavFaisSeMali, $countMaSavFaisSeMali),
                                            'Expert' => prepareData($techniciansExMali, $countSavoirsExMali, $countTechSavFaisExMali, $countMaSavFaisExMali)
                                        ],
                                        'RCA' => [
                                            'Junior' => prepareData($techniciansRca, $countSavoirsJuRca, $countTechSavFaisJuRca, $countMaSavFaisJuRca),
                                            'Senior' => prepareData(array_merge($techniciansSeRca, $techniciansExRca), $countSavoirsSeRca, $countTechSavFaisSeRca, $countMaSavFaisSeRca),
                                            'Expert' => prepareData($techniciansExRca, $countSavoirsExRca, $countTechSavFaisExRca, $countMaSavFaisExRca)
                                        ],
                                        'RDC' => [
                                            'Junior' => prepareData($techniciansRdc, $countSavoirsJuRdc, $countTechSavFaisJuRdc, $countMaSavFaisJuRdc),
                                            'Senior' => prepareData(array_merge($techniciansSeRdc, $techniciansExRdc), $countSavoirsSeRdc, $countTechSavFaisSeRdc, $countMaSavFaisSeRdc),
                                            'Expert' => prepareData($techniciansExRdc, $countSavoirsExRdc, $countTechSavFaisExRdc, $countMaSavFaisExRdc)
                                        ],
                                        'SENEGAL' => [
                                            'Junior' => prepareData($techniciansSe, $countSavoirsJuSe, $countTechSavFaisJuSe, $countMaSavFaisJuSe),
                                            'Senior' => prepareData(array_merge($techniciansSeSe, $techniciansExSe), $countSavoirsSeSe, $countTechSavFaisSeSe, $countMaSavFaisSeSe),
                                            'Expert' => prepareData($techniciansExSe, $countSavoirsExSe, $countTechSavFaisExSe, $countMaSavFaisExSe)
                                        ]
                                        // Add other locations similarly
                                    ];

                                    // Initialize grand totals
                                    $grandTotals = [
                                        'totalTechnicians' => 0,
                                        'totalSavoirs' => 0,
                                        'totalTechSavFais' => 0,
                                        'totalMaSavFais' => 0
                                    ];

                                    // Initialize aggregated totals for each level
                                    $aggregatedTotals = [
                                        'Junior' => [
                                            'totalTechnicians' => 0,
                                            'totalSavoirs' => 0,
                                            'totalTechSavFais' => 0,
                                            'totalMaSavFais' => 0
                                        ],
                                        'Senior' => [
                                            'totalTechnicians' => 0,
                                            'totalSavoirs' => 0,
                                            'totalTechSavFais' => 0,
                                            'totalMaSavFais' => 0
                                        ],
                                        'Expert' => [
                                            'totalTechnicians' => 0,
                                            'totalSavoirs' => 0,
                                            'totalTechSavFais' => 0,
                                            'totalMaSavFais' => 0
                                        ]
                                    ];

                                    // Print rows for each location and calculate totals
                                    foreach ($locations as $location => $levels) {
                                        // Initialize location totals
                                        $locationTotals = [
                                            'totalTechnicians' => 0,
                                            'totalSavoirs' => 0,
                                            'totalTechSavFais' => 0,
                                            'totalMaSavFais' => 0
                                        ];

                                        // Print details for Junior, Senior, and Expert levels
                                        $isFirstOfLocation = true;
                                        foreach ($levels as $level => $data) {
                                            echo generateTableRow($location, ucfirst($level), $data['totalTechnicians'], $data['totalSavoirs'], $data['totalTechSavFais'], $data['totalMaSavFais'], $isFirstOfLocation);

                                            // Update location totals
                                            $locationTotals['totalTechnicians'] += $data['totalTechnicians'];
                                            $locationTotals['totalSavoirs'] += $data['totalSavoirs'];
                                            $locationTotals['totalTechSavFais'] += $data['totalTechSavFais'];
                                            $locationTotals['totalMaSavFais'] += $data['totalMaSavFais'];

                                            // Update aggregated totals
                                            $aggregatedTotals[$level]['totalTechnicians'] += $data['totalTechnicians'];
                                            $aggregatedTotals[$level]['totalSavoirs'] += $data['totalSavoirs'];
                                            $aggregatedTotals[$level]['totalTechSavFais'] += $data['totalTechSavFais'];
                                            $aggregatedTotals[$level]['totalMaSavFais'] += $data['totalMaSavFais'];

                                            $isFirstOfLocation = false;
                                        }

                                        // Print location total
                                        echo generateTableRow($location . 'Global', $global, $locationTotals['totalTechnicians'], $locationTotals['totalSavoirs'], $locationTotals['totalTechSavFais'], $locationTotals['totalMaSavFais'], false, true);

                                        // Update grand totals
                                        $grandTotals['totalTechnicians'] += $locationTotals['totalTechnicians'];
                                        $grandTotals['totalSavoirs'] += $locationTotals['totalSavoirs'];
                                        $grandTotals['totalTechSavFais'] += $locationTotals['totalTechSavFais'];
                                        $grandTotals['totalMaSavFais'] += $locationTotals['totalMaSavFais'];
                                    }
                                    echo '<tr>
                                        <td colspan="9" ">&nbsp;</td>
                                    </tr>';
                                    echo '<td rowspan="5" style="text-align: center; font-weight: bold; vertical-align: middle; height: 50px;">GROUPE CFAO</td>';


                                    // Print aggregated totals for Junior, Senior, and Expert levels
                                    foreach ($aggregatedTotals as $level => $data) {
                                        echo generateTableRow('All Locations',ucfirst($level), $data['totalTechnicians'], $data['totalSavoirs'], $data['totalTechSavFais'], $data['totalMaSavFais'], false);
                                    }

                                    // Print grand total
                                    echo generateGrandTotalRow($grandTotals);
                                    ?>
                                </tbody>
                                <style>
                                /* Style for Global rows */
                                .global-row,
                                .table-row {
                                    font-weight: bolder;
                                    /* Make the text bold */
                                    background-color: #edf2f7 !important;
                                    /* Change the background color */
                                }
                                </style>

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
            name: `StateCountry.xlsx`
        })
    });
});
</script>
<?php include_once "partials/footer.php"; ?>
<?php
} ?>