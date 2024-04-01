<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ./index.php");
    exit();
} else {
     ?>
<?php
require_once "../vendor/autoload.php";
if (isset($_POST["submit"])) {
    // Create connection
    $conn = new MongoDB\Client("mongodb://localhost:27017"); // Connecting in database
    $academy = $conn->academy;
    // Connecting in collections
    $questions = $academy->questions;
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;
    $array = [];
    $filePath = $_FILES["excel"]["tmp_name"];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $data = $spreadsheet->getActiveSheet()->toArray();
    foreach ($data as $row) {
        $level = ucfirst($row["0"]);
        $speciality = ucfirst($row["1"]);
        $label = $row["2"];
        $proposal1 = $row["3"];
        $proposal2 = $row["4"];
        $proposal3 = $row["5"];
        $proposal4 = $row["6"];
        $image = $row["7"];
        $answer = $row["8"];
        $ref = $row["9"];
        $type = ucfirst($row["10"]);
        $exist = $questions->findOne([
            '$and' => [
                ["ref" => $ref],
                ["label" => $label],
                [
                    "speciality" => $speciality,
                ],
                ["level" => $level],
                ["answer" => $answer],
                ["type" => $type],
            ],
        ]);
        if ($exist) {
            $error_msg = "Cette question " . $label . " existe déjà.";
        } elseif ($type == "Factuelle") {
            $question = [
                "image" => $image,
                "ref" => $ref,
                "label" => ucfirst($label),
                "proposal1" => ucfirst($proposal1),
                "proposal2" => ucfirst($proposal2),
                "proposal3" => ucfirst($proposal3),
                "proposal4" => ucfirst($proposal4),
                "answer" => ucfirst($answer),
                "speciality" => ucfirst($speciality),
                "type" => $type,
                "level" => $level,
                "active" => true,
                "created" => date("d-m-y"),
            ];
            $result = $questions->insertOne($question);
            $quizz = $quizzes->findOne([
                '$and' => [
                    ["speciality" => $speciality],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ]);
            $bus = $vehicles->findOne([
                '$and' => [
                    ["label" => "Bus"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ]);
            $camions = $vehicles->findOne([
                '$and' => [
                    ["label" => "Camions"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ]);
            $chariots = $vehicles->findOne([
                '$and' => [
                    ["label" => "Chariots"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ]);
            $engins = $vehicles->findOne([
                '$and' => [
                    ["label" => "Engins"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ]);
            $voitures = $vehicles->findOne([
                '$and' => [
                    ["label" => "Voitures"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ]);
            if ($quizz) {
                ++$quizz->total;
                $quizzes->updateOne(
                    ["_id" => new MongoDB\BSON\ObjectId($quizz->_id)],
                    ['$set' => $quizz]
                );
                $quizzes->updateOne(
                    ["_id" => new MongoDB\BSON\ObjectId($quizz->_id)],
                    [
                        '$push' => [
                            "questions" => new MongoDB\BSON\ObjectId(
                                $result->getInsertedId()
                            ),
                        ],
                    ]
                );
            } else {
                $quiz = [
                    "questions" => [],
                    "label" => "QCM " . $speciality . "",
                    "type" => "Factuel",
                    "speciality" => ucfirst($speciality),
                    "level" => ucfirst($level),
                    "total" => 0,
                    "active" => true,
                    "created" => date("d-m-y"),
                ];
                $insert = $quizzes->insertOne($quiz);
                $quizzes->updateOne(
                    [
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insert->getInsertedId()
                        ),
                    ],
                    [
                        '$set' => ["total" => +1],
                    ]
                );
                $quizzes->updateOne(
                    [
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insert->getInsertedId()
                        ),
                    ],
                    [
                        '$push' => [
                            "questions" => new MongoDB\BSON\ObjectId(
                                $result->getInsertedId()
                            ),
                        ],
                    ]
                );
                if ($speciality == "Arbre de Transmission") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Assistance à la Conduite") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Transfert") {
                    if ($camions) {
                        if (
                            $camions->brand == "RENAULT TRUCK" ||
                            $camions->brand == "MERCEDES TRUCK" ||
                            $camions->brand == "SINOTRUK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Vitesse") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Vitesse Automatique") {
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Vitesse Mécanique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA FORKLIFT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif (
                    $speciality == "Boite de Vitesse à Variation Continue"
                ) {
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Climatisation") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        if (
                            $camions->brand == "RENAULT TRUCK" ||
                            $camions->brand == "MERCEDES TRUCK" ||
                            $camions->brand == "SINOTRUK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Demi Arbre de Roue") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA FORKLIFT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Direction") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Electricité et Electronique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Freinage") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        ++$camions["total"];
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Freinage Electromagnétique") {
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA BT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                } elseif ($speciality == "Freinage Hydraulique") {
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Freinage Pneumatique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Hydraulique") {
                    if ($camions) {
                        if (
                            $camions->brand == "RENAULT TRUCK" ||
                            $camions->brand == "MERCEDES TRUCK" ||
                            $camions->brand == "SINOTRUK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Diesel") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Electrique") {
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Essence") {
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Thermique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        ++$camions["total"];
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Multiplexage") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Pont") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA FORFLIT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Réducteur") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Pneumatique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        ++$camions["total"];
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension à Lame") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension Ressort") {
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension Pneumatique") {
                    if ($camions) {
                        if (
                            ($camions && $camions->brand == "RENAULT TRUCK") ||
                            $camions->brand == "MERCEDES TRUCK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($voitures) {
                        if ($voitures->brand != "SUZUKI") {
                            $voitures["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $voitures->_id
                                    ),
                                ],
                                ['$set' => $voitures]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $voitures->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                } elseif ($speciality == "Transversale") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                }
            }
            $success_msg = "Question ajoutée avec succès";
        } elseif ($type == "Declarative") {
            $question = [
                "image" => $image,
                "ref" => $ref,
                "label" => ucfirst($label),
                "proposal1" =>
                    "1-" . $speciality . "-" . $level . "-" . $label . "-1",
                "proposal2" =>
                    "2-" . $speciality . "-" . $level . "-" . $label . "-2",
                "proposal3" =>
                    "3-" . $speciality . "-" . $level . "-" . $label . "-3",
                "speciality" => ucfirst($speciality),
                "type" => $type,
                "level" => $level,
                "active" => true,
                "created" => date("d-m-y"),
            ];
            $result = $questions->insertOne($question);
            $quizz = $quizzes->findOne([
                '$and' => [
                    ["speciality" => $speciality],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ]);
            $bus = $vehicles->findOne([
                '$and' => [
                    ["label" => "Bus"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ]);
            $camions = $vehicles->findOne([
                '$and' => [
                    ["label" => "Camions"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ]);
            $chariots = $vehicles->findOne([
                '$and' => [
                    ["label" => "Chariots"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ]);
            $engins = $vehicles->findOne([
                '$and' => [
                    ["label" => "Engins"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ]);
            $voitures = $vehicles->findOne([
                '$and' => [
                    ["label" => "Voitures"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ]);
            if ($quizz) {
                ++$quizz->total;
                $quizzes->updateOne(
                    ["_id" => new MongoDB\BSON\ObjectId($quizz->_id)],
                    ['$set' => $quizz]
                );
                $quizzes->updateOne(
                    ["_id" => new MongoDB\BSON\ObjectId($quizz->_id)],
                    [
                        '$push' => [
                            "questions" => new MongoDB\BSON\ObjectId(
                                $result->getInsertedId()
                            ),
                        ],
                    ]
                );
            } else {
                $quiz = [
                    "questions" => [],
                    "label" => "Tâche " . $speciality . "",
                    "type" => "Declaratif",
                    "speciality" => ucfirst($speciality),
                    "level" => ucfirst($level),
                    "total" => 0,
                    "active" => true,
                    "created" => date("d-m-y"),
                ];
                $insert = $quizzes->insertOne($quiz);
                $quizzes->updateOne(
                    [
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insert->getInsertedId()
                        ),
                    ],
                    [
                        '$set' => ["total" => +1],
                    ]
                );
                $quizzes->updateOne(
                    [
                        "_id" => new MongoDB\BSON\ObjectId(
                            $insert->getInsertedId()
                        ),
                    ],
                    [
                        '$push' => [
                            "questions" => new MongoDB\BSON\ObjectId(
                                $result->getInsertedId()
                            ),
                        ],
                    ]
                );
                if ($speciality == "Arbre de Transmission") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Assistance à la Conduite") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Transfert") {
                    if ($camions) {
                        if (
                            $camions->brand == "RENAULT TRUCK" ||
                            $camions->brand == "MERCEDES TRUCK" ||
                            $camions->brand == "SINOTRUK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Vitesse") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Vitesse Automatique") {
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Boite de Vitesse Mécanique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA FORKLIFT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif (
                    $speciality == "Boite de Vitesse à Variation Continue"
                ) {
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Climatisation") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        if (
                            $camions->brand == "RENAULT TRUCK" ||
                            $camions->brand == "MERCEDES TRUCK" ||
                            $camions->brand == "SINOTRUK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Demi Arbre de Roue") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA FORKLIFT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Direction") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Electricité et Electronique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Freinage") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        ++$camions["total"];
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Freinage Electromagnétique") {
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA BT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                } elseif ($speciality == "Freinage Hydraulique") {
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Freinage Pneumatique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Hydraulique") {
                    if ($camions) {
                        if (
                            $camions->brand == "RENAULT TRUCK" ||
                            $camions->brand == "MERCEDES TRUCK" ||
                            $camions->brand == "SINOTRUK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Diesel") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Electrique") {
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Essence") {
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Moteur Thermique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        ++$camions["total"];
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Multiplexage") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Pont") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        if ($chariots->brand == "TOYOTA FORFLIT") {
                            $chariots["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                ['$set' => $chariots]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $chariots->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Réducteur") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Pneumatique") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        ++$camions["total"];
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension à Lame") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension Ressort") {
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                } elseif ($speciality == "Suspension Pneumatique") {
                    if ($camions) {
                        if (
                            ($camions && $camions->brand == "RENAULT TRUCK") ||
                            $camions->brand == "MERCEDES TRUCK"
                        ) {
                            $camions["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                ['$set' => $camions]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $camions->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                    if ($voitures) {
                        if ($voitures->brand != "SUZUKI") {
                            $voitures["total"]++;
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $voitures->_id
                                    ),
                                ],
                                ['$set' => $voitures]
                            );
                            $vehicles->updateOne(
                                [
                                    "_id" => new MongoDB\BSON\ObjectId(
                                        $voitures->_id
                                    ),
                                ],
                                [
                                    '$push' => [
                                        "quizzes" => new MongoDB\BSON\ObjectId(
                                            $insert->getInsertedId()
                                        ),
                                    ],
                                ]
                            );
                        }
                    }
                } elseif ($speciality == "Transversale") {
                    if ($bus) {
                        $bus["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            ['$set' => $bus]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($bus->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($camions) {
                        $camions["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            ['$set' => $camions]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($camions->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($chariots) {
                        $chariots["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            ['$set' => $chariots]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $chariots->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($engins) {
                        $engins["total"]++;
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            ['$set' => $engins]
                        );
                        $vehicles->updateOne(
                            ["_id" => new MongoDB\BSON\ObjectId($engins->_id)],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                    if ($voitures) {
                        $voitures["total"]++;
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            ['$set' => $voitures]
                        );
                        $vehicles->updateOne(
                            [
                                "_id" => new MongoDB\BSON\ObjectId(
                                    $voitures->_id
                                ),
                            ],
                            [
                                '$push' => [
                                    "quizzes" => new MongoDB\BSON\ObjectId(
                                        $insert->getInsertedId()
                                    ),
                                ],
                            ]
                        );
                    }
                }
            }
            $success_msg = "Questions ajoutées avec succès";
        }
    }
}
?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title>Importer Questions | CFAO Mobility Academy</title>
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
                <h1 class="my-3 text-center">Importer des questions</h1>

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

                <form enctype="multipart/form-data" method="POST"><br>
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required form-label fw-bolder text-dark fs-6">Importer des questions via
                            Excel</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="file" class="form-control form-control-solid" placeholder="" name="excel" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="submit" name="submit" class="btn btn-primary">
                            <span class="indicator-label">
                                Valider
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

<?php include_once "partials/footer.php"; ?>
<?php
} ?>
