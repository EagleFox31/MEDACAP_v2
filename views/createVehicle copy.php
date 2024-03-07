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
    $level = $_POST[ 'level' ];
    $quizBusF = [];
    $quizCamF = [];
    $quizCamTrckF = [];
    $quizCamOF = [];
    $quizChaF = [];
    $quizChaBtF = [];
    $quizEngF = [];
    $quizVlF = [];
    $quizVlsF = [];
    $quizBusD = [];
    $quizCamD = [];
    $quizCamTrckD = [];
    $quizCamOD = [];
    $quizChaD = [];
    $quizChaBtD = [];
    $quizEngD = [];
    $quizVlD = [];
    $quizVlsD = [];
    $userArr = [];
    $userArrR = [];
    $userArrD = [];

    $exist = $vehicles->findOne([
        '$and' => [
            [ 'label' => $label ],
            [ 'brand' => $brand ],
            [ 'level' => $level ],
        ],
    ]);

    $arbreFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Arbre de Transmission" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $assistanceFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Assistance à la Conduite" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteAutoFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse Automatique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteMecaFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse Mécanique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteVaCoFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse à Variation Continue" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $transfertFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Transfert" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $climatisationFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Climatisation" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $demiFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Demi Arbre de Roue" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $directionFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Direction" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $electriciteFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Electricité et Electronique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageElecFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Electromagnétique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageHydFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Hydraulique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinagePneuFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Pneumatique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $hydrauliqueFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Hydraulique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurDieselFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Diesel" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurElecFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Electrique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurEssenceFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Essence" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurThermiqueFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Thermique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $multiplexageFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Multiplexage" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $pontFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Pont" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $pneuFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Pneumatique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $reducteurFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Reducteur" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionLameFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension à Lame" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionRessortFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension Ressort" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionPneuFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension Pneumatique" ],
            [ 'type' => 'Factuel' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $transversaleFac = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Transversale" ],
            [ 'type' => 'Factuel' ],
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
            if ($user[$i]->level == 'Senior' || $user[$i]->level == 'Expert') {
                array_push($userArrR, $user[$i]->_id);
            }
            if ($user[$i]->level == 'Expert') {
                array_push($userArrD, $user[$i]->_id);
            }
        }
    }
    
    if ($arbreFac) {
        array_push($quizBusF, $arbreFac->_id);
        array_push($quizCamF, $arbreFac->_id);
        array_push($quizCamTrckF, $arbreFac->_id);
        array_push($quizCamOF, $arbreFac->_id);
        array_push($quizEngF, $arbreFac->_id);
        array_push($quizVlF, $arbreFac->_id);
        array_push($quizVlsF, $arbreFac->_id);
    }
    if ($assistanceFac) {
        array_push($quizCamF, $assistanceFac->_id);
        array_push($quizCamTrckF, $assistanceFac->_id);
        array_push($quizCamOF, $assistanceFac->_id);
        array_push($quizChaF, $assistanceFac->_id);
        array_push($quizChaBtF, $assistanceFac->_id);
        array_push($quizVlF, $assistanceFac->_id);
        array_push($quizVlsF, $assistanceFac->_id);
        array_push($quizEngF, $assistanceFac->_id);
        array_push($quizBusF, $assistanceFac->_id);
    }
    if ($boiteFac) {
        array_push($quizBusF, $boiteFac->_id);
        array_push($quizCamF, $boiteFac->_id);
        array_push($quizCamTrckF, $boiteFac->_id);
        array_push($quizCamOF, $boiteFac->_id);
        array_push($quizVlF, $boiteFac->_id);
        array_push($quizVlsF, $boiteFac->_id);
        array_push($quizEngF, $boiteFac->_id);
        array_push($quizChaF, $boiteFac->_id);
        array_push($quizChaBtF, $boiteFac->_id);
    }
    if ($boiteAutoFac) {
        array_push($quizVlF, $boiteAutoFac->_id);
        array_push($quizVlsF, $boiteAutoFac->_id);
    }
    if ($boiteMecaFac) {
        array_push($quizBusF, $boiteMecaFac->_id);
        array_push($quizCamF, $boiteMecaFac->_id);
        array_push($quizCamTrckF, $boiteMecaFac->_id);
        array_push($quizCamOF, $boiteMecaFac->_id);
        array_push($quizVlF, $boiteMecaFac->_id);
        array_push($quizVlsF, $boiteMecaFac->_id);
        array_push($quizEngF, $boiteMecaFac->_id);
        array_push($quizChaF, $boiteMecaFac->_id);
    }
    if ($boiteVaCoFac) {
        array_push($quizVlF, $boiteVaCoFac->_id);
        array_push($quizVlsF, $boiteVaCoFac->_id);
    }
    if ($transfertFac) {
        array_push($quizBusF, $transfertFac->_id);
        array_push($quizCamF, $transfertFac->_id);
        array_push($quizCamTrckF, $transfertFac->_id);
        array_push($quizVlF, $transfertFac->_id);
        array_push($quizVlsF, $transfertFac->_id);
        array_push($quizEngF, $transfertFac->_id);
    }
    if ($climatisationFac) {
        array_push($quizBusF, $climatisationFac->_id);
        array_push($quizCamF, $climatisationFac->_id);
        array_push($quizCamTrckF, $climatisationFac->_id);
        array_push($quizVlF, $climatisationFac->_id);
        array_push($quizVlsF, $climatisationFac->_id);
        array_push($quizEngF, $climatisationFac->_id);
        array_push($quizChaF, $climatisationFac->_id);
        array_push($quizChaBtF, $climatisationFac->_id);
    }
    if ($demiFac) {
        array_push($quizBusF, $demiFac->_id);
        array_push($quizCamF, $demiFac->_id);
        array_push($quizCamTrckF, $demiFac->_id);
        array_push($quizCamOF, $demiFac->_id);
        array_push($quizVlF, $demiFac->_id);
        array_push($quizVlsF, $demiFac->_id);
        array_push($quizEngF, $demiFac->_id);
        array_push($quizChaF, $demiFac->_id);
    }
    if ($directionFac) {
        array_push($quizBusF, $directionFac->_id);
        array_push($quizCamF, $directionFac->_id);
        array_push($quizCamTrckF, $directionFac->_id);
        array_push($quizCamOF, $directionFac->_id);
        array_push($quizVlF, $directionFac->_id);
        array_push($quizVlsF, $directionFac->_id);
        array_push($quizEngF, $directionFac->_id);
        array_push($quizChaF, $directionFac->_id);
        array_push($quizChaBtF, $directionFac->_id);
    }
    if ($electriciteFac) {
        array_push($quizBusF, $electriciteFac->_id);
        array_push($quizCamF, $electriciteFac->_id);
        array_push($quizCamTrckF, $electriciteFac->_id);
        array_push($quizCamOF, $electriciteFac->_id);
        array_push($quizVlF, $electriciteFac->_id);
        array_push($quizVlsF, $electriciteFac->_id);
        array_push($quizEngF, $electriciteFac->_id);
        array_push($quizChaF, $electriciteFac->_id);
        array_push($quizChaBtF, $electriciteFac->_id);
    }
    if ($freinageFac) {
        array_push($quizBusF, $freinageFac->_id);
        array_push($quizCamF, $freinageFac->_id);
        array_push($quizCamTrckF, $freinageFac->_id);
        array_push($quizCamOF, $freinageFac->_id);
        array_push($quizVlF, $freinageFac->_id);
        array_push($quizVlsF, $freinageFac->_id);
        array_push($quizEngF, $freinageFac->_id);
        array_push($quizChaF, $freinageFac->_id);
    }
    if ($freinageElecFac) {
        array_push($quizChaBtF, $freinageElecFac->_id);
    }
    if ($freinageHydFac) {
        array_push($quizVlF, $freinageHydFac->_id);
        array_push($quizVlsF, $freinageHydFac->_id);
        array_push($quizEngF, $freinageHydFac->_id);
        array_push($quizChaF, $freinageHydFac->_id);
        array_push($quizChaBtF, $freinageHydFac->_id);
    }
    if ($freinagePneuFac) {
        array_push($quizBusF, $freinagePneuFac->_id);
        array_push($quizCamF, $freinagePneuFac->_id);
        array_push($quizCamTrckF, $freinagePneuFac->_id);
        array_push($quizCamOF, $freinagePneuFac->_id);
    }
    if ($hydrauliqueFac) {
        array_push($quizEngF, $hydrauliqueFac->_id);
        array_push($quizChaF, $hydrauliqueFac->_id);
        array_push($quizChaBtF, $hydrauliqueFac->_id);
        array_push($quizCamF, $hydrauliqueFac->_id);
        array_push($quizCamTrckF, $hydrauliqueFac->_id);
    }
    if ($moteurDieselFac) {
        array_push($quizBusF, $moteurDieselFac->_id);
        array_push($quizCamF, $moteurDieselFac->_id);
        array_push($quizCamTrckF, $moteurDieselFac->_id);
        array_push($quizCamOF, $moteurDieselFac->_id);
        array_push($quizVlF, $moteurDieselFac->_id);
        array_push($quizVlsF, $moteurDieselFac->_id);
        array_push($quizEngF, $moteurDieselFac->_id);
        array_push($quizChaF, $moteurDieselFac->_id);
        array_push($quizChaBtF, $moteurDieselFac->_id);
    }
    if ($moteurElecFac) {
        array_push($quizVlF, $moteurElecFac->_id);
        array_push($quizVlsF, $moteurElecFac->_id);
        array_push($quizChaF, $moteurElecFac->_id);
        array_push($quizChaBtF, $moteurElecFac->_id);
    }
    if ($moteurEssenceFac) {
        array_push($quizVlF, $moteurEssenceFac->_id);
        array_push($quizVlsF, $moteurEssenceFac->_id);
        array_push($quizChaF, $moteurEssenceFac->_id);
        array_push($quizChaBtF, $moteurEssenceFac->_id);
    }
    if ($moteurThermiqueFac) {
        array_push($quizBusF, $moteurThermiqueFac->_id);
        array_push($quizCamF, $moteurThermiqueFac->_id);
        array_push($quizCamTrckF, $moteurThermiqueFac->_id);
        array_push($quizCamOF, $moteurThermiqueFac->_id);
        array_push($quizVlF, $moteurThermiqueFac->_id);
        array_push($quizVlsF, $moteurThermiqueFac->_id);
        array_push($quizEngF, $moteurThermiqueFac->_id);
        array_push($quizChaF, $moteurThermiqueFac->_id);
    }
    if ($multiplexageFac) {
        array_push($quizBusF, $multiplexageFac->_id);
        array_push($quizCamF, $multiplexageFac->_id);
        array_push($quizCamTrckF, $multiplexageFac->_id);
        array_push($quizCamOF, $multiplexageFac->_id);
        array_push($quizVlF, $multiplexageFac->_id);
        array_push($quizVlsF, $multiplexageFac->_id);
        array_push($quizEngF, $multiplexageFac->_id);
        array_push($quizChaF, $multiplexageFac->_id);
        array_push($quizChaBtF, $multiplexageFac->_id);
    }
    if ($pontFac) {
        array_push($quizBusF, $pontFac->_id);
        array_push($quizCamF, $pontFac->_id);
        array_push($quizCamTrckF, $pontFac->_id);
        array_push($quizCamOF, $pontFac->_id);
        array_push($quizVlF, $pontFac->_id);
        array_push($quizVlsF, $pontFac->_id);
        array_push($quizEngF, $pontFac->_id);
        array_push($quizChaF, $pontFac->_id);

    }
    if ($pneuFac) {
        array_push($quizBusF, $pneuFac->_id);
        array_push($quizCamF, $pneuFac->_id);
        array_push($quizCamTrckF, $pneuFac->_id);
        array_push($quizCamOF, $pneuFac->_id);
        array_push($quizVlF, $pneuFac->_id);
        array_push($quizVlsF, $pneuFac->_id);
        array_push($quizEngF, $pneuFac->_id);
        array_push($quizChaF, $pneuFac->_id);

    }
    if ($reducteurFac) {
        array_push($quizBusF, $reducteurFac->_id);
        array_push($quizCamF, $reducteurFac->_id);
        array_push($quizCamTrckF, $reducteurFac->_id);
        array_push($quizCamOF, $reducteurFac->_id);
        array_push($quizEngF, $reducteurFac->_id);
        array_push($quizChaF, $reducteurFac->_id);
        array_push($quizChaBtF, $reducteurFac->_id);
    }
    if ($suspensionFac) {
        array_push($quizBusF, $suspensionFac->_id);
        array_push($quizCamF, $suspensionFac->_id);
        array_push($quizCamTrckF, $suspensionFac->_id);
        array_push($quizCamOF, $suspensionFac->_id);
        array_push($quizVlF, $suspensionFac->_id);
        array_push($quizVlsF, $suspensionFac->_id);
    }
    if ($suspensionLameFac) {
        array_push($quizBusF, $suspensionLameFac->_id);
        array_push($quizCamF, $suspensionLameFac->_id);
        array_push($quizCamTrckF, $suspensionLameFac->_id);
        array_push($quizCamOF, $suspensionLameFac->_id);
        array_push($quizVlF, $suspensionLameFac->_id);
        array_push($quizVlsF, $suspensionLameFac->_id);
    }
    if ($suspensionRessortFac) {
        array_push($quizVlF, $suspensionRessortFac->_id);
        array_push($quizVlsF, $suspensionRessortFac->_id);
    }
    if ($suspensionPneuFac) {
        array_push($quizCamTrckF, $suspensionPneuFac->_id);
        array_push($quizVlF, $suspensionPneuFac->_id);
    }
    if ($transversaleFac) {
        array_push($quizBusF, $transversaleFac->_id);
        array_push($quizCamF, $transversaleFac->_id);
        array_push($quizCamTrckF, $transversaleFac->_id);
        array_push($quizCamOF, $transversaleFac->_id);
        array_push($quizVlF, $transversaleFac->_id);
        array_push($quizVlsF, $transversaleFac->_id);
        array_push($quizEngF, $transversaleFac->_id);
        array_push($quizChaF, $transversaleFac->_id);
        array_push($quizChaBtF, $transversaleFac->_id);
    }
    
    $arbreDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Arbre de Transmission" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $assistanceDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Assistance à la Conduite" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteAutoDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse Automatique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteMecaDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse Mécanique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $boiteVaCoDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Vitesse à Variation Continue" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $transfertDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Boite de Transfert" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $climatisationDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Climatisation" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $demiDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Demi Arbre de Roue" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $directionDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Direction" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $electriciteDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Electricité et Electronique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageElecDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Electromagnétique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinageHydDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Hydraulique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $freinagePneuDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Freinage Pneumatique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $hydrauliqueDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Hydraulique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurDieselDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Diesel" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurElecDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Electrique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurEssenceDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Essence" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $moteurThermiqueDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Moteur Thermique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $multiplexageDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Multiplexage" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $pontDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Pont" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $pneuDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Pneumatique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $reducteurDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Reducteur" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionLameDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension à Lame" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionRessortDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension Ressort" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $suspensionPneuDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Suspension Pneumatique" ],
            [ 'type' => 'Declaratif' ],
            [ 'level' => $level ],
            [ 'active' => true ],
        ],
    ]);
    $transversaleDecla = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => "Transversale" ],
            [ 'type' => 'Declaratif' ],
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
    
    if ($arbreDecla) {
        array_push($quizBusD, $arbreDecla->_id);
        array_push($quizCamD, $arbreDecla->_id);
        array_push($quizCamTrckD, $arbreDecla->_id);
        array_push($quizCamOD, $arbreDecla->_id);
        array_push($quizEngD, $arbreDecla->_id);
        array_push($quizVlD, $arbreDecla->_id);
        array_push($quizVlsD, $arbreDecla->_id);
    }
    if ($assistanceDecla) {
        array_push($quizCamD, $assistanceDecla->_id);
        array_push($quizCamTrckD, $assistanceDecla->_id);
        array_push($quizCamOD, $assistanceDecla->_id);
        array_push($quizChaD, $assistanceDecla->_id);
        array_push($quizChaBtD, $assistanceDecla->_id);
        array_push($quizVlD, $assistanceDecla->_id);
        array_push($quizVlsD, $assistanceDecla->_id);
        array_push($quizEngD, $assistanceDecla->_id);
        array_push($quizBusD, $assistanceDecla->_id);
    }
    if ($boiteDecla) {
        array_push($quizBusD, $boiteDecla->_id);
        array_push($quizCamD, $boiteDecla->_id);
        array_push($quizCamTrckD, $boiteDecla->_id);
        array_push($quizCamOD, $boiteDecla->_id);
        array_push($quizVlD, $boiteDecla->_id);
        array_push($quizVlsD, $boiteDecla->_id);
        array_push($quizEngD, $boiteDecla->_id);
        array_push($quizChaD, $boiteDecla->_id);
        array_push($quizChaBtD, $boiteDecla->_id);
    }
    if ($boiteAutoDecla) {
        array_push($quizVlD, $boiteAutoDecla->_id);
        array_push($quizVlsD, $boiteAutoDecla->_id);
    }
    if ($boiteMecaDecla) {
        array_push($quizBusD, $boiteMecaDecla->_id);
        array_push($quizCamD, $boiteMecaDecla->_id);
        array_push($quizCamTrckD, $boiteMecaDecla->_id);
        array_push($quizCamOD, $boiteMecaDecla->_id);
        array_push($quizVlD, $boiteMecaDecla->_id);
        array_push($quizVlsD, $boiteMecaDecla->_id);
        array_push($quizEngD, $boiteMecaDecla->_id);
        array_push($quizChaD, $boiteMecaDecla->_id);
    }
    if ($boiteVaCoDecla) {
        array_push($quizVlD, $boiteVaCoDecla->_id);
        array_push($quizVlsD, $boiteVaCoDecla->_id);
    }
    if ($transfertDecla) {
        array_push($quizBusD, $transfertDecla->_id);
        array_push($quizCamD, $transfertDecla->_id);
        array_push($quizCamTrckD, $transfertDecla->_id);
        array_push($quizVlD, $transfertDecla->_id);
        array_push($quizVlsD, $transfertDecla->_id);
        array_push($quizEngD, $transfertDecla->_id);
    }
    if ($climatisationDecla) {
        array_push($quizBusD, $climatisationDecla->_id);
        array_push($quizCamD, $climatisationDecla->_id);
        array_push($quizCamTrckD, $climatisationDecla->_id);
        array_push($quizVlD, $climatisationDecla->_id);
        array_push($quizVlsD, $climatisationDecla->_id);
        array_push($quizEngD, $climatisationDecla->_id);
        array_push($quizChaD, $climatisationDecla->_id);
        array_push($quizChaBtD, $climatisationDecla->_id);
    }
    if ($demiDecla) {
        array_push($quizBusD, $demiDecla->_id);
        array_push($quizCamD, $demiDecla->_id);
        array_push($quizCamTrckD, $demiDecla->_id);
        array_push($quizCamOD, $demiDecla->_id);
        array_push($quizVlD, $demiDecla->_id);
        array_push($quizVlsD, $demiDecla->_id);
        array_push($quizEngD, $demiDecla->_id);
        array_push($quizChaD, $demiDecla->_id);
    }
    if ($directionDecla) {
        array_push($quizBusD, $directionDecla->_id);
        array_push($quizCamD, $directionDecla->_id);
        array_push($quizCamTrckD, $directionDecla->_id);
        array_push($quizCamOD, $directionDecla->_id);
        array_push($quizVlD, $directionDecla->_id);
        array_push($quizVlsD, $directionDecla->_id);
        array_push($quizEngD, $directionDecla->_id);
        array_push($quizChaD, $directionDecla->_id);
        array_push($quizChaBtD, $directionDecla->_id);
    }
    if ($electriciteDecla) {
        array_push($quizBusD, $electriciteDecla->_id);
        array_push($quizCamD, $electriciteDecla->_id);
        array_push($quizCamTrckD, $electriciteDecla->_id);
        array_push($quizCamOD, $electriciteDecla->_id);
        array_push($quizVlD, $electriciteDecla->_id);
        array_push($quizVlsD, $electriciteDecla->_id);
        array_push($quizEngD, $electriciteDecla->_id);
        array_push($quizChaD, $electriciteDecla->_id);
        array_push($quizChaBtD, $electriciteDecla->_id);
    }
    if ($freinageDecla) {
        array_push($quizBusD, $freinageDecla->_id);
        array_push($quizCamD, $freinageDecla->_id);
        array_push($quizCamTrckD, $freinageDecla->_id);
        array_push($quizCamOD, $freinageDecla->_id);
        array_push($quizVlD, $freinageDecla->_id);
        array_push($quizVlsD, $freinageDecla->_id);
        array_push($quizEngD, $freinageDecla->_id);
        array_push($quizChaD, $freinageDecla->_id);
    }
    if ($freinageElecDecla) {
        array_push($quizChaBtD, $freinageElecDecla->_id);
    }
    if ($freinageHydDecla) {
        array_push($quizVlD, $freinageHydDecla->_id);
        array_push($quizVlsD, $freinageHydDecla->_id);
        array_push($quizEngD, $freinageHydDecla->_id);
        array_push($quizChaD, $freinageHydDecla->_id);
        array_push($quizChaBtD, $freinageHydDecla->_id);
    }
    if ($freinagePneuDecla) {
        array_push($quizBusD, $freinagePneuDecla->_id);
        array_push($quizCamD, $freinagePneuDecla->_id);
        array_push($quizCamTrckD, $freinagePneuDecla->_id);
        array_push($quizCamOD, $freinagePneuDecla->_id);
    }
    if ($hydrauliqueDecla) {
        array_push($quizEngD, $hydrauliqueDecla->_id);
        array_push($quizChaD, $hydrauliqueDecla->_id);
        array_push($quizChaBtD, $hydrauliqueDecla->_id);
        array_push($quizCamD, $hydrauliqueDecla->_id);
        array_push($quizCamTrckD, $hydrauliqueDecla->_id);
    }
    if ($moteurDieselDecla) {
        array_push($quizBusD, $moteurDieselDecla->_id);
        array_push($quizCamD, $moteurDieselDecla->_id);
        array_push($quizCamTrckD, $moteurDieselDecla->_id);
        array_push($quizCamOD, $moteurDieselDecla->_id);
        array_push($quizVlD, $moteurDieselDecla->_id);
        array_push($quizVlsD, $moteurDieselDecla->_id);
        array_push($quizEngD, $moteurDieselDecla->_id);
        array_push($quizChaD, $moteurDieselDecla->_id);
        array_push($quizChaBtD, $moteurDieselDecla->_id);
    }
    if ($moteurElecDecla) {
        array_push($quizVlD, $moteurElecDecla->_id);
        array_push($quizVlsD, $moteurElecDecla->_id);
        array_push($quizChaD, $moteurElecDecla->_id);
        array_push($quizChaBtD, $moteurElecDecla->_id);
    }
    if ($moteurEssenceDecla) {
        array_push($quizVlD, $moteurEssenceDecla->_id);
        array_push($quizVlsD, $moteurEssenceDecla->_id);
        array_push($quizChaD, $moteurEssenceDecla->_id);
        array_push($quizChaBtD, $moteurEssenceDecla->_id);
    }
    if ($moteurThermiqueDecla) {
        array_push($quizBusD, $moteurThermiqueDecla->_id);
        array_push($quizCamD, $moteurThermiqueDecla->_id);
        array_push($quizCamTrckD, $moteurThermiqueDecla->_id);
        array_push($quizCamOD, $moteurThermiqueDecla->_id);
        array_push($quizVlD, $moteurThermiqueDecla->_id);
        array_push($quizVlsD, $moteurThermiqueDecla->_id);
        array_push($quizEngD, $moteurThermiqueDecla->_id);
        array_push($quizChaD, $moteurThermiqueDecla->_id);
    }
    if ($multiplexageDecla) {
        array_push($quizBusD, $multiplexageDecla->_id);
        array_push($quizCamD, $multiplexageDecla->_id);
        array_push($quizCamTrckD, $multiplexageDecla->_id);
        array_push($quizCamOD, $multiplexageDecla->_id);
        array_push($quizVlD, $multiplexageDecla->_id);
        array_push($quizVlsD, $multiplexageDecla->_id);
        array_push($quizEngD, $multiplexageDecla->_id);
        array_push($quizChaD, $multiplexageDecla->_id);
        array_push($quizChaBtD, $multiplexageDecla->_id);
    }
    if ($pontDecla) {
        array_push($quizBusD, $pontDecla->_id);
        array_push($quizCamD, $pontDecla->_id);
        array_push($quizCamTrckD, $pontDecla->_id);
        array_push($quizCamOD, $pontDecla->_id);
        array_push($quizVlD, $pontDecla->_id);
        array_push($quizVlsD, $pontDecla->_id);
        array_push($quizEngD, $pontDecla->_id);
        array_push($quizChaD, $pontDecla->_id);

    }
    if ($pneuDecla) {
        array_push($quizBusD, $pneuDecla->_id);
        array_push($quizCamD, $pneuDecla->_id);
        array_push($quizCamTrckD, $pneuDecla->_id);
        array_push($quizCamOD, $pneuDecla->_id);
        array_push($quizVlD, $pneuDecla->_id);
        array_push($quizVlsD, $pneuDecla->_id);
        array_push($quizEngD, $pneuDecla->_id);
        array_push($quizChaD, $pneuDecla->_id);

    }
    if ($reducteurDecla) {
        array_push($quizBusD, $reducteurDecla->_id);
        array_push($quizCamD, $reducteurDecla->_id);
        array_push($quizCamTrckD, $reducteurDecla->_id);
        array_push($quizCamOD, $reducteurDecla->_id);
        array_push($quizEngD, $reducteurDecla->_id);
        array_push($quizChaD, $reducteurDecla->_id);
        array_push($quizChaBtD, $reducteurDecla->_id);
    }
    if ($suspensionDecla) {
        array_push($quizBusD, $suspensionDecla->_id);
        array_push($quizCamD, $suspensionDecla->_id);
        array_push($quizCamTrckD, $suspensionDecla->_id);
        array_push($quizCamOD, $suspensionDecla->_id);
        array_push($quizVlD, $suspensionDecla->_id);
        array_push($quizVlsD, $suspensionDecla->_id);
    }
    if ($suspensionLameDecla) {
        array_push($quizBusD, $suspensionLameDecla->_id);
        array_push($quizCamD, $suspensionLameDecla->_id);
        array_push($quizCamTrckD, $suspensionLameDecla->_id);
        array_push($quizCamOD, $suspensionLameDecla->_id);
        array_push($quizVlD, $suspensionLameDecla->_id);
        array_push($quizVlsD, $suspensionLameDecla->_id);
    }
    if ($suspensionRessortDecla) {
        array_push($quizVlD, $suspensionRessortDecla->_id);
        array_push($quizVlsD, $suspensionRessortDecla->_id);
    }
    if ($suspensionPneuDecla) {
        array_push($quizCamTrckD, $suspensionPneuDecla->_id);
        array_push($quizVlD, $suspensionPneuDecla->_id);
    }
    if ($transversaleDecla) {
        array_push($quizBusD, $transversaleDecla->_id);
        array_push($quizCamD, $transversaleDecla->_id);
        array_push($quizCamTrckD, $transversaleDecla->_id);
        array_push($quizCamOD, $transversaleDecla->_id);
        array_push($quizVlD, $transversaleDecla->_id);
        array_push($quizVlsD, $transversaleDecla->_id);
        array_push($quizEngD, $transversaleDecla->_id);
        array_push($quizChaD, $transversaleDecla->_id);
        array_push($quizChaBtD, $transversaleDecla->_id);
    }
    
    if ( empty( $label ) ||
    empty( $level ) ||
    empty( $brand ) ) {
        $error = 'Champ obligatoire';
    } elseif ( $exist ) {
        $error_msg = 'Ce véhicule existe déjà.';
    } elseif ($label == "Bus")  {

        if ($level == 'Junior') {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizBusF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBusF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );

            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizBusD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBusD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );
                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior') {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizBusF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBusF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );

            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizBusD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBusD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert') {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizBusF,
                'label' => ucfirst( $label ),
                'type' => 'Facteul',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBusF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizBusD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizBusD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Camions")  {
        if ($level == 'Junior' && $brand == "MERCEDES TRUCK" || $brand == "RENAULT TRUCK") {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizCamTrckF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrckF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );

            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizCamTrckD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrckD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand == "FUSO" || $brand == "HINO") {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizCamOF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamOF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );

            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizCamOD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamOD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand == "SINOTRUK") {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizCamF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizCamD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "MERCEDES TRUCK" || $brand == "RENAULT TRUCK") {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizCamTrckF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrckF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizCamTrckD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrckD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "FUSO" || $brand == "HINO") {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizCamOF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamOF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizCamOD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamOD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "SINOTRUK") {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizCamF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizCamD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "MERCEDES TRUCK" || $brand == "RENAULT TRUCK") {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizCamTrckF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrckF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizCamTrckD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamTrckD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "FUSO" || $brand == "HINO") {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizCamOF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamOF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizCamOD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamOD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "SINOTRUK") {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizCamF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizCamD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizCamD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Chariots") {

        if ($level == 'Junior' && $brand == "TOYOTA BT") {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizChaBtF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBtF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizChaBtD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBtD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand == "TOYOTA FORFLIT") {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizChaF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizChaD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "TOYOTA BT")  {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizChaBtF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBtF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizChaBtD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBtD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "TOYOTA FORFLIT")  {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizChaF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizChaD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "TOYOTA BT")  {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizChaBtF,
                'label' => ucfirst( $label ),
                'type' =>'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBtF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizChaBtD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaBtD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "TOYOTA FORFLIT")  {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizChaF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizChaD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizChaD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Engins") {

        if ($level == 'Junior') {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizEngF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEngF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizEngD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEngD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior') {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizEngF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEngF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizEngD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEngD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert') {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizEngF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEngF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizEngD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizEngD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        }
    } elseif ($label == "Voitures") {

        if ($level == 'Junior' && $brand == "SUZUKI") {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizVlsF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlsF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );

            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizVlsD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlsD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Junior' && $brand != "SUZUKI") {
            $vehicleFac = [
                'users' => $userArr,
                'quizzes' => $quizVlF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArr,
                'quizzes' => $quizVlD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
    
            for ($i = 0; $i < count($userArr); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArr[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand == "SUZUKI") {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizVlsF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlsF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizVlsD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlsD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Senior' && $brand != "SUZUKI") {
            $vehicleFac = [
                'users' => $userArrR,
                'quizzes' => $quizVlF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrR,
                'quizzes' => $quizVlD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrR); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrR[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand == "SUZUKI") {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizVlsF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlsF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizVlsD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlsD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
            }
            $success_msg = 'Véhicule ajouté avec succès';
        } elseif ($level == 'Expert' && $brand != "SUZUKI") {
            $vehicleFac = [
                'users' => $userArrD,
                'quizzes' => $quizVlF,
                'label' => ucfirst( $label ),
                'type' => 'Factuel',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlF),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultFac = $vehicles->insertOne( $vehicleFac );
            
            $vehicleDecla = [
                'users' => $userArrD,
                'quizzes' => $quizVlD,
                'label' => ucfirst( $label ),
                'type' => 'Declaratif',
                'brand' => $brand,
                'level' => ucfirst( $level ),
                'total' => count($quizVlD),
                'active' => true,
                'created' => date("d-m-Y")
            ];
            $resultDecla = $vehicles->insertOne( $vehicleDecla );
            for ($i = 0; $i < count($userArrD); $i++) {
                $allocateFac = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultFac->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Factuel',
                    'level' => $level,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateFac );

                $allocateDecla = [
                    'vehicle' => new MongoDB\BSON\ObjectId( $resultDecla->getInsertedId() ),
                    'user' => new MongoDB\BSON\ObjectId( $userArrD[$i] ),
                    'type' => 'Declaratif',
                    'level' => $level,
                    'activeManager' => false,
                    'active' => false,
                    'created' => date("d-m-Y"),
                ];
                $allocations->insertOne( $allocateDecla );
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
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row hidden" id="metier">
                          <!--begin::Label-->
                          <label class="form-label fw-bolder text-dark fs-6">
                            <span class="required">Niveau</span> <span class="ms-1" data-bs-toggle="tooltip" title="Choississez le niveau du technicien ou du manager">
                              <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                          </label>
                          <!--end::Label-->
                          <!--begin::Input-->
                          <select name="level" aria-label="Select a Country" data-control="select2" data-placeholder="Sélectionnez le niveau..." class="form-select form-select-solid fw-bold">
                            <option>Sélectionnez le
                              niveau...</option>
                            <option <?php if (isset($_POST['submit']) && $_POST['level'] == "Junior") { echo 'selected = selected'; } ?> value="Junior">
                              Junior
                            </option>
                            <option <?php if (isset($_POST['submit']) && $_POST['level'] == "Senior") { echo 'selected = selected'; } ?> value="Senior">
                              Senior
                            </option>
                            <option <?php if (isset($_POST['submit']) && $_POST['level'] == "Expert") { echo 'selected = selected'; } ?> value="Expert">
                              Expert
                            </option>
                          </select>
                          <!--end::Input-->
                          <?php
                          if (isset($error)) {
                              ?>
                          <span class='text-danger'>
                            <?php echo $error; ?>
                          </span>
                          <?php
                                 } ?>
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