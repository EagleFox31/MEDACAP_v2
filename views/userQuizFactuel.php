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

    $id = $_GET[ 'id' ];
    $level = $_GET[ 'level' ];
    $vehicle = $_GET[ 'vehicle' ];
    $brand = $_GET[ 'brand' ];

    $vehicule = $vehicles->findOne([
        '$and' => [
            ['users' => new MongoDB\BSON\ObjectId($id)],
            ['label' => $vehicle],
            ['brand' => $brand],
            ['type' => "Factuel"],
            ["level" => $level],
            ["active" => true]
        ]
    ]);
    $cal = round(100 / $vehicule['total'], 0);
    $number = round($cal, 0);
    $arrQuizzes = iterator_to_array($vehicule->quizzes);

    if ( isset( $_POST[ 'valid' ] ) ) {
        $time = $_POST[ 'timer' ];
        $body = $_POST;
        // assuming POST method, you can replace it with $_GET if it's a GET method
        $proposals = array_values($body);
    
        $score = 0;
        $scoreAss = 0;
        $scoreAr = 0;
        $scoreBoi = 0;
        $scoreBoT = 0;
        $scoreClim = 0;
        $scoreDir = 0;
        $scoreElec = 0;
        $scoreMoD = 0;
        $scoreMoEl = 0;
        $scoreMoE = 0;
        $scoreHyd = 0;
        $scoreFreiH = 0;
        $scoreFreiP = 0;
        $scoreMulti = 0;
        $scorePont = 0;
        $scorePneu = 0;
        $scoreRe = 0;
        $scoreSusH = 0;
        $scoreSusR = 0;
        $scoreSusP = 0;
        $scoreTran = 0;
        $quizQuestion = [];
        $quizQuestionAssistance = [];
        $quizQuestionArbre = [];
        $quizQuestionBoite = [];
        $quizQuestionTransfert = [];
        $quizQuestionClimatisation = [];
        $quizQuestionDirection = [];
        $quizQuestionElectricite = [];
        $quizQuestionFreinage = [];
        $quizQuestionFrein = [];
        $quizQuestionHydraulique = [];
        $quizQuestionMoteurDiesel = [];
        $quizQuestionMoteurElec = [];
        $quizQuestionMoteurEssence = [];
        $quizQuestionMultiplexage = [];
        $quizQuestionPont = [];
        $quizQuestionPneu = [];
        $quizQuestionReducteur = [];
        $quizQuestionSuspensionLame = [];
        $quizQuestionSuspensionRessort = [];
        $quizQuestionSuspensionPneumatique = [];
        $quizQuestionTransversale = [];
        $answers = [];
        $answersAssistance = [];
        $answersArbre = [];
        $answersBoite = [];
        $answersTransfert = [];
        $answersClimatisation = [];
        $answersDirection = [];
        $answersElectricite = [];
        $answersFreinage = [];
        $answersFrein = [];
        $answersHydraulique = [];
        $answersMoteurDiesel = [];
        $answersMoteurElec = [];
        $answersMoteurEssence = [];
        $answersMultiplexage = [];
        $answersPont = [];
        $answersPneu = [];
        $answersReducteur = [];
        $answersSuspensionLame = [];
        $answersSuspensionRessort = [];
        $answersSuspensionPneumatique = [];
        $answersTransversale = [];
        $proposal = [];
        $proposalAssistance = [];
        $proposalArbre = [];
        $proposalBoite = [];
        $proposalTransfert = [];
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
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality  == "Assistance à la Conduite") {
                        $answer = $questionsData->answer;
                        if ($proposals[$i] === $answer) {
                            $scoreAss += 1;
                            $proposalAssistance = "Maitrisé";
                            $score += 1;
                            $proposal = "Maitrisé";
                        } else {
                            $scoreAss += 0;
                            $proposalAssistance = "Non maitrisé";
                            $score += 0;
                            $proposal = "Non maitrisé";
                        }
                        array_push($answersAssistance, $proposalAssistance);
                        array_push($quizQuestionAssistance, $questionsData->_id);
                        array_push($answers, $proposal);
                        array_push($quizQuestion, $questionsData->_id);
                        
                        $newResult = [
                            'questions' => $quizQuestionAssistance,
                            'answers' => $answersAssistance,
                            'userAnswers' => $proposals,
                            'quiz' => new MongoDB\BSON\ObjectId($assistanceID),
                            'user' => new MongoDB\BSON\ObjectId($id),
                            'score' => $scoreAss,
                            'speciality' => $quizAssistance->speciality ,
                            'level' => $level,
                            'type' => $quizAssistance->type,
                            'total' => count($quizQuestionAssistance),
                            'time' => $time,
                            'active' => true,
                            'created' => date("d-m-y")
                        ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizArbre' ])) {
            $arbreID = $_POST[ 'quizArbre' ];
            $quizArbre= $quizzes->findOne([
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
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality  == "Arbre de Transmission") {
                        $answer = $questionsData->answer;
                        if ($proposals[$i] === $answer) {
                            $scoreAr += 1;
                            $proposalArbre = "Maitrisé";
                            $score += 1;
                            $proposal = "Maitrisé";
                        } else {
                            $scoreAr += 0;
                            $proposalArbre = "Non maitrisé";
                            $score += 0;
                            $proposal = "Non maitrisé";
                        }
                        array_push($answersArbre, $proposalArbre);
                        array_push($quizQuestionAssistance, $questionsData->_id);
                        array_push($answers, $proposal);
                        array_push($quizQuestion, $questionsData->_id);
                        
                        $newResult = [
                            'questions' => $quizQuestionArbre,
                            'answers' => $answersArbre,
                            'userAnswers' => $proposals,
                            'quiz' => new MongoDB\BSON\ObjectId($arbreID),
                            'user' => new MongoDB\BSON\ObjectId($id),
                            'score' => $scoreAr,
                            'speciality' => $quizArbre->speciality ,
                            'level' => $level,
                            'type' => $quizArbre->type,
                            'total' => count($quizQuestionArbre),
                            'time' => $time,
                            'active' => true,
                            'created' => date("d-m-y")
                        ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizTransfert' ])) {
            $transfertID = $_POST[ 'quizTransfert' ];
            $quizTransfert = $quizzes->findOne([
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
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality  == "Boite de Transfert") {
                        $answer = $questionsData->answer;
                        if ($proposals[$i] === $answer) {
                            $scoreBoT += 1;
                            $proposalTransfert = "Maitrisé";
                            $score += 1;
                            $proposal = "Maitrisé";
                        } else {
                            $scoreBoT += 0;
                            $proposalTransfert = "Non maitrisé";
                            $score += 0;
                            $proposal = "Non maitrisé";
                        }
                        array_push($answersTransfert, $proposalTransfert);
                        array_push($quizQuestionTransfert, $questionsData->_id);
                        array_push($answers, $proposal);
                        array_push($quizQuestion, $questionsData->_id);
                        
                        $newResult = [
                            'questions' => $quizQuestionTransfert,
                            'answers' => $answersTransfert,
                            'userAnswers' => $proposals,
                            'quiz' => new MongoDB\BSON\ObjectId($transfertID),
                            'user' => new MongoDB\BSON\ObjectId($id),
                            'score' => $scoreBoT,
                            'speciality' => $quizTransfert->speciality ,
                            'level' => $level,
                            'type' => $quizTransfert->type,
                            'total' => count($quizQuestionTransfert),
                            'time' => $time,
                            'active' => true,
                            'created' => date("d-m-y")
                        ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizBoite' ])) {
            $boiteID = $_POST[ 'quizBoite' ];
            $quizBoite= $quizzes->findOne([
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
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality  == "Boite de Vitesse") {
                        $answer = $questionsData->answer;
                        if ($proposals[$i] === $answer) {
                            $scoreBoi += 1;
                            $proposalBoite = "Maitrisé";
                            $score += 1;
                            $proposal = "Maitrisé";
                        } else {
                            $scoreBoi += 0;
                            $proposalBoite = "Non maitrisé";
                            $score += 0;
                            $proposal = "Non maitrisé";
                        }
                        array_push($answersBoite, $proposalBoite);
                        array_push($quizQuestionBoite, $questionsData->_id);
                        array_push($answers, $proposal);
                        array_push($quizQuestion, $questionsData->_id);
                        
                        $newResult = [
                            'questions' => $quizQuestionBoite,
                            'answers' => $answersBoite,
                            'userAnswers' => $proposals,
                            'quiz' => new MongoDB\BSON\ObjectId($boiteID),
                            'user' => new MongoDB\BSON\ObjectId($id),
                            'score' => $scoreBoi,
                            'speciality' => $quizBoite->speciality ,
                            'level' => $level,
                            'type' => $quizBoite->type,
                            'total' => count($quizQuestionBoite),
                            'time' => $time,
                            'active' => true,
                            'created' => date("d-m-y")
                        ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
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
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Climatisation") {
                        $answer = $questionsData->answer;
                        if ($proposals[$i] === $answer) {
                            $scoreClim += 1;
                            $proposalClimatisation = "Maitrisé";
                            $score += 1;
                            $proposal = "Maitrisé";
                        } else {
                            $scoreClim += 0;
                            $proposalClimatisation = "Non maitrisé";
                            $score += 0;
                            $proposal = "Non maitrisé";
                        }
                        array_push($answersClimatisation, $proposalClimatisation);
                        array_push($quizQuestionClimatisation, $questionsData->_id);
                        array_push($answers, $proposal);
                        array_push($quizQuestion, $questionsData->_id);
                        
                        $newResult = [
                            'questions' => $quizQuestionClimatisation,
                            'answers' => $answersClimatisation,
                            'quiz' => new MongoDB\BSON\ObjectId($climatisationID),
                            'user' => new MongoDB\BSON\ObjectId($id),
                            'score' => $scoreClim,
                            'speciality' => $quizClimatisation->speciality ,
                            'level' => $level,
                            'type' => $quizClimatisation->type,
                            'total' => count($quizQuestionClimatisation),
                            'time' => $time,
                            'active' => true,
                            'created' => date("d-m-y")
                        ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizDirection' ])) {
            $directionID = $_POST[ 'quizDirection' ];
            $quizDirection = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($directionID)],
                    ["active" => true]
                ]
            ]);
                             
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality  == "Direction") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreDir += 1;
                        $proposalDirection = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreDir += 0;
                        $proposalDirection = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersDirection, $proposalDirection);
                    array_push($quizQuestionDirection, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionDirection,
                        'answers' => $answersDirection,
                        'quiz' => new MongoDB\BSON\ObjectId($directionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreDir,
                        'speciality' => $quizDirection->speciality ,
                        'level' => $level,
                        'type' => $quizDirection->type,
                        'total' => count($quizQuestionDirection),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizElectricite' ])) {
            $electriciteID = $_POST[ 'quizElectricite' ];
            $quizElectricite = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($electriciteID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Electricité") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreElec += 1;
                        $proposalElectricite = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreElec += 0;
                        $proposalElectricite = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersElectricite, $proposalElectricite);
                    array_push($quizQuestionElectricite, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionElectricite,
                        'answers' => $answersElectricite,
                        'quiz' => new MongoDB\BSON\ObjectId($electriciteID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreElec,
                        'speciality' => $quizElectricite->speciality,
                        'level' => $level,
                        'type' => $quizElectricite->type,
                        'total' => count($quizQuestionElectricite),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizFreinage' ])) {
            $freinageID = $_POST[ 'quizFreinage' ];
            $quizFreinage = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($freinageID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Freinage Hydraulique") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreFreiH += 1;
                        $proposalFreinage = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreFreiH += 0;
                        $proposalFreinage = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersFreinage, $proposalFreinage);
                    array_push($quizQuestionFreinage, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionFreinage,
                        'answers' => $answersFreinage,
                        'quiz' => new MongoDB\BSON\ObjectId($freinageID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreFreiH,
                        'speciality' => $quizFreinage->speciality ,
                        'level' => $level,
                        'type' => $quizFreinage->type,
                        'total' => count($quizQuestionFreinage),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizFrein' ])) {
            $freinID = $_POST[ 'quizFrein' ];
            $quizFrein = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($freinID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Freinage Pneumatique") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreFreiP += 1;
                        $proposalFrein = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreFreiP += 0;
                        $proposalFrein = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersFrein, $proposalFrein);
                    array_push($quizQuestionFrein, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionFrein,
                        'answers' => $answersFrein,
                        'quiz' => new MongoDB\BSON\ObjectId($freinID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreFreiP,
                        'speciality' => $quizFrein->speciality ,
                        'level' => $level,
                        'type' => $quizFrein->type,
                        'total' => count($quizQuestionFrein),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizHydraulique' ])) {
            $hydrauliqueID = $_POST[ 'quizHydraulique' ];
            $quizHydraulique = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($hydrauliqueID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Hydraulique") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreHyd += 1;
                        $proposalHydraulique = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreHyd += 0;
                        $proposalHydraulique = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersHydraulique, $proposalHydraulique);
                    array_push($quizQuestionHydraulique, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionHydraulique,
                        'answers' => $answersHydraulique,
                        'quiz' => new MongoDB\BSON\ObjectId($hydrauliqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreHyd,
                        'speciality' => $quizHydraulique->speciality ,
                        'level' => $level,
                        'type' => $quizHydraulique->type,
                        'total' => count($quizQuestionHydraulique),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizMoteurDiesel' ])) {
            $moteurDieselID = $_POST[ 'quizMoteurDiesel' ];
            $quizMoteurDiesel = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($moteurDieselID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Moteur Diesel") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreMoD += 1;
                        $proposalMoteurDiesel = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreMoD += 0;
                        $proposalMoteurDiesel = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersMoteurDiesel, $proposalMoteurDiesel);
                    array_push($quizQuestionMoteurDiesel, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionMoteurDiesel,
                        'answers' => $answersMoteurDiesel,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreMoD,
                        'speciality' => $quizMoteurDiesel->speciality ,
                        'level' => $level,
                        'type' => $quizMoteurDiesel->type,
                        'total' => count($quizQuestionMoteurDiesel),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizMoteurElec' ])) {
            $moteurElecID = $_POST[ 'quizMoteurElec' ];
            $quizMoteurElec = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($moteurElecID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Moteur Electrique") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreMoEl += 1;
                        $proposalMoteurElec = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreMoEl += 0;
                        $proposalMoteurElec = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersMoteurElec, $proposalMoteurElec);
                    array_push($quizQuestionMoteurElec, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionMoteurElec,
                        'answers' => $answersMoteurElec,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurElecID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreMoEl,
                        'speciality' => $quizMoteurElec->speciality ,
                        'level' => $level,
                        'type' => $quizMoteurElec->type,
                        'total' => count($quizQuestionMoteurElec),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizMoteurEssence' ])) {
            $moteurEssenceID = $_POST[ 'quizMoteurEssence' ];
            $quizMoteurEssence = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($moteurEssenceID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Moteur Essence") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreMoE += 1;
                        $proposalMoteurEssence = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreMoE += 0;
                        $proposalMoteurEssence = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersMoteurEssence, $proposalMoteurEssence);
                    array_push($quizQuestionMoteurEssence, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionMoteurEssence,
                        'answers' => $answersMoteurEssence,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurEssenceID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreMoE,
                        'speciality' => $quizMoteurEssence->speciality ,
                        'level' => $level,
                        'type' => $quizMoteurEssence->type,
                        'total' => count($quizQuestionMoteurEssence),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($moteurID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizMultiplexage' ])) {
            $multiplexageID = $_POST[ 'quizMultiplexage' ];
            $quizMultiplexage = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($multiplexageID)],
                    ["active" => true]
                ]
            ]);                 
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality  == "Multiplexage") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreMulti += 1;
                        $proposalMultiplexage = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreMulti += 0;
                        $proposalMultiplexage = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersMultiplexage, $proposalMultiplexage);
                    array_push($quizQuestionMultiplexage, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionMultiplexage,
                        'answers' => $answersMultiplexage,
                        'quiz' => new MongoDB\BSON\ObjectId($multiplexageID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreMulti,
                        'speciality' => $quizMultiplexage->speciality ,
                        'level' => $level,
                        'type' => $quizMultiplexage->type,
                        'total' => count($quizQuestionMultiplexage),
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
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizPont' ])) {
            $pontID = $_POST[ 'quizPont' ];
            $quizPont = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($pontID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Pont") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scorePont += 1;
                        $proposalPont= "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scorePont += 0;
                        $proposalPont = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersPont, $proposalPont);
                    array_push($quizQuestionPont, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionPont,
                        'answers' => $answersPont,
                        'quiz' => new MongoDB\BSON\ObjectId($pontID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scorePont,
                        'speciality' => $quizPont->speciality ,
                        'level' => $level,
                        'type' => $quizPont->type,
                        'total' => count($quizQuestionPont),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizPneumatique' ])) {
            $pneumatiqueID = $_POST[ 'quizPneumatique' ];
            $quizPneumatique = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($pneumatiqueID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Pneumatique") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scorePneu += 1;
                        $proposalPneu = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scorePneu += 0;
                        $proposalPneu = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersPneu, $proposalPneu);
                    array_push($quizQuestionPneu, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionPneu,
                        'answers' => $answersPneu,
                        'quiz' => new MongoDB\BSON\ObjectId($pneumatiqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scorePneu,
                        'speciality' => $quizPneumatique->speciality ,
                        'level' => $level,
                        'type' => $quizPneumatique->type,
                        'total' => count($quizQuestionPneu),
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
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizReducteur' ])) {
            $reducteurID = $_POST[ 'quizReducteur' ];
            $quizReducteur = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($reducteurID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Reducteur") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreRed += 1;
                        $proposalReducteur = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreRed += 0;
                        $proposalReducteur = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersReducteur, $proposalReducteur);
                    array_push($quizQuestionReducteur, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionReducteur,
                        'answers' => $answersReducteur,
                        'quiz' => new MongoDB\BSON\ObjectId($reducteurID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreRed,
                        'speciality' => $quizReducteur->speciality ,
                        'level' => $level,
                        'type' => $quizReducteur->type,
                        'total' => count($quizQuestionReducteur),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizSuspensionLame' ])) {
            $suspensionLameID = $_POST[ 'quizSuspensionLame' ];
            $quizSuspensionLame = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($suspensionLameID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Suspension à Lame") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreSusL += 1;
                        $proposalSuspensionLame = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreSusL += 0;
                        $proposalSuspensionLame = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersSuspensionLame, $proposalSuspensionLame);
                    array_push($quizQuestionSuspensionLame, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionSuspensionLame,
                        'answers' => $answersSuspensionLame,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionLameID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreSusL,
                        'speciality' => $quizSuspensionLame->speciality ,
                        'level' => $level,
                        'type' => $quizSuspensionLame->type,
                        'total' => count($quizQuestionSuspensionLame),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizSuspensionRessort' ])) {
            $suspensionRessortID = $_POST[ 'quizSuspensionRessort' ];
            $quizSuspensionRessort = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($suspensionRessortID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Suspension Ressort") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreSusR += 1;
                        $proposalSuspensionRessort = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreSusR += 0;
                        $proposalSuspensionRessort = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersSuspensionRessort, $proposalSuspensionRessort);
                    array_push($quizQuestionSuspensionRessort, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionSuspensionRessort,
                        'answers' => $answersSuspensionRessort,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionRessortID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreSusR,
                        'speciality' => $quizSuspensionRessort->speciality ,
                        'level' => $level,
                        'type' => $quizSuspensionRessort->type,
                        'total' => count($quizQuestionSuspensionRessort),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizSuspensionPneumatique' ])) {
            $suspensionPneumatiqueID = $_POST[ 'quizSuspensionPneumatique' ];
            $quizSuspensionPneumatique = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($suspensionPneumatiqueID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Suspension Pneumatique") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreSusT += 1;
                        $proposalSuspensionPneumatique = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreSusT += 0;
                        $proposalSuspensionPneumatique = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersSuspensionPneumatique, $proposalSuspensionPneumatique);
                    array_push($quizQuestionSuspensionPneumatique, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionSuspensionPneumatique,
                        'answers' => $answersSuspensionPneumatique,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionPneumatiqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreSusT,
                        'speciality' => $quizSuspensionPneumatique->speciality ,
                        'level' => $level,
                        'type' => $quizSuspensionPneumatique->type,
                        'total' => count($quizQuestionSuspensionPneumatique),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizTransversale' ])) {
            $transversaleID = $_POST[ 'quizTransversale' ];
            $quizTransversale = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($transversaleID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
                $questionsData = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                        ['proposal4' => $proposals[$i]],
                    ],
                    'type' => 'Factuelle',
                ]);
    
                if ($questionsData != null) {
                    if ($questionsData->speciality == "Transversale") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreTran += 1;
                        $proposalTransversale = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreTran += 0;
                        $proposalTransversale = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersTransversale, $proposalTransversale);
                    array_push($quizQuestionTransversale, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionTransversale,
                        'answers' => $answersTransversale,
                        'quiz' => new MongoDB\BSON\ObjectId($transversaleID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreTran,
                        'speciality' => $quizTransversale->speciality ,
                        'level' => $level,
                        'type' => $quizTransversale->type,
                        'total' => count($quizQuestionTransversale),
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                }
                }
            }
           $insertedResult = $results->insertOne($newResult);
        }
        $vehicule = $vehicles->findOne([
            '$and' => [
                ['label' => $vehicle],
                ['level' => $level],
                ['type' => 'Factuel'],
                ['active' => true],
            ],
        ]);

        $newResult = [
            'questions' => $quizQuestion,
            'answers' => $answers,
            'userAnswers' => $proposals,
            'user' => new MongoDB\BSON\ObjectId($id),
            'vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id),
            'score' => $score,
            'level' => $level,
            'type' => 'Factuel',
            'typeR' => 'Technicien',
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

        $allocationData->active = true;
        $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);

        header('Location: ./dashboard.php');
    }

?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Questionnaires à Choix Multiples | CFAO Mobility Academy</title>
<!--end::Title-->

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" />
<link href="../public/css/userQuiz.css" rel="stylesheet" type="text/css" />


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <div class="container">
                <div class="heading">
                    <h1 class="heading__text">Question à choix multiple</h1>
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
                    <!-- <center>
            <div class="timer">
                <div class="time_left_txt">Temps Restant</div>
                <div class="timer_sec" name="time" id="timer_sec" value="1">
                    </div>
                </div>
            </center> -->
                    <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                        Vous devez repondre à toutes les questions avant
                        de pouvoir valider le questionnaire.
                    </p>
                    <form class="quiz-form" method="POST">
                        <input class="hidden" type="text" name="timer" id="clock" />
                        <div class="quiz-form__quiz">
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $assistanceFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Assistance à la Conduite"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
                if ($assistanceFac) {
                $quizzAssistance = $quizzes->aggregate([
                [
                '$match' => [
                '_id' => new MongoDB\BSON\ObjectId($assistanceFac->_id),
                ],
                ],
                [
                '$lookup' => [
                'from' => 'questions',
                'localField' => 'questions',
                'foreignField' => '_id',
                'as' => 'questions',
                ],
                ],
                [
                '$unwind' => '$questions',
                ],
                [
                '$sample' => [
                'size' => $number,
                ],
                ],
                [
                '$group' => [
                '_id' => '$_id',
                'questions' => [
                '$push' => '$questions._id',
                ],
                ],
                ],
                ]);
                $arrQuizzAssistance = iterator_to_array($quizzAssistance);
                $arrQuestions = $arrQuizzAssistance[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Assistance à la conduite
                            </p>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                            <input class="hidden" type="text" name="quizAssistance"
                                value="<?php echo $assistanceFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $arbreFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Arbre de Transmission"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
                if ($arbreFac) {
                $quizzArbre = $quizzes->aggregate([
                [
                '$match' => [
                '_id' => new MongoDB\BSON\ObjectId($arbreFac->_id),
                ],
                ],
                [
                '$lookup' => [
                'from' => 'questions',
                'localField' => 'questions',
                'foreignField' => '_id',
                'as' => 'questions',
                ],
                ],
                [
                '$unwind' => '$questions',
                ],
                [
                '$sample' => [
                'size' => $number,
                ],
                ],
                [
                '$group' => [
                '_id' => '$_id',
                'questions' => [
                '$push' => '$questions._id',
                ],
                ],
                ],
                ]);
                $arrQuizzArbre = iterator_to_array($quizzArbre);
                $arrQuestions = $arrQuizzArbre[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Arbre de Transmission
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
                            <input class="hidden" type="text" name="quizArbre" value="<?php echo $arbreFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $transfertFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Boite de Transfert"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($transfertFac) {
                    $quizzTransfert = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($transfertFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzTransfert = iterator_to_array($quizzTransfert);
                    $arrQuestions = $arrQuizzTransfert[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Boite de Transfert
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
                            <input class="hidden" type="text" name="quizTransfert"
                                value="<?php echo $TransfertFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransfert<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransfert<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransfertn<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransfert<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $boiteFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Boite de Vitesse"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($boiteFac) {
                    $quizzBoite = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($boiteFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzBoite = iterator_to_array($quizzBoite);
                    $arrQuestions = $arrQuizzBoite[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Boite de Vitesse
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
                            <input class="hidden" type="text" name="quizBoite" value="<?php echo $boiteFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerBoiten<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $climatisationFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Climatisation"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($climatisationFac) {
                    $quizzClimatisation = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($climatisationFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzClimatisation = iterator_to_array($quizzClimatisation);
                    $arrQuestions = $arrQuizzClimatisation[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Climatisation
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
                                value="<?php echo $climatisationFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $directionFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Direction"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($directionFac) {
                    $quizzDirection = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($directionFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzDirection = iterator_to_array($quizzDirection);
                    $arrQuestions = $arrQuizzDirection[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Direction
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
                            <input class="hidden" type="text" name="quizDirection"
                                value="<?php echo $directionFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $electriciteFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Electricité"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($electriciteFac) {
                    $quizzElectricite = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($electriciteFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzElectricite = iterator_to_array($quizzElectricite);
                    $arrQuestions = $arrQuizzElectricite[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Electricité
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
                            <input class="hidden" type="text" name="quizElectricite"
                                value="<?php echo $electriciteFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $freinageFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Freinage Hydraulique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($freinageFac) {
                    $quizzFreinage = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($freinageFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzFreinage = iterator_to_array($quizzFreinage);
                    $arrQuestions = $arrQuizzFreinage[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Freinage Hydraulique
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
                            <input class="hidden" type="text" name="quizFreinage"
                                value="<?php echo $freinageFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $freinFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Freinage Pneumatique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($freinFac) {
                    $quizzFrein = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($freinFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzFrein = iterator_to_array($quizzFrein);
                    $arrQuestions = $arrQuizzFrein[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Freinage Pneumatique
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
                            <input class="hidden" type="text" name="quizFrein" value="<?php echo $freinFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $hydrauliqueFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Hydraulique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($hydrauliqueFac) {
                    $quizzHydraulique = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($hydrauliqueFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzHydraulique = iterator_to_array($quizzHydraulique);
                    $arrQuestions = $arrQuizzHydraulique[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Hydraulique
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
                            <input class="hidden" type="text" name="quizHydraulique"
                                value="<?php echo $hydrauliqueFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $moteurDieselFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Moteur Diesel"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($moteurDieselFac) {
                    $quizzMoteurDiesel = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($moteurDieselFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzMoteurDiesel = iterator_to_array($quizzMoteurDiesel);
                    $arrQuestions = $arrQuizzMoteurDiesel[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Moteur Diesel
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
                                value="<?php echo $moteurDieselFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                            for ($j = 0; $j < count($arrQuizzes); $j++) { 
                            $moteurElecFac=$quizzes->findOne([
                                '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                                ['speciality' => "Moteur Electrique"],
                                ['type' => "Factuel"],
                                ["level" => $level],
                                ["active" => true]
                                ]
                                ]);

                                if ($moteurElecFac) {
                                $quizzMoteurElec = $quizzes->aggregate([
                                [
                                '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($moteurElecFac->_id),
                                ],
                                ],
                                [
                                '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                                ],
                                ],
                                [
                                '$unwind' => '$questions',
                                ],
                                [
                                '$sample' => [
                                'size' => $number,
                                ],
                                ],
                                [
                                '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                '$push' => '$questions._id',
                                ],
                                ],
                                ],
                                ]);
                                $arrQuizzMoteurElec = iterator_to_array($quizzMoteurElec);
                                $arrQuestions = $arrQuizzMoteurElec[0]['questions'];
                                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Moteur Electrique
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
                            <input class="hidden" type="text" name="quizMoteurElec"
                                value="<?php echo $moteurElecFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) { 
                                    $moteurEssenceFac=$quizzes->findOne([
                                        '$and' => [
                                        ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                                        ['speciality' => "Moteur Essence"],
                                        ['type' => "Factuel"],
                                        ["level" => $level],
                                        ["active" => true]
                                        ]
                                        ]);

                                        if ($moteurEssenceFac) {
                                        $quizzMoteurEssence = $quizzes->aggregate([
                                        [
                                        '$match' => [
                                        '_id' => new MongoDB\BSON\ObjectId($moteurEssenceFac->_id),
                                        ],
                                        ],
                                        [
                                        '$lookup' => [
                                        'from' => 'questions',
                                        'localField' => 'questions',
                                        'foreignField' => '_id',
                                        'as' => 'questions',
                                        ],
                                        ],
                                        [
                                        '$unwind' => '$questions',
                                        ],
                                        [
                                        '$sample' => [
                                        'size' => $number,
                                        ],
                                        ],
                                        [
                                        '$group' => [
                                        '_id' => '$_id',
                                        'questions' => [
                                        '$push' => '$questions._id',
                                        ],
                                        ],
                                        ],
                                        ]);
                                        $arrQuizzMoteurEssence = iterator_to_array($quizzMoteurEssence);
                                        $arrQuestions = $arrQuizzMoteurEssence[0]['questions'];
                                        ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Moteur Essence
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
                                value="<?php echo $moteurEssenceFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $multiplexageFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Multiplexage"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($multiplexageFac) {
                    $quizzMultiplexage = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($multiplexageFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzMultiplexage = iterator_to_array($quizzMultiplexage);
                    $arrQuestions = $arrQuizzMultiplexage[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Multiplexage
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
                                value="<?php echo $multiplexageFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>">
                                <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $pontFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Pont"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($pontFac) {
                    $quizzPont = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($pontFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzPont = iterator_to_array($quizzPont);
                    $arrQuestions = $arrQuizzPont[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Pont
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
                            <input class="hidden" type="text" name="quizPont" value="<?php echo $pontFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $pneumatiqueFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Pneumatique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($pneumatiqueFac) {
                    $quizzPneumatique = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($pneumatiqueFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzPneumatique = iterator_to_array($quizzPneumatique);
                    $arrQuestions = $arrQuizzPneumatique[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Pneumatique
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
                            <input class="hidden" type="text" name="quizPneumatique"
                                value="<?php echo $pneumatiqueFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $reducteurFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Reducteur"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($reducteurFac) {
                    $quizzReducteur = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($ReducteurFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzReducteur = iterator_to_array($quizzReducteur);
                    $arrQuestions = $arrQuizzreducteur[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Reducteur
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
                            <input class="hidden" type="text" name="quizReducteur"
                                value="<?php echo $reducteurFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>">
                                <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $suspensionLameFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Suspension à Lame"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($suspensionLameFac) {
                    $quizzSuspensionLame = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($suspensionLameFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzSuspensionLame = iterator_to_array($quizzSuspensionLame);
                    $arrQuestions = $arrQuizzSuspensionLame[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Suspension à Lame
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
                                value="<?php echo $suspensionLameFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>">
                                <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $suspensionRessortFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Suspension Ressort"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($suspensionRessortFac) {
                    $quizzSuspensionRessort = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($suspensionRessortFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzSuspensionRessort = iterator_to_array($quizzSuspensionRessort);
                    $arrQuestions = $arrQuizzSuspensionRessort[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Suspension Ressort
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
                                value="<?php echo $suspensionRessortFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>">
                                <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $suspensionPneumatiqueFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Suspension Pneumatique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($suspensionPneumatiqueFac) {
                    $quizzSuspensionPneumatique = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($suspensionPneumatiqueFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzSuspensionPneumatique = iterator_to_array($quizzSuspensionPneumatique);
                    $arrQuestions = $arrQuizzSuspensionPneumatique[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Suspension Pneumatique
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
                                value="<?php echo $suspensionPneumatiqueFac->_id ?>" />
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
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionPneumatique<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionPneumatique<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerSuspensionPneumatique<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>">
                                <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $transversaleFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Transversale"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($transversaleFac) {
                    $quizzTransversale = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($transversaleFac->_id),
                            ],
                        ],
                        [
                            '$lookup' => [
                                'from' => 'questions',
                                'localField' => 'questions',
                                'foreignField' => '_id',
                                'as' => 'questions',
                            ],
                        ],
                        [
                            '$unwind' => '$questions',
                        ],
                        [
                            '$sample' => [
                                'size' => $number,
                            ],
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'questions' => [
                                    '$push' => '$questions._id',
                                ],
                            ],
                        ],
                    ]);
                    $arrQuizzTransversale = iterator_to_array($quizzTransversale);
                    $arrQuestions = $arrQuizzTransversale[0]['questions'];
                ?>
                            <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                                Connaissance Transversale
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
                                value="<?php echo $transversaleFac->_id ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1 ?> - <?php echo $question->label ?>
                            </p>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal1 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal2 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal3 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3 ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1 ?>"
                                    value="<?php echo $question->proposal4 ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4 ?>
                                </span>
                            </label>
                            <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>">
                                <br>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </div>
                        <button class="btn btn-primary submit" style="margin-top: 100px;" name="valid"
                            type="submit">Terminer</button>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->

<script>
// const startingMinutes = document
//     .getElementById("timer_sec")
//     .getAttribute("value");
// let time = startingMinutes * 60;

// const countDown = document.getElementById("timer_sec");

// setInterval(updateCountDown, 1000);

// function updateCountDown() {
//     let minutes = Math.floor(time / 60);
//     let seconds = time % 60;
//     time--;
//     if (time > 0) {
//         minutes = minutes < 10 ? "0" + minutes : minutes;
//         seconds = seconds < 10 ? "0" + seconds : seconds;
//         countDown.innerHTML = `${minutes}:${seconds}`;
//     } else if (time < 0) {
//         clearInterval(updateCountDown);
//         minutes = "00";
//         seconds = "00";
//         countDown.innerHTML = `${minutes}:${seconds}`;
//         document.getElementById(".submit").addEventListener("click")
//     }
// }

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