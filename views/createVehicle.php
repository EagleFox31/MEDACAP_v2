<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {

if ( isset( $_POST[ 'submit' ] ) ) {

    require_once '../vendor/autoload.php';

    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $vehicles = $academy->vehicles;
    $quizzes = $academy->quizzes;
    $allocations = $academy->allocations;

    $label = $_POST[ 'label' ];
    $brand = $_POST[ 'brand' ];
    $type = $_POST[ 'type' ];
    $level = $_POST[ 'level' ];
    $quizBus = [];
    $quizCam = [];
    $quizCamTrck = [];
    $quizCamO = [];
    $quizCha = [];
    $quizChaBt = [];
    $quizEng = [];
    $quizVl = [];
    $quizVls = [];
    $userArr = [];
    $userArrR = [];
    $userArrD = [];

    $exist = $vehicles->findOne([
        '$and' => [
            [ 'label' => $label ],
            [ 'brand' => $brand ],
            [ 'level' => $level ],
            [ 'type' => $type ],
        ],
    ]);

    $arbre = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Arbre de Transmission" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $assistance = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Assistance à la Conduite" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boite = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteAuto = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse Automatique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteMeca = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse Mécanique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteVaCo = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse à Variation Continue" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $transfert = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Transfert" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $climatisation = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Climatisation" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $demi = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Demi Arbre de Roue" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $direction = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Direction" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $electricite = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Electricité et Electronique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinage = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageElec = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Electromagnétique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageHyd = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Hydraulique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinagePneu = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Pneumatique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $hydraulique = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Hydraulique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurDiesel = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Diesel" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurElec = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Electrique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurEssence = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Essence" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurThermique = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Thermique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $multiplexage = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Multiplexage" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $pont = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Pont" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $pneu = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Pneumatique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $reducteur = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Reducteur" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspension = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionLame = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension à Lame" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionRessort = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension Ressort" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionPneu = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension Pneumatique" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $transversale = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Transversale" ],
            [ 'type' => $type ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    
    $user = $users->find([
        '$and' => [
            [ 'vehicle' => $label ],
            [ 'brand' => $brand ],
            [ 'active' => true ],
        ],
    ])->toArray();
    
    for ($i = 0; $i < count($user); $i++) {
        if ($user[$i]->profile == 'Technicien'  || $user[$i]->profile == 'Manager (à évaluer)') {
            array_push($userArr, $user[$i]->_id);
            if ($user[$i]->level == 'Senior (Réparation)' || $user[$i]->level == 'Expert (Diagnostic)') {
                array_push($userArrR, $user[$i]->_id);
            }
            if ($user[$i]->level == 'Expert (Diagnostic)') {
                array_push($userArrD, $user[$i]->_id);
            }
        }
    }
    
    if ($arbre) {
        array_push($quizBus, $arbre->_id);
        array_push($quizCam, $arbre->_id);
        array_push($quizCamTrck, $arbre->_id);
        array_push($quizCamO, $arbre->_id);
        array_push($quizEng, $arbre->_id);
        array_push($quizVl, $arbre->_id);
        array_push($quizVls, $arbre->_id);
    }
    if ($assistance) {
        array_push($quizCam, $assistance->_id);
        array_push($quizCamTrck, $assistance->_id);
        array_push($quizCamO, $assistance->_id);
        array_push($quizCha, $assistance->_id);
        array_push($quizChaBt, $assistance->_id);
        array_push($quizVl, $assistance->_id);
        array_push($quizVls, $assistance->_id);
        array_push($quizEng, $assistance->_id);
        array_push($quizBus, $assistance->_id);
    }
    if ($boite) {
        array_push($quizBus, $boite->_id);
        array_push($quizCam, $boite->_id);
        array_push($quizCamTrck, $boite->_id);
        array_push($quizCamO, $boite->_id);
        array_push($quizVl, $boite->_id);
        array_push($quizVls, $boite->_id);
        array_push($quizEng, $boite->_id);
        array_push($quizCha, $boite->_id);
        array_push($quizChaBt, $boite->_id);
    }
    if ($boiteAuto) {
        array_push($quizVl, $boiteAuto->_id);
        array_push($quizVls, $boiteAuto->_id);
    }
    if ($boiteMeca) {
        array_push($quizBus, $boiteMeca->_id);
        array_push($quizCam, $boiteMeca->_id);
        array_push($quizCamTrck, $boiteMeca->_id);
        array_push($quizCamO, $boiteMeca->_id);
        array_push($quizVl, $boiteMeca->_id);
        array_push($quizVls, $boiteMeca->_id);
        array_push($quizEng, $boiteMeca->_id);
        array_push($quizCha, $boiteMeca->_id);
    }
    if ($boiteVaCo) {
        array_push($quizVl, $boiteVaCo->_id);
        array_push($quizVls, $boiteVaCo->_id);
    }
    if ($transfert) {
        array_push($quizBus, $transfert->_id);
        array_push($quizCam, $transfert->_id);
        array_push($quizCamTrck, $transfert->_id);
        array_push($quizVl, $transfert->_id);
        array_push($quizVls, $transfert->_id);
        array_push($quizEng, $transfert->_id);
    }
    if ($climatisation) {
        array_push($quizBus, $climatisation->_id);
        array_push($quizCam, $climatisation->_id);
        array_push($quizCamTrck, $climatisation->_id);
        array_push($quizVl, $climatisation->_id);
        array_push($quizVls, $climatisation->_id);
        array_push($quizEng, $climatisation->_id);
        array_push($quizCha, $climatisation->_id);
        array_push($quizChaBt, $climatisation->_id);
    }
    if ($demi) {
        array_push($quizBus, $demi->_id);
        array_push($quizCam, $demi->_id);
        array_push($quizCamTrck, $demi->_id);
        array_push($quizCamO, $demi->_id);
        array_push($quizVl, $demi->_id);
        array_push($quizVls, $demi->_id);
        array_push($quizEng, $demi->_id);
        array_push($quizCha, $demi->_id);
    }
    if ($direction) {
        array_push($quizBus, $direction->_id);
        array_push($quizCam, $direction->_id);
        array_push($quizCamTrck, $direction->_id);
        array_push($quizCamO, $direction->_id);
        array_push($quizVl, $direction->_id);
        array_push($quizVls, $direction->_id);
        array_push($quizEng, $direction->_id);
        array_push($quizCha, $direction->_id);
        array_push($quizChaBt, $direction->_id);
    }
    if ($electricite) {
        array_push($quizBus, $electricite->_id);
        array_push($quizCam, $electricite->_id);
        array_push($quizCamTrck, $electricite->_id);
        array_push($quizCamO, $electricite->_id);
        array_push($quizVl, $electricite->_id);
        array_push($quizVls, $electricite->_id);
        array_push($quizEng, $electricite->_id);
        array_push($quizCha, $electricite->_id);
        array_push($quizChaBt, $electricite->_id);
    }
    if ($freinage) {
        array_push($quizBus, $freinage->_id);
        array_push($quizCam, $freinage->_id);
        array_push($quizCamTrck, $freinage->_id);
        array_push($quizCamO, $freinage->_id);
        array_push($quizVl, $freinage->_id);
        array_push($quizVls, $freinage->_id);
        array_push($quizEng, $freinage->_id);
        array_push($quizCha, $freinage->_id);
    }
    if ($freinageElec) {
        array_push($quizChaBt, $freinageElec->_id);
    }
    if ($freinageHyd) {
        array_push($quizVl, $freinageHyd->_id);
        array_push($quizVls, $freinageHyd->_id);
        array_push($quizEng, $freinageHyd->_id);
        array_push($quizCha, $freinageHyd->_id);
        array_push($quizChaBt, $freinageHyd->_id);
    }
    if ($freinagePneu) {
        array_push($quizBus, $freinagePneu->_id);
        array_push($quizCam, $freinagePneu->_id);
        array_push($quizCamTrck, $freinagePneu->_id);
        array_push($quizCamO, $freinagePneu->_id);
    }
    if ($hydraulique) {
        array_push($quizEng, $hydraulique->_id);
        array_push($quizCha, $hydraulique->_id);
        array_push($quizChaBt, $hydraulique->_id);
        array_push($quizCam, $hydraulique->_id);
        array_push($quizCamTrck, $hydraulique->_id);
    }
    if ($moteurDiesel) {
        array_push($quizBus, $moteurDiesel->_id);
        array_push($quizCam, $moteurDiesel->_id);
        array_push($quizCamTrck, $moteurDiesel->_id);
        array_push($quizCamO, $moteurDiesel->_id);
        array_push($quizVl, $moteurDiesel->_id);
        array_push($quizVls, $moteurDiesel->_id);
        array_push($quizEng, $moteurDiesel->_id);
        array_push($quizCha, $moteurDiesel->_id);
        array_push($quizChaBt, $moteurDiesel->_id);
    }
    if ($moteurElec) {
        array_push($quizVl, $moteurElec->_id);
        array_push($quizVls, $moteurElec->_id);
        array_push($quizCha, $moteurElec->_id);
        array_push($quizChaBt, $moteurElec->_id);
    }
    if ($moteurEssence) {
        array_push($quizVl, $moteurEssence->_id);
        array_push($quizVls, $moteurEssence->_id);
        array_push($quizCha, $moteurEssence->_id);
        array_push($quizChaBt, $moteurEssence->_id);
    }
    if ($moteurThermique) {
        array_push($quizBus, $moteurThermique->_id);
        array_push($quizCam, $moteurThermique->_id);
        array_push($quizCamTrck, $moteurThermique->_id);
        array_push($quizCamO, $moteurThermique->_id);
        array_push($quizVl, $moteurThermique->_id);
        array_push($quizVls, $moteurThermique->_id);
        array_push($quizEng, $moteurThermique->_id);
        array_push($quizCha, $moteurThermique->_id);
    }
    if ($multiplexage) {
        array_push($quizBus, $multiplexage->_id);
        array_push($quizCam, $multiplexage->_id);
        array_push($quizCamTrck, $multiplexage->_id);
        array_push($quizCamO, $multiplexage->_id);
        array_push($quizVl, $multiplexage->_id);
        array_push($quizVls, $multiplexage->_id);
        array_push($quizEng, $multiplexage->_id);
        array_push($quizCha, $multiplexage->_id);
        array_push($quizChaBt, $multiplexage->_id);
    }
    if ($pont) {
        array_push($quizBus, $pont->_id);
        array_push($quizCam, $pont->_id);
        array_push($quizCamTrck, $pont->_id);
        array_push($quizCamO, $pont->_id);
        array_push($quizVl, $pont->_id);
        array_push($quizVls, $pont->_id);
        array_push($quizEng, $pont->_id);
        array_push($quizCha, $pont->_id);

    }
    if ($pneu) {
        array_push($quizBus, $pneu->_id);
        array_push($quizCam, $pneu->_id);
        array_push($quizCamTrck, $pneu->_id);
        array_push($quizCamO, $pneu->_id);
        array_push($quizVl, $pneu->_id);
        array_push($quizVls, $pneu->_id);
        array_push($quizEng, $pneu->_id);
        array_push($quizCha, $pneu->_id);

    }
    if ($reducteur) {
        array_push($quizBus, $reducteur->_id);
        array_push($quizCam, $reducteur->_id);
        array_push($quizCamTrck, $reducteur->_id);
        array_push($quizCamO, $reducteur->_id);
        array_push($quizEng, $reducteur->_id);
        array_push($quizCha, $reducteur->_id);
        array_push($quizChaBt, $reducteur->_id);
    }
    if ($suspension) {
        array_push($quizBus, $suspension->_id);
        array_push($quizCam, $suspension->_id);
        array_push($quizCamTrck, $suspension->_id);
        array_push($quizCamO, $suspension->_id);
        array_push($quizVl, $suspension->_id);
        array_push($quizVls, $suspension->_id);
    }
    if ($suspensionLame) {
        array_push($quizBus, $suspensionLame->_id);
        array_push($quizCam, $suspensionLame->_id);
        array_push($quizCamTrck, $suspensionLame->_id);
        array_push($quizCamO, $suspensionLame->_id);
        array_push($quizVl, $suspensionLame->_id);
        array_push($quizVls, $suspensionLame->_id);
    }
    if ($suspensionRessort) {
        array_push($quizVl, $suspensionRessort->_id);
        array_push($quizVls, $suspensionRessort->_id);
    }
    if ($suspensionPneu) {
        array_push($quizCamTrck, $suspensionPneu->_id);
        array_push($quizVl, $suspensionPneu->_id);
    }
    if ($transversale) {
        array_push($quizBus, $transversale->_id);
        array_push($quizCam, $transversale->_id);
        array_push($quizCamTrck, $transversale->_id);
        array_push($quizCamO, $transversale->_id);
        array_push($quizVl, $transversale->_id);
        array_push($quizVls, $transversale->_id);
        array_push($quizEng, $transversale->_id);
        array_push($quizCha, $transversale->_id);
        array_push($quizChaBt, $transversale->_id);
    }
    
    if ( empty( $label ) ||
    empty( $type ) ||
    empty( $level ) ||
    empty( $brand ) ) {
        $error = 'Champ obligatoire';
    } elseif ( $exist ) {
        $error_msg = 'Ce véhicule existe déjà.';
    } elseif ($label == "Bus")  {

        if ($level == 'Junior') {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizBus,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBus),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior') {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizBus,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBus),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert') {
            $vehicle = [
                'users' => $userArrM,
                'quizzes' => $quizBus,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBus),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Camions")  {
        if ($level == 'Junior' && $brand == "MERCEDES TRUCK" || $brand == "RENAULT TRUCK") {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizCamTrck,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrck),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand == "FUSO" || $brand == "HINO") {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizCamO,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamO),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand == "SINOTRUK") {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizCam,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCam),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "MERCEDES TRUCK" || $brand == "RENAULT TRUCK") {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizCamTrck,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrck),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "FUSO" || $brand == "HINO") {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizCamO,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamO),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "SINOTRUK") {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizCam,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCam),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "MERCEDES TRUCK" || $brand == "RENAULT TRUCK") {
            $vehicle = [
                'users' => $userArrD,
                'quizzes' => $quizCamTrck,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrck),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "FUSO" || $brand == "HINO") {
            $vehicle = [
                'users' => $userArrD,
                'quizzes' => $quizCamO,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamO),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "SINOTRUK") {
            $vehicle = [
                'users' => $userArrD,
                'quizzes' => $quizCam,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCam),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Chariots") {

        if ($level == 'Junior' && $brand == "TOYOTA BT") {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizChaBt,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBt),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand == "TOYOTA FORFLIT") {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizCha,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCha),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "TOYOTA BT")  {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizChaBt,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBt),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "TOYOTA FORFLIT")  {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizCha,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCha),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "TOYOTA BT")  {
            $vehicle = [
                'users' => $userArrD,
                'quizzes' => $quizChaBt,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBt),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "TOYOTA FORFLIT")  {
            $vehicle = [
                'users' => $userArrD,
                'quizzes' => $quizCha,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCha),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Engins") {

        if ($level == 'Junior') {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizEng,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEng),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior') {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizEng,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEng),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert') {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizEng,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEng),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Voitures") {

        if ($level == 'Junior' && $brand == "SUZUKI") {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizVls,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVls),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i]),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand != "SUZUKI") {
            $vehicle = [
                'users' => $userArr,
                'quizzes' => $quizVl,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVl),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
    
            for ($i = 0; $i < count($userArr); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "SUZUKI") {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizVls,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVls),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand != "SUZUKI") {
            $vehicle = [
                'users' => $userArrR,
                'quizzes' => $quizVl,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVl),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrR); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "SUZUKI") {
            $vehicle = [
                'users' => $userArrD,
                'quizzes' => $quizVls,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVls),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand != "SUZUKI") {
            $vehicle = [
                'users' => $userArrD,
                'quizzes' => $quizVl,
                'label' => ucfirst( $label ),
                'type' => $type,
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVl),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $result = $vehicles->insertOne( $vehicle );
            for ($i = 0; $i < count($userArrD); $i++) {
                if ($type == 'Factuel') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                } elseif ($type == 'Declaratif') {
                    $allocates = [
                        'vehicle' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ),
                        'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                        'type' => $type,
                        'level' => $level,
                        'activeManager' => false,
                        'active' => false,
                        'created' => date("d-m-Y"),
                    ];
                    $allocations->insertOne( $allocates );
                }
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    }
}

