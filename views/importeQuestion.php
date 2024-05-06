<?php
session_start();
include_once "language.php";

if (!isset($_SESSION["id"])) {
    header("Location: ../");
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
    
    // remove header
    unset($data[0]);

    foreach ($data as $row) {
        $level = ucfirst($row["0"]);
        $speciality = ucfirst($row["1"]);
        $title = $row["2"];
        $label = $row["3"];
        $proposal1 = $row["4"];
        $proposal2 = $row["5"];
        $proposal3 = $row["6"];
        $proposal4 = $row["7"];
        $image = $row["8"];
        $answer = $row["9"];
        $ref = $row["10"];
        $type = ucfirst($row["11"]);

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
            $error_msg = $error_question;
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
            $bus = $vehicles->find([
                '$and' => [
                    ["label" => "Bus"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ])->toArray();
            $camions = $vehicles->find([
                '$and' => [
                    ["label" => "Camions"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ]);
            $chariots = $vehicles->find([
                '$and' => [
                    ["label" => "Chariots"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ])->toArray();
            $engins = $vehicles->find([
                '$and' => [
                    ["label" => "Engins"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ])->toArray();
            $voitures = $vehicles->find([
                '$and' => [
                    ["label" => "Voitures"],
                    ["level" => $level],
                    ["type" => "Factuel"],
                    ["active" => true],
                ],
            ])->toArray();
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
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Assistance à la Conduite") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Transfert") {
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Vitesse") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Vitesse Automatique") {
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Vitesse Mécanique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif (
                    $speciality == "Boite de Vitesse à Variation Continue"
                ) {
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Climatisation") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Demi Arbre de Roue") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Direction") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Electricité et Electronique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Freinage") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Freinage Electromagnétique") {
                    foreach ($chariots as $chariots) {
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
                    }
                } elseif ($speciality == "Freinage Hydraulique") {
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Freinage Pneumatique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                } elseif ($speciality == "Hydraulique") {
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                } elseif ($speciality == "Moteur Diesel") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Moteur Electrique") {
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Moteur Essence") {
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Moteur Thermique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Multiplexage") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Pont") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Reducteur") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                } elseif ($speciality == "Pneumatique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension à Lame") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension Ressort") {
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension Pneumatique") {
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                    }
                } elseif ($speciality == "Transversale") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
            }
            $success_msg = $success_question;
        } elseif ($type == "Declarative") {
            if ($level == "Expert") {
                $question = [
                    "image" => $image,
                    "ref" => $ref,
                    "title" => ucfirst($title),
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
            } else {
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
            }
            $quizz = $quizzes->findOne([
                '$and' => [
                    ["speciality" => $speciality],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ]);
            $bus = $vehicles->find([
                '$and' => [
                    ["label" => "Bus"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ])->toArray();
            $camions = $vehicles->find([
                '$and' => [
                    ["label" => "Camions"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ])->toArray();
            $chariots = $vehicles->find([
                '$and' => [
                    ["label" => "Chariots"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ])->toArray();
            $engins = $vehicles->find([
                '$and' => [
                    ["label" => "Engins"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ])->toArray();
            $voitures = $vehicles->find([
                '$and' => [
                    ["label" => "Voitures"],
                    ["level" => $level],
                    ["type" => "Declaratif"],
                    ["active" => true],
                ],
            ])->toArray();
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
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Assistance à la Conduite") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Transfert") {
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Vitesse") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Vitesse Automatique") {
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Boite de Vitesse Mécanique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif (
                    $speciality == "Boite de Vitesse à Variation Continue"
                ) {
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Climatisation") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Demi Arbre de Roue") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Direction") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Electricité et Electronique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Freinage") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Freinage Electromagnétique") {
                    foreach ($chariots as $chariots) {
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
                    }
                } elseif ($speciality == "Freinage Hydraulique") {
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Freinage Pneumatique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                } elseif ($speciality == "Hydraulique") {
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                } elseif ($speciality == "Moteur Diesel") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Moteur Electrique") {
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Moteur Essence") {
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Moteur Thermique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Multiplexage") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Pont") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Reducteur") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                } elseif ($speciality == "Pneumatique") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension à Lame") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension Ressort") {
                    foreach ($voitures as $voitures) {
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
                } elseif ($speciality == "Suspension Pneumatique") {
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($voitures as $voitures) {
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
                    }
                } elseif ($speciality == "Transversale") {
                    foreach ($bus as $bus) {
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
                    }
                    foreach ($camions as $camions) {
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
                    }
                    foreach ($chariots as $chariots) {
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
                    }
                    foreach ($engins as $engins) {
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
                    }
                    foreach ($voitures as $voitures) {
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
            }
            $success_msg = $success_question;
        }
    }
}
?>
<?php include_once "partials/header.php"; ?>
<!--begin::Title-->
<title><?php echo $import_question ?> | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Modal body-->
            <div class="container mt-5 w-50 text-center">
                <img src="../public/images/logo.png" alt="10" height="170" style="display: block; max-width: 75%; height: auto; margin-left: 25px;">
                <h1 class="my-3 text-center"><?php echo $import_question ?></h1>

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
                            <?php echo $excel ?></label>
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
