<?php
session_start();
include_once "language.php";

if (!isset($_SESSION["id"])) {
    header("Location: ./index.php");
    exit();
} else {

    require_once "../vendor/autoload.php";

    // Create connection
    $conn = new MongoDB\Client("mongodb://localhost:27017");

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $vehicles = $academy->vehicles;
    $tests = $academy->tests;
    $quizzes = $academy->quizzes;
    $allocations = $academy->allocations;

    if (isset($_POST["submit"])) {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $emailAddress = $_POST["email"];
        $phone = $_POST["phone"];
        $matriculation = $_POST["matricule"];
        $userName = $_POST["username"];
        $departement = $_POST["department"];
        $fonction = $_POST["role"];
        $profile = $_POST["profile"];
        $sex = $_POST["gender"];
        $agency = $_POST["agency"];
        $passWord = $_POST["password"];
        $certificate = $_POST["certificate"];
        $specialitySenior = $_POST["specialitySenior"];
        $specialityExpert = $_POST["specialityExpert"];
        $birthDate = date("d-m-Y", strtotime($_POST["birthdate"]));
        $recrutementDate = date("d-m-Y", strtotime($_POST["recrutmentDate"]));
        $level = $_POST["level"];
        if (isset($_POST["brandJu"])) {
          $brandJunior = $_POST["brandJu"];
        }
        if (isset($_POST["brandSe"])) {
          $brandSenior = $_POST["brandSe"];
        }
        if (isset($_POST["brandEx"])) {
          $brandExpert = $_POST["brandEx"];
        }

        $techs = [];

        $password_hash = sha1($passWord);
        $member = $users->findOne([
            '$and' => [["username" => $userName], ["active" => true]],
        ]);
        $manager = $users->findOne([
            '$and' => [["_id" => new MongoDB\BSON\ObjectId($_SESSION["id"])], ["active" => true]],
        ]);
        if (
            empty($firstName) ||
            empty($lastName) ||
            empty($fonction) ||
            empty($userName) ||
            empty($matriculation) ||
            empty($birthDate) ||
            empty($certificate) ||
            empty($departement) ||
            empty($recrutementDate) ||
            empty($sex) ||
            empty($agency) ||
            empty($level) ||
            empty($brandJunior) ||
            !filter_var($emailAddress, FILTER_VALIDATE_EMAIL) ||
            preg_match('/^[\D]{15}$/', $phone) ||
            preg_match(
                '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{6,}$/',
                $passWord
            )
        ) {
            $error = $champ_obligatoire;
            $email_error = $email_invalid;
            $phone_error = $phone_invalid;
            $password_error = $password_invalid;
        } elseif ($member) {
            $error_msg = $exist_user;
        } else {
            $person = [
                "users" => [],
                "username" => $userName,
                "matricule" => $matriculation,
                "firstName" => ucfirst($firstName),
                "lastName" => ucfirst($lastName),
                "email" => $emailAddress,
                "phone" => +$phone,
                "gender" => $sex,
                "level" => $level,
                "country" => $manager["country"],
                "profile" => $profile,
                "birthdate" => $birthDate,
                "recrutmentDate" => $recrutementDate,
                "certificate" => ucfirst($certificate), 
                "subsidiary" => ucfirst($manager["subsidiary"]),
                "agency" => ucfirst($agency),
                "department" => ucfirst($departement),
                "brandJunior" => $brandJunior ?? [],
                "brandSenior" => $brandSenior ?? [],
                "brandExpert" => $brandExpert ?? [],
                "specialitySenior" => $specialitySenior,
                "specialityExpert" => $specialityExpert,
                "role" => ucfirst($fonction),
                "password" => $password_hash,
                "manager" => new MongoDB\BSON\ObjectId($_SESSION["id"]),
                "active" => true,
                "created" => date("d-m-Y"),
            ];
            $user = $users->insertOne($person);

            $users->updateOne(
                ["_id" => new MongoDB\BSON\ObjectId($_SESSION["id"])],
                [
                    '$push' => [
                        "users" => new MongoDB\BSON\ObjectId(
                            $user->getInsertedId()
                        ),
                    ],
                ]
            );
            if (isset($_POST["brandEx"])) {
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
            if (isset($_POST["brandSe"])) {
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "activeTest" => false,
                    "active" => false,
                    "created" => date("d-m-Y"),
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
                    "activeTest" => false,
                    "activeManager" => false,
                    "active" => false,
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "activeTest" => false,
                    "active" => false,
                    "created" => date("d-m-Y"),
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
                    "activeTest" => false,
                    "activeManager" => false,
                    "active" => false,
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "created" => date("d-m-y"),
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
                    "activeTest" => false,
                    "active" => false,
                    "created" => date("d-m-Y"),
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
                    "activeTest" => false,
                    "activeManager" => false,
                    "active" => false,
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-Y"),
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
                    "created" => date("d-m-Y"),
                ];
                $allocations->insertOne($allocateExDecla);
  
                $success_msg = $success_tech;
            }
          }
    }
    ?>

<?php include_once "partials/header.php"; ?>

<!--begin::Title-->
<title><?php echo $title_addCollab ?> | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content" data-select2-id="select2-data-kt_content">
  <!--begin::Post-->
  <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
    <!--begin::Container-->
    <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
      <!--begin::Modal body-->
      <div class="container mt-5 w-50">
        <img src="../public/images/logo.png" alt="10" height="170" style="display: block; max-width: 75%; height: auto; margin-left: 25px;">
        <h1 class='my-3 text-center'><?php echo $title_addCollab ?></h1>

        <?php if (isset($success_msg)) { ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
          <center><strong><?php echo $success_msg; ?></strong></center>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;
            </span>
          </button>
        </div>
        <?php } ?>
        <?php if (isset($error_msg)) { ?>
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
          <center><strong><?php echo $error_msg; ?></strong></center>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;
            </span>
          </button>
        </div>
        <?php } ?>

        <form method='POST'><br>
          <!--begin::Input group-->
          <div class='row fv-row mb-7'>
            <!--begin::Input group-->
            <div class='row g-9 mb-7'>
              <!--begin::Col-->
              <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='required form-label fw-bolder text-dark fs-6'><?php echo $prenoms ?></label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='firstName' />
                <?php if (isset($error)) { ?>
                <span class='text-danger'>
                  <?php echo $error; ?>
                </span>
                <?php } ?>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class='col-md-6 fv-row'>
                <!--begin::Label-->
                <label class='required form-label fw-bolder text-dark fs-6'><?php echo $noms ?></label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class='form-control form-control-solid' placeholder='' name='lastName' />
                <!--end::Input-->
                <?php if (isset($error)) { ?>
                <span class='text-danger'>
                  <?php echo $error; ?>
                </span>
                <?php } ?>
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'><?php echo $username ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='text' class='form-control form-control-solid' placeholder='' name='username' />
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'><?php echo $matricule ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='text' class='form-control form-control-solid' placeholder='' name='matricule' />
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='d-flex flex-column mb-7 fv-row'>
              <!--begin::Label-->
              <label class='form-label fw-bolder text-dark fs-6'>
                <span class='required'><?php echo $gender ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name='gender' aria-label='Select a Country' data-control='select2' data-placeholder='<?php echo $select_gender ?>' class='form-select form-select-solid fw-bold'>
                <option><?php echo $select_gender ?></option>
                <option value='Feminin'>
                  <?php echo $female ?>
                </option>
                <option value='Masculin'>
                  <?php echo $male ?>
                </option>
              </select>
              <!--end::Input-->
            </div>
            <!--end::Input group-->
            <?php if (isset($error)) { ?>
            <span class='text-danger'>
              <?php echo $error; ?>
            </span>
            <?php } ?>
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='form-label fw-bolder text-dark fs-6'>
                <span><?php echo $email ?></span>
                <span class='ms-1' data-bs-toggle='tooltip' title='Votre adresse email doit être active'>
                  <i class='ki-duotone ki-information fs-7'><span class='path1'></span><span class='path2'></span><span class='path3'></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='email' class='form-control form-control-solid' placeholder='' name='email'/>
              <!--end::Input-->
              <?php if (isset($email_error)) { ?>
              <span class='text-danger'>
                <?php echo $email_error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-7'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'><?php echo $phoneNumber ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='text' class='form-control form-control-solid' placeholder='' name='phone' />
              <!--end::Input-->
              <?php if (isset($phone_error)) { ?>
              <span class='text-danger'>
                <?php echo $phone_error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='fv-row mb-15'>
              <!--begin::Label-->
              <label class='required form-label fw-bolder text-dark fs-6'><?php echo $birthdate ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type='date' class='form-control form-control-solid' placeholder='' name='birthdate' />
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class='d-flex flex-column mb-7 fv-row'>
              <!--begin::Label-->
              <label class='form-label fw-bolder text-dark fs-6'>
                <span class='required'><?php echo $country ?></span> <span class='ms-1' data-bs-toggle='tooltip' title="Votre pays d'origine">
                  <i class='ki-duotone ki-information fs-7'><span class='path1'></span><span class='path2'></span><span class='path3'></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name='country' aria-label='Select a Country' data-control='select2' data-placeholder='<?php echo $select_country ?>' class='form-select form-select-solid fw-bold'>
                <option><?php echo $select_country ?></option>
                <option value='Afghanistan'>Afghanistan</option>
                <option value='Albania'>Albania</option>
                <option value='Algeria'>Algeria</option>
                <option value='American Samoa'>American Samoa</option>
                <option value='Andorra'>Andorra</option>
                <option value='Angola'>Angola</option>
                <option value='Anguilla'>Anguilla</option>
                <option value='Antartica'>Antarctica</option>
                <option value='Antigua and Barbuda'>Antigua and Barbuda</option>
                <option value='Argentina'>Argentina</option>
                <option value='Armenia'>Armenia</option>
                <option value='Aruba'>Aruba</option>
                <option value='Australia'>Australia</option>
                <option value='Austria'>Austria</option>
                <option value='Azerbaijan'>Azerbaijan</option>
                <option value='Bahamas'>Bahamas</option>
                <option value='Bahrain'>Bahrain</option>
                <option value='Bangladesh'>Bangladesh</option>
                <option value='Barbados'>Barbados</option>
                <option value='Belarus'>Belarus</option>
                <option value='Belgium'>Belgium</option>
                <option value='Belize'>Belize</option>
                <option value='Benin'>Benin</option>
                <option value='Bermuda'>Bermuda</option>
                <option value='Bhutan'>Bhutan</option>
                <option value='Bolivia'>Bolivia</option>
                <option value='Bosnia and Herzegowina'>Bosnia and Herzegowina</option>
                <option value='Botswana'>Botswana</option>
                <option value='Bouvet Island'>Bouvet Island</option>
                <option value='Brazil'>Brazil</option>
                <option value='British Indian Ocean Territory'>British Indian Ocean Territory</option>
                <option value='Brunei Darussalam'>Brunei Darussalam</option>
                <option value='Bulgaria'>Bulgaria</option>
                <option value='Burkina Faso'>Burkina Faso</option>
                <option value='Burundi'>Burundi</option>
                <option value='Cambodia'>Cambodia</option>
                <option value='Cameroon'>Cameroon</option>
                <option value='Canada'>Canada</option>
                <option value='Cape Verde'>Cape Verde</option>
                <option value='Cayman Islands'>Cayman Islands</option>
                <option value='Central African Republic'>Central African Republic</option>
                <option value='Chad'>Chad</option>
                <option value='Chile'>Chile</option>
                <option value='China'>China</option>
                <option value='Christmas Island'>Christmas Island</option>
                <option value='Cocos Islands'>Cocos ( Keeling ) Islands</option>
                <option value='Colombia'>Colombia</option>
                <option value='Comoros'>Comoros</option>
                <option value='Congo'>Congo</option>
                <option value='Congo'>Congo, the Democratic Republic of the</option>
                <option value='Cook Islands'>Cook Islands</option>
                <option value='Costa Rica'>Costa Rica</option>
                <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                <option value="Croatia">Croatia (Hrvatska)</option>
                <option value="Cuba">Cuba</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="France Metropolitan">France, Metropolitan</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Territories">French Southern Territories</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guinea-Bissau">Guinea-Bissau</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
                <option value="Holy See">Holy See (Vatican City State)</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Iran">Iran (Islamic Republic of)</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Democratic People's Republic of Korea">Korea, Democratic People's
                  Republic of
                </option>
                <option value="Korea">Korea, Republic of</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Lao">Lao People's Democratic Republic</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malawi">Malawi</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Micronesia">Micronesia, Federated States of</option>
                <option value="Moldova">Moldova, Republic of</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Namibia">Namibia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherlands">Netherlands</option>
                <option value="Netherlands Antilles">Netherlands Antilles</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau">Palau</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Philippines">Philippines</option>
                <option value="Pitcairn">Pitcairn</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russian Federation</option>
                <option value="Rwanda">Rwanda</option>
                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                <option value="Saint LUCIA">Saint LUCIA</option>
                <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                <option value="Samoa">Samoa</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia (Slovak Republic)</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
                <option value="Span">Spain</option>
                <option value="SriLanka">Sri Lanka</option>
                <option value="St. Helena">St. Helena</option>
                <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syrian Arab Republic</option>
                <option value="Taiwan">Taiwan, Province of China</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania, United Republic of</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks and Caicos">Turks and Caicos Islands</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
                <option value="United States Minor Outlying Islands">United States Minor Outlying
                  Islands</option>
                <option value="Uruguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Viet Nam</option>
                <option value="Virgin Islands ( British )">Virgin Islands (British)</option>
                <option value="Virgin Islands ( U.S )">Virgin Islands (U.S.)</option>
                <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                <option value="Western Sahara">Western Sahara</option>
                <option value="Yemen">Yemen</option>
                <option value="Serbia">Serbia</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $department ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <!-- <div class="form-check" style="margin-top: 10px">
                <input class="form-check-input" type="radio" value="Equipment" id="equip">
                <label class="form-check-label text-black">
                  Equipment
                </label>
              </div> <br>
              <div class="form-check">
                <input class="form-check-input" type="radio" value="Motors" id="motors">
                <label class="form-check-label text-black">
                  Motors
                </label>
              </div> -->
              <select onchange="enableBrand(this)" name="department" aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_department ?>" class="form-select form-select-solid fw-bold">
                <option><?php echo $select_department ?></option>
                <option value="Equipment">
                  Equipment
                </option>
                <option value="Motors">
                  Motors
                </option>
                <option value="Equipment, Motors">
                  Equipment & Motors
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <!-- <div class="d-flex flex-column mb-7 fv-row"> -->
              <!--begin::Label-->
              <!-- <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $level ?></span> <span class="ms-1" data-bs-toggle="tooltip" title="Choississez le niveau du technicien ou du manager">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
              </label> -->
              <!--end::Label-->
              <!--begin::Input-->
              <!-- <select name="level" onchange="enableSpeciality(this)" aria-label="<?php echo $select_level ?>" class="form-select form-select-solid fw-bold">
                <option><?php echo $select_level ?></option>
                <option value="Junior">
                  <?php echo $junior ?>
                </option>
                <option value="Senior">
                  <?php echo $senior ?>
                </option>
                <option value="Expert">
                  <?php echo $expert ?>
                </option>
              </select> -->
              <!--end::Input-->
              <!-- <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div> -->
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $level ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <div class="form-check" style="margin-top: 10px">
                <input class="form-check-input" onclick="checkedRa()" type="radio" name="level" value="Junior" id="junior">
                <label class="form-check-label text-black">
                  <?php echo $junior ?> (<?php echo $maintenance ?>)
                </label>
              </div>
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandEquipmentJu">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $junior ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandJu[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="FUSO">
                  <?php echo $fuso ?>
                </option>
                <option value="HINO">
                  <?php echo $hino ?>
                </option>
                <option value="JCB">
                  <?php echo $jcb ?>
                </option>
                <option value="KING LONG">
                  <?php echo $kingLong ?>
                </option>
                <option value="LOVOL">
                  <?php echo $lovol ?>
                </option>
                <option value="MERCEDES TRUCK">
                  <?php echo $mercedesTruck ?>
                </option>
                <option value="RENAULT TRUCK">
                  <?php echo $renaultTruck ?>
                </option>
                <option value="SINOTRUCK">
                  <?php echo $sinotruk ?>
                </option>
                <option value="TOYOTA BT">
                  <?php echo $toyotaBt ?>
                </option>
                <option value="TOYOTA FORKLIFT">
                  <?php echo $toyotaForklift ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandMotorsJu">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $junior ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandJu[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="BYD">
                  <?php echo $byd ?>
                </option>
                <option value="CITROEN">
                  <?php echo $citroen ?>
                </option>
                <option value="MERCEDES">
                  <?php echo $mercedes ?>
                </option>
                <option value="MUTSUBISHI">
                  <?php echo $mutsubishi ?>
                </option>
                <option value="PEUGEOT">
                  <?php echo $peugeot ?>
                </option>
                <option value="SUZUKI">
                  <?php echo $suzuki ?>
                </option>
                <option value="TOYOTA">
                  <?php echo $toyota ?>
                </option>
                <option value="YAMAHA BATEAU">
                  <?php echo $yamahaBateau ?>
                </option>
                <option value="YAMAHA MOTO">
                  <?php echo $yamahaMoto ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandEqMoJu">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $junior ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandJu[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="FUSO">
                  <?php echo $fuso ?>
                </option>
                <option value="HINO">
                  <?php echo $hino ?>
                </option>
                <option value="JCB">
                  <?php echo $jcb ?>
                </option>
                <option value="KING LONG">
                  <?php echo $kingLong ?>
                </option>
                <option value="LOVOL">
                  <?php echo $lovol ?>
                </option>
                <option value="MERCEDES TRUCK">
                  <?php echo $mercedesTruck ?>
                </option>
                <option value="RENAULT TRUCK">
                  <?php echo $renaultTruck ?>
                </option>
                <option value="SINOTRUCK">
                  <?php echo $sinotruk ?>
                </option>
                <option value="TOYOTA BT">
                  <?php echo $toyotaBt ?>
                </option>
                <option value="TOYOTA FORKLIFT">
                  <?php echo $toyotaForklift ?>
                </option>
                <option value="BYD">
                  <?php echo $byd ?>
                </option>
                <option value="CITROEN">
                  <?php echo $citroen ?>
                </option>
                <option value="MERCEDES">
                  <?php echo $mercedes ?>
                </option>
                <option value="MUTSUBISHI">
                  <?php echo $mutsubishi ?>
                </option>
                <option value="PEUGEOT">
                  <?php echo $peugeot ?>
                </option>
                <option value="SUZUKI">
                  <?php echo $suzuki ?>
                </option>
                <option value="TOYOTA">
                  <?php echo $toyota ?>
                </option>
                <option value="YAMAHA BATEAU">
                  <?php echo $yamahaBateau ?>
                </option>
                <option value="YAMAHA MOTO">
                  <?php echo $yamahaMoto ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
              <div class="form-check" style="margin-top: 10px">
                <input class="form-check-input" onclick="checkedRa()" type="radio" name="level" value="Senior" id="senior">
                <label class="form-check-label text-black">
                  <?php echo $senior ?> (<?php echo $reparation ?>)
                </label>
              </div>
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandEquipmentSe">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandSe[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?> <?php echo $senior ?></option>
                <option value="FUSO">
                  <?php echo $fuso ?>
                </option>
                <option value="HINO">
                  <?php echo $hino ?>
                </option>
                <option value="JCB">
                  <?php echo $jcb ?>
                </option>
                <option value="KING LONG">
                  <?php echo $kingLong ?>
                </option>
                <option value="LOVOL">
                  <?php echo $lovol ?>
                </option>
                <option value="MERCEDES TRUCK">
                  <?php echo $mercedesTruck ?>
                </option>
                <option value="RENAULT TRUCK">
                  <?php echo $renaultTruck ?>
                </option>
                <option value="SINOTRUCK">
                  <?php echo $sinotruk ?>
                </option>
                <option value="TOYOTA BT">
                  <?php echo $toyotaBt ?>
                </option>
                <option value="TOYOTA FORKLIFT">
                  <?php echo $toyotaForklift ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandMotorsSe">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $senior ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandSe[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="BYD">
                  <?php echo $byd ?>
                </option>
                <option value="CITROEN">
                  <?php echo $citroen ?>
                </option>
                <option value="MERCEDES">
                  <?php echo $mercedes ?>
                </option>
                <option value="MUTSUBISHI">
                  <?php echo $mutsubishi ?>
                </option>
                <option value="PEUGEOT">
                  <?php echo $peugeot ?>
                </option>
                <option value="SUZUKI">
                  <?php echo $suzuki ?>
                </option>
                <option value="TOYOTA">
                  <?php echo $toyota ?>
                </option>
                <option value="YAMAHA BATEAU">
                  <?php echo $yamahaBateau ?>
                </option>
                <option value="YAMAHA MOTO">
                  <?php echo $yamahaMoto ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandEqMoSe">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $senior ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandSe[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="FUSO">
                  <?php echo $fuso ?>
                </option>
                <option value="HINO">
                  <?php echo $hino ?>
                </option>
                <option value="JCB">
                  <?php echo $jcb ?>
                </option>
                <option value="KING LONG">
                  <?php echo $kingLong ?>
                </option>
                <option value="LOVOL">
                  <?php echo $lovol ?>
                </option>
                <option value="MERCEDES TRUCK">
                  <?php echo $mercedesTruck ?>
                </option>
                <option value="RENAULT TRUCK">
                  <?php echo $renaultTruck ?>
                </option>
                <option value="SINOTRUCK">
                  <?php echo $sinotruk ?>
                </option>
                <option value="TOYOTA BT">
                  <?php echo $toyotaBt ?>
                </option>
                <option value="TOYOTA FORKLIFT">
                  <?php echo $toyotaForklift ?>
                </option>
                <option value="BYD">
                  <?php echo $byd ?>
                </option>
                <option value="CITROEN">
                  <?php echo $citroen ?>
                </option>
                <option value="MERCEDES">
                  <?php echo $mercedes ?>
                </option>
                <option value="MUTSUBISHI">
                  <?php echo $mutsubishi ?>
                </option>
                <option value="PEUGEOT">
                  <?php echo $peugeot ?>
                </option>
                <option value="SUZUKI">
                  <?php echo $suzuki ?>
                </option>
                <option value="TOYOTA">
                  <?php echo $toyota ?>
                </option>
                <option value="YAMAHA BATEAU">
                  <?php echo $yamahaBateau ?>
                </option>
                <option value="YAMAHA MOTO">
                  <?php echo $yamahaMoto ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" id="metierSe">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $speciality ?> <?php echo $senior ?></span> <span class="ms-1" data-bs-toggle="tooltip" title="Choississez la spécialité du collaborateur">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="specialitySenior" aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_speciality ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_speciality ?></option>
                <option value="Boite de Vitesse">
                  <?php echo $boite_vitesse ?>
                </option>
                <option value="Electricté et Electronique">
                  <?php echo $elec ?>
                </option>
                <option value="Hydraulique">
                  <?php echo $hydraulique ?>
                </option>
                <option value="Moteur">
                  <?php echo $moteur ?>
                </option>
                <option value="Transmission">
                  <?php echo $transmission ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
              <div class="form-check" style="margin-top: 10px">
                <input class="form-check-input" onclick="checkedRa()" type="radio" name="level" value="Expert" id="expert">
                <label class="form-check-label text-black">
                  <?php echo $expert ?> (<?php echo $diagnostic ?>)
                </label>
              </div>
            </div> 
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandEquipmentEx">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $expert ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandEx[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="FUSO">
                  <?php echo $fuso ?>
                </option>
                <option value="HINO">
                  <?php echo $hino ?>
                </option>
                <option value="JCB">
                  <?php echo $jcb ?>
                </option>
                <option value="KING LONG">
                  <?php echo $kingLong ?>
                </option>
                <option value="LOVOL">
                  <?php echo $lovol ?>
                </option>
                <option value="MERCEDES TRUCK">
                  <?php echo $mercedesTruck ?>
                </option>
                <option value="RENAULT TRUCK">
                  <?php echo $renaultTruck ?>
                </option>
                <option value="SINOTRUCK">
                  <?php echo $sinotruk ?>
                </option>
                <option value="TOYOTA BT">
                  <?php echo $toyotaBt ?>
                </option>
                <option value="TOYOTA FORKLIFT">
                  <?php echo $toyotaForklift ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandMotorsEx">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $expert ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandEx[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="BYD">
                  <?php echo $byd ?>
                </option>
                <option value="CITROEN">
                  <?php echo $citroen ?>
                </option>
                <option value="MERCEDES">
                  <?php echo $mercedes ?>
                </option>
                <option value="MUTSUBISHI">
                  <?php echo $mutsubishi ?>
                </option>
                <option value="PEUGEOT">
                  <?php echo $peugeot ?>
                </option>
                <option value="SUZUKI">
                  <?php echo $suzuki ?>
                </option>
                <option value="TOYOTA">
                  <?php echo $toyota ?>
                </option>
                <option value="YAMAHA BATEAU">
                  <?php echo $yamahaBateau ?>
                </option>
                <option value="YAMAHA MOTO">
                  <?php echo $yamahaMoto ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" style="margin-top: 10px" id="brandEqMoEx">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $brand ?> <?php echo $expert ?></span>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="brandEx[]" multiple aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_brand ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_brand ?></option>
                <option value="FUSO">
                  <?php echo $fuso ?>
                </option>
                <option value="HINO">
                  <?php echo $hino ?>
                </option>
                <option value="JCB">
                  <?php echo $jcb ?>
                </option>
                <option value="KING LONG">
                  <?php echo $kingLong ?>
                </option>
                <option value="LOVOL">
                  <?php echo $lovol ?>
                </option>
                <option value="MERCEDES TRUCK">
                  <?php echo $mercedesTruck ?>
                </option>
                <option value="RENAULT TRUCK">
                  <?php echo $renaultTruck ?>
                </option>
                <option value="SINOTRUCK">
                  <?php echo $sinotruk ?>
                </option>
                <option value="TOYOTA BT">
                  <?php echo $toyotaBt ?>
                </option>
                <option value="TOYOTA FORKLIFT">
                  <?php echo $toyotaForklift ?>
                </option>
                <option value="BYD">
                  <?php echo $byd ?>
                </option>
                <option value="CITROEN">
                  <?php echo $citroen ?>
                </option>
                <option value="MERCEDES">
                  <?php echo $mercedes ?>
                </option>
                <option value="MUTSUBISHI">
                  <?php echo $mutsubishi ?>
                </option>
                <option value="PEUGEOT">
                  <?php echo $peugeot ?>
                </option>
                <option value="SUZUKI">
                  <?php echo $suzuki ?>
                </option>
                <option value="TOYOTA">
                  <?php echo $toyota ?>
                </option>
                <option value="YAMAHA BATEAU">
                  <?php echo $yamahaBateau ?>
                </option>
                <option value="YAMAHA MOTO">
                  <?php echo $yamahaMoto ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row d-none" id="metierEx">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6">
                <span class="required"><?php echo $speciality ?> <?php echo $expert ?></span> <span class="ms-1" data-bs-toggle="tooltip" title="Choississez la spécialité du collaborateur">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <select name="specialityExpert" aria-label="Select a Country" data-control="select2" data-placeholder="<?php echo $select_speciality ?>" class="form-select form-select-solid fw-bold">
                <option value=""><?php echo $select_speciality ?></option>
                <option value="Boite de Vitesse">
                  <?php echo $boite_vitesse ?>
                </option>
                <option value="Electricté et Electronique">
                  <?php echo $elec ?>
                </option>
                <option value="Hydraulique">
                  <?php echo $hydraulique ?>
                </option>
                <option value="Moteur">
                  <?php echo $moteur ?>
                </option>
                <option value="Transmission">
                  <?php echo $transmission ?>
                </option>
              </select>
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6"><?php echo $certificat ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid" placeholder="" name="certificate" />
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6"><?php echo $agence ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid" name="agency" />
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6"><?php echo $role ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid fw-bold" placeholder="" name="role" />
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required form-label fw-bolder text-dark fs-6"><?php echo $recrutmentDate ?></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="date" class="form-control form-control-solid" placeholder="" name="recrutmentDate" />
              <!--end::Input-->
              <?php if (isset($error)) { ?>
              <span class='text-danger'>
                <?php echo $error; ?>
              </span>
              <?php } ?>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-10 fv-row" data-kt-password-meter="true">
              <!--begin::Wrapper-->
              <div class="mb-1">
                <!--begin::Label-->
                <label class="required form-label fw-bolder text-dark fs-6"><?php echo $password ?></label>
                <!--end::Label-->
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                  <input class="form-control form-control-solid" type="password" placeholder="" name="password" autocomplete="off" />
                </div>
                <!--end::Input wrapper-->
                <?php if (isset($password_error)) { ?>
                <span class="text-danger">
                  <?php echo $password_error; ?>
                </span>
                <?php } ?>
                <?php if (isset($error)) { ?>
                <span class='text-danger'>
                  <?php echo $error; ?>
                </span>
                <?php } ?>
                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                  </div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                  </div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                  </div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px">
                  </div>
                </div>
                <!--end::Meter-->
              </div>
              <!--end::Wrapper-->
              <!--begin::Hint-->
              <div class="text-muted"><?php echo $password_text ?>.</div>
              <!--end::Input group-->
            </div>
            <!--end::Input group-->
            <!--end::Scroll-->
            <!--end::Modal body-->
            <!--begin::Modal footer-->
            <div class=" modal-footer flex-center">
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
          </div>
        </form>
      </div>
      <!--end::Modal body-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Post-->
</div>
<!--end::Body-->
<?php include "./partials/footer.php"; ?>
<?php
}
?>

<script>
  var metierSe = document.querySelector('#metierSe');
  var metierEx = document.querySelector('#metierEx');
  var brandMotorsJu = document.querySelector('#brandMotorsJu');
  var brandEquipmentJu = document.querySelector('#brandEquipmentJu');
  var brandEqMoJu = document.querySelector('#brandEqMoJu');
  var brandMotorsSe = document.querySelector('#brandMotorsSe');
  var brandEquipmentSe = document.querySelector('#brandEquipmentSe');
  var brandEqMoSe = document.querySelector('#brandEqMoSe');
  var brandMotorsEx = document.querySelector('#brandMotorsEx');
  var brandEquipmentEx = document.querySelector('#brandEquipmentEx');
  var brandEqMoEx = document.querySelector('#brandEqMoEx');

  function enableBrand(answer) {
    checkedRa(answer.value);
  }

  function checkedRa(departValue) {
    var junior = document.querySelector('#junior');
    var senior = document.querySelector('#senior');
    var expert = document.querySelector('#expert');
    if(departValue == 'Motors') {
      if(junior.checked) {
        brandMotorsJu.classList.remove('d-none');
        brandEquipmentJu.classList.add('d-none');
        brandEqMoJu.classList.add('d-none');
        brandMotorsSe.classList.add('d-none');
        brandEquipmentSe.classList.add('d-none');
        brandEqMoSe.classList.add('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.add('d-none');
      } else if(senior.checked) {
        brandMotorsJu.classList.remove('d-none');
        brandEquipmentJu.classList.add('d-none');
        brandEqMoJu.classList.add('d-none');
        brandMotorsSe.classList.remove('d-none');
        brandEquipmentSe.classList.add('d-none');
        brandEqMoSe.classList.add('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.add('d-none');
      } else if(expert.checked) {
        brandMotorsJu.classList.remove('d-none');
        brandEquipmentJu.classList.add('d-none');
        brandEqMoJu.classList.add('d-none');
        brandMotorsSe.classList.remove('d-none');
        brandEquipmentSe.classList.add('d-none');
        brandEqMoSe.classList.add('d-none');
        brandMotorsEx.classList.remove('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.add('d-none');
      }
    } else if(departValue == 'Equipment') {
      if(junior.checked) {
        brandMotorsJu.classList.add('d-none');
        brandEquipmentJu.classList.remove('d-none');
        brandEqMoJu.classList.add('d-none');
        brandMotorsSe.classList.add('d-none');
        brandEquipmentSe.classList.add('d-none');
        brandEqMoSe.classList.add('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.add('d-none');
      } else if(senior.checked) {
        brandMotorsJu.classList.add('d-none');
        brandEquipmentJu.classList.remove('d-none');
        brandEqMoJu.classList.add('d-none');
        brandMotorsSe.classList.add('d-none');
        brandEquipmentSe.classList.remove('d-none');
        brandEqMoSe.classList.add('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.add('d-none');
      } else if(expert.checked) {
        brandMotorsJu.classList.add('d-none');
        brandEquipmentJu.classList.remove('d-none');
        brandEqMoJu.classList.add('d-none');
        brandMotorsSe.classList.add('d-none');
        brandEquipmentSe.classList.remove('d-none');
        brandEqMoSe.classList.add('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.remove('d-none');
        brandEqMoEx.classList.add('d-none');
      }
    } else if(departValue == 'Equipment, Motors') {
      if(junior.checked) {
        brandMotorsJu.classList.add('d-none');
        brandEquipmentJu.classList.add('d-none');
        brandEqMoJu.classList.remove('d-none');
        brandMotorsSe.classList.add('d-none');
        brandEquipmentSe.classList.add('d-none');
        brandEqMoSe.classList.add('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.add('d-none');
      } else if(senior.checked) {
        brandMotorsJu.classList.add('d-none');
        brandEquipmentJu.classList.add('d-none');
        brandEqMoJu.classList.remove('d-none');
        brandMotorsSe.classList.add('d-none');
        brandEquipmentSe.classList.add('d-none');
        brandEqMoSe.classList.remove('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.add('d-none');
      } else if(expert.checked) {
        brandMotorsJu.classList.add('d-none');
        brandEquipmentJu.classList.add('d-none');
        brandEqMoJu.classList.remove('d-none');
        brandMotorsSe.classList.add('d-none');
        brandEquipmentSe.classList.add('d-none');
        brandEqMoSe.classList.remove('d-none');
        brandMotorsEx.classList.add('d-none');
        brandEquipmentEx.classList.add('d-none');
        brandEqMoEx.classList.remove('d-none');
      }
    }
    if(senior.checked) {
      metierSe.classList.remove('d-none');
      metierEx.classList.add('d-none');
    } else if(expert.checked){
      metierSe.classList.add('d-none');
      metierEx.classList.remove('d-none');
    }
</script>