?>
<?php
include_once 'partials/header.php'
?>

<!--begin::Title-->
<title>Ajouter Véhicule | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Modal body-->
            <div class='container mt-5 w-50'>
                <img src='../public/images/logo.png' alt='10' height='170'
                    style='display: block; margin-left: auto; margin-right: auto; width: 50%;'>
                <h1 class='my-3 text-center'>Ajouter un véhicule</h1>

                <?php
                 if(isset($success_msg)) {
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <center><strong><?php echo $success_msg ?></strong></center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
                }
                ?>
                <?php
                 if(isset($error_msg)) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <center><strong><?php echo $error_msg ?></strong></center>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
                }
                ?>

                <form method="POST"><br>
                    <!--begin::Input group-->
                    <div class="row fv-row mb-7">
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6">
                                <span class="required">Nom du type de véhicule</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="label" aria-label="Select a Country" data-control="select2"
                                data-placeholder="Sélectionnez votre type de vehicule..."
                                class="form-select form-select-solid fw-bold">
                                <option>Sélectionnez votre type de vehicule...</option>
                                <option value="Bus">
                                    Bus
                                </option>
                                <option value="Camions">
                                    Camions
                                </option>
                                <option value="Chariots">
                                    Chariots
                                </option>
                                <option value="Engins">
                                    Engins
                                </option>
                                <option value="Voitures">
                                    Voitures
                                </option>
                            </select>
                            <!--end::Input-->
                            <?php
                     if(isset($error)) {
                    ?>
                            <span class='text-danger'>
                                <?php echo $error ?>
                            </span>
                            <?php
                    }
                    ?>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6">
                                <span class="required">Type</span>
                                </span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="type" aria-label="Select a Country" data-placeholder="Sélectionnez le type..."
                                data-dropdown-parent="#kt_modal_add_customer"
                                class="form-select form-select-solid fw-bold">
                                <option value="">Sélectionnez le
                                    type...</option>
                                <option value="Factuel">
                                    Factuel
                                </option>
                                <option value="Declaratif">
                                    Declaratif
                                </option>
                            </select>
                            <!--end::Input-->
                            <?php
                     if(isset($error)) {
                    ?>
                            <span class='text-danger'>
                                <?php echo $error ?>
                            </span>
                            <?php
                    }
                    ?>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6">
                                <span class="required">Marque de véhicule</span>
                                </span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="brand" aria-label=" Select a Country" data-control="select2"
                                data-placeholder="Sélectionnez la marque du véhicule..."
                                class="form-select form-select-solid fw-bold">
                                <option>Sélectionnez la marque de véhicules...</option>
                                <option value="CITROEN">
                                    CITROEN
                                </option>
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
                                <option value="MERCEDES">
                                    MERCEDES
                                </option>
                                <option value="MERCEDES TRUCK">
                                    MERCEDES TRUCK
                                </option>
                                <option value="RENAULT TRUCK">
                                    RENAULT TRUCK
                                </option>
                                <option value="PEUGEOT">
                                    PEUGEOT
                                </option>
                                <option value="SINOTRUCK">
                                    SINOTRUCK
                                </option>
                                <option value="SUZUKI">
                                    SUZUKI
                                </option>
                                <option value="TOYOTA">
                                    TOYOTA
                                </option>
                                <option value="TOYOTA BT">
                                    TOYOTA BT
                                </option>
                                <option value="TOYOTA FORFLIT">
                                    TOYOTA FORFLIT
                                </option>
                            </select>
                            <!--end::Input-->
                            <?php
                     if(isset($error)) {
                    ?>
                            <span class='text-danger'>
                                <?php echo $error ?>
                            </span>
                            <?php
                    }
                    ?>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6">
                                <span class="required">Niveau</span>
                                </span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="level" aria-label="Select a Country"
                                data-placeholder="Sélectionnez le niveau..."
                                data-dropdown-parent="#kt_modal_add_customer"
                                class="form-select form-select-solid fw-bold">
                                <option value="">Sélectionnez le
                                    niveau...</option>
                                <option value="Junior">
                                    Junior
                                </option>
                                <option value="Senior">
                                    Senior
                                </option>
                                <option value="Expert">
                                    Expert
                                </option>
                            </select>
                            <!--end::Input-->
                            <?php
                     if(isset($error)) {
                    ?>
                            <span class='text-danger'>
                                <?php echo $error ?>
                            </span>
                            <?php
                    }
                    ?>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="submit" name="submit" class=" btn btn-primary">
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
<?php
include_once 'partials/footer.php'
?>
<?php } ?>