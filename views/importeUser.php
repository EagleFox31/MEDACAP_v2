<?php
session_start();
include_once "language.php";

if (!isset($_SESSION["id"])) {
    header("Location: ../");
    exit();
} else {

    require_once "../vendor/autoload.php";

    if (isset($_POST["submit"])) {
        // Create connection
        $conn = new MongoDB\Client("mongodb://localhost:27017");

        // Connecting in database
        $academy = $conn->academy;

        // Connecting in collections
        $users = $academy->users;
        $vehicles = $academy->vehicles;
        $tests = $academy->tests;
        $allocations = $academy->allocations;

        $filePath = $_FILES["excel"]["tmp_name"];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        // remove header
        unset($data[0]);

        foreach ($data as $row) {
            $brandJunior = [];
            $brandSenior = [];
            $brandExpert = [];
            
            $specialitySenior = [];
            $specialityExpert = [];

            $username = $row["0"];
            $matricule = $row["1"];
            $firstName = $row["2"];
            $lastName = $row["3"];
            $email = $row["4"];
            $phone = $row["5"];
            $gender = $row["6"];
            $birthdate = date($row["7"]);
            $level = $row["8"];
            $country = $row["9"];
            $profile = $row["10"];
            $certificate = $row["11"];
            $subsidiary = $row["12"];
            $agency = $row["13"];
            $department = $row["14"];
            $role = $row["15"];
            $recrutmentDate = date($row["16"]);
            $usernameManager = $row["17"];
            $password = sha1($row["18"]);

            if (isset($row["19"]) != "") {
                $subBrand1 = strtoupper($row["19"]);
                array_push($brandJunior, $subBrand1);
            }
            if (isset($row["20"]) != "") {
                $subBrand2 = strtoupper($row["20"]);
                array_push($brandJunior, $subBrand2);
            }
            if (isset($row["21"]) != "") {
                $subBrand3 = strtoupper($row["21"]);
                array_push($brandJunior, $subBrand3);
            }
            if (isset($row["22"]) != "") {
                $subBrand4 = strtoupper($row["22"]);
                array_push($brandJunior, $subBrand4);
            }
            if (isset($row["23"]) != "") {
                $subBrand5 = strtoupper($row["23"]);
                array_push($brandJunior, $subBrand5);
            }
            if (isset($row["24"]) != "") {
                $subBrand6 = strtoupper($row["24"]);
                array_push($brandJunior, $subBrand6);
            }
            if (isset($row["25"]) != "") {
                $subBrand7 = strtoupper($row["25"]);
                array_push($brandJunior, $subBrand7);
            }
            if (isset($row["26"]) != "") {
                $subBrand8 = strtoupper($row["26"]);
                array_push($brandJunior, $subBrand8);
            }
            if (isset($row["27"]) != "") {
                $subBrand9 = strtoupper($row["27"]);
                array_push($brandJunior, $subBrand9);
            }
            if (isset($row["28"]) != "") {
                $subBrand10 = strtoupper($row["28"]);
                array_push($brandJunior, $subBrand10);
            }
            if (isset($row["29"]) != "") {
                $subBrand11 = strtoupper($row["29"]);
                array_push($brandJunior, $subBrand11);
            }
            if (isset($row["30"]) != "") {
                $subBrand12 = strtoupper($row["30"]);
                array_push($brandJunior, $subBrand12);
            }
            if (isset($row["31"]) != "") {
                $subBrand13 = strtoupper($row["31"]);
                array_push($brandJunior, $subBrand13);
            }
            if (isset($row["32"]) != "") {
                $subBrand14 = strtoupper($row["32"]);
                array_push($brandJunior, $subBrand14);
            }
            if (isset($row["33"]) != "") {
                $subBrand15 = strtoupper($row["33"]);
                array_push($brandJunior, $subBrand15);
            }
            if (isset($row["34"]) != "") {
                $subBrand16 = strtoupper($row["34"]);
                array_push($brandJunior, $subBrand16);
            }
            if (isset($row["35"]) != "") {
                $subBrand17 = strtoupper($row["35"]);
                array_push($brandJunior, $subBrand17);
            }

            if (isset($row["36"]) != "") {
                $subBrand1 = strtoupper($row["36"]);
                array_push($brandSenior, $subBrand1);
            }
            if (isset($row["37"]) != "") {
                $subBrand2 = strtoupper($row["37"]);
                array_push($brandSenior, $subBrand2);
            }
            if (isset($row["38"]) != "") {
                $subBrand3 = strtoupper($row["38"]);
                array_push($brandSenior, $subBrand3);
            }
            if (isset($row["39"]) != "") {
                $subBrand4 = strtoupper($row["39"]);
                array_push($brandSenior, $subBrand4);
            }
            if (isset($row["40"]) != "") {
                $subBrand5 = strtoupper($row["40"]);
                array_push($brandSenior, $subBrand5);
            }
            if (isset($row["41"]) != "") {
                $subBrand6 = strtoupper($row["41"]);
                array_push($brandSenior, $subBrand6);
            }
            if (isset($row["42"]) != "") {
                $subBrand7 = strtoupper($row["42"]);
                array_push($brandSenior, $subBrand7);
            }
            if (isset($row["43"]) != "") {
                $subBrand8 = strtoupper($row["43"]);
                array_push($brandSenior, $subBrand8);
            }
            if (isset($row["44"]) != "") {
                $subBrand9 = strtoupper($row["44"]);
                array_push($brandSenior, $subBrand9);
            }
            if (isset($row["45"]) != "") {
                $subBrand10 = strtoupper($row["45"]);
                array_push($brandSenior, $subBrand10);
            }
            if (isset($row["46"]) != "") {
                $subBrand11 = strtoupper($row["46"]);
                array_push($brandSenior, $subBrand11);
            }
            if (isset($row["47"]) != "") {
                $subBrand12 = strtoupper($row["47"]);
                array_push($brandSenior, $subBrand12);
            }
            if (isset($row["48"]) != "") {
                $subBrand13 = strtoupper($row["48"]);
                array_push($brandSenior, $subBrand13);
            }
            if (isset($row["49"]) != "") {
                $subBrand14 = strtoupper($row["49"]);
                array_push($brandSenior, $subBrand14);
            }
            if (isset($row["50"]) != "") {
                $subBrand15 = strtoupper($row["50"]);
                array_push($brandSenior, $subBrand15);
            }
            if (isset($row["51"]) != "") {
                $subBrand16 = strtoupper($row["51"]);
                array_push($brandSenior, $subBrand16);
            }
            if (isset($row["52"]) != "") {
                $subBrand17 = strtoupper($row["52"]);
                array_push($brandSenior, $subBrand17);
            }

            if (isset($row["53"]) != "") {
                $subBrand1 = strtoupper($row["53"]);
                array_push($brandExpert, $subBrand1);
            }
            if (isset($row["54"]) != "") {
                $subBrand2 = strtoupper($row["54"]);
                array_push($brandExpert, $subBrand2);
            }
            if (isset($row["55"]) != "") {
                $subBrand3 = strtoupper($row["55"]);
                array_push($brandExpert, $subBrand3);
            }
            if (isset($row["56"]) != "") {
                $subBrand4 = strtoupper($row["56"]);
                array_push($brandExpert, $subBrand4);
            }
            if (isset($row["57"]) != "") {
                $subBrand5 = strtoupper($row["57"]);
                array_push($brandExpert, $subBrand5);
            }
            if (isset($row["58"]) != "") {
                $subBrand6 = strtoupper($row["58"]);
                array_push($brandExpert, $subBrand6);
            }
            if (isset($row["59"]) != "") {
                $subBrand7 = strtoupper($row["59"]);
                array_push($brandExpert, $subBrand7);
            }
            if (isset($row["60"]) != "") {
                $subBrand8 = strtoupper($row["60"]);
                array_push($brandExpert, $subBrand8);
            }
            if (isset($row["61"]) != "") {
                $subBrand9 = ucfirst($row["61"]);
                array_push($brandExpert, $subBrand9);
            }
            if (isset($row["62"]) != "") {
                $subBrand10 = ucfirst($row["62"]);
                array_push($brandExpert, $subBrand10);
            }
            if (isset($row["63"]) != "") {
                $subBrand11 = ucfirst($row["63"]);
                array_push($brandExpert, $subBrand11);
            }
            if (isset($row["64"]) != "") {
                $subBrand12 = ucfirst($row["64"]);
                array_push($brandExpert, $subBrand12);
            }
            if (isset($row["65"]) != "") {
                $subBrand13 = ucfirst($row["65"]);
                array_push($brandExpert, $subBrand13);
            }
            if (isset($row["66"]) != "") {
                $subBrand14 = ucfirst($row["66"]);
                array_push($brandExpert, $subBrand14);
            }
            if (isset($row["67"]) != "") {
                $subBrand15 = ucfirst($row["67"]);
                array_push($brandExpert, $subBrand15);
            }
            if (isset($row["68"]) != "") {
                $subBrand16 = ucfirst($row["68"]);
                array_push($brandExpert, $subBrand16);
            }
            if (isset($row["69"]) != "") {
                $subBrand17 = ucfirst($row["69"]);
                array_push($brandExpert, $subBrand17);
            }
            
            if (isset($row["70"]) != "") {
                $specialitySenior1 = ucfirst($row["70"]);
                array_push($specialitySenior, $specialitySenior1);
            }
            if (isset($row["71"]) != "") {
                $specialitySenior2 = ucfirst($row["71"]);
                array_push($specialitySenior, $specialitySenior2);
            }
            if (isset($row["72"]) != "") {
                $specialitySenior3 = ucfirst($row["72"]);
                array_push($specialitySenior, $specialitySenior3);
            }
            if (isset($row["73"]) != "") {
                $specialitySenior4 = ucfirst($row["73"]);
                array_push($specialitySenior, $specialitySenior4);
            }
            
            if (isset($row["74"]) != "") {
                $specialityExpert1 = ucfirst($row["74"]);
                array_push($specialityExpert, $specialityExpert1);
            }
            if (isset($row["75"]) != "") {
                $specialityExpert2 = ucfirst($row["75"]);
                array_push($specialityExpert, $specialityExpert2);
            }
            if (isset($row["76"]) != "") {
                $specialityExpert3 = ucfirst($row["76"]);
                array_push($specialityExpert, $specialityExpert3);
            }
            if (isset($row["77"]) != "") {
                $specialityExpert4 = ucfirst($row["77"]);
                array_push($specialityExpert, $specialityExpert4);
            }

            $member = $users->findOne([
                '$and' => [["username" => $username], ["active" => true]],
            ]);
            if (isset($usernameManager)) {
                $manager = $users->findOne([
                    '$and' => [
                        [
                            "username" => $usernameManager,
                            "active" => true,
                        ],
                    ],
                ]);
            }
            if ($member) {
                $person = [
                    "matricule" => $matricule,
                    "firstName" => ucfirst($firstName),
                    "lastName" => strtoupper($lastName),
                    "email" => $email,
                    "phone" => +$phone,
                    "gender" => $gender,
                    "level" => $level,
                    "country" => $country,
                    "profile" => $profile,
                    "birthdate" => $birthdate,
                    "recrutmentDate" => $recrutmentDate,
                    "certificate" => ucfirst($certificate),
                    "subsidiary" => strtoupper($subsidiary),
                    "agency" => ucfirst($agency),
                    "department" => ucfirst($department),
                    "role" => ucfirst($role),
                    "password" => $password,
                    "visiblePassword" => $row["18"],
                    "updated" => date("d-m-Y H:I:S"),
                ];
                $users->updateOne(
                    ["_id" => new MongoDB\BSON\ObjectId($member->_id)],
                    [
                        '$set' => $person
                    ]
                );
                if ($profile == "Technicien") {
                    $success_msg = $success_tech;
                }
                if ($profile == "Manager") {
                    $success_msg = $success_manager;
                }
                if ($profile == "Admin") {
                    $success_msg = $success_admin;
                }
            } elseif ($profile == "Technicien") {
                if (isset($usernameManager)) {
                    $person = [
                        "users" => [],
                        "username" => $username,
                        "matricule" => $matricule,
                        "firstName" => ucfirst($firstName),
                        "lastName" => strtoupper($lastName),
                        "email" => $email,
                        "phone" => +$phone,
                        "gender" => $gender,
                        "level" => $level,
                        "country" => $country,
                        "profile" => $profile,
                        "birthdate" => $birthdate,
                        "recrutmentDate" => $recrutmentDate,
                        "certificate" => ucfirst($certificate),
                        "subsidiary" => ucfirst($subsidiary),
                        "agency" => ucfirst($agency),
                        "department" => ucfirst($department),
                        "brandJunior" => $brandJunior ?? [],
                        "brandSenior" => $brandSenior ?? [],
                        "brandExpert" => $brandExpert ?? [],
                        "specialitySenior" => $specialitySenior ?? [],
                        "specialityExpert" => $specialityExpert ?? [],
                        "role" => ucfirst($role),
                        "password" => $password,
                        "visiblePassword" => $row["18"],
                        "manager" => new MongoDB\BSON\ObjectId($manager->_id),
                        "test" => true,
                        "active" => true,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $user = $users->insertOne($person);

                    $users->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($manager->_id)],
                        [
                            '$push' => [
                                "users" => new MongoDB\BSON\ObjectId(
                                    $user->getInsertedId()
                                ),
                            ],
                        ]
                    );
                } else {
                    $person = [
                        "users" => [],
                        "username" => $username,
                        "matricule" => $matricule,
                        "firstName" => ucfirst($firstName),
                        "lastName" => strtoupper($lastName),
                        "email" => $email,
                        "phone" => +$phone,
                        "gender" => $gender,
                        "level" => $level,
                        "country" => $country,
                        "profile" => $profile,
                        "birthdate" => $birthdate,
                        "recrutmentDate" => $recrutmentDate,
                        "certificate" => ucfirst($certificate),
                        "subsidiary" => ucfirst($subsidiary),
                        "agency" => ucfirst($agency),
                        "department" => ucfirst($department),
                        "brandJunior" => $brandJunior ?? [],
                        "brandSenior" => $brandSenior ?? [],
                        "brandExpert" => $brandExpert ?? [],
                        "specialitySenior" => $specialitySenior ?? [],
                        "specialityExpert" => $specialityExpert ?? [],
                        "role" => ucfirst($role),
                        "password" => $password,
                        "visiblePassword" => $_POST["18"],
                        "manager" => "",
                        "test" => true,
                        "active" => true,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $user = $users->insertOne($person);
                }
                if (isset($brandExpert)) {
                    for ($i = 0; $i < count($brandExpert); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                            [
                                '$addToSet' => [
                                    "brandSenior" => $brandExpert[$i]
                                ],
                            ]
                        );
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                            [
                                '$addToSet' => [
                                    "brandJunior" => $brandExpert[$i]
                                ],
                            ]
                        );
                    }
                }
                if (isset($brandSenior)) {
                    for ($i = 0; $i < count($brandSenior); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                            [
                                '$addToSet' => [
                                    "brandJunior" => $brandSenior[$i]
                                ],
                            ]
                        );
                    }
                }
                if ($level == "Junior") {
                    $person = $users->findOne(
                        ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                    );
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandJunior'],
                        "type" => "Factuel",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertFac = $tests->insertOne($testFac);

                    $testDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandJunior'],
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertDecla = $tests->insertOne($testDecla);

                    for ($n = 0; $n < count($person['brandJunior']); ++$n) {
                        $vehicleFacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);

                        if ($vehicleDeclacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    $saveTestFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                    ]);
                    $saveTestFac["total"] = count($saveTestFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestFac]
                    );

                    $allocateFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Junior",
                        "activeTest" => true,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateFac);

                    $saveTestDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestDecla["total"] = count($saveTestDecla["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestDecla]
                    );

                    $allocateDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "activeTest" => true,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateDecla);

                    $success_msg = $success_tech;
                } elseif ($level == "Senior") {
                    $person = $users->findOne(
                        ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                    );
                    $testJuFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertFac = $tests->insertOne($testJuFac);

                    $testJuDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertDecla = $tests->insertOne($testJuDecla);

                    $testSeFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeFac = $tests->insertOne($testSeFac);

                    $testSeDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeDecla = $tests->insertOne($testSeDecla);

                    for ($n = 0; $n < count($person['brandJunior']); ++$n) {
                        $vehicleFacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['specialitySenior']); ++$n) {
                        $specialityFacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Factuelle"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($specialityFacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $specialityDeclacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Declarative"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);

                        if ($specialityDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['brandSenior']); ++$n) {
                        $vehicleFacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }

                    $saveTestFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                    ]);
                    $saveTestFac["total"] = count($saveTestFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestFac]
                    );

                    $allocateFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Junior",
                        "activeTest" => true,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateFac);

                    $saveTestDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestDecla["total"] = count($saveTestDecla["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestDecla]
                    );

                    $allocateDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "activeTest" => true,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateDecla);

                    $saveTestSeFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                    ]);
                    $saveTestSeFac["total"] = count($saveTestSeFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeFac]
                    );

                    $allocateSeFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Senior",
                        "activeTest" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeFac);

                    $saveTestSeDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestSeDecla["total"] = count(
                        $saveTestSeDecla["quizzes"]
                    );
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeDecla]
                    );

                    $allocateSeDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "activeTest" => false,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeDecla);

                    $success_msg = $success_tech;
                } elseif ($level == "Expert") {
                    $person = $users->findOne(
                        ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                    );
                    $testJuFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertFac = $tests->insertOne($testJuFac);

                    $testJuDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertDecla = $tests->insertOne($testJuDecla);

                    $testSeFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeFac = $tests->insertOne($testSeFac);

                    $testSeDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeDecla = $tests->insertOne($testSeDecla);

                    $testExFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $brandExpert,
                        "type" => "Factuel",
                        "level" => "Expert",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertExFac = $tests->insertOne($testExFac);

                    $testExDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $brandExpert,
                        "type" => "Declaratif",
                        "level" => "Expert",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertExDecla = $tests->insertOne($testExDecla);

                    for ($n = 0; $n < count($person['brandJunior']); ++$n) {
                        $vehicleFacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    for ($n = 0; $n < count($person['brandSenior']); ++$n) {
                        $vehicleFacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['specialitySenior']); ++$n) {
                        $specialityFacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Factuelle"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($specialityFacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $specialityDeclacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Declarative"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);

                        if ($specialityDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['specialityExpert']); ++$n) {
                        $specialityFacEx = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialityExpert'][$n]],
                                ["type" => "Factuelle"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);
                        if ($specialityFacEx) {
                            for (
                                $a = 0;
                                $a < count($specialityFacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityFacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $specialityDeclacEx = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialityExpert'][$n]],
                                ["type" => "Declarative"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);

                        if ($specialityDeclacEx) {
                            for (
                                $a = 0;
                                $a < count($specialityDeclacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityDeclacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    for ($n = 0; $n < count($brandExpert); ++$n) {
                        $vehicleFacEx = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brandExpert[$n]],
                                ["type" => "Factuel"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacEx) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacEx = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brandExpert[$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacEx) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }

                    $saveTestFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                    ]);
                    $saveTestFac["total"] = count($saveTestFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestFac]
                    );

                    $allocateFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Junior",
                        "activeTest" => true,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateFac);

                    $saveTestDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestDecla["total"] = count($saveTestDecla["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestDecla]
                    );

                    $allocateDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "activeTest" => true,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateDecla);

                    $saveTestSeFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                    ]);
                    $saveTestSeFac["total"] = count($saveTestSeFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeFac]
                    );

                    $allocateSeFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Senior",
                        "activeTest" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeFac);

                    $saveTestSeDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestSeDecla["total"] = count(
                        $saveTestSeDecla["quizzes"]
                    );
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeDecla]
                    );

                    $allocateSeDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "activeTest" => false,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeDecla);

                    $saveTestExFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertExFac->getInsertedId()
                        ),
                    ]);
                    $saveTestExFac["total"] = count($saveTestExFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertExFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestExFac]
                    );

                    $allocateExFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertExFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Expert",
                        "activeTest" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateExFac);

                    $saveTestExDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertExDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestExDecla["total"] = count(
                        $saveTestExDecla["quizzes"]
                    );
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertExDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestExDecla]
                    );

                    $allocateExDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertExDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Expert",
                        "activeTest" => false,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateExDecla);

                    $success_msg = $success_tech;
                }
            } elseif ($profile == "Manager & Technicien") {
                if ($usernameManager) {
                    $personM = [
                        "users" => [],
                        "username" => $username,
                        "matricule" => $matricule,
                        "firstName" => ucfirst($firstName),
                        "lastName" => strtoupper($lastName),
                        "email" => $email,
                        "phone" => +$phone,
                        "gender" => $gender,
                        "level" => $level,
                        "country" => $country,
                        "profile" => "Manager",
                        "birthdate" => $birthdate,
                        "recrutmentDate" => $recrutmentDate,
                        "certificate" => ucfirst($certificate),
                        "subsidiary" => ucfirst($subsidiary),
                        "agency" => ucfirst($agency),
                        "department" => ucfirst($department),
                        "brandJunior" => $brandJunior ?? [],
                        "brandSenior" => $brandSenior ?? [],
                        "brandExpert" => $brandExpert ?? [],
                        "specialitySenior" => $specialitySenior ?? [],
                        "specialityExpert" => $specialityExpert ?? [],
                        "role" => ucfirst($role),
                        "password" => $password,
                        "visiblePassword" => $row["18"],
                        "manager" => new MongoDB\BSON\ObjectId($manager->_id),
                        "test" => true,
                        "active" => true,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $user = $users->insertOne($personM);
                    $users->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($manager->_id)],
                        [
                            '$push' => [
                                "users" => new MongoDB\BSON\ObjectId(
                                    $user->getInsertedId()
                                ),
                            ],
                        ]
                    );
                } else {
                    $personM = [
                        "users" => [],
                        "username" => $username,
                        "matricule" => $matricule,
                        "firstName" => ucfirst($firstName),
                        "lastName" => strtoupper($lastName),
                        "email" => $email,
                        "phone" => +$phone,
                        "gender" => $gender,
                        "level" => $level,
                        "country" => $country,
                        "profile" => "Manager",
                        "birthdate" => $birthdate,
                        "recrutmentDate" => $recrutmentDate,
                        "certificate" => ucfirst($certificate),
                        "subsidiary" => ucfirst($subsidiary),
                        "agency" => ucfirst($agency),
                        "department" => ucfirst($department),
                        "brandJunior" => $brandJunior ?? [],
                        "brandSenior" => $brandSenior ?? [],
                        "brandExpert" => $brandExpert ?? [],
                        "specialitySenior" => $specialitySenior ?? [],
                        "specialityExpert" => $specialityExpert ?? [],
                        "role" => ucfirst($role),
                        "password" => $password,
                        "visiblePassword" => $row["18"],
                        "manager" => "",
                        "test" => true,
                        "active" => true,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $user = $users->insertOne($personM);
                }
                if (isset($brandExpert)) {
                    for ($i = 0; $i < count($brandExpert); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                            [
                                '$addToSet' => [
                                    "brandSenior" => $brandExpert[$i]
                                ],
                            ]
                        );
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                            [
                                '$addToSet' => [
                                    "brandJunior" => $brandExpert[$i]
                                ],
                            ]
                        );
                    }
                }
                if (isset($brandSenior)) {
                    for ($i = 0; $i < count($brandSenior); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                            [
                                '$addToSet' => [
                                    "brandJunior" => $brandSenior[$i]
                                ],
                            ]
                        );
                    }
                }
                if ($level == "Junior") {
                    $person = $users->findOne(
                        ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                    );
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandJunior'],
                        "type" => "Factuel",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertFac = $tests->insertOne($testFac);

                    $testDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandJunior'],
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertDecla = $tests->insertOne($testDecla);

                    for ($n = 0; $n < count($person['brandJunior']); ++$n) {
                        $vehicleFacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);

                        if ($vehicleDeclacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    $saveTestFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                    ]);
                    $saveTestFac["total"] = count($saveTestFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestFac]
                    );

                    $allocateFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Junior",
                        "activeTest" => true,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateFac);

                    $saveTestDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestDecla["total"] = count($saveTestDecla["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestDecla]
                    );

                    $allocateDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "activeTest" => true,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateDecla);

                    $success_msg = $success_tech;
                } elseif ($level == "Senior") {
                    $person = $users->findOne(
                        ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                    );
                    $testJuFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertFac = $tests->insertOne($testJuFac);

                    $testJuDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertDecla = $tests->insertOne($testJuDecla);

                    $testSeFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeFac = $tests->insertOne($testSeFac);

                    $testSeDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeDecla = $tests->insertOne($testSeDecla);

                    for ($n = 0; $n < count($person['brandJunior']); ++$n) {
                        $vehicleFacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['specialitySenior']); ++$n) {
                        $specialityFacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Factuelle"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($specialityFacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $specialityDeclacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Declarative"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);

                        if ($specialityDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['brandSenior']); ++$n) {
                        $vehicleFacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }

                    $saveTestFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                    ]);
                    $saveTestFac["total"] = count($saveTestFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestFac]
                    );

                    $allocateFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Junior",
                        "activeTest" => true,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateFac);

                    $saveTestDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestDecla["total"] = count($saveTestDecla["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestDecla]
                    );

                    $allocateDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "activeTest" => true,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateDecla);

                    $saveTestSeFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                    ]);
                    $saveTestSeFac["total"] = count($saveTestSeFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeFac]
                    );

                    $allocateSeFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Senior",
                        "activeTest" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeFac);

                    $saveTestSeDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestSeDecla["total"] = count(
                        $saveTestSeDecla["quizzes"]
                    );
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeDecla]
                    );

                    $allocateSeDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "activeTest" => false,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeDecla);

                    $success_msg = $success_tech;
                } elseif ($level == "Expert") {
                    $person = $users->findOne(
                        ["_id" => new MongoDB\BSON\ObjectId($user->getInsertedId())],
                    );
                    $testJuFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertFac = $tests->insertOne($testJuFac);

                    $testJuDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertDecla = $tests->insertOne($testJuDecla);

                    $testSeFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Factuel",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeFac = $tests->insertOne($testSeFac);

                    $testSeDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $person['brandSenior'],
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertSeDecla = $tests->insertOne($testSeDecla);

                    $testExFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $brandExpert,
                        "type" => "Factuel",
                        "level" => "Expert",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertExFac = $tests->insertOne($testExFac);

                    $testExDecla = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "brand" => $brandExpert,
                        "type" => "Declaratif",
                        "level" => "Expert",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y h:i:s"),
                    ];
                    $insertExDecla = $tests->insertOne($testExDecla);

                    for ($n = 0; $n < count($person['brandJunior']); ++$n) {
                        $vehicleFacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacJu = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandJunior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Junior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacJu) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacJu->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacJu->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    for ($n = 0; $n < count($person['brandSenior']); ++$n) {
                        $vehicleFacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Factuel"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $person['brandSenior'][$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['specialitySenior']); ++$n) {
                        $specialityFacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Factuelle"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);
                        if ($specialityFacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityFacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityFacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $specialityDeclacSe = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialitySenior'][$n]],
                                ["type" => "Declarative"],
                                ["level" => "Senior"],
                                ["active" => true],
                            ],
                        ]);

                        if ($specialityDeclacSe) {
                            for (
                                $a = 0;
                                $a < count($specialityDeclacSe->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertSeDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityDeclacSe->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    
                    for ($n = 0; $n < count($person['specialityExpert']); ++$n) {
                        $specialityFacEx = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialityExpert'][$n]],
                                ["type" => "Factuelle"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);
                        if ($specialityFacEx) {
                            for (
                                $a = 0;
                                $a < count($specialityFacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityFacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $specialityDeclacEx = $academy->specialities->findOne([
                            '$and' => [
                                ["label" => $person['specialityExpert'][$n]],
                                ["type" => "Declarative"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);

                        if ($specialityDeclacEx) {
                            for (
                                $a = 0;
                                $a < count($specialityDeclacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $specialityDeclacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }
                    for ($n = 0; $n < count($brandExpert); ++$n) {
                        $vehicleFacEx = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brandExpert[$n]],
                                ["type" => "Factuel"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleFacEx) {
                            for (
                                $a = 0;
                                $a < count($vehicleFacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExFac->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleFacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }

                        $vehicleDeclacEx = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brandExpert[$n]],
                                ["type" => "Declaratif"],
                                ["level" => "Expert"],
                                ["active" => true],
                            ],
                        ]);
                        if ($vehicleDeclacEx) {
                            for (
                                $a = 0;
                                $a < count($vehicleDeclacEx->quizzes);
                                ++$a
                            ) {
                                $tests->updateOne(
                                    [
                                        "_id" => new MongoDB\BSON\ObjectId(
                                            $insertExDecla->getInsertedId()
                                        ),
                                    ],
                                    [
                                        '$addToSet' => [
                                            "quizzes" =>
                                                $vehicleDeclacEx->quizzes[$a],
                                        ],
                                    ]
                                );
                            }
                        }
                    }

                    $saveTestFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                    ]);
                    $saveTestFac["total"] = count($saveTestFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestFac]
                    );

                    $allocateFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Junior",
                        "activeTest" => true,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateFac);

                    $saveTestDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestDecla["total"] = count($saveTestDecla["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestDecla]
                    );

                    $allocateDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Junior",
                        "activeTest" => true,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateDecla);

                    $saveTestSeFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                    ]);
                    $saveTestSeFac["total"] = count($saveTestSeFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeFac]
                    );

                    $allocateSeFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Senior",
                        "activeTest" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeFac);

                    $saveTestSeDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestSeDecla["total"] = count(
                        $saveTestSeDecla["quizzes"]
                    );
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertSeDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestSeDecla]
                    );

                    $allocateSeDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertSeDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Senior",
                        "activeTest" => false,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateSeDecla);

                    $saveTestExFac = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertExFac->getInsertedId()
                        ),
                    ]);
                    $saveTestExFac["total"] = count($saveTestExFac["quizzes"]);
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertExFac->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestExFac]
                    );

                    $allocateExFac = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertExFac->getInsertedId()
                        ),
                        "type" => "Factuel",
                        "level" => "Expert",
                        "activeTest" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateExFac);

                    $saveTestExDecla = $tests->findOne([
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insertExDecla->getInsertedId()
                        ),
                    ]);
                    $saveTestExDecla["total"] = count(
                        $saveTestExDecla["quizzes"]
                    );
                    $tests->updateOne(
                        [
                            "_id" => new MongoDB\BSON\ObjectId(
                                $insertExDecla->getInsertedId()
                            ),
                        ],
                        ['$set' => $saveTestExDecla]
                    );

                    $allocateExDecla = [
                        "user" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                        "test" => new MongoDB\BSON\ObjectId(
                            $insertExDecla->getInsertedId()
                        ),
                        "type" => "Declaratif",
                        "level" => "Expert",
                        "activeTest" => false,
                        "activeManager" => false,
                        "active" => false,
                        "created" => date("d-m-Y H:I:S"),
                    ];
                    $allocations->insertOne($allocateExDecla);

                    $success_msg = $success_manager;
                }
            } elseif ($profile == "Manager") {
                $personM = [
                    "users" => [],
                    "username" => $username,
                    "matricule" => $matricule,
                    "firstName" => ucfirst($firstName),
                    "lastName" => strtoupper($lastName),
                    "email" => $email,
                    "phone" => +$phone,
                    "gender" => $gender,
                    "level" => $level,
                    "country" => $country,
                    "profile" => "Manager",
                    "birthdate" => $birthdate,
                    "recrutmentDate" => $recrutmentDate,
                    "certificate" => ucfirst($certificate),
                    "subsidiary" => ucfirst($subsidiary),
                    "agency" => ucfirst($agency),
                    "department" => ucfirst($department),
                    "role" => ucfirst($role),
                    "password" => $password,
                    "visiblePassword" => $row["18"],
                    "test" => false,
                    "active" => true,
                    "created" => date("d-m-Y H:I:S"),
                ];
                $user = $users->insertOne($personM);
                $success_msg = $success_manager;
            } elseif ($profile == "Admin") {
                $personA = [
                    "users" => [],
                    "username" => $username,
                    "matricule" => $matricule,
                    "firstName" => ucfirst($firstName),
                    "lastName" => strtoupper($lastName),
                    "email" => $email,
                    "phone" => +$phone,
                    "gender" => $gender,
                    "level" => $level,
                    "country" => $country,
                    "profile" => $profile,
                    "birthdate" => $birthdate,
                    "recrutmentDate" => $recrutmentDate,
                    "certificate" => ucfirst($certificate),
                    "subsidiary" => ucfirst($subsidiary),
                    "agency" => ucfirst($agency),
                    "department" => ucfirst($department),
                    "role" => ucfirst($role),
                    "password" => $password,
                    "visiblePassword" => $row["18"],
                    "active" => true,
                    "created" => date("d-m-Y H:I:S"),
                ];
                $users->insertOne($personA);
                $success_msg = $success_admin;
            } elseif ($profile == "Directeur Filiale" || $profile == "Directeur Groupe") {
                $personD = [
                    "users" => [],
                    "username" => $username,
                    "matricule" => $matricule,
                    "firstName" => ucfirst($firstName),
                    "lastName" => strtoupper($lastName),
                    "email" => $email,
                    "phone" => +$phone,
                    "gender" => $gender,
                    "level" => $level,
                    "country" => $country,
                    "profile" => $profile,
                    "birthdate" => $birthdate,
                    "recrutmentDate" => $recrutmentDate,
                    "certificate" => ucfirst($certificate),
                    "subsidiary" => ucfirst($subsidiary),
                    "agency" => ucfirst($agency),
                    "department" => ucfirst($department),
                    "role" => ucfirst($role),
                    "password" => $password,
                    "visiblePassword" => $row["18"],
                    "active" => true,
                    "created" => date("d-m-Y H:I:S"),
                ];
                $users->insertOne($personD);
                $success_msg = $success_directeur;
            }
        }
    }
    ?>
