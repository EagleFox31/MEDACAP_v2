<?php
session_start();

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
    $allocations = $academy->allocations;
    $tests = $academy->tests;

    if (isset($_POST["submit"])) {
        $brand = $_POST["brand"];
        $technician = $_POST["technician"];

        if (!$brand || !$technician) {
            $error_msg = "Veuillez remplir tous les champs.";
        } else {
            $user = $users->findOne([
                '$and' => [
                    ["_id" => new MongoDB\BSON\ObjectId($technician)],
                    ["active" => true],
                ],
            ]);
            $testExistFacJu = $tests->findOne([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($user['_id'])],
                    ["type" => 'Factuel'],
                    ["level" => 'Junior'],
                ],
            ]);
            $testExistFDeclaJu = $tests->findOne([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($user['_id'])],
                    ["type" => 'Declaratif'],
                    ["level" => 'Junior'],
                ],
            ]);
            $testExistFacSe = $tests->findOne([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($user['_id'])],
                    ["type" => 'Factuel'],
                    ["level" => 'Senior'],
                ],
            ]);
            $testExistFDeclaSe = $tests->findOne([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($user['_id'])],
                    ["type" => 'Declaratif'],
                    ["level" => 'Senior'],
                ],
            ]);
            $testExistFacEx = $tests->findOne([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($user['_id'])],
                    ["type" => 'Factuel'],
                    ["level" => 'Expert'],
                ],
            ]);
            $testExistFDeclaEx = $tests->findOne([
                '$and' => [
                    ["user" => new MongoDB\BSON\ObjectId($user['_id'])],
                    ["type" => 'Declaratif'],
                    ["level" => 'Expert'],
                ],
            ]);
            if ($user['level'] == 'Junior') {
                if ($testExistFacJu) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistFacJu['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleFac = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Factuel'],
                                ["level" => 'Junior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleFac['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistFacJu['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleFac['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistFac) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Factuel",
                        "level" =>"Junior",
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insert = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insert->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleFac['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocate = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insert->getInsertedId()),
                        'type' => 'Factuel',
                        'level' => "Junior",
                        'activeTest' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocate);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistDeclaJu) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaJu['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleDecla = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Declaratif'],
                                ["level" => 'Junior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleDecla['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaJu['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleDecla['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistDecla) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Declaratif",
                        'level' => 'Junior',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insert = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insert->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleDecla['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocate = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insert->getInsertedId()),
                        'type' => 'Declaratif',
                        'level' => 'Junior',
                        'activeTest' => false,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocate);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                }
            } elseif ($user['level'] == 'Senior') {
                if ($testExistFacJu) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistFacJu['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleFac = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Factuel'],
                                ["level" => 'Junior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleFac['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistFacJu['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleFac['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistFac) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Factuel",
                        "level" => $vehicleFac->level,
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insert = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insert->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleFac['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocate = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insert->getInsertedId()),
                        'type' => 'Factuel',
                        'level' => $vehicleFac->level,
                        'activeTest' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocate);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistDeclaJu) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaJu['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleDecla = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Declaratif'],
                                ["level" => 'Junior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleDecla['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaJu['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleDecla['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistDecla) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Declaratif",
                        'level' => 'Junior',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insert = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insert->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleDecla['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocate = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insert->getInsertedId()),
                        'type' => 'Declaratif',
                        'level' => 'Junior',
                        'activeTest' => false,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocate);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistFacSe) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistFacSe['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleFacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Factuel'],
                                ["level" => 'Senior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleFacSe['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistFacSe['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleFacSe['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistFacSe) {
                    $testFacSe = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Factuel",
                        'level' => 'Senior',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insertSe = $tests->insertOne($testFacSe);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insertSe->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleFacSe['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocateSe = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insertSe->getInsertedId()),
                        'type' => 'Factuel',
                        'level' => 'Senior',
                        'activeTest' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocateSe);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistDeclaSe) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaJu['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleDeclaSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Declaratif'],
                                ["level" => 'Senior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleDeclaSe['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaSe['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleDeclaSe['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistDeclaSe) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Declaratif",
                        "level" => 'Senior',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insertSe = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insertSe->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleDeclaSe['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocateSe = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insertSe->getInsertedId()),
                        'type' => 'Declaratif',
                        "level" => 'Senior',
                        'activeTest' => false,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocateSe);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                }
            } else {
                if ($testExistFacJu) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistFacJu['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleFac = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Factuel'],
                                ["level" => 'Junior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleFac['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistFacJu['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleFac['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistFac) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Factuel",
                        "level" => $vehicleFac->level,
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insert = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insert->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleFac['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocate = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insert->getInsertedId()),
                        'type' => 'Factuel',
                        'level' => $vehicleFac->level,
                        'activeTest' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocate);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistDeclaJu) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaJu['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleDecla = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Declaratif'],
                                ["level" => 'Junior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleDecla['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaJu['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleDecla['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistDecla) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Declaratif",
                        'level' => 'Junior',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insert = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insert->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleDecla['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocate = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insert->getInsertedId()),
                        'type' => 'Declaratif',
                        'level' => 'Junior',
                        'activeTest' => false,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocate);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistFacSe) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistFacSe['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleFacSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Factuel'],
                                ["level" => 'Senior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleFacSe['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistFacSe['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleFacSe['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistFacSe) {
                    $testFacSe = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Factuel",
                        'level' => 'Senior',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insertSe = $tests->insertOne($testFacSe);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insertSe->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleFacSe['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocateSe = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insertSe->getInsertedId()),
                        'type' => 'Factuel',
                        'level' => 'Senior',
                        'activeTest' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocateSe);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistDeclaSe) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaSe['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleDeclaSe = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Declaratif'],
                                ["level" => 'Senior'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleDeclaSe['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaSe['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleDeclaSe['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistDeclaSe) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Declaratif",
                        "level" => 'Senior',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insertSe = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insertSe->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleDeclaSe['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocateSe = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insertSe->getInsertedId()),
                        'type' => 'Declaratif',
                        "level" => 'Senior',
                        'activeTest' => false,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocateSe);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistFacEx) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistFacEx['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleFacEx = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Factuel'],
                                ["level" => 'Expert'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleFacEx['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistFacEx['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleFacEx['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistFacEx) {
                    $testFacEx = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Factuel",
                        'level' => 'Expert',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insertEx = $tests->insertOne($testFacEx);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insertEx->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleFacSe['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocateEx = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insertEx->getInsertedId()),
                        'type' => 'Factuel',
                        'level' => 'Expert',
                        'activeTest' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocateEx);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                } elseif ($testExistDeclaEx) {
                    for ($i = 0; $i < count($brand); $i++) {
                        $tests->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaEx['_id'])],
                            [
                                '$push' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        $vehicleDeclaEx = $vehicles->findOne([
                            '$and' => [
                                ["brand" => $brand[$i]],
                                ["type" => 'Declaratif'],
                                ["level" => 'Expert'],
                                ["active" => true],
                            ],
                        ]);
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                        for ($i = 0; $i < count($vehicleDeclaEx['quizzes']); $i++) {
                            $tests->updateOne(
                                ["_id" => new MongoDB\BSON\ObjectId($testExistDeclaEx['_id'])],
                                [
                                    '$addToSet' => [
                                        "quizzes" => $vehicleDeclaEx['quizzes'][$i]
                                    ],
                                ]
                            );
                        }
                    }
                } elseif (!$testExistDeclaEx) {
                    $testFac = [
                        "quizzes" => [],
                        "user" => new MongoDB\BSON\ObjectId($user['_id']),
                        "brand" => $brand,
                        "type" => "Declaratif",
                        "level" => 'Expert',
                        "total" => 0,
                        "active" => true,
                        "created" => date("d-m-y"),
                    ];
                    $insertEx = $tests->insertOne($testFac);

                    $tests->updateOne(
                        ["_id" => new MongoDB\BSON\ObjectId($insertEx->getInsertedId())],
                        [
                            '$addToSet' => [
                                "quizzes" => $vehicleDeclaEx['quizzes'][$i]
                            ],
                        ]
                    );
                    $allocateEx = [
                        'user' => new MongoDB\BSON\ObjectId($user['_id']),
                        'test' => new MongoDB\BSON\ObjectId($insertEx->getInsertedId()),
                        'type' => 'Declaratif',
                        "level" => 'Expert',
                        'activeTest' => false,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-y"),
                    ];
                    $allocations->insertOne($allocateEx);
                    for ($i = 0; $i < count($brand); $i++) {
                        $users->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($user['_id'])],
                            [
                                '$addToSet' => [
                                    "brand" => $brand[$i]
                                ],
                            ]
                        );
                    }
                }
            }
            $success_msg = "Technicien(s) affecté(s) avec succès";
        }
    }
    ?>

<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title>Assigner Technicien au Test | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Modal body-->
            <div class="container mt-5 w-50">
                <img src="../public/images/logo.png" alt="10" height="170"
                    style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
                <h1 class="my-3 text-center">Assigner un technicien à un test</h1>

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

                <form method="POST"><br>
                    <!--begin::Input group-->
                    <div class="row fv-row mb-7">
                        <!--begin::Col-->
                        <div class="col-xl-6">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6">
                                    <span class="required">Marque de Véhicule</span>

                                    <span class="ms-1" data-bs-toggle="tooltip" title="Veuillez choisir le véhicule">
                                        <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span></i> </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="brand[]" multiple aria-label="Select a Country" data-control="select2"
                                    data-placeholder="Sélectionnez la marque de véhicule..."
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Sélectionnez la marque de véhicule...</option>
                                    <option value="FUSO">
                                      FUSO
                                    </option>
                                    <option value="HINO">
                                      HINO
                                    </option>
                                    <option value="JCB">
                                      JCB
                                    </option>
                                    <option value="KING LONG">
                                      KING LONG
                                    </option>
                                    <option value="LOVOL">
                                      LOVOL
                                    </option>
                                    <option value="MERCEDES TRUCK">
                                      MERCEDES TRUCK
                                    </option>
                                    <option value="RENAULT TRUCK">
                                      RENAULT TRUCK
                                    </option>
                                    <option value="SINOTRUCK">
                                      SINOTRUCK
                                    </option>
                                    <option value="TOYOTA BT">
                                      TOYOTA BT
                                    </option>
                                    <option value="TOYOTA FORFLIT">
                                      TOYOTA FORFLIT
                                    </option>
                                    <option value="BYD">
                                      BYD
                                    </option>
                                    <option value="CITROEN">
                                      CITROEN VL
                                    </option>
                                    <option value="MERCEDES">
                                      MERCEDES VL
                                    </option>
                                    <option value="MUTSUBISHI">
                                      MUTSUBISHI VL
                                    </option>
                                    <option value="PEUGEOT">
                                      PEUGEOT VL
                                    </option>
                                    <option value="SUZUKI">
                                      SUZUKI VL
                                    </option>
                                    <option value="TOYOTA">
                                      TOYOTA VL
                                    </option>
                                    <option value="YAMAHA BATEAU">
                                      YAMAHA BATEAU
                                    </option>
                                    <option value="YAMAHA MOTO">
                                      YAMAHA MOTO
                                    </option>
                                </select>
                                <?php if (isset($error)) { ?>
                                <span class='text-danger'>
                                    <?php echo $error; ?>
                                </span>
                                <?php } ?>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xl-6">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6">
                                    <span class="required">Technicien</span>

                                    <span class="ms-1" data-bs-toggle="tooltip" title="Veuillez choisir le technicien">
                                        <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span></i> </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="technician" aria-label="Select a Country"
                                    data-control="select2" data-placeholder="Sélectionnez le technicien..."
                                    class="form-select form-select-solid fw-bold">
                                    <option value="">Sélectionnez le technicien...</option>
                                    <?php
                                    $user = $users->find([
                                        '$and' => [
                                            ["profile" => "Technicien"],
                                            ["active" => true],
                                        ],
                                    ]);
                                    foreach ($user as $user) { ?>
                                    <option value='<?php echo $user->_id; ?>'>
                                        <?php echo $user->firstName; ?> <?php echo $user->lastName; ?>
                                    </option>
                                    <?php }
                                    ?>
                                </select>
                                <?php if (isset($error)) { ?>
                                <span class='text-danger'>
                                    <?php echo $error; ?>
                                </span>
                                <?php } ?>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="text-center" style="margin-bottom: 50px;">
                        <button type="submit" name="submit" class="btn btn-lg btn-primary">
                            Valider
                        </button>
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

<?php include_once "partials/footer.php"; ?>
<?php
} ?>
