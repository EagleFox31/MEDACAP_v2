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

    $id = $_GET["id"];
    $level = $_GET["level"];
    
    if (isset( $_POST[ 'valid' ] )) {
        if (isset($_POST[ 'quizAssistance' ])) {
            $assistanceID = $_POST[ 'quizAssistance' ];
            $quizAssistance = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($assistanceID)]);
        }
        if (isset($_POST[ 'quizClimatisation' ])) {
            $climatisationID = $_POST[ 'quizClimatisation' ];
            $quizClimatisation = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($climatisationID)]);
        }
        if (isset($_POST[ 'quizDirection' ])) {
            $directionID = $_POST[ 'quizDirection' ];
            $quizDirection = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($directionID)]);
        }
        if (isset($_POST[ 'quizElectricite' ])) {
            $electriciteID = $_POST[ 'quizElectricite' ];
            $quizElectricite = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($electriciteID)]);
        }
        if (isset($_POST[ 'quizFreinage' ])) {
            $freinageID = $_POST[ 'quizFreinage' ];
            $quizFreinage = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($freinageID)]);
        }
        if (isset($_POST[ 'quizHydraulique' ])) {
            $hydrauliqueID = $_POST[ 'quizHydraulique' ];
            $quizHydraulique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($hydrauliqueID)]);
        }
        if (isset($_POST[ 'quizMoteur' ])) {
            $moteurID = $_POST[ 'quizMoteur' ];
            $quizMoteur = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($moteurID)]);
        }
        if (isset($_POST[ 'quizMultiplexage' ])) {
            $multiplexageID = $_POST[ 'quizMultiplexage' ];
            $quizMultiplexage = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($multiplexageID)]);
        }
        if (isset($_POST[ 'quizPneumatique' ])) {
            $pneumatiqueID = $_POST[ 'quizPneumatique' ];
            $quizPneumatique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($pneumatiqueID)]);
        }
        if (isset($_POST[ 'quizSuspension' ])) {
            $suspensionID = $_POST[ 'quizSuspension' ];
            $quizSuspension = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($suspensionID)]);
        }
        if (isset($_POST[ 'quizTransmission' ])) {
            $transmissionID = $_POST[ 'quizTransmission' ];
            $quizTransmission = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($transmissionID)]);
        }
        if (isset($_POST[ 'quizTransversale' ])) {
            $transversaleID = $_POST[ 'quizTransversale' ];
            $quizTransversale = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($transversaleID)]);
        }
        $time = $_POST[ 'timer' ];
        $body = $_POST; // assuming POST method, you can replace it with $_GET if it's a GET method
        $proposals = array_values($body);
        
        $scoreAss = [];
        $scoreClim = [];
        $scoreDir = [];
        $scoreElec = [];
        $scoreMo = [];
        $scoreHyd = [];
        $scoreFrei = [];
        $scoreMulti = [];
        $scorePneu = [];
        $scoreSus = [];
        $scoreTran = [];
        $scoreMission = [];

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

        for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Assistance à la Conduite") {
                    if ($proposals[$i] == "1-Assistance à la Conduite-".$questionsData->label."-1") {
                        array_push($scoreAss, "Je connais");
                        array_push($proposalAssistance, "Oui");
                    } else {
                        array_push($proposalAssistance, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizAssistance->questions,
                        'answers' => $proposalAssistance,
                        'quiz' => new MongoDB\BSON\ObjectId($assistanceID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreAss),
                        'speciality' => $quizAssistance->speciality,
                        'level' => $quizAssistance->level,
                        'type' => $quizAssistance->type,
                        'total' => $quizAssistance->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($assistanceID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Climatisation") {
                    if ($proposals[$i] == "1-Climatisation-".$questionsData->label."-1") {
                        array_push($scoreClim, "Je connais");
                        array_push($proposalClimatisation, "Oui");
                    } else {
                        array_push($proposalClimatisation, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizClimatisation->questions,
                        'answers' => $proposalClimatisation,
                        'quiz' => new MongoDB\BSON\ObjectId($climatisationID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreClim),
                        'speciality' => $quizClimatisation->speciality,
                        'level' => $quizClimatisation->level,
                        'type' => $quizClimatisation->type,
                        'total' => $quizClimatisation->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($climatisationID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Direction") {
                    if ($proposals[$i] == "1-Direction-".$questionsData->label."-1") {
                        array_push($scoreDir, "Je connais");
                        array_push($proposalDirection, "Oui");
                    } else {
                        array_push($proposalDirection, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizDirection->questions,
                        'answers' => $proposalDirection,
                        'quiz' => new MongoDB\BSON\ObjectId($directionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreDir),
                        'speciality' => $quizDirection->speciality,
                        'level' => $quizDirection->level,
                        'type' => $quizDirection->type,
                        'total' => $quizDirection->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($directionID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                }  elseif ($questionsData->speciality  == "Electricité") {
                    if ($proposals[$i] == "1-Electricité-".$questionsData->label."-1") {
                        array_push($scoreElec, "Je connais");
                        array_push($proposalElectricite, "Oui");
                    } else {
                        array_push($proposalElectricite, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizElectricite->questions,
                        'answers' => $proposalElectricite,
                        'quiz' => new MongoDB\BSON\ObjectId($electriciteID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreElec),
                        'speciality' => $quizElectricite->speciality,
                        'level' => $quizElectricite->level,
                        'type' => $quizElectricite->type,
                        'total' => $quizElectricite->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($electriciteID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Freinage") {
                    if ($proposals[$i] == "1-Freinage-".$questionsData->label."-1") {
                        array_push($scoreFrei, "Je connais");
                        array_push($proposalFreinage, "Oui");
                    } else {
                        array_push($proposalFreinage, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizFreinage->questions,
                        'answers' => $proposalFreinage,
                        'quiz' => new MongoDB\BSON\ObjectId($freinageID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreFrei),
                        'speciality' => $quizFreinage->speciality,
                        'level' => $quizFreinage->level,
                        'type' => $quizFreinage->type,
                        'total' => $quizFreinage->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($freinageID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Hydraulique") {
                    if ($proposals[$i] == "1-Hydraulique-".$questionsData->label."-1") {
                        array_push($scoreHyd, "Je connais");
                        array_push($proposalHydraulique, "Oui");
                    } else {
                        array_push($proposalHydraulique, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizHydraulique->questions,
                        'answers' => $proposalHydraulique,
                        'quiz' => new MongoDB\BSON\ObjectId($hydrauliqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreHyd),
                        'speciality' => $quizHydraulique->speciality,
                        'level' => $quizHydraulique->level,
                        'type' => $quizHydraulique->type,
                        'total' => $quizHydraulique->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($hydrauliqueID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Moteur") {
                    if ($proposals[$i] == "1-Moteur-".$questionsData->label."-1") {
                        array_push($scoreMo, "Je connais");
                        array_push($proposalMoteur, "Oui");
                    } else {
                        array_push($proposalMoteur, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizMoteur->questions,
                        'answers' => $proposalMoteur,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreMo),
                        'speciality' => $quizMoteur->speciality,
                        'level' => $quizMoteur->level,
                        'type' => $quizMoteur->type,
                        'total' => $quizMoteur->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($moteurID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Multiplexage & Electronique") {
                    if ($proposals[$i] == "1-Multiplexage & Electronique-".$questionsData->label."-1") {
                        array_push($scoreMulti, "Je connais");
                        array_push($proposalMultiplexage, "Oui");
                    } else {
                        array_push($proposalMultiplexage, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizMultiplexage->questions,
                        'answers' => $proposalMultiplexage,
                        'quiz' => new MongoDB\BSON\ObjectId($multiplexageID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreMulti),
                        'speciality' => $quizMultiplexage->speciality,
                        'level' => $quizMultiplexage->level,
                        'type' => $quizMultiplexage->type,
                        'total' => $quizMultiplexage->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($multiplexageID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Pneumatique") {
                    if ($proposals[$i] == "1-Pneumatique-".$questionsData->label."-1") {
                        array_push($scorePneu, "Je connais");
                        array_push($proposalPneu, "Oui");
                    } else {
                        array_push($proposalPneu, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizPneumatique->questions,
                        'answers' => $proposalPneu,
                        'quiz' => new MongoDB\BSON\ObjectId($pneumatiqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scorePneu),
                        'speciality' => $quizPneumatique->speciality,
                        'level' => $quizPneumatique->level,
                        'type' => $quizPneumatique->type,
                        'total' => $quizPneumatique->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($pneumatiqueID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Suspension") {
                    if ($proposals[$i] == "1-Suspension-".$questionsData->label."-1") {
                        array_push($scoreSus, "Je connais");
                        array_push($proposalSuspension, "Oui");
                    } else {
                        array_push($proposalSuspension, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizSuspension->questions,
                        'answers' => $proposalSuspension,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreSus),
                        'speciality' => $quizSuspension->speciality,
                        'level' => $quizSuspension->level,
                        'type' => $quizSuspension->type,
                        'total' => $quizSuspension->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($suspensionID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Transmission") {
                    if ($proposals[$i] == "1-Transmission-".$questionsData->label."-1") {
                        array_push($scoreMission, "Je connais");
                        array_push($proposalTransmission, "Oui");
                    } else {
                        array_push($proposalTransmission, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizTransmission->questions,
                        'answers' => $proposalTransmission,
                        'quiz' => new MongoDB\BSON\ObjectId($transmissionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreMission),
                        'speciality' => $quizTransmission->speciality,
                        'level' => $quizTransmission->level,
                        'type' => $quizTransmission->type,
                        'total' => $quizTransmission->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
                    $allocationData = $allocations->findOne([
                        '$and' => [
                            ['user' => new MongoDB\BSON\ObjectId($id)],
                            ['quiz' => new MongoDB\BSON\ObjectId($transmissionID)],
                        ],
                    ]);
    
                    $allocationData->active = false;
                    $updatedAllocation = $allocations->updateOne(['_id' => $allocationData->_id], ['$set' => $allocationData]);
                } elseif ($questionsData->speciality  == "Transversale") {
                    if ($proposals[$i] == "1-Transversale-".$questionsData->label."-1") {
                        array_push($scoreTran, "Je connais");
                        array_push($proposalTransversale, "Oui");
                    } else {
                        array_push($proposalTransversale, "Non");
                    }
                    
                    $result = [
                        'questions' => $quizTransversale->questions,
                        'answers' => $proposalTransversale,
                        'quiz' => new MongoDB\BSON\ObjectId($transversaleID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => count($scoreTran),
                        'speciality' => $quizTransversale->speciality,
                        'level' => $quizTransversale->level,
                        'type' => $quizTransversale->type,
                        'total' => $quizTransversale->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
    
                    $insertedResult = $results->insertOne($result);
    
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
        header('Location: ./dashboard.php');
    }
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Tâches Professionnelles | CFAO Mobility Academy</title>
<!--end::Title-->

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" />
<link href="../public/css/userQuiz.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="heading">
        <h1 class="heading__text">Tâches professionnelles</h1>
    </div>

    <!-- Quiz section -->
    <div class="quiz">
        <center style="margin-bottom: 50px;">
            <div class="timer">
                <div class="time_left_txt">Questions Restantes</div>
                <div class="timer_sec" id="num" value="1">
                </div>
            </div>
        </center>
        <center style="margin-bottom: 50px;">
            <div class="timer">
                <div class="time_left_txt">Durée Questionnaire</div>
                <div class="timer_sec" name="time" id="timer_sec" value="0">
                </div>
            </div>
        </center>
        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
            Vous devez repondre à toutes les questions avant
            de pouvoir valider le questionnaire.
        </p>
        <form class="quiz-form" method="POST">
            <input class="hidden" type="text" name="timer" id="clock" />
            <div class="quiz-form__quiz">
                <?php
                    $assistanceDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Assistance à la Conduite"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
                if ($assistanceDecla) {
                $arrQuestions = $assistanceDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Assistance à la conduite
                </p>
                <input class="hidden" type="text" name="quizAssistance" value="<?php echo $assistanceDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $climatisationDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Climatisation"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($climatisationDecla) {
                    $arrQuestions = $climatisationDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Climatisation
                </p>
                <input class="hidden" type="text" name="quizClimatisation"
                    value="<?php echo $climatisationDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $directionDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Direction"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($directionDecla) {
                    $arrQuestions = $directionDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Direction
                </p>
                <input class="hidden" type="text" name="quizDirection" value="<?php echo $directionDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $electriciteDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Electricité"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($electriciteDecla) {
                    $arrQuestions = $electriciteDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Electricité
                </p>
                <input class="hidden" type="text" name="quizElectricite" value="<?php echo $electriciteDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $freinageDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Freinage"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($freinageDecla) {
                    $arrQuestions = $freinageDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Freinage
                </p>
                <input class="hidden" type="text" name="quizFreinage" value="<?php echo $freinageDecla->_id ?>" />
                <input class="hidden" type="text" name="quizElectricite" value="<?php echo $electriciteDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $hydrauliqueDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Hydraulique"],
                            ['type' => "Declaraif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($hydrauliqueDecla) {
                    $arrQuestions = $hydrauliqueDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Hydraulique
                </p>
                <input class="hidden" type="text" name="quizHydraulique" value="<?php echo $hydrauliqueDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $moteurDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Moteur"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($moteurDecla) {
                    $arrQuestions = $moteurDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Moteur
                </p>
                <input class="hidden" type="text" name="quizMoteur" value="<?php echo $moteurDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteur<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteur<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $multiplexageDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Multiplexage & Electronique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($multiplexageDecla) {
                    $arrQuestions = $multiplexageDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Multiplexage & Electronique
                </p>
                <input class="hidden" type="text" name="quizMultiplexage"
                    value="<?php echo $multiplexageDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $pneumatiqueDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Pneumatique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($pneumatiqueDecla) {
                    $arrQuestions = $pneumatiqueDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Pneumatique
                </p>
                <input class="hidden" type="text" name="quizPneumatique" value="<?php echo $pneumatiqueDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $suspensionDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Suspension"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($suspensionDecla) {
                    $arrQuestions = $suspensionDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Suspension
                </p>
                <input class="hidden" type="text" name="quizSuspension" value="<?php echo $suspensionDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $transmissionDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Transmission"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($transmissionDecla) {
                    $arrQuestions = $transmissionDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Transmission
                </p>
                <input class="hidden" type="text" name="quizTransmission"
                    value="<?php echo $transmissionDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransmission<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransmission<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
                <?php } ?>
                <?php } ?>
                <?php
                    $transversaleDecla = $quizzes->findOne([
                        '$and' => [
                            ['users' => new MongoDB\BSON\ObjectId($id)],
                            ['speciality' => "Transversale"],
                            ['type' => "Declatuel"],
                            ["level" => $level],
                        ]
                    ]);
    
                    if ($transversaleDecla) {
                    $arrQuestions = $transversaleDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 60px; font-size: 30px;">
                    Système Transversale
                </p>
                <input class="hidden" type="text" name="quizTransversale"
                    value="<?php echo $transversaleDecla->_id ?>" />
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<?php echo $question->image ?? "" ?>"> <br>
                </div>
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i + 1 ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Je connais pas
                    </span>
                </label>
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
<?php } ?>