<?php include_once "partials/header.php"; ?>
<style>
input {
    background-color: #fff ! important;
    border-style: solid;
}
</style>
<!--begin::Title-->
<title><?php echo $import_user ?> | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class="container-xxl">
            <!--begin::Modal body-->
            <div class="container mt-5 w-50 text-center">
                <img src="../public/images/logo.png" alt="10" height="170" style="display: block; max-width: 75%; height: auto; margin-left: 25px;">
                <h1 class="my-3 text-center"><?php echo $import_user ?></h1>

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

                <form enctype='multipart/form-data' method='POST'><br>
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required form-label fw-bolder text-dark fs-6">Importer des utilisateurs via <?php echo $excel ?></label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="input-group">
                            <input type="file" class="form-control form-control-solid" placeholder="" name="excel" style="text-align: center;" />
                            <div class="input-group-append">
                                <span class="input-group-text" style = "height: 10px !important; padding: 15px;">.xlsx</span>
                            </div>
                        </div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="submit" name="submit" class="btn btn-primary">
                            <span class="indicator-label">
                                <?php echo $valider ?>
                            </span>
                            <span class="indicator-progress">
                                Patientez... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->

<script>
    // Function to handle closing of the alert message
    document.addEventListener('DOMContentLoaded', function() {
        const closeButtons = document.querySelectorAll('.alert .close');
        closeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert');
                alert.remove();
            });
        });
    });
</script>
<?php include_once "partials/footer.php"; ?>

<?php
} ?>
