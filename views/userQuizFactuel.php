<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ./index.php');
    exit();
} else {
    require_once '../vendor/autoload.php';

    // Create connection
    $conn = new MongoDB\Client('mongodb://localhost:27017');

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;
    $questions = $academy->questions;
    $results = $academy->results;
    $exams = $academy->exams;
    $allocations = $academy->allocations;

    $id = $_GET['id'];
    $level = $_GET['level'];
    $vehicle = $_GET['vehicle'];
    $brand = $_GET['brand'];
    $questionsTag = [];

    $vehicule = $vehicles->findOne([
        '$and' => [
            ['users' => new MongoDB\BSON\ObjectId($id)],
            ['label' => $vehicle],
            ['brand' => $brand],
            ['type' => 'Factuel'],
            ['level' => $level],
            ['active' => true],
        ],
    ]);
    $exam = $exams->findOne([
        '$and' => [
            ['user' => new MongoDB\BSON\ObjectId($id)],
            ['vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id)],
            ['active' => true],
        ],
    ]);
    $cal = round(100 / $vehicule['total'], 0);
    $number = round($cal, 0);
    $arrQuizzes = iterator_to_array($vehicule->quizzes);
    
    if (isset($_POST['save'])) {
        $questionsTag = $_POST['questionsTag'];
        $time = $_POST['time'];
        $questionsTags = [];
        $body = $_POST;
        // assuming POST method, you can replace it with $_GET if it's a GET method
        $proposals = array_values($body);
        $answers = [];
        for ($i = 0; $i < count($questionsTag); ++$i) {
            array_push($questionsTags, new MongoDB\BSON\ObjectId($questionsTag[$i]));
        }
        for ($i = 0; $i < count($proposals); ++$i) {
            $data = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                    ['proposal4' => $proposals[$i]],
                ],
                'type' => 'Factuelle',
            ]);
            if ($data) {
                array_push($answers, $proposals[$i]);
            }
        }
        $vehicle = $vehicles->findOne([
            '$and' => [
                ['label' => $vehicle],
                ['level' => $level],
                ['type' => 'Factuel'],
                ['active' => true],
            ],
        ]);
        
        if (isset($_POST['quizAssistance'])) {
            $assistanceID = new MongoDB\BSON\ObjectId($_POST['quizAssistance']);
        }
        if (isset($_POST['quizArbre'])) {
            $arbreID = new MongoDB\BSON\ObjectId($_POST['quizArbre']);
        }
        if (isset($_POST['quizTransfert'])) {
            $transfertID = new MongoDB\BSON\ObjectId($_POST['quizTransfert']);
        }
        if (isset($_POST['quizBoite'])) {
            $boiteID = new MongoDB\BSON\ObjectId($_POST['quizBoite']);
        }
        if (isset($_POST['quizClimatisation'])) {
            $climatisationID = new MongoDB\BSON\ObjectId($_POST['quizClimatisation']);
        }
        if (isset($_POST['quizDirection'])) {
            $directionID = new MongoDB\BSON\ObjectId($_POST['quizDirection']);
        }
        if (isset($_POST['quizElectricite'])) {
            $electriciteID = new MongoDB\BSON\ObjectId($_POST['quizElectricite']);
        }
        if (isset($_POST['quizFrei'])) {
            $freiID = new MongoDB\BSON\ObjectId($_POST['quizFrei']);
        }
        if (isset($_POST['quizFreinageElec'])) {
            $freinageElecID = new MongoDB\BSON\ObjectId($_POST['quizFreinageElec']);
        }
        if (isset($_POST['quizFreinage'])) {
            $freinageID = new MongoDB\BSON\ObjectId($_POST['quizFreinage']);
        }
        if (isset($_POST['quizFrein'])) {
            $freinID = new MongoDB\BSON\ObjectId($_POST['quizFrein']);
        }
        if (isset($_POST['quizHydraulique'])) {
            $hydrauliqueID = new MongoDB\BSON\ObjectId($_POST['quizHydraulique']);
        }
        if (isset($_POST['quizMoteurDiesel'])) {
            $moteurDieselID = new MongoDB\BSON\ObjectId($_POST['quizMoteurDiesel']);
        }
        if (isset($_POST['quizMoteurElec'])) {
            $moteurElecID = new MongoDB\BSON\ObjectId($_POST['quizMoteurElec']);
        }
        if (isset($_POST['quizMoteurEssence'])) {
            $moteurEssenceID = new MongoDB\BSON\ObjectId($_POST['quizMoteurEssence']);
        }
        if (isset($_POST['quizMoteur'])) {
            $moteurID = new MongoDB\BSON\ObjectId($_POST['quizMoteur']);
        }
        if (isset($_POST['quizMultiplexage'])) {
            $multiplexageID = new MongoDB\BSON\ObjectId($_POST['quizMultiplexage']);
        }
        if (isset($_POST['quizPont'])) {
            $pontID = new MongoDB\BSON\ObjectId($_POST['quizPont']);
        }
        if (isset($_POST['quizPneumatique'])) {
            $pneumatiqueID = new MongoDB\BSON\ObjectId($_POST['quizPneumatique']);
        }
        if (isset($_POST['quizReducteur'])) {
            $reducteurID = new MongoDB\BSON\ObjectId($_POST['quizReducteur']);
        }
        if (isset($_POST['quizSuspension'])) {
            $suspensionID = new MongoDB\BSON\ObjectId($_POST['quizSuspension']);
        }
        if (isset($_POST['quizSuspensionLame'])) {
            $suspensionLameID = new MongoDB\BSON\ObjectId($_POST['quizSuspensionLame']);
        }
        if (isset($_POST['quizSuspensionRessort'])) {
            $suspensionRessortID = new MongoDB\BSON\ObjectId($_POST['quizSuspensionRessort']);
        }
        if (isset($_POST['quizSuspensionPneumatique'])) {
            $suspensionPneumatiqueID = new MongoDB\BSON\ObjectId($_POST['quizSuspensionPneumatique']);
        }
        if (isset($_POST['quizTransversale'])) {
            $transversaleID = new MongoDB\BSON\ObjectId($_POST['quizTransversale']);
        }
        if (!isset($_POST['quizAssistance'])) {
            $assistanceID = null;
        }
        if (!isset($_POST['quizArbre'])) {
            $arbreID = null;
        }
        if (!isset($_POST['quizTransfert'])) {
            $transfertID = null;
        }
        if (!isset($_POST['quizBoite'])) {
            $boiteID = null;
        }
        if (!isset($_POST['quizClimatisation'])) {
            $climatisationID = null;
        }
        if (!isset($_POST['quizDirection'])) {
            $directionID = null;
        }
        if (!isset($_POST['quizFrei'])) {
            $freiID = null;
        }
        if (!isset($_POST['quizFreinageElec'])) {
            $freinageElecID = null;
        }
        if (!isset($_POST['quizFreinage'])) {
            $freinageID = null;
        }
        if (!isset($_POST['quizFrein'])) {
            $freinID = null;
        }
        if (!isset($_POST['quizHydraulique'])) {
            $hydrauliqueID = null;
        }
        if (!isset($_POST['quizElectricite'])) {
            $electriciteID = null;
        }
        if (!isset($_POST['quizMoteurDiesel'])) {
            $moteurDieselID = null;
        }
        if (!isset($_POST['quizMoteurElec'])) {
            $moteurElecID = null;
        }
        if (!isset($_POST['quizMoteurEssence'])) {
            $moteurEssenceID = null;
        }
        if (!isset($_POST['quizMoteur'])) {
            $moteurID = null;
        }
        if (!isset($_POST['quizMultiplexage'])) {
            $multiplexageID = null;
        }
        if (!isset($_POST['quizPont'])) {
            $pontID = null;
        }
        if (!isset($_POST['quizPneumatique'])) {
            $pneumatiqueID = null;
        }
        if (!isset($_POST['quizReducteur'])) {
            $reducteurID = null;
        }
        if (!isset($_POST['quizSuspension'])) {
            $suspensionID = null;
        }
        if (!isset($_POST['quizSuspensionLame'])) {
            $suspensionLameID = null;
        }
        if (!isset($_POST['quizSuspensionRessort'])) {
            $suspensionRessortID = null;
        }
        if (!isset($_POST['quizSuspensionPneumatique'])) {
            $suspensionPneumatiqueID = null;
        }
        if (!isset($_POST['quizTransversale'])) {
            $transversaleID = null;
        }

        if($exam) {
            $exam->answers = $answers;
            $exam->time = $time;
            $exams->updateOne(
                [ '_id' => new MongoDB\BSON\ObjectId($exam->_id) ],
                [ '$set' => $exam ]
            );
        } else {
            $exam = [
                'questions' => $questionsTags,
                'answers' => $answers,
                'user' => new MongoDB\BSON\ObjectId($id),
                'vehicle' => new MongoDB\BSON\ObjectId($vehicle->_id),
                'quizAssistance' => $assistanceID,
                'quizArbre' => $arbreID,
                'quizTransfert' => $transfertID,
                'quizBoite' => $boiteID,
                'quizClimatisation' => $climatisationID,
                'quizDirection' => $directionID,
                'quizElectricite' => $electriciteID,
                'quizFrei' => $freiID,
                'quizFreinageElec' => $freinageElecID,
                'quizFreinage' => $freinageID,
                'quizFrein' => $freinID,
                'quizHydraulique' => $hydrauliqueID,
                'quizMoteurDiesel' => $moteurDieselID,
                'quizMoteurElec' => $moteurElecID,
                'quizMoteurEssence' => $moteurEssenceID,
                'quizMoteur' => $moteurID,
                'quizMultiplexage' => $multiplexageID,
                'quizPont' => $pontID,
                'quizPneumatique' => $pneumatiqueID,
                'quizReducteur' => $reducteurID,
                'quizSuspension' => $suspensionID,
                'quizSuspensionLame' => $suspensionLameID,
                'quizSuspensionRessort' => $suspensionRessortID,
                'quizSuspensionPneumatique' => $suspensionPneumatiqueID,
                'quizTransversale' => $transversaleID,
                'time' => $time,
                'active' => true,
                'created' => date('d-m-y')
            ];
        
            $exams->insertOne($exam);
        }
    }

    if (isset($_POST['valid'])) {
        $time = $_POST['timer'];
        $questionsTag = $_POST['questionsTag'];
        $body = $_POST;
        // assuming POST method, you can replace it with $_GET if it's a GET method
        $proposals = array_values($body);
        $userAnswer = [];
        for ($i = 0; $i < count($proposals); ++$i) {
            $data = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                    ['proposal4' => $proposals[$i]],
                ],
                'type' => 'Factuelle',
            ]);
            if ($data) {
                array_push($userAnswer, $proposals[$i]);
            }
        }

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
        $scoreMoT = 0;
        $scoreHyd = 0;
        $scoreFrei = 0;
        $scoreFreiE = 0;
        $scoreFreiH = 0;
        $scoreFreiP = 0;
        $scoreMulti = 0;
        $scorePont = 0;
        $scorePneu = 0;
        $scoreRe = 0;
        $scoreSus = 0;
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
        $quizQuestionFrei = [];
        $quizQuestionFreinage = [];
        $quizQuestionFrein = [];
        $quizQuestionFreinElec = [];
        $quizQuestionHydraulique = [];
        $quizQuestionMoteurDiesel = [];
        $quizQuestionMoteurElec = [];
        $quizQuestionMoteurEssence = [];
        $quizQuestionMoteurThermique = [];
        $quizQuestionMultiplexage = [];
        $quizQuestionPont = [];
        $quizQuestionPneu = [];
        $quizQuestionReducteur = [];
        $quizQuestionSuspension = [];
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
        $answersFrei = [];
        $answersFreinElec = [];
        $answersFreinage = [];
        $answersFrein = [];
        $answersHydraulique = [];
        $answersMoteurDiesel = [];
        $answersMoteurElec = [];
        $answersMoteurEssence = [];
        $answersMoteurThermique = [];
        $answersMultiplexage = [];
        $answersPont = [];
        $answersPneu = [];
        $answersReducteur = [];
        $answersSuspension = [];
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
        $proposalFrei = [];
        $proposalFreinElec = [];
        $proposalFreinage = [];
        $proposalFrein = [];
        $proposalHydraulique = [];
        $proposalMoteurDiesel = [];
        $proposalMoteurElec = [];
        $proposalMoteurEssence = [];
        $proposalMoteurThermique = [];
        $proposalMultiplexage = [];
        $proposalPont = [];
        $proposalPneu = [];
        $proposalReducteur = [];
        $proposalSuspension = [];
        $proposalSuspensionLame = [];
        $proposalSuspensionRessort = [];
        $proposalSuspensionPneumatique = [];
        $proposalTransversale = [];

        if (isset($_POST['quizAssistance'])) {
            $assistanceID = $_POST['quizAssistance'];
            $quizAssistance = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($assistanceID)],
                    ['active' => true],
                ],
            ]);

            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Assistance à la Conduite') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreAss;
                        $proposalAssistance = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreAss += 0;
                        $proposalAssistance = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersAssistance, $proposalAssistance);
                    array_push($quizQuestionAssistance, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                        'questions' => $quizQuestionAssistance,
                        'answers' => $answersAssistance,
                        'quiz' => new MongoDB\BSON\ObjectId($assistanceID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreAss,
                        'speciality' => $quizAssistance->speciality,
                        'level' => $level,
                        'type' => $quizAssistance->type,
                        'total' => count($quizQuestionAssistance),
                        'time' => $time,
                        'active' => true,
                        'created' => date('d-m-y'),
                    ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizArbre'])) {
            $arbreID = $_POST['quizArbre'];
            $quizArbre = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($arbreID)],
                    ['active' => true],
                ],
            ]);

            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);
                if ($questionsData->speciality == 'Arbre de Transmission') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreAr;
                        $proposalArbre = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreAr += 0;
                        $proposalArbre = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersArbre, $proposalArbre);
                    array_push($quizQuestionArbre, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                        'questions' => $quizQuestionArbre,
                        'answers' => $answersArbre,
                        'quiz' => new MongoDB\BSON\ObjectId($arbreID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreAr,
                        'speciality' => $quizArbre->speciality,
                        'level' => $level,
                        'type' => $quizArbre->type,
                        'total' => count($quizQuestionArbre),
                        'time' => $time,
                        'active' => true,
                        'created' => date('d-m-y'),
                    ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizTransfert'])) {
            $transfertID = $_POST['quizTransfert'];
            $quizTransfert = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($transfertID)],
                    ['active' => true],
                ],
            ]);

            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Boite de Transfert') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreBoT;
                        $proposalTransfert = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreBoT += 0;
                        $proposalTransfert = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersTransfert, $proposalTransfert);
                    array_push($quizQuestionTransfert, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                        'questions' => $quizQuestionTransfert,
                        'answers' => $answersTransfert,
                        'quiz' => new MongoDB\BSON\ObjectId($transfertID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreBoT,
                        'speciality' => $quizTransfert->speciality,
                        'level' => $level,
                        'type' => $quizTransfert->type,
                        'total' => count($quizQuestionTransfert),
                        'time' => $time,
                        'active' => true,
                        'created' => date('d-m-y'),
                    ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizBoite'])) {
            $boiteID = $_POST['quizBoite'];
            $quizBoite = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($boiteID)],
                    ['active' => true],
                ],
            ]);

            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Boite de Vitesse') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreBoi;
                        $proposalBoite = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreBoi += 0;
                        $proposalBoite = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersBoite, $proposalBoite);
                    array_push($quizQuestionBoite, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                        'questions' => $quizQuestionBoite,
                        'answers' => $answersBoite,
                        'quiz' => new MongoDB\BSON\ObjectId($boiteID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'score' => $scoreBoi,
                        'speciality' => $quizBoite->speciality,
                        'level' => $level,
                        'type' => $quizBoite->type,
                        'total' => count($quizQuestionBoite),
                        'time' => $time,
                        'active' => true,
                        'created' => date('d-m-y'),
                    ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizClimatisation'])) {
            $climatisationID = $_POST['quizClimatisation'];
            $quizClimatisation = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($climatisationID)],
                    ['active' => true],
                ],
            ]);

            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Climatisation') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreClim;
                        $proposalClimatisation = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreClim += 0;
                        $proposalClimatisation = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                        'speciality' => $quizClimatisation->speciality,
                        'level' => $level,
                        'type' => $quizClimatisation->type,
                        'total' => count($quizQuestionClimatisation),
                        'time' => $time,
                        'active' => true,
                        'created' => date('d-m-y'),
                    ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizDirection'])) {
            $directionID = $_POST['quizDirection'];
            $quizDirection = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($directionID)],
                    ['active' => true],
                ],
            ]);

            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Direction') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreDir;
                        $proposalDirection = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreDir += 0;
                        $proposalDirection = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizDirection->speciality,
                    'level' => $level,
                    'type' => $quizDirection->type,
                    'total' => count($quizQuestionDirection),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizElectricite'])) {
            $electriciteID = $_POST['quizElectricite'];
            $quizElectricite = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($electriciteID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Electricité et Electronique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreElec;
                        $proposalElectricite = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreElec += 0;
                        $proposalElectricite = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizFrei'])) {
            $freiID = $_POST['quizFrei'];
            $quizFrei = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($freiID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Freinage') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreFrei;
                        $proposalFrei = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreFrei += 0;
                        $proposalFrei = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersFrei, $proposalFrei);
                    array_push($quizQuestionFrei, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                    'questions' => $quizQuestionFrei,
                    'answers' => $answersFrei,
                    'quiz' => new MongoDB\BSON\ObjectId($freiID),
                    'user' => new MongoDB\BSON\ObjectId($id),
                    'score' => $scoreFrei,
                    'speciality' => $quizFrei->speciality,
                    'level' => $level,
                    'type' => $quizFrei->type,
                    'total' => count($quizQuestionFrei),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizFreinageElec'])) {
            $freinageElecID = $_POST['quizFreinageElec'];
            $quizFreinageElec = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($freinageElecID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Freinage Electromagnétique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreFreiE;
                        $proposalFreinageElec = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreFreiH += 0;
                        $proposalFreinageElec = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersFreinageElec, $proposalFreinageElec);
                    array_push($quizQuestionFreinageElec, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                    'questions' => $quizQuestionFreinageElec,
                    'answers' => $answersFreinageElec,
                    'quiz' => new MongoDB\BSON\ObjectId($FreinageElecID),
                    'user' => new MongoDB\BSON\ObjectId($id),
                    'score' => $scoreFreiE,
                    'speciality' => $quizFreinageElec->speciality,
                    'level' => $level,
                    'type' => $quizFreinageElec->type,
                    'total' => count($quizQuestionFreinageElec),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizFreinage'])) {
            $freinageID = $_POST['quizFreinage'];
            $quizFreinage = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($freinageID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Freinage Hydraulique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreFreiH;
                        $proposalFreinage = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreFreiH += 0;
                        $proposalFreinage = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizFreinage->speciality,
                    'level' => $level,
                    'type' => $quizFreinage->type,
                    'total' => count($quizQuestionFreinage),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizFrein'])) {
            $freinID = $_POST['quizFrein'];
            $quizFrein = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($freinID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Freinage Pneumatique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreFreiP;
                        $proposalFrein = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreFreiP += 0;
                        $proposalFrein = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizFrein->speciality,
                    'level' => $level,
                    'type' => $quizFrein->type,
                    'total' => count($quizQuestionFrein),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizHydraulique'])) {
            $hydrauliqueID = $_POST['quizHydraulique'];
            $quizHydraulique = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($hydrauliqueID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Hydraulique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreHyd;
                        $proposalHydraulique = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreHyd += 0;
                        $proposalHydraulique = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizHydraulique->speciality,
                    'level' => $level,
                    'type' => $quizHydraulique->type,
                    'total' => count($quizQuestionHydraulique),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizMoteurDiesel'])) {
            $moteurDieselID = $_POST['quizMoteurDiesel'];
            $quizMoteurDiesel = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($moteurDieselID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Moteur Diesel') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreMoD;
                        $proposalMoteurDiesel = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreMoD += 0;
                        $proposalMoteurDiesel = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersMoteurDiesel, $proposalMoteurDiesel);
                    array_push($quizQuestionMoteurDiesel, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                    'questions' => $quizQuestionMoteurDiesel,
                    'answers' => $answersMoteurDiesel,
                    'quiz' => new MongoDB\BSON\ObjectId($moteurDieselID),
                    'user' => new MongoDB\BSON\ObjectId($id),
                    'score' => $scoreMoD,
                    'speciality' => $quizMoteurDiesel->speciality,
                    'level' => $level,
                    'type' => $quizMoteurDiesel->type,
                    'total' => count($quizQuestionMoteurDiesel),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizMoteurElec'])) {
            $moteurElecID = $_POST['quizMoteurElec'];
            $quizMoteurElec = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($moteurElecID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Moteur Electrique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreMoEl;
                        $proposalMoteurElec = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreMoEl += 0;
                        $proposalMoteurElec = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizMoteurElec->speciality,
                    'level' => $level,
                    'type' => $quizMoteurElec->type,
                    'total' => count($quizQuestionMoteurElec),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizMoteurEssence'])) {
            $moteurEssenceID = $_POST['quizMoteurEssence'];
            $quizMoteurEssence = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($moteurEssenceID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Moteur Essence') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreMoE;
                        $proposalMoteurEssence = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreMoE += 0;
                        $proposalMoteurEssence = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizMoteurEssence->speciality,
                    'level' => $level,
                    'type' => $quizMoteurEssence->type,
                    'total' => count($quizQuestionMoteurEssence),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizMoteur'])) {
            $moteurThermiqueID = $_POST['quizMoteur'];
            $quizMoteurThermique = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($moteurThermiqueID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Moteur Thermique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreMoT;
                        $proposalMoteurThermique = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreMoE += 0;
                        $proposalMoteurThermique = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
                    }
                    array_push($answersMoteurThermique, $proposalMoteurThermique);
                    array_push($quizQuestionMoteurThermique, $questionsData->_id);
                    array_push($answers, $proposal);
                    array_push($quizQuestion, $questionsData->_id);

                    $newResult = [
                    'questions' => $quizQuestionMoteurThermique,
                    'answers' => $answersMoteurThermique,
                    'quiz' => new MongoDB\BSON\ObjectId($moteurThermiqueID),
                    'user' => new MongoDB\BSON\ObjectId($id),
                    'score' => $scoreMoT,
                    'speciality' => $quizMoteurThermique->speciality,
                    'level' => $level,
                    'type' => $quizMoteurThermique->type,
                    'total' => count($quizQuestionMoteurThermique),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizMultiplexage'])) {
            $multiplexageID = $_POST['quizMultiplexage'];
            $quizMultiplexage = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($multiplexageID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Multiplexage') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreMulti;
                        $proposalMultiplexage = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreMulti += 0;
                        $proposalMultiplexage = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizMultiplexage->speciality,
                    'level' => $level,
                    'type' => $quizMultiplexage->type,
                    'total' => count($quizQuestionMultiplexage),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizPont'])) {
            $pontID = $_POST['quizPont'];
            $quizPont = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($pontID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Pont') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scorePont;
                        $proposalPont = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scorePont += 0;
                        $proposalPont = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizPont->speciality,
                    'level' => $level,
                    'type' => $quizPont->type,
                    'total' => count($quizQuestionPont),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizPneumatique'])) {
            $pneumatiqueID = $_POST['quizPneumatique'];
            $quizPneumatique = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($pneumatiqueID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Pneumatique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scorePneu;
                        $proposalPneu = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scorePneu += 0;
                        $proposalPneu = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizPneumatique->speciality,
                    'level' => $level,
                    'type' => $quizPneumatique->type,
                    'total' => count($quizQuestionPneu),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizReducteur'])) {
            $reducteurID = $_POST['quizReducteur'];
            $quizReducteur = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($reducteurID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Reducteur') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreRed;
                        $proposalReducteur = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreRed += 0;
                        $proposalReducteur = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizReducteur->speciality,
                    'level' => $level,
                    'type' => $quizReducteur->type,
                    'total' => count($quizQuestionReducteur),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizSuspension'])) {
            $suspensionID = $_POST['quizSuspension'];
            $quizSuspension = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($suspensionID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Suspension') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreSus;
                        $proposalSuspension = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreSus += 0;
                        $proposalSuspension = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizSuspension->speciality,
                    'level' => $level,
                    'type' => $quizSuspension->type,
                    'total' => count($quizQuestionSuspension),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizSuspensionLame'])) {
            $suspensionLameID = $_POST['quizSuspensionLame'];
            $quizSuspensionLame = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($suspensionLameID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Suspension à Lame') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreSusL;
                        $proposalSuspensionLame = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreSusL += 0;
                        $proposalSuspensionLame = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizSuspensionLame->speciality,
                    'level' => $level,
                    'type' => $quizSuspensionLame->type,
                    'total' => count($quizQuestionSuspensionLame),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizSuspensionRessort'])) {
            $suspensionRessortID = $_POST['quizSuspensionRessort'];
            $quizSuspensionRessort = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($suspensionRessortID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Suspension Ressort') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreSusR;
                        $proposalSuspensionRessort = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreSusR += 0;
                        $proposalSuspensionRessort = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizSuspensionRessort->speciality,
                    'level' => $level,
                    'type' => $quizSuspensionRessort->type,
                    'total' => count($quizQuestionSuspensionRessort),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizSuspensionPneumatique'])) {
            $suspensionPneumatiqueID = $_POST['quizSuspensionPneumatique'];
            $quizSuspensionPneumatique = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($suspensionPneumatiqueID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Suspension Pneumatique') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreSusT;
                        $proposalSuspensionPneumatique = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreSusT += 0;
                        $proposalSuspensionPneumatique = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizSuspensionPneumatique->speciality,
                    'level' => $level,
                    'type' => $quizSuspensionPneumatique->type,
                    'total' => count($quizQuestionSuspensionPneumatique),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
                }
            }
            $insertedResult = $results->insertOne($newResult);
        }
        if (isset($_POST['quizTransversale'])) {
            $transversaleID = $_POST['quizTransversale'];
            $quizTransversale = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($transversaleID)],
                    ['active' => true],
                ],
            ]);
            for ($i = 0; $i < count($questionsTag); ++$i) {
                $questionsData = $questions->findOne([
                    '$and' => [
                        ['_id' => new MongoDB\BSON\ObjectId($questionsTag[$i])],
                        ['active' => true],
                    ],
                ]);

                if ($questionsData->speciality == 'Transversale') {
                    $answer = $questionsData->answer;
                    if ($userAnswer[$i] === $answer) {
                        ++$scoreTran;
                        $proposalTransversale = 'Maitrisé';
                        ++$score;
                        $proposal = 'Maitrisé';
                    } else {
                        $scoreTran += 0;
                        $proposalTransversale = 'Non maitrisé';
                        $score += 0;
                        $proposal = 'Non maitrisé';
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
                    'speciality' => $quizTransversale->speciality,
                    'level' => $level,
                    'type' => $quizTransversale->type,
                    'total' => count($quizQuestionTransversale),
                    'time' => $time,
                    'active' => true,
                    'created' => date('d-m-y'),
                ];
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
            'userAnswers' => $userAnswer,
            'user' => new MongoDB\BSON\ObjectId($id),
            'vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id),
            'score' => $score,
            'level' => $level,
            'type' => 'Factuel',
            'typeR' => 'Technicien',
            'total' => count($quizQuestion),
            'time' => $time,
            'active' => true,
            'created' => date('d-m-y'),
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
    } ?>
<?php
include_once 'partials/header.php'; ?>
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
                <form class="quiz-form" method="POST">
                        <center class="center" style="margin-top: -100px;">
                            <div class="timer" style="margin-right: 400px;">
                                <div class="time_left_txt">Questions Restantes</div>
                                <div class="timer_sec" id="num" value="1">
                                </div>
                            </div>
                            <div class="timer" style="margin-top: -45px; margin-left: 400px">
                                <div class="time_left_txt">Durée(heure et minute)</div>
                                <div class="timer_sec" id="timer_sec" value="<?php echo $exam['time'] ?? "180"; ?>">
                                </div>
                            </div>
                            <div style="margin-top: -45px; margin-left: 0px">
                                <button type="submit" class="btn btn-secondary btn-lg" name="save">Enregistrer</button>
                            </div>
                        </center>
                        <div class="heading" style="margin-top: 10px;">
                            <h1 class="heading__text">Questionnaire à choix multiple</h1>
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
                        <input class="hidden" type="text" name="timer" id="clock" />
                        <input class="hidden" type="text" name="time" id="clock1" />
                        <div class="quiz-form__quiz" style="">
                        <?php if (!isset($exam)) { ?>
                        <?php
                 $k = 1;
                 $a = 1;
    for ($j = 0; $j < count($arrQuizzes); ++$j) {
        $assistanceFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Assistance à la Conduite'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
            $arrQuestions = $arrQuizzAssistance[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizAssistance"
                                value="<?php echo $assistanceFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerAssistance<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
        } ?>
                            <?php
    } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $arbreFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Arbre de Transmission'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzArbre[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizArbre" value="<?php echo $arbreFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1; ?>" value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1; ?>" value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1; ?>" value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerArbre<?php echo $i + 1; ?>" value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $transfertFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Boite de Transfert'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzTransfert[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizTransfert"
                                value="<?php echo $transfertFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransfert<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransfert<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransfert<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransfert<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $boiteFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Boite de Vitesse'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzBoite[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizBoite" value="<?php echo $boiteFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerBoite<?php echo $i + 1; ?>" value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerBoite<?php echo $i + 1; ?>" value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerBoite<?php echo $i + 1; ?>" value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerBoite<?php echo $i + 1; ?>" value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $climatisationFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Climatisation'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzClimatisation[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizClimatisation"
                                value="<?php echo $climatisationFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerClimatisation<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $directionFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Direction'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzDirection[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizDirection"
                                value="<?php echo $directionFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerDirection<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $electriciteFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Electricité et Electronique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzElectricite[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizElectricite"
                                value="<?php echo $electriciteFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerElectricite<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $freiFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Freinage'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
                    ]);

                    if ($freiFac) {
                        $quizzFrei = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($freiFac->_id),
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
                        $arrQuizzFrei = iterator_to_array($quizzFrei);
                        $arrQuestions = $arrQuizzFrei[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizFrei"
                                value="<?php echo $freiFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrei<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrei<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrei<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrei<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $freinageElecFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Freinage Electromagnétique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
                    ]);

                    if ($freinageElecFac) {
                        $quizzfreinageElec = $quizzes->aggregate([
                        [
                            '$match' => [
                                '_id' => new MongoDB\BSON\ObjectId($freinageElecFac->_id),
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
                        $arrQuizzfreinageElec = iterator_to_array($quizzfreinageElec);
                        $arrQuestions = $arrQuizzfreinageElec[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizfreinageElec"
                                value="<?php echo $freinageElecFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerfreinageElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerfreinageElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerfreinageElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerfreinageElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $freinageFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Freinage Hydraulique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzFreinage[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizFreinage"
                                value="<?php echo $freinageFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFreinage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $freinFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Freinage Pneumatique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzFrein[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizFrein" value="<?php echo $freinFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1; ?>" value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1; ?>" value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1; ?>" value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerFrein<?php echo $i + 1; ?>" value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $hydrauliqueFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Hydraulique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzHydraulique[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizHydraulique"
                                value="<?php echo $hydrauliqueFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerHydraulique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $moteurDieselFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Moteur Diesel'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuestions = $arrQuizzMoteurDiesel[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizMoteurDiesel"
                                value="<?php echo $moteurDieselFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurDiesel<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                            for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                $moteurElecFac = $quizzes->findOne([
                                '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                                ['speciality' => 'Moteur Electrique'],
                                ['type' => 'Factuel'],
                                ['level' => $level],
                                ['active' => true],
                                ],
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
                                    $arrQuestions = $arrQuizzMoteurElec[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizMoteurElec"
                                value="<?php echo $moteurElecFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurElec<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                } ?>
                            <?php
                            } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $moteurEssenceFac = $quizzes->findOne([
                                        '$and' => [
                                        ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                                        ['speciality' => 'Moteur Essence'],
                                        ['type' => 'Factuel'],
                                        ['level' => $level],
                                        ['active' => true],
                                        ],
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
                                        $arrQuestions = $arrQuizzMoteurEssence[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizMoteurEssence"
                                value="<?php echo $moteurEssenceFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteurEssence<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $moteurFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Moteur Thermique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                        $arrQuizzMoteur = iterator_to_array($quizzMoteur);
                        $arrQuestions = $arrQuizzMoteur[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizMoteur"
                                value="<?php echo $moteurFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMoteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                    } ?>
                            <?php
                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $multiplexageFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Multiplexage'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzMultiplexage[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizMultiplexage"
                                value="<?php echo $multiplexageFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerMultiplexage<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $pontFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Pont'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzPont[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizPont" value="<?php echo $pontFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1; ?>" value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1; ?>" value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1; ?>" value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPont<?php echo $i + 1; ?>" value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $pneumatiqueFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Pneumatique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzPneumatique[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizPneumatique"
                                value="<?php echo $pneumatiqueFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1; ?>" value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1; ?>" value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1; ?>" value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerPneu<?php echo $i + 1; ?>" value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $reducteurFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Reducteur'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzreducteur[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizReducteur"
                                value="<?php echo $reducteurFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerReducteur<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $suspensionFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Suspension'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuizzSuspension = iterator_to_array($quizzSuspension);
                                        $arrQuestions = $arrQuizzSuspension[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizSuspension"
                                value="<?php echo $suspensionFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspension<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspension<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspension<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspension<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $suspensionLameFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Suspension à Lame'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzSuspensionLame[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizSuspensionLame"
                                value="<?php echo $suspensionLameFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionLame<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $suspensionRessortFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Suspension Ressort'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzSuspensionRessort[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizSuspensionRessort"
                                value="<?php echo $suspensionRessortFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionRessort<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                    } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $suspensionPneumatiqueFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Suspension Pneumatique'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzSuspensionPneumatique[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizSuspensionPneumatique"
                                value="<?php echo $suspensionPneumatiqueFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionPneumatique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionPneumatique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionPneumatique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerSuspensionPneumatique<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                              } ?>
                            <?php
                                    } ?>
                            <?php
                                } ?>
                            <?php
                                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                                    $transversaleFac = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Transversale'],
                            ['type' => 'Factuel'],
                            ['level' => $level],
                            ['active' => true],
                        ],
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
                                        $arrQuestions = $arrQuizzTransversale[0]['questions']; ?>
                            <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                            <input class="hidden" type="text" name="quizTransversale"
                                value="<?php echo $transversaleFac->_id; ?>" />
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $k++; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answerTransversale<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php
                             } ?>
                            <?php
                                } ?>
                            <?php
                                } ?>
                            <?php
                                } elseif (isset($exam)) {
                            for ($i = 0; $i < count($exam['questions']); ++$i) {
                                $question = $questions->findone([
                            '$and' => [
                                        ['_id' => new MongoDB\BSON\ObjectId($exam['questions'][$i])],
                                        ['active' => true],
                                    ],
                                ]);
                        ?>
                        <?php
                            if($exam['quizAssistance'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizAssistance"
                            value="<?php echo $exam['quizAssistance']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizArbre'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizArbre"
                            value="<?php echo $exam['quizArbre']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizTransfert'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizTransfert"
                            value="<?php echo $exam['quizTransfert']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam["quizBoite"] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizBoite"
                            value="<?php echo $exam['quizBoite']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizClimatisation'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizClimatisation"
                            value="<?php echo $exam['quizClimatisation']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizDirection'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizDirection"
                            value="<?php echo $exam['quizDirection']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizElectricite'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizElectricite"
                            value="<?php echo $exam['quizElectricite']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizFrei'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizFrei"
                            value="<?php echo $exam['quizFrei']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizFreinageElec'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizfreinageElec"
                            value="<?php echo $exam['quizFreinageElec']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizFreinage'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizFreinage"
                            value="<?php echo $exam['quizFreinage']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizFrein'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizFrein"
                         value="<?php echo $exam['quizFrein']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizHydraulique'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizHydraulique"
                            value="<?php echo $exam['quizHydraulique']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizMoteurDiesel'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizMoteurDiesel"
                            value="<?php echo $exam['quizMoteurDiesel']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizMoteurElec'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizMoteurElec"
                            value="<?php echo $exam['quizMoteurElec']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizMoteurEssence'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizMoteurEssence"
                            value="<?php echo $exam['quizMoteurEssence']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizMoteur'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizMoteur"
                            value="<?php echo $exam['quizMoteur']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizMultiplexage'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizMultiplexage"
                            value="<?php echo $exam['quizMultiplexage']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizPont'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizPont" 
                        value="<?php echo $exam['quizPont']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizPneumatique'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizPneumatique"
                            value="<?php echo $exam['quizPneumatique']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizReducteur'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizReducteur"
                            value="<?php echo $exam['quizReducteur']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizSuspension'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizSuspension"
                            value="<?php echo $exam['quizSuspension']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizSuspensionLame'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizSuspensionLame"
                            value="<?php echo $exam['quizSuspensionLame']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizSuspensionRessort'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizSuspensionRessort"
                            value="<?php echo $exam['quizSuspensionRessort']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizSuspensionPneumatique'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizSuspensionPneumatique"
                            value="<?php echo $exam['quizSuspensionPneumatique']; ?>" />
                        <?php } ?>
                        <?php
                            if($exam['quizTransversale'] != null) {
                        ?>
                        <input class="hidden" type="text" name="quizTransversale"
                            value="<?php echo $exam['quizTransversale']; ?>" />
                        <?php } ?>
                            <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                            <p class="quiz-form__question fw-bold" id="question"
                                style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                                <?php echo $i + 1; ?> - <?php echo $question->label; ?>
                            </p>
                            <div style="margin-top: 50px; display: flex; justify-content: center;">
                                <img id="image" alt="" src="../public/files/<?php echo $question->image ?? ''; ?>"> <br>
                            </div>
                            <?php
                                if(isset($exam['answers'][$i])) {
                            ?>
                            <?php
                                if($exam['questions'][$i] == $question->_id) {
                            ?>
                            <?php
                                if($exam['answers'][$i] == $question->proposal1) {
                            ?>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" checked/>
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php } elseif($exam['answers'][$i] == $question->proposal2) {
                            ?>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" checked/>
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php } elseif($exam['answers'][$i] == $question->proposal3) {
                            ?>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" checked/>
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php } elseif($exam['answers'][$i] == $question->proposal4) {
                            ?>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" checked/>
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php } elseif($exam['answers'][$i] != $question->proposal1 || $exam['answers'][$i] != $question->proposal2 || $exam['answers'][$i] != $question->proposal3 || $exam['answers'][$i] != $question->proposal4) { ?>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php } } } else { ?>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal1; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal1; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal2; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal2; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal3; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal3; ?>
                                </span>
                            </label>
                            <label class="quiz-form__ans">
                                <input type="radio" onclick="checkedRadio()"
                                    name="answer<?php echo $i + 1; ?>"
                                    value="<?php echo $question->proposal4; ?>" />
                                <span class="design"></span>
                                <span class="text">
                                    <?php echo $question->proposal4; ?>
                                </span>
                            </label>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <div style="margin-top: 70px; align-items: center; justify-content: space-evenly; display: flex;">
                                <button type="submit" id="button" class="btn btn-primary btn-lg" name="valid">Terminer</button>
                            </div>
                        </div>
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
const startingMinutes = document
    .getElementById("timer_sec")
    .getAttribute("value");
let time = startingMinutes * 60;

const countDown = document.getElementById("timer_sec");

setInterval(updateCountDown, 1000);

function updateCountDown() {
    let minutes = Math.floor(time / 60);
    let seconds = time % 60;
    time--;
    if (time > 0) {
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        countDown.innerHTML = `${minutes}:${seconds}`;
        document.getElementById("clock").value = `${minutes}:${seconds}`;
        document.getElementById("clock1").value = `${minutes}`;
    } else if (time < 0) {
        clearInterval(updateCountDown);
        minutes = "00";
        seconds = "00";
        countDown.innerHTML = `${minutes}:${seconds}`;
        document.getElementById("clock").value = `${minutes}:${seconds}`;
        document.getElementById("clock1").value = `${minutes}`;
        // document.getElementById(".submit").addEventListener("click")
    }
}

// var timer = setInterval(countTimer, 1000);
// var totalSecond = 0;

// function countTimer() {
//     totalSecond++;

//     var hour = Math.floor(totalSecond / 3600);
//     var minutes = Math.floor((totalSecond - hour * 3600) / 60);
//     var seconds = totalSecond - (hour * 3600 + minutes * 60);

//     if (minutes <= 9 && hour > 9) {
//         document.getElementById("timer_sec").innerHTML = hour + ":" + "0" + minutes;
//         document.getElementById("clock").value = hour + ":" + "0" + minutes + ":" + seconds;
//     }
//     if (hour <= 9 && minutes > 9) {
//         document.getElementById("timer_sec").innerHTML = "0" + hour + ":" + minutes;
//         document.getElementById("clock").value = "0" + hour + ":" + minutes + ":" + seconds;
//     }
//     if (hour <= 9 && minutes <= 9) {
//         document.getElementById("timer_sec").innerHTML = "0" + hour + ":" + "0" + minutes;
//         document.getElementById("clock").value = "0" + hour + ":" + "0" + minutes + ":" + seconds;
//     }
//     if (hour == 9 && minutes == 9) {
//         document.getElementById("timer_sec").innerHTML = "0" + hour + ":" + "0" + minutes;
//         document.getElementById("clock").value = "0" + hour + ":" + "0" + minutes + ":" + seconds;
//     }
//     Et
// }

let radio;
const ques = document.querySelectorAll("#question");
const submitBtn = document.querySelector("#button")
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
include_once 'partials/footer.php'; ?>
<?php
}
        ?>