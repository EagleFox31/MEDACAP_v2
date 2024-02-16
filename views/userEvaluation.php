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
    $exams = $academy->exams;
    $tests = $academy->tests;
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
    
    $technician = $users->findOne([
        '$and' => [
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['active' => true],
        ],
    ]);

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

    $exam = $exams->findOne([
        '$and' => [
            ['user' => new MongoDB\BSON\ObjectId($manager)],
            ['vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id)],
            ['active' => true],
        ],
    ]);

    if (isset($_POST['save'])) {
        $questionsTag = $_POST['questionsTag'];
        $hr = $_POST['hr'];
        $mn = $_POST['mn'];
        $sc = $_POST['sc'];
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
                ],
                'type' => 'Declarative',
            ]);
            if ($data) {
                array_push($answers, $proposals[$i]);
            }
        }
        
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
        if (isset($_POST['quizBoiteAuto'])) {
            $boiteAutoID = new MongoDB\BSON\ObjectId($_POST['quizBoiteAuto']);
        }
        if (isset($_POST['quizBoiteMan'])) {
            $boiteManID = new MongoDB\BSON\ObjectId($_POST['quizBoiteMan']);
        }
        if (isset($_POST['quizBoiteVc'])) {
            $boiteVcID = new MongoDB\BSON\ObjectId($_POST['quizBoiteVc']);
        }
        if (isset($_POST['quizClimatisation'])) {
            $climatisationID = new MongoDB\BSON\ObjectId($_POST['quizClimatisation']);
        }
        if (isset($_POST['quizDemi'])) {
            $demiID = new MongoDB\BSON\ObjectId($_POST['quizDemi']);
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
        if (!isset($_POST['quizBoiteAuto'])) {
            $boiteAutoID = null;
        }
        if (!isset($_POST['quizBoiteMan'])) {
            $boiteManID = null;
        }
        if (!isset($_POST['quizBoiteVc'])) {
            $boiteVcID = null;
        }
        if (!isset($_POST['quizClimatisation'])) {
            $climatisationID = null;
        }
        if (!isset($_POST['quizDemi'])) {
            $demiID = null;
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
            $exam->hour = $hr;
            $exam->minute = $mn;
            $exam->second = $sc;
            $exam->total = count($answers);
            $exams->updateOne(
                [ '_id' => new MongoDB\BSON\ObjectId($exam->_id) ],
                [ '$set' => $exam ]
            );
        } else {
            $exam = [
                'questions' => $questionsTags,
                'answers' => $answers,
                'user' => new MongoDB\BSON\ObjectId($manager),
                'vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id),
                'quizAssistance' => $assistanceID,
                'quizArbre' => $arbreID,
                'quizTransfert' => $transfertID,
                'quizBoite' => $boiteID,
                'quizBoiteAuto' => $boiteAutoID,
                'quizBoiteMan' => $boiteManID,
                'quizBoiteVc' => $boiteVcID,
                'quizClimatisation' => $climatisationID,
                'quizDemi' => $demiID,
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
                'hour' => $hr,
                'minute' => $mn,
                'second' => $sc,
                'total' => count($answers),
                'active' => true,
                'created' => date('d-m-y')
            ];
        
            $exams->insertOne($exam);
        }
    }
    
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
                ],
                'type' => 'Declarative',
            ]);
            if ($data) {
                array_push($answers, $proposals[$i]);
            }
        }
        
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
        if (isset($_POST['quizBoiteAuto'])) {
            $boiteAutoID = new MongoDB\BSON\ObjectId($_POST['quizBoiteAuto']);
        }
        if (isset($_POST['quizBoiteMan'])) {
            $boiteManID = new MongoDB\BSON\ObjectId($_POST['quizBoiteMan']);
        }
        if (isset($_POST['quizBoiteVc'])) {
            $boiteVcID = new MongoDB\BSON\ObjectId($_POST['quizBoiteVc']);
        }
        if (isset($_POST['quizClimatisation'])) {
            $climatisationID = new MongoDB\BSON\ObjectId($_POST['quizClimatisation']);
        }
        if (isset($_POST['quizDemi'])) {
            $demiID = new MongoDB\BSON\ObjectId($_POST['quizDemi']);
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
        if (!isset($_POST['quizBoiteAuto'])) {
            $boiteAutoID = null;
        }
        if (!isset($_POST['quizBoiteMan'])) {
            $boiteManID = null;
        }
        if (!isset($_POST['quizBoiteVc'])) {
            $boiteVcID = null;
        }
        if (!isset($_POST['quizClimatisation'])) {
            $climatisationID = null;
        }
        if (!isset($_POST['quizDemi'])) {
            $demiID = null;
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
            $exam->hour = $hr;
            $exam->minute = $mn;
            $exam->second = $sc;
            $exam->total = count($answers);
            $exams->updateOne(
                [ '_id' => new MongoDB\BSON\ObjectId($exam->_id) ],
                [ '$set' => $exam ]
            );
        } else {
            $exam = [
                'questions' => $questionsTags,
                'answers' => $answers,
                'user' => new MongoDB\BSON\ObjectId($manager),
                'vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id),
                'quizAssistance' => $assistanceID,
                'quizArbre' => $arbreID,
                'quizTransfert' => $transfertID,
                'quizBoite' => $boiteID,
                'quizBoiteAuto' => $boiteAutoID,
                'quizBoiteMan' => $boiteManID,
                'quizBoiteVc' => $boiteVcID,
                'quizClimatisation' => $climatisationID,
                'quizDemi' => $demiID,
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
                'hour' => $hr,
                'minute' => $mn,
                'second' => $sc,
                'total' => count($answers),
                'active' => true,
                'created' => date('d-m-y')
            ];
        
            $exams->insertOne($exam);
        }
    }

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
        $scoreBoiA = [];
        $scoreBoiM = [];
        $scoreBoT = [];
        $scoreBoV= [];
        $scoreClim = [];
        $scoreDe = [];
        $scoreDir = [];
        $scoreElec = [];
        $scoreMoD = [];
        $scoreMoEl = [];
        $scoreMoE = [];
        $scoreMoT = [];
        $scoreHyd = [];
        $scoreFrei = [];
        $scoreFreiE = [];
        $scoreFreiH = [];
        $scoreFreiP = [];
        $scoreMulti = [];
        $scorePont = [];
        $scorePneu = [];
        $scoreRe = [];
        $scoreSus = [];
        $scoreSusL = [];
        $scoreSusH = [];
        $scoreSusR = [];
        $scoreSusP = [];
        $scoreTran = [];

        $quizQuestion = [];
        $proposal = [];
        $proposalAssistance = [];
        $proposalArbre = [];
        $proposalBoite = [];
        $proposalBoiteAuto = [];
        $proposalBoiteMan = [];
        $proposalBoiteVc = [];
        $proposalClimatisation = [];
        $proposalDemi = [];
        $proposalDirection = [];
        $proposalElectricite = [];
        $proposalFrei = [];
        $proposalFreinageElec = [];
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
        $proposalSuspensionLame = [];
        $proposalSuspension = [];
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
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Assistance à la Conduite") {
                    if ($proposals[$i] == "1-Assistance à la Conduite-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreAss, "Il sait faire");
                        array_push($proposalAssistance, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalAssistance, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Arbre de Transmission") {
                    if ($proposals[$i] == "1-Arbre de Transmission-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreAr, "Il sait faire");
                        array_push($proposalArbre, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalArbre, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Boite de Transfert") {
                    if ($proposals[$i] == "1-Boite de Transfert-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreBoT, "Il sait faire");
                        array_push($proposalTransfert, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalBoite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Boite de Vitesse") {
                    if ($proposals[$i] == "1-Boite de Vitesse-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreBoi, "Il sait faire");
                        array_push($proposalBoite, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalBoite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
        if (isset($_POST[ 'quizBoiteAuto' ])) {
            $boiteAutoID = $_POST[ 'quizBoiteAuto' ];
            $quizBoiteAuto = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($boiteAutoID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Boite de Vitesse Automatique") {
                    if ($proposals[$i] == "1-Boite de Vitesse-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreBoiA, "Il sait faire");
                        array_push($proposalBoiteAuto, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalBoiteAuto, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizBoiteAuto->questions,
                        'answers' => $proposalBoiteAuto,
                        'quiz' => new MongoDB\BSON\ObjectId($boiteAutoID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreBoi),
                        'speciality' => $quizBoiteAuto->speciality,
                        'level' => $level,
                        'type' => $quizBoiteAuto->type,
                        'typeR' => 'Manager',
                        'total' => $quizBoiteAuto->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizBoiteMan' ])) {
            $boiteManID = $_POST[ 'quizBoiteMan' ];
            $quizBoiteMan = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($boiteManID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Boite de Vitesse Mécanique") {
                    if ($proposals[$i] == "1-Boite de Vitesse-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreBoiM, "Il sait faire");
                        array_push($proposalBoiteMan, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalBoite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizBoiteMan->questions,
                        'answers' => $proposalBoiteMan,
                        'quiz' => new MongoDB\BSON\ObjectId($boiteManID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreBoiM),
                        'speciality' => $quizBoiteMan->speciality,
                        'level' => $level,
                        'type' => $quizBoiteMan->type,
                        'typeR' => 'Manager',
                        'total' => $quizBoiteMan->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizBoiteVc' ])) {
            $boiteVcID = $_POST[ 'quizBoiteVc' ];
            $quizBoiteVc = $quizzes->findOne([
                '$and' => [
                    ['_id' => new MongoDB\BSON\ObjectId($boiteVcID)],
                    ["active" => true]
                ]
            ]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Boite de Vitesse Mécanique") {
                    if ($proposals[$i] == "1-Boite de Vitesse-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreBoiV, "Il sait faire");
                        array_push($proposalBoiteVc, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalBoite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizBoiteVc->questions,
                        'answers' => $proposalBoiteVc,
                        'quiz' => new MongoDB\BSON\ObjectId($boiteVcID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreBoiV),
                        'speciality' => $quizBoiteVc->speciality,
                        'level' => $level,
                        'type' => $quizBoiteVc->type,
                        'typeR' => 'Manager',
                        'total' => $quizBoiteVc->total,
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Climatisation") {
                    if ($proposals[$i] == "1-Climatisation-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreClim, "Il sait faire");
                        array_push($proposalClimatisation, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalClimatisation, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
        if (isset($_POST[ 'quizDemi' ])) {
            $demiID = $_POST[ 'quizDemi' ];
            $quizDemi = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($demiID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Demi Arbre de Roue") {
                    if ($proposals[$i] == "1-Demi Arbre de Roue-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreDir, "Il sait faire");
                        array_push($proposalDemi, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalDemi, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizDemi->questions,
                        'answers' => $proposalDemi,
                        'quiz' => new MongoDB\BSON\ObjectId($demiID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreDir),
                        'speciality' => $quizDemi->speciality,
                        'level' => $level,
                        'type' => $quizDemi->type,
                        'typeR' => 'Manager',
                        'total' => $quizDemi->total,
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Direction") {
                    if ($proposals[$i] == "1-Direction-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreDir, "Il sait faire");
                        array_push($proposalDirection, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalDirection, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Electricité et Electronique") {
                    if ($proposals[$i] == "1-Electricité-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreElec, "Il sait faire");
                        array_push($proposalElectricite, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalElectricite, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
        if (isset($_POST[ 'quizFrei' ])) {
            $freiID = $_POST[ 'quizFrei' ];
            $quizFrei = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($freiID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Freinage") {
                    if ($proposals[$i] == "1-Freinage-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreFrei, "Il sait faire");
                        array_push($proposalFrei, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalFrei, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizFrei->questions,
                        'answers' => $proposalFrei,
                        'quiz' => new MongoDB\BSON\ObjectId($freiID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreFrei),
                        'speciality' => $quizFrei->speciality,
                        'level' => $level,
                        'type' => $quizFrei->type,
                        'typeR' => 'Manager',
                        'total' => $quizFrei->total,
                        'time' => $time,
                        'active' => true,
                        'created' => date("d-m-y")
                    ];
                }
            }
        }
          $insertedResult = $results->insertOne($result);
        }
        if (isset($_POST[ 'quizFreinageElec' ])) {
            $freinageElecID = $_POST[ 'quizFreinageElec' ];
            $quizFreinageElec = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($freinageElecID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Freinage Electromagnétique") {
                    if ($proposals[$i] == "1-Freinage Electromagnétique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreFreiE, "Il sait faire");
                        array_push($proposalFreinageElec, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalFreinageElec, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizFreinageElec->questions,
                        'answers' => $proposalFreinageElec,
                        'quiz' => new MongoDB\BSON\ObjectId($freinageElecID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreFreiE),
                        'speciality' => $quizFreinageElec->speciality,
                        'level' => $level,
                        'type' => $quizFreinageElec->type,
                        'typeR' => 'Manager',
                        'total' => $quizFreinageElec->total,
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Freinage Hydraulique") {
                    if ($proposals[$i] == "1-Freinage Hydraulique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreFreiH, "Il sait faire");
                        array_push($proposalFreinage, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalFreinage, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Freinage Pneumatique") {
                    if ($proposals[$i] == "1-Freinage Pneumatique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreFreiP, "Il sait faire");
                        array_push($proposalFrein, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalFreinage, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizFrein->questions,
                        'answers' => $proposalFrein,
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Hydraulique") {
                    if ($proposals[$i] == "1-Hydraulique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreHyd, "Il sait faire");
                        array_push($proposalHydraulique, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalHydraulique, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Moteur Diesel") {
                    if ($proposals[$i] == "1-Moteur Diesel-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMoD, "Il sait faire");
                        array_push($proposalMoteurDiesel, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMoteurDiesel, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Moteur Electrique") {
                    if ($proposals[$i] == "1-Moteur Electrique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMoEl, "Il sait faire");
                        array_push($proposalMoteurElec, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMoteurElec, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Moteur Essence") {
                    if ($proposals[$i] == "1-Moteur Essence-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMoE, "Il sait faire");
                        array_push($proposalMoteurEssence, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMoteurEssence, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
        if (isset($_POST[ 'quizMoteurThermique' ])) {
            $moteurThermiqueID = $_POST[ 'quizMoteurThermique' ];
            $quizMoteurThermique = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($moteurThermiqueID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Moteur Thermique") {
                    if ($proposals[$i] == "1-Moteur Thermique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMoT, "Il sait faire");
                        array_push($proposalMoteurThermique, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMoteurThermique, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizMoteurThermique->questions,
                        'answers' => $proposalMoteurThermique,
                        'quiz' => new MongoDB\BSON\ObjectId($moteurThermiqueID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreMoT),
                        'speciality' => $quizMoteurThermique->speciality,
                        'level' => $level,
                        'type' => $quizMoteurThermique->type,
                        'typeR' => 'Manager',
                        'total' => $quizMoteurThermique->total,
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Multiplexage") {
                    if ($proposals[$i] == "1-Multiplexage-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreMulti, "Il sait faire");
                        array_push($proposalMultiplexage, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalMultiplexage, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Pont") {
                    if ($proposals[$i] == "1-Pont-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scorePont, "Il sait faire");
                        array_push($proposalPont, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalPont, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Pneumatique") {
                    if ($proposals[$i] == "1-Pneumatique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scorePneu, "Il sait faire");
                        array_push($proposalPneu, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalPneu, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Reducteur") {
                    if ($proposals[$i] == "1-Reducteur-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scorePneu, "Il sait faire");
                        array_push($proposalPneu, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalPneu, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
        if (isset($_POST[ 'quizSuspension' ])) {
            $suspensionID = $_POST[ 'quizSuspension' ];
            $quizSuspension = $quizzes->findOne(['_id' => new MongoDB\BSON\ObjectId($suspensionID)]);
            for ($i = 0; $i < count($proposals); $i++) {
            $questionsData = $questions->findOne([
                '$or' => [
                    ['proposal1' => $proposals[$i]],
                    ['proposal2' => $proposals[$i]],
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Suspension") {
                    if ($proposals[$i] == "1-Suspension à -".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreSus, "Il sait faire");
                        array_push($proposalSuspension, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalSuspension, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
                    $result = [
                        'questions' => $quizSuspension->questions,
                        'answers' => $proposalSuspension,
                        'quiz' => new MongoDB\BSON\ObjectId($suspensionID),
                        'user' => new MongoDB\BSON\ObjectId($id),
                        'manager' => new MongoDB\BSON\ObjectId($manager),
                        'score' => count($scoreSus),
                        'speciality' => $quizSuspension->speciality,
                        'level' => $level,
                        'type' => $quizSuspension->type,
                        'typeR' => 'Manager',
                        'total' => $quizSuspension->total,
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Suspension à Lame") {
                    if ($proposals[$i] == "1-Suspension à Lame-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreSusL, "Il sait faire");
                        array_push($proposalSuspensionLame, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalSuspensionLame, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Suspension Ressort") {
                    if ($proposals[$i] == "1-Suspension Ressort-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreSusR, "Il sait faire");
                        array_push($proposalSuspensionRessort, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalSuspensionRessort, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Suspension Pneumatique") {
                    if ($proposals[$i] == "1-Suspension Pneumatique-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreSusP, "Il sait faire");
                        array_push($proposalSuspensionPneumatique, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalSuspensionPneumatique, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
                    ['proposal3' => $proposals[$i]],
                ],
            ]);
            
            if ($questionsData != null) {
                if ($questionsData->speciality  == "Transversale") {
                    if ($proposals[$i] == "1-Transversale-".$questionsData->level."-".$questionsData->label."-1") {
                        array_push($scoreTran, "Il sait faire");
                        array_push($proposalTransversale, "Oui");
                        array_push($score, "Il sait faire");
                        array_push($proposal, "Oui");
                    } else {
                        array_push($proposalTransversale, "Non");
                        array_push($proposal, "Non");
                    }
                    
                    array_push($quizQuestion, $questionsData->_id);
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
            $managerAnswer = [];
            for ($i = 0; $i < count($proposals); ++$i) {
                $data = $questions->findOne([
                    '$or' => [
                        ['proposal1' => $proposals[$i]],
                        ['proposal2' => $proposals[$i]],
                        ['proposal3' => $proposals[$i]],
                    ],
                    'type' => 'Declarative',
                ]);
                if ($data) {
                    array_push($managerAnswer, $proposals[$i]);
                }
            }
        $newResult = [
            'questions' => $quizQuestion,
            'answers' => $proposal,
            'managerAnswers' => $managerAnswer,
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
<title>Evaluation de <?php echo $user->firstName ?> <?php echo $user->lastName ?> | CFAO MobIlity Academy</title>
<!--end::Title-->

<link href="https://fonts.googleapis.com/css2?famIly=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" />
<link href="../public/css/userQuiz.css" rel="stylesheet" type="text/css" />
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
                <div class="time_left_txt">Durée estimée</div>
                <div class="timer_sec" id="timer_sec"></div>
                </div>
                <div style="margin-top: -45px; margin-left: 0px">
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
            </center>
            <div class="heading" style="margin-top: 10px;">
              <h1 class="heading__text">Evaluation de <?php echo $user->firstName ?> <?php echo $user->lastName ?></h1>
            </div>
        
            <!-- Quiz section -->
            <div class="quiz" style="margin-bottom: 40px;">
                <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                    Vous devez repondre à toutes les questions avant
                    de pouvoir valider le questionnaire.
                </p>
            <input class="hidden" type="text" name="timer" id="clock" />
            <input class="hidden" type="text" name="hr" id="hr" />
            <input class="hidden" type="text" name="mn" id="mn" />
            <input class="hidden" type="text" name="sc" id="sc" />
            <div class="quiz-form__quiz">
                            <?php if (!isset($exam)) { ?>
                <?php
                $k = 1;
                $existTest = $tests->findOne([
                    '$and' => [
                        ['user' => new MongoDB\BSON\ObjectId($id)],
                        ['vehicle' => new MongoDB\BSON\ObjectId($vehicule->_id),],
                        ['brand' => $brand],
                        ['subBrand' => $technician->subBrand,],
                    ],
                ]);
                if ($existTest) {
                    $arrQuizzes = $existTest['quizzes'];
                }
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerAssistance<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerArbre<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransfert<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransfert<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransfert<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoite<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $boiteAutoDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Boite de Vitesse Automatique'],
                            ['type' => 'Declaratif'],
                            ['level' => $level],
                            ['active' => true],
                        ],
                    ]);
                    if ($boiteAutoDecla) {
                        $arrQuestions = $boiteAutoDecla['questions']; ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                <input class="hidden" type="text" name="quizboiteAuto" value="<?php echo $boiteAutoDecla->_id; ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label; ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1; ?>" onclick="checkedRadio()"
                        name="answerboiteAuto<?php echo $i + 1; ?>" value="<?php echo $question->proposal1; ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1; ?>" onclick="checkedRadio()"
                        name="answerboiteAuto<?php echo $i + 1; ?>" value="<?php echo $question->proposal2; ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sais pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1; ?>" onclick="checkedRadio()"
                        name="answerboiteAuto<?php echo $i + 1; ?>" value="<?php echo $question->proposal3; ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php
                    } ?>
                <?php
                    } ?>
                <?php
                } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); ++$j) {
                    $boiteManDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => 'Boite de Vitesse Mécanique'],
                            ['type' => 'Declaratif'],
                            ['level' => $level],
                            ['active' => true],
                        ],
                    ]);
                    if ($boiteManDecla) {
                        $arrQuestions = $boiteManDecla['questions']; ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); ++$i) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ['active' => true],
                            ],
                        ]); ?>
                <input class="hidden" type="text" name="quizboiteMan" value="<?php echo $boiteManDecla->_id; ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label; ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1; ?>" onclick="checkedRadio()"
                        name="answerboiteMan<?php echo $i + 1; ?>" value="<?php echo $question->proposal1; ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1; ?>" onclick="checkedRadio()"
                        name="answerboiteMan<?php echo $i + 1; ?>" value="<?php echo $question->proposal2; ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sais pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1; ?>" onclick="checkedRadio()"
                        name="answerboiteMan<?php echo $i + 1; ?>" value="<?php echo $question->proposal3; ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php
                    } ?>
                <?php
                    } ?>
                <?php
                } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $boiteVcDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Boite de Vitesse à Vitesse Continue"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
                if ($boiteVcDecla) {
                $arrQuestions = $boiteVcDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizBoiteVc" value="<?php echo $boiteVcDecla->_id ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoiteVc<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoiteVc<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerBoiteVc<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerClimatisation<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $demiDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Demi Arbre de Roue"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($demiDecla) {
                    $arrQuestions = $demiDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizDemi" value="<?php echo $demiDecla->_id ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDemi<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDemi<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDemi<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerDirection<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $electriciteDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Electricité et Electronique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($electriciteDecla) {
                    $arrQuestions = $electriciteDecla['questions'];
                ?>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerElectricite<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $freiDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Freinage"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($freiDecla) {
                    $arrQuestions = $freiDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizFrei" value="<?php echo $freiDecla->_id ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrei<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrei<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrei<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $freinageElecDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Freinage Electromagnétique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($freinageElecDecla) {
                    $arrQuestions = $freinageElecDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizFreinageElec" value="<?php echo $freinageElecDecla->_id ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinageElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinageElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinageElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $freinageDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Freinage Hydraulique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($freinageDecla) {
                    $arrQuestions = $freinageDecla['questions'];
                ?>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFreinage<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerFrein<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerHydraulique<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurDiesel<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurDiesel<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurDiesel<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurElec<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurEssence<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurEssence<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurEssence<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $moteurThermiqueDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Moteur Thermique"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($moteurThermiqueDecla) {
                    $arrQuestions = $moteurThermiqueDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizMoteurThermique"
                    value="<?php echo $moteurThermiqueDecla->_id ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurThermique<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurThermique<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMoteurThermique<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerMultiplexage<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPont<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerPneu<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerRed<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerRed<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerRed<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
                <?php
                for ($j = 0; $j < count($arrQuizzes); $j++) {
                    $suspensionDecla = $quizzes->findOne([
                        '$and' => [
                            ['_id' => new MongoDB\BSON\ObjectId($arrQuizzes[$j])],
                            ['speciality' => "Suspension"],
                            ['type' => "Declaratif"],
                            ["level" => $level],
                            ["active" => true]
                        ]
                    ]);
    
                    if ($suspensionDecla) {
                    $arrQuestions = $suspensionDecla['questions'];
                ?>
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '$and' => [
                                ['_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])],
                                ["active" => true]
                            ]
                        ]);
                ?>
                <input class="hidden" type="text" name="quizSuspension"
                    value="<?php echo $suspensionDecla->_id ?>" />
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspension<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionLame<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionLame<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionLame<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionRessort<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionRessort<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionRessort<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionPneumatique<?php echo $i + 1 ?>"
                        value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionPneumatique<?php echo $i + 1 ?>"
                        value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerSuspensionPneumatique<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
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
                <input class="hidden" type="text" name="questionsTag[]" value="<?php echo $question->_id; ?>" />
                <p class="quiz-form__question fw-bold" id="question"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $k++ ?> - <?php echo $question->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal1 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il sait faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal2 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il ne sait pas faire
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal<?php echo $i + 1 ?>" onclick="checkedRadio()"
                        name="answerTransversale<?php echo $i + 1 ?>" value="<?php echo $question->proposal3 ?>" />
                    <span class="design"></span>
                    <span class="text">
                        Il n'a jamais fait
                    </span>
                </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
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
                    if($exam["quizBoiteAuto"] != null) {
                ?>
                <input class="hidden" type="text" name="quizBoiteAuto"
                    value="<?php echo $exam['quizBoiteAuto']; ?>" />
                <?php } ?>
                <?php
                    if($exam["quizBoiteMan"] != null) {
                ?>
                <input class="hidden" type="text" name="quizBoiteMan"
                    value="<?php echo $exam['quizBoiteMan']; ?>" />
                <?php } ?>
                <?php
                    if($exam['quizClimatisation'] != null) {
                ?>
                <input class="hidden" type="text" name="quizClimatisation"
                    value="<?php echo $exam['quizClimatisation']; ?>" />
                <?php } ?>
                <?php
                    if($exam['quizDemi'] != null) {
                ?>
                <input class="hidden" type="text" name="quizDemi"
                    value="<?php echo $exam['quizDemi']; ?>" />
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
                    Il sait faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal2; ?>" />
                <span class="design"></span>
                <span class="text">
                    Il ne sait pas faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal3; ?>" />
                <span class="design"></span>
                <span class="text">
                    Il n'a jamais fait
                </span>
            </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
            <?php } elseif($exam['answers'][$i] == $question->proposal2) {
            ?>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal1; ?>"/>
                <span class="design"></span>
                <span class="text">
                    Il sait faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal2; ?>" checked/>
                <span class="design"></span>
                <span class="text">
                    Il ne sait pas faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal3; ?>" />
                <span class="design"></span>
                <span class="text">
                    Il n'a jamais fait
                </span>
            </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
            <?php } elseif($exam['answers'][$i] == $question->proposal3) {
            ?>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal1; ?>"/>
                <span class="design"></span>
                <span class="text">
                    Il sait faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal2; ?>" />
                <span class="design"></span>
                <span class="text">
                    Il ne sait pas faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal3; ?>" checked/>
                <span class="design"></span>
                <span class="text">
                    Il n'a jamais fait
                </span>
            </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
            <?php } elseif($exam['answers'][$i] != $question->proposal1 || $exam['answers'][$i] != $question->proposal2 || $exam['answers'][$i] != $question->proposal3) { ?>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal1; ?>"/>
                <span class="design"></span>
                <span class="text">
                    Il sait faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal2; ?>" />
                <span class="design"></span>
                <span class="text">
                    Il ne sait pas faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal3; ?>"/>
                <span class="design"></span>
                <span class="text">
                    Il n'a jamais fait
                </span>
            </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
            <?php } } } else { ?>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal1; ?>"/>
                <span class="design"></span>
                <span class="text">
                    Il sait faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal2; ?>" />
                <span class="design"></span>
                <span class="text">
                    Il ne sait pas faire
                </span>
            </label>
            <label class="quiz-form__ans">
                <input type="radio" onclick="checkedRadio()"
                    name="answer<?php echo $i + 1; ?>"
                    value="<?php echo $question->proposal3; ?>"/>
                <span class="design"></span>
                <span class="text">
                    Il n'a jamais fait
                </span>
            </label>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" name="save">Valider</button>
                </div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <div style="margin-top: 70px; align-items: center; justify-content: space-evenly; display: flex;">
                <button type="submit" class="btn btn-secondary btn-lg" name="back">Retour</button>
                <button type="submit" id="button" class="btn btn-primary btn-lg" name="valid">Terminer</button>
            </div>
        </div>
        </form>
    </div>
</div>
<script>
let hr = <?php echo $exam['hour'] ?? "03"; ?>;
let mn = <?php echo $exam['minute'] ?? "00"; ?>;
let sc = <?php echo $exam['second'] ?? "00"; ?> ; 
// const startingMinutes = document
//     .getElementById("timer_sec")
//     .getAttribute("value");
let time = Number(hr) * 3600 + Number(mn) *60 + Number(sc);

const countDown = document.getElementById("timer_sec");

setInterval(updateCountDown, 1000);

function updateCountDown() {
    let hour = Math.floor(time / 3600);
    let minutes = Math.floor((time / 60) - (hour * 60));
    let seconds = time - ((hour * 3600) + (minutes * 60));
    time--;
    if (time > 0) {
        hour = hour < 10 ? "0" + hour : hour;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        countDown.innerHTML = `${hour}:${minutes}:${seconds}`;
        document.getElementById("clock").value = `${hour}:${minutes}:${seconds}`;
        document.getElementById("hr").value = `${hour}`;
        document.getElementById("mn").value = `${minutes}`;
        document.getElementById("sc").value = `${seconds}`;
    } else if (time < 0) {
        clearInterval(updateCountDown);
        hour = "00";
        minutes = "00";
        seconds = "00";
        countDown.innerHTML = `${hour}:${minutes}:${seconds}`;
        document.getElementById("clock").value = `${hour}:${minutes}:${seconds}`;
        document.getElementById("hr").value = `${hour}`;
        document.getElementById("mn").value = `${minutes}`;
        document.getElementById("sc").value = `${seconds}`;
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
const cal = (num * ques.length) - <?php echo $exam['total'] ?? 0 ?>;
score.innerHTML = `${cal}`;
if (ques.length == <?php echo $exam['total'] ?? 0 ?>) {
    submitBtn.classList.remove("disabled");
}

// function checkedRadio() {
//     const radios = document.querySelectorAll("input[type='radio']:checked");
//     radios.forEach(async (rad, i) => {
//         radio = i + 1;
//     })
//     if (ques.length == radio) {
//         submitBtn.classList.remove("disabled");
//     }
//     const cal = (num * ques.length) - radio;
//     score.innerHTML = `${cal}`;
// }
</script>
<?php
include_once 'partials/footer.php'
        ?>
<?php }
        ?>