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
    $questions = $academy->questions;
    $results = $academy->results;
    $allocations = $academy->allocations;

    $id = $_GET[ 'id' ];
    $level = $_GET[ 'level' ];

    if ( isset( $_POST[ 'valid' ] ) ) {
        $time = $_POST[ 'timer' ];
        $body = $_POST;
        // assuming POST method, you can replace it with $_GET if it's a GET method
        $proposals = array_values($body);
    
        $score = 0;
        $scoreAss = 0;
        $scoreClim = 0;
        $scoreDir = 0;
        $scoreElec = 0;
        $scoreMo = 0;
        $scoreHyd = 0;
        $scoreFrei = 0;
        $scoreMulti = 0;
        $scorePneu = 0;
        $scoreSus = 0;
        $scoreTran = 0;
        $scoreMission = 0;
        $quizQuestion = [];
        $quizQuestionAssistance = [];
        $quizQuestionClimatisation = [];
        $quizQuestionDirection = [];
        $quizQuestionElectricite = [];
        $quizQuestionFreinage = [];
        $quizQuestionHydraulique = [];
        $quizQuestionMoteur = [];
        $quizQuestionMultiplexage = [];
        $quizQuestionPneu = [];
        $quizQuestionSuspension = [];
        $quizQuestionTransmission = [];
        $quizQuestionTransversale = [];
        $answers = [];
        $answersAssistance = [];
        $answersClimatisation = [];
        $answersDirection = [];
        $answersElectricite = [];
        $answersFreinage = [];
        $answersHydraulique = [];
        $answersMoteur = [];
        $answersMultiplexage = [];
        $answersPneu = [];
        $answersSuspension = [];
        $answersTransmission = [];
        $answersTransversale = [];
        $proposal = [];
        $proposalAssistance = [];
        $proposalClimatisation = [];
        $proposalDirection = [];
        $proposalElectricite = [];
        $proposalFreinage = [];
        $proposalHydraulique = [];
        $proposalMoteur = [];
        $proposalMultiplexage = [];
        $proposalPneu = [];
        $proposalSuspension = [];
        $proposalTransmission = [];
        $proposalTransversale = [];
        
        if (isset($_POST[ 'quizAssistance' ])) {
            $assistanceID = $_POST[ 'quizAssistance' ];
            $quizAssistance = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($assistanceID)]);
                 
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
                            'total' => $quizAssistance->number,
                            'time' => $time,
                            'active' => true,
                            'created' => date("d-m-y")
                        ];
                        
                        $allocationData = $allocations->findOne([
                            '$and' => [
                                ['user' => new MongoDB\BSON\ObjectId($id)],
                                ['quiz' => new MongoDB\BSON\ObjectId($assistanceID)],
                                ],
                                ]);
                                
                                $allocationData->active = false;
                                $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]
                        );
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        } 
        if (isset($_POST[ 'quizClimatisation' ])) {
            $climatisationID = $_POST[ 'quizClimatisation' ];
            $quizClimatisation = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($climatisationID)]);
                
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
                        'total' => $quizClimatisation->number,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($climatisationID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        } 
        if (isset($_POST[ 'quizDirection' ])) {
            $directionID = $_POST[ 'quizDirection' ];
            $quizDirection = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($directionID)]);
                             
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
                        'total' => $quizDirection->number,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($directionID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizElectricite' ])) {
            $electriciteID = $_POST[ 'quizElectricite' ];
            $quizElectricite = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($electriciteID)]);
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
                        'total' => $quizElectricite->number,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($electriciteID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        
        if (isset($_POST[ 'quizFreinage' ])) {
            $freinageID = $_POST[ 'quizFreinage' ];
            $quizFreinage = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($freinageID)]);
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
                    if ($questionsData->speciality == "Freinage") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreFrei += 1;
                        $proposalFreinage = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreFrei += 0;
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
                        'score' => $scoreFrei,
                        'speciality' => $quizFreinage->speciality ,
                        'level' => $level,
                        'type' => $quizFreinage->type,
                        'total' => $quizFreinage->number,
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
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST[ 'quizHydraulique' ])) {
            $hydrauliqueID = $_POST[ 'quizHydraulique' ];
            $quizHydraulique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($hydrauliqueID)]);
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
                        'total' => $quizHydraulique->number,
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
            $insertedResult = $results->insertOne($newResult);
        } 
        if (isset($_POST[ 'quizMoteur' ])) {
            $moteurID = $_POST[ 'quizMoteur' ];
            $quizMoteur = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($moteurID)]);                 
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
                    if ($questionsData->speciality == "Moteur") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreMo += 1;
                        $proposalMoteur = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreMo += 0;
                        $proposalMoteur = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersMoteur, $proposalMoteur);
                    array_push($quizQuestionMoteur, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionMoteur,
                        'answers' => $answersMoteur,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreMo,
                        'speciality' => $quizMoteur->speciality ,
                        'level' => $level,
                        'type' => $quizMoteur->type,
                        'total' => $quizMoteur->number,
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
            $quizMultiplexage = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($multiplexageID)]);                 
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
                    if ($questionsData->speciality  == "Multiplexage & Electronique") {
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
                        'total' => $quizMultiplexage->number,
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
        if (isset($_POST[ 'quizPneumatique' ])) {
            $pneumatiqueID = $_POST[ 'quizPneumatique' ];
            $quizPneumatique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($pneumatiqueID)]);                 
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
                        'total' => $quizPneumatique->number,
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
        if (isset($_POST[ 'quizSuspension' ])) {
            $suspensionID = $_POST[ 'quizSuspension' ];
            $quizSuspension = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($suspensionID)]);                 
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
                    if ($questionsData->speciality == "Suspension") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreSus += 1;
                        $proposalSuspension = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreSus += 0;
                        $proposalSuspension = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersSuspension, $proposalSuspension);
                    array_push($quizQuestionSuspension, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionSuspension,
                        'answers' => $answersSuspension,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreSus,
                        'speciality' => $quizSuspension->speciality ,
                        'level' => $level,
                        'type' => $quizSuspension->type,
                        'total' => $quizSuspension->number,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($suspensionID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        } 
        if (isset($_POST[ 'quizTransmission' ])) {
            $transmissionID = $_POST[ 'quizTransmission' ];
            $quizTransmission = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($transmissionID)]);                 
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
                    if ($questionsData->speciality == "Transmission") {
                    $answer = $questionsData->answer;
                    if ($proposals[$i] === $answer) {
                        $scoreMissionMission += 1;
                        $proposalTransmission = "Maitrisé";
                        $score += 1;
                        $proposal = "Maitrisé";
                    } else {
                        $scoreMission += 0;
                        $proposalTransmission = "Non maitrisé";
                        $score += 0;
                        $proposal = "Non maitrisé";
                    }
                    array_push($answersTransmission, $proposalTransmission);
                    array_push($quizQuestionTransmission, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);
                    
                    $newResult = [
                        'questions' => $quizQuestionTransmission,
                        'answers' => $answersTransmission,
                        'quiz' => new MongoDB\BSON\ObjectId($transmissionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreMission,
                        'speciality' => $quizTransmission->speciality ,
                        'level' => $level,
                        'type' => $quizTransmission->type,
                        'total' => $quizTransmission->number,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($transmissionID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        } 
        if (isset($_POST[ 'quizTransversale' ])) {
            $transversaleID = $_POST[ 'quizTransversale' ];
            $quizTransversale = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($transversaleID)]);                 
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
                        'total' => $quizTransversale->number,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($transversaleID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                    }
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        $newResult = [
            'questions' => $quizQuestion,
            'answers' => $answers,
            'userAnswers' => $proposals,
            'user' => new MongoDB\BSON\ObjectId($id),
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
                    $assistanceFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Assistance à la Conduite"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                'size' => $assistanceFac->number,
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
                <input class="hidden" type="text" name="quizAssistance" value="<?php echo $assistanceFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $climatisationFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Climatisation"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $climatisationFac->number,
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
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
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
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $directionFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Direction"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $directionFac->number,
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
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizDirection" value="<?php echo $directionFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $electriciteFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Electricité"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $electriciteFac->number,
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
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizElectricite" value="<?php echo $electriciteFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $freinageFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Freinage"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $freinageFac->number,
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
                    Connaissance Freinage
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizFreinage" value="<?php echo $freinageFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $hydrauliqueFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Hydraulique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $hydrauliqueFac->number,
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
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizHydraulique" value="<?php echo $hydrauliqueFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $moteurFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Moteur"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($moteurFac) {
                    $quizzMoteur = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($moteurFac->_id),
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
                                'size' => $moteurFac->number,
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
                    $arrQuizzMoteur = iterator_to_array($quizzMoteur);
                    $arrQuestions = $arrQuizzMoteur[0]['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Connaissance Moteur
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizMoteur" value="<?php echo $moteurFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteur<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteur<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteur<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteur<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $multiplexageFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Multiplexage & Electronique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $multiplexageFac->number,
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
                    Connaissance Multiplexage & Electronique
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizMultiplexage" value="<?php echo $multiplexageFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $pneumatiqueFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Pneumatique"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $pneumatiqueFac->number,
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
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizPneumatique" value="<?php echo $pneumatiqueFac->_id ?>" />
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
                <?php
                    $suspensionFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Suspension"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($suspensionFac) {
                    $quizzSuspension = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($suspensionFac->_id),
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
                                'size' => $suspensionFac->number,
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
                    $arrQuizzSuspension = iterator_to_array($quizzSuspension);
                    $arrQuestions = $arrQuizzSuspension[0]['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Connaissance Suspension
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizSuspension" value="<?php echo $suspensionFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $transmissionFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Transmission"],
                            ['type' => "Factuel"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($transmissionFac) {
                    $quizzTransmission = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($transmissionFac->_id),
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
                                'size' => $transmissionFac->number,
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
                    $arrQuizzTransmission = iterator_to_array($quizzTransmission);
                    $arrQuestions = $arrQuizzTransmission[0]['questions'];
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Connaissance Transmission
                </p>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizTransmission" value="<?php echo $transmissionFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransmission<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransmission<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransmission<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransmission<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
                <?php
                    $transversaleFac = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Transversale"],
                            ['type' => "Factuel"],
                            ["level" => $level],
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
                                'size' => $transversaleFac->number,
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
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <input class="hidden" type="text" name="quizTransversale" value="<?php echo $transversaleFac->_id ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal1 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal2 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        <?php echo $question->proposal3 ?>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal4 ?>" />
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
            </div>
            <button class="btn btn-primary submit" style="margin-top: 100px;" name="valid"
                type="submit">Terminer</button>
        </form>
    </div>
</div>
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