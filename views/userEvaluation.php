<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
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
    $questions = $academy->questions;
    $results = $academy->results;
    $allocations = $academy->allocations;
    
    $id = $_GET[ 'user' ];
    $user = $users->findone([
                '$and' => [
                    [
                        '_id' => new MongoDB\BSON\ObjectId( $id ),
                        'active' => true,
                    ],
                ]
            ]);
    $manager = $_GET[ 'id' ];
    $level = $_GET[ 'level' ];
    $vehicle = $_GET[ 'vehicle' ];
    $brand = $_GET[ 'brand' ];

    $vehicule = $vehicles->findOne([
        '$and' => [
            ['users' => new MongoDB\BSON\ObjectId($id)],
            ['label' => $vehicle],
            ['brand' => $brand],
            ['type' => "Declaratif"],
            ["level" => $level],
            ["active" => true]
        ]
    ]);
    $arrQuizzes = iterator_to_array($vehicule->quizzes);

    if ( isset( $_POST[ 'valid' ] ) ) {
        $time = $_POST[ 'timer' ];
        $body = $_POST;
        // assuming POST method, you can replace it with $_GET if it's a GET method
        $proposals = array_values($body);
        
        $scoreF = 0;
        $score = [];
        $scoreAss = [];
        $scoreAr = [];
        $scoreBoi = [];
        $scoreBoT = [];
        $scoreClim = [];
        $scoreDir = [];
        $scoreElec = [];
        $scoreMoD = [];
        $scoreMoEl = [];
        $scoreMoE = [];
        $scoreHyd = [];
        $scoreFreiH = [];
        $scoreFreiP = [];
        $scoreMulti = [];
        $scorePont = [];
        $scorePneu = [];
        $scoreRe = [];
        $scoreSusH = [];
        $scoreSusR = [];
        $scoreSusP = [];
        $scoreTran = [];

        $quizQuestion = [];
        $proposal = [];
        $proposalAssistance = [];
        $proposalArbre = [];
        $proposalBoite = [];
        $proposalClimatisation = [];
        $proposalDirection = [];
        $proposalElectricite = [];
        $proposalFreinage = [];
        $proposalFrein = [];
        $proposalHydraulique = [];
        $proposalMoteurDiesel = [];
        $proposalMoteurElec = [];
        $proposalMoteurEssence = [];
        $proposalMultiplexage = [];
        $proposalPont = [];
        $proposalPneu = [];
        $proposalReducteur = [];
        $proposalSuspensionLame = [];
        $proposalSuspensionRessort = [];
        $proposalSuspensionPneumatique = [];
        $proposalTransversale = [];

        if (isset($_POST[ 'quizAssistance' ])) {
            $assistanceID = $_POST[ 'quizAssistance' ];
            $quizAssistance = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($assistanceID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Assistance à la Conduite") {
                    if ($proposals[$i] == "1-Assistance à la Conduite-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreAss, "il connait");
                        array_push($proposalAssistance, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalAssistance, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizAssistance->questions);
                    $result = [
                        'questions' => $quizAssistance->questions,
                        'answers' => $proposalAssistance,
                        'quiz' => new MongoDB\BSON\ObjectId($assistanceID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreAss),
                        'speciality' => $quizAssistance->speciality,
                        'level' => $level,
                        'type' => $quizAssistance->type,
                        'typeR' => 'Manager',
                        'total' => $quizAssistance->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizArbre' ])) {
            $arbreID = $_POST[ 'quizArbre' ];
            $quizArbre = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($arbreID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Arbre de Transmission") {
                    if ($proposals[$i] == "1-Arbre de Transmission-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreAr, "il connait");
                        array_push($proposalArbre, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalArbre, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizArbre->questions);
                    $result = [
                        'questions' => $quizArbre->questions,
                        'answers' => $proposalArbre,
                        'quiz' => new MongoDB\BSON\ObjectId($arbreID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreAr),
                        'speciality' => $quizArbre->speciality,
                        'level' => $level,
                        'type' => $quizArbre->type,
                        'typeR' => 'Manager',
                        'total' => $quizArbre->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizTransfert' ])) {
            $transfertID = $_POST[ 'quizTransfert' ];
            $quizTransfert= $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($transfertID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Boite de Transfert") {
                    if ($proposals[$i] == "1-Boite de Transfert-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreBoT, "il connait");
                        array_push($proposalTransfert, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalBoite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizTransfert->questions);
                    $result = [
                        'questions' => $quizTransfert->questions,
                        'answers' => $proposalTransfert,
                        'quiz' => new MongoDB\BSON\ObjectId($transfertID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreBoT),
                        'speciality' => $quizTransfert->speciality,
                        'level' => $level,
                        'type' => $quizTransfert->type,
                        'typeR' => 'Manager',
                        'total' => $quizTransfert->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizBoite' ])) {
            $boiteID = $_POST[ 'quizBoite' ];
            $quizBoite = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($boiteID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Boite de Vitesse") {
                    if ($proposals[$i] == "1-Boite de Vitesse-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreBoi, "il connait");
                        array_push($proposalBoite, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalBoite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizBoite->questions);
                    $result = [
                        'questions' => $quizBoite->questions,
                        'answers' => $proposalBoite,
                        'quiz' => new MongoDB\BSON\ObjectId($boiteID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreBoi),
                        'speciality' => $quizBoite->speciality,
                        'level' => $level,
                        'type' => $quizBoite->type,
                        'typeR' => 'Manager',
                        'total' => $quizBoite->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizClimatisation' ])) {
            $climatisationID = $_POST[ 'quizClimatisation' ];
            $quizClimatisation = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($climatisationID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Climatisation") {
                    if ($proposals[$i] == "1-Climatisation-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreClim, "il connait");
                        array_push($proposalClimatisation, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalClimatisation, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizClimatisation->questions);
                    $result = [
                        'questions' => $quizClimatisation->questions,
                        'answers' => $proposalClimatisation,
                        'quiz' => new MongoDB\BSON\ObjectId($climatisationID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreClim),
                        'speciality' => $quizClimatisation->speciality,
                        'level' => $level,
                        'type' => $quizClimatisation->type,
                        'typeR' => 'Manager',
                        'total' => $quizClimatisation->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizDirection' ])) {
            $directionID = $_POST[ 'quizDirection' ];
            $quizDirection = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($directionID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Direction") {
                    if ($proposals[$i] == "1-Direction-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreDir, "il connait");
                        array_push($proposalDirection, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalDirection, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizDirection->questions);
                    $result = [
                        'questions' => $quizDirection->questions,
                        'answers' => $proposalDirection,
                        'quiz' => new MongoDB\BSON\ObjectId($directionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreDir),
                        'speciality' => $quizDirection->speciality,
                        'level' => $level,
                        'type' => $quizDirection->type,
                        'typeR' => 'Manager',
                        'total' => $quizDirection->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizElectricite' ])) {
            $electriciteID = $_POST[ 'quizElectricite' ];
            $quizElectricite = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($electriciteID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Electricité") {
                    if ($proposals[$i] == "1-Electricité-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreElec, "il connait");
                        array_push($proposalElectricite, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalElectricite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizElectricite->questions);
                    $result = [
                        'questions' => $quizElectricite->questions,
                        'answers' => $proposalElectricite,
                        'quiz' => new MongoDB\BSON\ObjectId($electriciteID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreElec),
                        'speciality' => $quizElectricite->speciality,
                        'level' => $level,
                        'type' => $quizElectricite->type,
                        'typeR' => 'Manager',
                        'total' => $quizElectricite->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizFreinage' ])) {
            $freinageID = $_POST[ 'quizFreinage' ];
            $quizFreinage = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($freinageID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Freinage Hydraulique") {
                    if ($proposals[$i] == "1-Freinage Hydraulique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreFreiH, "il connait");
                        array_push($proposalFreinage, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalFreinage, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizFreinage->questions);
                    $result = [
                        'questions' => $quizFreinage->questions,
                        'answers' => $proposalFreinage,
                        'quiz' => new MongoDB\BSON\ObjectId($freinageID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreFreiH),
                        'speciality' => $quizFreinage->speciality,
                        'level' => $level,
                        'type' => $quizFreinage->type,
                        'typeR' => 'Manager',
                        'total' => $quizFreinage->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($freinageID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizFrein' ])) {
            $freinID = $_POST[ 'quizFrein' ];
            $quizFrein = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($freinID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Freinage Pneumatique") {
                    if ($proposals[$i] == "1-Freinage Pneumatique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreFreiP, "il connait");
                        array_push($proposalFrein, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalFreinage, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizFrein->questions);
                    $result = [
                        'questions' => $quizFrein->questions,
                        'answers' => $proposalFreinage,
                        'quiz' => new MongoDB\BSON\ObjectId($freinID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreFreiP),
                        'speciality' => $quizFrein->speciality,
                        'level' => $level,
                        'type' => $quizFrein->type,
                        'typeR' => 'Manager',
                        'total' => $quizFrein->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizHydraulique' ])) {
            $hydrauliqueID = $_POST[ 'quizHydraulique' ];
            $quizHydraulique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($hydrauliqueID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Hydraulique") {
                    if ($proposals[$i] == "1-Hydraulique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreHyd, "il connait");
                        array_push($proposalHydraulique, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalHydraulique, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizHydraulique->questions);
                    $result = [
                        'questions' => $quizHydraulique->questions,
                        'answers' => $proposalHydraulique,
                        'quiz' => new MongoDB\BSON\ObjectId($hydrauliqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreHyd),
                        'speciality' => $quizHydraulique->speciality,
                        'level' => $level,
                        'type' => $quizHydraulique->type,
                        'typeR' => 'Manager',
                        'total' => $quizHydraulique->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($hydrauliqueID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizMoteurDiesel' ])) {
            $moteurDieselID = $_POST[ 'quizMoteurDiesel' ];
            $quizMoteurDiesel = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($moteurDieselID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Moteur Diesel") {
                    if ($proposals[$i] == "1-Moteur Diesel-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMoD, "il connait");
                        array_push($proposalMoteurDiesel, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMoteurDiesel, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizMoteurDiesel->questions);
                    $result = [
                        'questions' => $quizMoteurDiesel->questions,
                        'answers' => $proposalMoteurDiesel,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurDieselID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreMoD),
                        'speciality' => $quizMoteurDiesel->speciality,
                        'level' => $level,
                        'type' => $quizMoteurDiesel->type,
                        'typeR' => 'Manager',
                        'total' => $quizMoteurDiesel->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizMoteurElec' ])) {
            $moteurElecID = $_POST[ 'quizMoteurElec' ];
            $quizMoteurElec = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($moteurElecID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Moteur Electrique") {
                    if ($proposals[$i] == "1-Moteur Electrique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMoEl, "il connait");
                        array_push($proposalMoteurElec, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMoteurElec, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizMoteurElec->questions);
                    $result = [
                        'questions' => $quizMoteurElec->questions,
                        'answers' => $proposalMoteurElec,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurElecID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreMoEl),
                        'speciality' => $quizMoteurElec->speciality,
                        'level' => $level,
                        'type' => $quizMoteurElec->type,
                        'typeR' => 'Manager',
                        'total' => $quizMoteurElec->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizMoteurEssence' ])) {
            $moteurEssenceID = $_POST[ 'quizMoteurEssence' ];
            $quizMoteurEssence = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($moteurEssenceID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Moteur Essence") {
                    if ($proposals[$i] == "1-Moteur Essence-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMoE, "il connait");
                        array_push($proposalMoteurEssence, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMoteurEssence, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizMoteurEssence->questions);
                    $result = [
                        'questions' => $quizMoteurEssence->questions,
                        'answers' => $proposalMoteurEssence,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurEssenceID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreMoE),
                        'speciality' => $quizMoteurEssence->speciality,
                        'level' => $level,
                        'type' => $quizMoteurEssence->type,
                        'typeR' => 'Manager',
                        'total' => $quizMoteurEssence->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizMultiplexage' ])) {
            $multiplexageID = $_POST[ 'quizMultiplexage' ];
            $quizMultiplexage = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($multiplexageID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Multiplexage & Electronique") {
                    if ($proposals[$i] == "1-Multiplexage & Electronique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMulti, "il connait");
                        array_push($proposalMultiplexage, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMultiplexage, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizMultiplexage->questions);
                    $result = [
                        'questions' => $quizMultiplexage->questions,
                        'answers' => $proposalMultiplexage,
                        'quiz' => new MongoDB\BSON\ObjectId($multiplexageID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreMulti),
                        'speciality' => $quizMultiplexage->speciality,
                        'level' => $level,
                        'type' => $quizMultiplexage->type,
                        'typeR' => 'Manager',
                        'total' => $quizMultiplexage->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($multiplexageID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizPont' ])) {
            $pontID = $_POST[ 'quizPont' ];
            $quizPont = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($pontID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Pont") {
                    if ($proposals[$i] == "1-Pont-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scorePont, "il connait");
                        array_push($proposalPont, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalPont, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizPont->questions);
                    $result = [
                        'questions' => $quizPont->questions,
                        'answers' => $proposalPont,
                        'quiz' => new MongoDB\BSON\ObjectId($pontID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scorePont),
                        'speciality' => $quizPont->speciality,
                        'level' => $level,
                        'type' => $quizPont->type,
                        'typeR' => 'Manager',
                        'total' => $quizPont->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizPneumatique' ])) {
            $pneumatiqueID = $_POST[ 'quizPneumatique' ];
            $quizPneumatique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($pneumatiqueID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Pneumatique") {
                    if ($proposals[$i] == "1-Pneumatique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scorePneu, "il connait");
                        array_push($proposalPneu, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalPneu, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizPneumatique->questions);
                    $result = [
                        'questions' => $quizPneumatique->questions,
                        'answers' => $proposalPneu,
                        'quiz' => new MongoDB\BSON\ObjectId($pneumatiqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scorePneu),
                        'speciality' => $quizPneumatique->speciality,
                        'level' => $level,
                        'type' => $quizPneumatique->type,
                        'typeR' => 'Manager',
                        'total' => $quizPneumatique->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($pneumatiqueID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizReducteur' ])) {
            $reducteurID = $_POST[ 'quizReducteur' ];
            $quizReducteur = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($reducteurID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Reducteur") {
                    if ($proposals[$i] == "1-Reducteur-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scorePneu, "il connait");
                        array_push($proposalPneu, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalPneu, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizReducteur->questions);
                    $result = [
                        'questions' => $quizReducteur->questions,
                        'answers' => $proposalPneu,
                        'quiz' => new MongoDB\BSON\ObjectId($reducteurID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scorePneu),
                        'speciality' => $quizReducteur->speciality,
                        'level' => $level,
                        'type' => $quizReducteur->type,
                        'typeR' => 'Manager',
                        'total' => $quizReducteur->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizSuspensionLame' ])) {
            $suspensionLameID = $_POST[ 'quizSuspensionLame' ];
            $quizSuspensionLame = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($suspensionLameID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Suspension à Lame") {
                    if ($proposals[$i] == "1-Suspension à Lame-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreSusL, "il connait");
                        array_push($proposalSuspensionLame, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalSuspensionLame, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizSuspension->questions);
                    $result = [
                        'questions' => $quizSuspensionLame->questions,
                        'answers' => $proposalSuspensionLame,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionLameID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreSusL),
                        'speciality' => $quizSuspensionLame->speciality,
                        'level' => $level,
                        'type' => $quizSuspensionLame->type,
                        'typeR' => 'Manager',
                        'total' => $quizSuspensionLame->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizSuspensionRessort' ])) {
            $suspensionRessortID = $_POST[ 'quizSuspensionRessort' ];
            $quizSuspensionRessort = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($suspensionRessortID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Suspension Ressort") {
                    if ($proposals[$i] == "1-Suspension Ressort-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreSusR, "il connait");
                        array_push($proposalSuspensionRessort, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalSuspensionRessort, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizSuspensionRessort->questions);
                    $result = [
                        'questions' => $quizSuspensionRessort->questions,
                        'answers' => $proposalSuspensionRessort,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionRessortID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreSusR),
                        'speciality' => $quizSuspensionRessort->speciality,
                        'level' => $level,
                        'type' => $quizSuspensionRessort->type,
                        'typeR' => 'Manager',
                        'total' => $quizSuspensionRessort->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizSuspensionPneumatique' ])) {
            $suspensionPneumatiqueID = $_POST[ 'quizSuspensionPneumatique' ];
            $quizSuspensionPneumatique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($suspensionPneumatiqueID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Suspension Pneumatique") {
                    if ($proposals[$i] == "1-Suspension Pneumatique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreSusP, "il connait");
                        array_push($proposalSuspensionPneumatique, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalSuspensionPneumatique, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizSuspensionPneumatique->questions);
                    $result = [
                        'questions' => $quizSuspensionPneumatique->questions,
                        'answers' => $proposalSuspensionPneumatique,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionPneumatiqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreSusP),
                        'speciality' => $quizSuspensionPneumatique->speciality,
                        'level' => $level,
                        'type' => $quizSuspensionPneumatique->type,
                        'typeR' => 'Manager',
                        'total' => $quizSuspensionPneumatique->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizTransversale' ])) {
            $transversaleID = $_POST[ 'quizTransversale' ];
            $quizTransversale = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($transversaleID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Transversale") {
                    if ($proposals[$i] == "1-Transversale-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreTran, "il connait");
                        array_push($proposalTransversale, "Oui");
                        array_push($score, "il connait");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalTransversale, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $quizTransversale->questions);
                    $result = [
                        'questions' => $quizTransversale->questions,
                        'answers' => $proposalTransversale,
                        'quiz' => new MongoDB\BSON\ObjectId($transversaleID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreTran),
                        'speciality' => $quizTransversale->speciality,
                        'level' => $quizTransversale->level,
                        'type' => $quizTransversale->type,
                        'typeR' => 'Manager',
                        'total' => $quizTransversale->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        $vehicule = $vehicles->findOne([
            '$and' => [
                ['label' => $vehicle],
                ['level' => $level],
                ['type' => 'Declaratif'],
                ['active' => true],
            ],
        ]);
        $newResult = [
            'questions' => $quizQuestion,
            'answers' => $proposal,
            'user' => new MongoDB\BSON\ObjectId($id),
            'vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id),
            'manager' => new MongoDB\BSON\ObjectId($manager),
            'score' => count($score),
            'level' => $level,
            'type' => 'Declaratif',
            'typeR' => 'Managers',
            'total' => count($quizQuestion),
            'time' => $time,
            'active' => true,
            'created' => date("d-m-y")
        ];
        $result = $results->insertOne($newResult);
        
        $allocationData = $allocations->findOne([
            '$and' => [
                ['user' => new MongoDB\BSON\ObjectId($id)],
                ['vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id)],
            ],
        ]);

        $allocationData->activeManager = true;
        $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);

        $technicienResult = $results->findOne([
            '$and' => [
                ['user' => new MongoDB\BSON\ObjectId($id)],
                ['typeR' => 'Techniciens'],
            ],
        ]);

        if($technicienResult) {
            for ($i = 0; $i < count($proposal); $i++) {
                if($proposal[$i] == 'Oui' && $technicienResult->answers[$i] == 'Oui') {
                    $scoreF += 1;
                } else {
                    $scoreF += 0;
                }
                $newresult = [
                    'user' => new MongoDB\BSON\ObjectId($id),
                    'manager' => new MongoDB\BSON\ObjectId($manager),
                    'vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id),
                    'score' => $scoreF,
                    'level' => $level,
                    'type' => 'Declaratif',
                    'typeR' => 'Technicien - Manager',
                    'total' => count($quizQuestion),
                    'active' => true,
                    'created' => date("d-m-y")
                ];
            }
            $insert = $results->insertOne($newresult);
        }

        header('Location: ./dashboard.php');
    }
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Evaluation de <?php echo $user->firstName ?> <?php echo $user->lastName ?> | CFAO Mobility Academy</title>
<!--end::Title-->

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" />
<link href="../public/css/userQuiz.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="heading">
        <h1 class="heading__text">Evaluation de <?php echo $user->firstName ?> <?php echo $user->lastName ?></h1>
        <center>
            <div class="timer" style="margin-right: 300px;">
                <div class="time_left_txt">Questions Restantes</div>
                <div class="timer_sec" id="num" value="1">
                </div>
            </div>
            <div class="timer" style="margin-top: -45px; margin-left: 300px">
                <div class="time_left_txt">Durée(heure et minute)</div>
                <div class="timer_sec" name="time" id="timer_sec" value="0">
                </div>
            </div>
        </center>
    </div>

    <!-- Quiz section -->
    <div class="quiz" style="margin-bottom: 40px;">
        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
            Vous devez repondre à toutes les questions avant
            de pouvoir valider le questionnaire.
        </p>
        <form class="quiz-form" method="POST">
            <input class="hidden" type="text" name="timer" id="clock" />
            <div class="quiz-form__quiz">
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $assistanceDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Assistance à la Conduite"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
                if ($assistanceDecla) {
                $arrQuestions = $assistanceDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Assistance à la conduite
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizAssistance" value="<?php echo $assistanceDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $arbreDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Arbre de Transmission"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
                if ($arbreDecla) {
                $arrQuestions = $arbreDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Arbre de Transmission
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizArbre" value="<?php echo $arbreDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $transfertDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Boite de Transfert"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
                if ($transfertDecla) {
                $arrQuestions = $transfertDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Boite de Transfert
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizTransfert" value="<?php echo $transfertDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransfert<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransfert<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $boiteDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Boite de Vitesse"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
                if ($boiteDecla) {
                $arrQuestions = $boiteDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Boite de Vitesse
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizBoite" value="<?php echo $boiteDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $climatisationDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Climatisation"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($climatisationDecla) {
                    $arrQuestions = $climatisationDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Climatisation
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizClimatisation"
                    value="<?php echo $climatisationDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $directionDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Direction"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($directionDecla) {
                    $arrQuestions = $directionDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Direction
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizDirection" value="<?php echo $directionDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $electriciteDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Electricité"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($electriciteDecla) {
                    $arrQuestions = $electriciteDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Electricité
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizElectricite" value="<?php echo $electriciteDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $freinageDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Freinage"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($freinageDecla) {
                    $arrQuestions = $freinageDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Freinage Hydraulique
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizFreinage" value="<?php echo $freinageDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $freinDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Freinage Pneumatique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($freinDecla) {
                    $arrQuestions = $freinDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Freinage Pneumatique
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizFrein" value="<?php echo $freinDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $hydrauliqueDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Hydraulique"],
                            ['type' => "Declaraif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($hydrauliqueDecla) {
                    $arrQuestions = $hydrauliqueDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Hydraulique
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizHydraulique" value="<?php echo $hydrauliqueDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $moteurDieselDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Moteur Diesel"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($moteurDieselDecla) {
                    $arrQuestions = $moteurDieselDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Moteur Diesel
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizMoteurDiesel"
                    value="<?php echo $moteurDieselDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurDiesel<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurDiesel<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $moteurElecDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Moteur Electrique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($moteurElecDecla) {
                    $arrQuestions = $moteurElecDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Moteur Electrique
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizMoteurElec" value="<?php echo $moteurElecDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $moteurEssenceDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Moteur Essence"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($moteurEssenceDecla) {
                    $arrQuestions = $moteurEssenceDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Moteur Essence
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizMoteurEssence"
                    value="<?php echo $moteurEssenceDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurEssence<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurEssence<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $multiplexageDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Multiplexage"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($multiplexageDecla) {
                    $arrQuestions = $multiplexageDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Multiplexage
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizMultiplexage"
                    value="<?php echo $multiplexageDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $pontDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Pont"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($pontDecla) {
                    $arrQuestions = $pontDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Pont
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizPont" value="<?php echo $pontDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $pneumatiqueDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Pneumatique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($pneumatiqueDecla) {
                    $arrQuestions = $pneumatiqueDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Pneumatique
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizPneumatique" value="<?php echo $pneumatiqueDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $reducteurDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Reducteur"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($reducteurDecla) {
                    $arrQuestions = $reducteurDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Reducteur
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizReducteur" value="<?php echo $reducteurDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerRed<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerRed<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $suspensionLameDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Suspension à Lame"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($suspensionLameDecla) {
                    $arrQuestions = $suspensionLameDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Suspension à Lame
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizSuspensionLame"
                    value="<?php echo $suspensionLameDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionLame<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionLame<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $suspensionRessortDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Suspension Ressort"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($suspensionRessortDecla) {
                    $arrQuestions = $suspensionRessortDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Suspension Ressort
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizSuspensionRessort"
                    value="<?php echo $suspensionRessortDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionRessort<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionRessort<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $suspensionPneumatiqueDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Suspension Pneumatique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($suspensionPneumatiqueDecla) {
                    $arrQuestions = $suspensionPneumatiqueDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Suspension Pneumatique
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizSuspensionPneumatique"
                    value="<?php echo $suspensionPneumatiqueDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionPneumatique<?php echo $i + 1 ?>"
                        value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionPneumatique<?php echo $i + 1 ?>"
                        value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $transversaleDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Transversale"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($transversaleDecla) {
                    $arrQuestions = $transversaleDecla['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Tâche Transversale
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizTransversale"
                    value="<?php echo $transversaleDecla->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il connait
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        il ne connait pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php } ?>
            </div>
            <button class="btn btn-primary submit" style="margin-top: 100px;" name="valid"
                type="submit">Terminer</button>
        </form>
    </div>
</div>
<script>
var timer = setInterval(countTimer, 1000);
var totalSecond = 0;

function countTimer() {
    totalSecond++;

    var hour = Math.floor(totalSecond / 3600);
    var minutes = Math.floor((totalSecond - hour * 3600) / 60);
    var seconds = totalSecond - (hour * 3600 + minutes * 60);

    if (minutes < 9 && hour > 9) {
        document.getElementById("timer_sec").innerHTML = hour + ":" + "0" + minutes;
        document.getElementById("clock").value = hour + ":" + "0" + minutes + ":" + seconds;
    } else if (hour < 9 && minutes > 9) {
        document.getElementById("timer_sec").innerHTML = "0" + hour + ":" + minutes;
        document.getElementById("clock").value = "0" + hour + ":" + minutes + ":" + seconds;
    } else if (hour < 9 && minutes < 9) {
        document.getElementById("timer_sec").innerHTML = "0" + hour + ":" + "0" + minutes;
        document.getElementById("clock").value = "0" + hour + ":" + "0" + minutes + ":" + seconds;
    } else if (hour == 9 && minutes == 9) {
        document.getElementById("timer_sec").innerHTML = "0" + hour + ":" + "0" + minutes;
        document.getElementById("clock").value = "0" + hour + ":" + "0" + minutes + ":" + seconds;
    } else {
        document.getElementById("timer_sec").innerHTML = hour + ":" + minutes;
        document.getElementById("clock").value = hour + ":" + minutes + ":" + seconds;
    }
}

let radio;
const ques = document.querySelectorAll("#question");
const submitBtn = document.querySelector("button")
submitBtn.classList.add("disabled")
const num = document.querySelector("#num").getAttribute('value');
const score = document.querySelector("#num");
const cal = (num * ques.length);
score.innerHTML = `${cal}`;

function checkedRadio() {
    const radios = document.querySelectorAll("input[type='radio']:checked");
    radios.forEach(async (rad, i) => {
        radio = i + 1;
    })
    if (ques.length == radio) {
        submitBtn.classList.remove("disabled");
    }
    const cal = (num * ques.length) - radio;
    score.innerHTML = `${cal}`;
}
</script>
<?php
include_once 'partials/footer.php'
        ?>
<?php }
        ?>