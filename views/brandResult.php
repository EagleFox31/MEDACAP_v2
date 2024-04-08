<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ./index.php");
    exit();
} else {

    require_once "../vendor/autoload.php";

    // Create connection
    $conn = new MongoDB\Client("mongodb://localhost:27017");

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $results = $academy->results;

    $user = $_GET["user"];
    $level = $_GET["level"];

    $technician = $users->findOne([
        '$and' => [
            [
                "_id" => new MongoDB\BSON\ObjectId($user),
                "active" => true,
            ],
        ],
    ]);
    for (
        $i = 0;
        $i < count($technician['brand']);
        $i++
    ) {
        if ($technician['brand'][$i] == 'KING LONG') {
            $kingLong = 'KING LONG';
        }
        if ($technician['brand'][$i] == 'FUSO') {
            $fuso = 'FUSO';
        }
        if ($technician['brand'][$i] == 'HINO') {
            $hino = 'HINO';
        }
        if ($technician['brand'][$i] == 'MERCEDES TRUCK') {
            $mercedesTruck = 'MERCEDES TRUCK';
        }
        if ($technician['brand'][$i] == 'RENAULT TRUCK') {
            $renaultTruck = 'RENAULT TRUCK';
        }
        if ($technician['brand'][$i] == 'SINOTRUK') {
            $sinotruk = 'SINOTRUK';
        }
        if ($technician['brand'][$i] == 'TOYOTA BT') {
            $toyotaBt = 'TOYOTA BT';
        }
        if ($technician['brand'][$i] == 'TOYOTA FORKLIFT') {
            $toyotaForflift = 'TOYOTA FORKLIFT';
        }
        if ($technician['brand'][$i] == 'JCB') {
            $jcb = 'JCB';
        }
        if ($technician['brand'][$i] == 'LOVOL') {
            $hino = 'LOVOL';
        }
        if ($technician['brand'][$i] == 'CITROEN') {
            $citroen = 'CITROEN';
        }
        if ($technician['brand'][$i] == 'MERCEDES') {
            $mercedes = 'MERCEDES';
        }
        if ($technician['brand'][$i] == 'PEUGEOT') {
            $peugeot = 'PEUGEOT';
        }
        if ($technician['brand'][$i] == 'SUZUKI') {
            $suzuki = 'SUZUKI';
        }
        if ($technician['brand'][$i] == 'TOYOTA') {
            $toyota = 'TOYOTA';
        }
    }
    $transmissionFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Arbre de Transmission",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $transmissionDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Arbre de Transmission",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $transmissionMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Arbre de Transmission",
            ],
            ["active" => true],
        ],
    ]);
    $assistanceConduiteFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Assistance à la Conduite",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $assistanceConduiteDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Assistance à la Conduite",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $assistanceConduiteMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Assistance à la Conduite",
            ],
            ["active" => true],
        ],
    ]);
    $transfertFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Boite de Transfert"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $transfertDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Boite de Transfert"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $transfertMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Boite de Transfert"],
            ["active" => true],
        ],
    ]);
    $boiteFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Boite de Vitesse"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $boiteDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Boite de Vitesse"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $boiteMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Boite de Vitesse"],
            ["active" => true],
        ],
    ]);
    $boiteManFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse Mécanique",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $boiteManDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse Mécanique",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $boiteManMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse Mécanique",
            ],
            ["active" => true],
        ],
    ]);
    $boiteAutoFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse Automatique",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $boiteAutoDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse Automatique",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $boiteAutoMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse Automatique",
            ],
            ["active" => true],
        ],
    ]);
    $boiteVaCoFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse à Variation Continue",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $boiteVaCoDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse à Variation Continue",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $boiteVaCoMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Boite de Vitesse à Variation Continue",
            ],
            ["active" => true],
        ],
    ]);
    $climatisationFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Climatisation"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $climatisationDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Climatisation"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $climatisationMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Climatisation"],
            ["active" => true],
        ],
    ]);
    $demiFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Demi Arbre de Roue"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $demiDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Demi Arbre de Roue"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $demiMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Demi Arbre de Roue"],
            ["active" => true],
        ],
    ]);
    $directionFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Direction"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $directionDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Direction"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $directionMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Direction"],
            ["active" => true],
        ],
    ]);
    $electriciteFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Electricité et Electronique",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $electriciteDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Electricité et Electronique",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $electriciteMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Electricité et Electronique",
            ],
            ["active" => true],
        ],
    ]);
    $freiFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Freinage"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $freiDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Freinage"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $freiMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Freinage"],
            ["active" => true],
        ],
    ]);
    $freinageElecFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Electromagnétique",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $freinageElecDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Electromagnétique",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $freinageElecMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Electromagnétique",
            ],
            ["active" => true],
        ],
    ]);
    $freinageFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Hydraulique",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $freinageDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Hydraulique",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $freinageMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Hydraulique",
            ],
            ["active" => true],
        ],
    ]);
    $freinFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Pneumatique",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $freinDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Pneumatique",
            ],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $freinMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Freinage Pneumatique",
            ],
            ["active" => true],
        ],
    ]);
    $hydrauliqueFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Hydraulique"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $hydrauliqueDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Hydraulique"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $hydrauliqueMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Hydraulique"],
            ["active" => true],
        ],
    ]);
    $moteurDieselFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Diesel"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $moteurDieselDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Diesel"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $moteurDieselMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Diesel"],
            ["active" => true],
        ],
    ]);
    $moteurElecFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Electrique"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $moteurElecDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Electrique"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $moteurElecMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Electrique"],
            ["active" => true],
        ],
    ]);
    $moteurEssenceFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Essence"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $moteurEssenceDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Essence"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $moteurEssenceMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Essence"],
            ["active" => true],
        ],
    ]);
    $moteurThermiqueFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Thermique"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $moteurThermiqueDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Thermique"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $moteurThermiqueMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Moteur Thermique"],
            ["active" => true],
        ],
    ]);
    $multiplexageFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Multiplexage"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $multiplexageDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Multiplexage"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $multiplexageMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Multiplexage"],
            ["active" => true],
        ],
    ]);
    $pneuFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Pneumatique"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $pneuDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Pneumatique"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $pneuMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Pneumatique"],
            ["active" => true],
        ],
    ]);
    $pontFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Pont"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $pontDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Pont"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $pontMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Pont"],
            ["active" => true],
        ],
    ]);
    $reducteurFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Réducteur"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $reducteurDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Réducteur"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $reducteurMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Réducteur"],
            ["active" => true],
        ],
    ]);
    $suspensionFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $suspensionDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $suspensionMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension"],
            ["active" => true],
        ],
    ]);
    $suspensionLameFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension à Lame"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $suspensionLameDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension à Lame"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $suspensionLameMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension à Lame"],
            ["active" => true],
        ],
    ]);
    $suspensionRessortFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension Ressort"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $suspensionRessortDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension Ressort"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $suspensionRessortMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Suspension Ressort"],
            ["active" => true],
        ],
    ]);
    $suspensionPneumatiqueFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Suspension Pneumatique",
            ],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $suspensionPneumatiqueDecla = $results->findOne(
        [
            '$and' => [
                [
                    "user" => new MongoDB\BSON\ObjectId(
                        $user
                    ),
                ],
                ["level" => $level],
                [
                    "speciality" =>
                        "Suspension Pneumatique",
                ],
                ["type" => "Declaratif"],
            ["active" => true],
            ],
        ]
    );
    $suspensionPneumatiqueMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            [
                "speciality" =>
                    "Suspension Pneumatique",
            ],
            ["active" => true],
        ],
    ]);
    $transversaleFac = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Transversale"],
            ["type" => "Factuel"],
            ["active" => true],
        ],
    ]);
    $transversaleDecla = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            ["level" => $level],
            ["speciality" => "Transversale"],
            ["type" => "Declaratif"],
            ["active" => true],
        ],
    ]);
    $transversaleMa = $results->findOne([
        '$and' => [
            [
                "user" => new MongoDB\BSON\ObjectId(
                    $user
                ),
            ],
            [
                "manager" => new MongoDB\BSON\ObjectId(
                    $technician->manager
                ),
            ],
            ["level" => $level],
            ["speciality" => "Transversale"],
            ["active" => true],
        ],
    ]);
    $resultFac = $results->findOne([
        '$and' => [
            ["user" => new MongoDB\BSON\ObjectId($user)],
            ["type" => "Factuel"],
            ["typeR" => "Technicien"],
            ["level" => $level],
            ["active" => true],
        ],
    ]);
    $resultDecla = $results->findOne([
        '$and' => [
            ["user" => new MongoDB\BSON\ObjectId($user)],
            ["type" => "Declaratif"],
            ["typeR" => "Techniciens"],
            ["level" => $level],
            ["active" => true],
        ],
    ]);
    $resultMa = $results->findOne([
        '$and' => [
            ["user" => new MongoDB\BSON\ObjectId($user)],
            ["manager" => new MongoDB\BSON\ObjectId($technician->manager)],
            ["typeR" => "Managers"],
            ["level" => $level],
            ["active" => true],
        ],
    ]);
    $resultTechMa = $results->findOne([
        '$and' => [
            ["user" => new MongoDB\BSON\ObjectId($user)],
            ["manager" => new MongoDB\BSON\ObjectId($technician->manager)],
            ["typeR" => "Technicien - Manager"],
            ["level" => $level],
            ["active" => true],
        ],
    ]);
    $transmissionTotalFac = $transmissionFac->total ?? 0;
    $assistanceConduiteTotalFac = $assistanceConduiteFac->total ?? 0;
    $transfertTotalFac = $transfertFac->total ?? 0;
    $boiteTotalFac = $boiteFac->total ?? 0;
    $boiteManTotalFac = $boiteManFac->total ?? 0;
    $boiteAutoTotalFac = $boiteAutoFac->total ?? 0;
    $boiteVaCoTotalFac = $boiteVaCoFac->total ?? 0;
    $climatisationTotalFac = $climatisationFac->total ?? 0;
    $demiTotalFac = $demiFac->total ?? 0;
    $directionTotalFac = $directionFac->total ?? 0;
    $electriciteTotalFac = $electriciteFac->total ?? 0;
    $freiTotalFac = $freiFac->total ?? 0;
    $freinageElecTotalFac = $freinageElecFac->total ?? 0;
    $freinageTotalFac = $freinageFac->total ?? 0;
    $freinTotalFac = $freinFac->total ?? 0;
    $hydrauliqueTotalFac = $hydrauliqueFac->total ?? 0;
    $moteurDieselTotalFac = $moteurDieselFac->total ?? 0;
    $moteurEssenceTotalFac = $moteurEssenceFac->total ?? 0;
    $moteurElecTotalFac = $moteurElecFac->total ?? 0;
    $moteurThermiqueTotalFac = $moteurThermiqueFac->total ?? 0;
    $multiplexageTotalFac = $multiplexageFac->total ?? 0;
    $pneuTotalFac = $pneuFac->total ?? 0;
    $pontTotalFac = $pontFac->total ?? 0;
    $reducteurTotalFac = $reducteurFac->total ?? 0;
    $suspensionTotalFac = $suspensionFac->total ?? 0;
    $suspensionLameTotalFac = $suspensionLameFac->total ?? 0;
    $suspensionRessortTotalFac = $suspensionRessortFac->total ?? 0;
    $suspensionPneumatiqueTotalFac = $suspensionPneumatiqueFac->total ?? 0;
    $transversaleTotalFac = $transversaleFac->total;
    
    $transmissionTotalDecla = $transmissionDecla->total ?? 0;
    $assistanceConduiteTotalDecla = $assistanceConduiteDecla->total ?? 0;
    $transfertTotalDecla = $transfertDecla->total ?? 0;
    $boiteTotalDecla = $boiteDecla->total ?? 0;
    $boiteManTotalDecla = $boiteManDecla->total ?? 0;
    $boiteAutoTotalDecla = $boiteAutoDecla->total ?? 0;
    $boiteVaCoTotalDecla = $boiteVaCoDecla->total ?? 0;
    $climatisationTotalDecla = $climatisationDecla->total ?? 0;
    $demiTotalDecla = $demiDecla->total ?? 0;
    $directionTotalDecla = $directionDecla->total ?? 0;
    $electriciteTotalDecla = $electriciteDecla->total ?? 0;
    $freiTotalDecla = $freiDecla->total ?? 0;
    $freinageElecTotalDecla = $freinageElecDecla->total ?? 0;
    $freinageTotalDecla = $freinageDecla->total ?? 0;
    $freinTotalDecla = $freinDecla->total ?? 0;
    $hydrauliqueTotalDecla = $hydrauliqueDecla->total ?? 0;
    $moteurDieselTotalDecla = $moteurDieselDecla->total ?? 0;
    $moteurEssenceTotalDecla = $moteurEssenceDecla->total ?? 0;
    $moteurElecTotalDecla = $moteurElecDecla->total ?? 0;
    $moteurThermiqueTotalDecla = $moteurThermiqueDecla->total ?? 0;
    $multiplexageTotalDecla = $multiplexageDecla->total ?? 0;
    $pneuTotalDecla = $pneuDecla->total ?? 0;
    $pontTotalDecla = $pontDecla->total ?? 0;
    $reducteurTotalDecla = $reducteurDecla->total ?? 0;
    $suspensionTotalDecla = $suspensionDecla->total ?? 0;
    $suspensionLameTotalDecla = $suspensionLameDecla->total ?? 0;
    $suspensionRessortTotalDecla = $suspensionRessortDecla->total ?? 0;
    $suspensionPneumatiqueTotalDecla = $suspensionPneumatiqueDecla->total ?? 0;
    $transversaleTotalDecla = $transversaleDecla->total;

    $transmissionScoreFac = $transmissionFac->score ?? 0;
    $assistanceConduiteScoreFac = $assistanceConduiteFac->score ?? 0;
    $transfertScoreFac = $transfertFac->score ?? 0;
    $boiteScoreFac = $boiteFac->score ?? 0;
    $boiteManScoreFac = $boiteManFac->score ?? 0;
    $boiteAutoScoreFac = $boiteAutoFac->score ?? 0;
    $boiteVaCoScoreFac = $boiteVaCoFac->score ?? 0;
    $climatisationScoreFac = $climatisationFac->score ?? 0;
    $demiScoreFac = $demiFac->score ?? 0;
    $directionScoreFac = $directionFac->score ?? 0;
    $electriciteScoreFac = $electriciteFac->score ?? 0;
    $freiScoreFac = $freiFac->score ?? 0;
    $freinageElecScoreFac = $freinageElecFac->score ?? 0;
    $freinageScoreFac = $freinageFac->score ?? 0;
    $freinScoreFac = $freinFac->score ?? 0;
    $hydrauliqueScoreFac = $hydrauliqueFac->score ?? 0;
    $moteurDieselScoreFac = $moteurDieselFac->score ?? 0;
    $moteurEssenceScoreFac = $moteurEssenceFac->score ?? 0;
    $moteurElecScoreFac = $moteurElecFac->score ?? 0;
    $moteurThermiqueScoreFac = $moteurThermiqueFac->score ?? 0;
    $multiplexageScoreFac = $multiplexageFac->score ?? 0;
    $pneuScoreFac = $pneuFac->score ?? 0;
    $pontScoreFac = $pontFac->score ?? 0;
    $reducteurScoreFac = $reducteurFac->score ?? 0;
    $suspensionScoreFac = $suspensionFac->score ?? 0;
    $suspensionLameScoreFac = $suspensionLameFac->score ?? 0;
    $suspensionRessortScoreFac = $suspensionRessortFac->score ?? 0;
    $suspensionPneumatiqueScoreFac = $suspensionPneumatiqueFac->score ?? 0;
    $transversaleScoreFac = $transversaleFac->score;
    
    $transmissionScoreDecla = $transmissionDecla->score ?? 0;
    $assistanceConduiteScoreDecla = $assistanceConduiteDecla->score ?? 0;
    $transfertScoreDecla = $transfertDecla->score ?? 0;
    $boiteScoreDecla = $boiteDecla->score ?? 0;
    $boiteManScoreDecla = $boiteManDecla->score ?? 0;
    $boiteAutoScoreDecla = $boiteAutoDecla->score ?? 0;
    $boiteVaCoScoreDecla = $boiteVaCoDecla->score ?? 0;
    $climatisationScoreDecla = $climatisationDecla->score ?? 0;
    $demiScoreDecla = $demiDecla->score ?? 0;
    $directionScoreDecla = $directionDecla->score ?? 0;
    $electriciteScoreDecla = $electriciteDecla->score ?? 0;
    $freiScoreDecla = $freiDecla->score ?? 0;
    $freinageElecScoreDecla = $freinageElecDecla->score ?? 0;
    $freinageScoreDecla = $freinageDecla->score ?? 0;
    $freinScoreDecla = $freinDecla->score ?? 0;
    $hydrauliqueScoreDecla = $hydrauliqueDecla->score ?? 0;
    $moteurDieselScoreDecla = $moteurDieselDecla->score ?? 0;
    $moteurEssenceScoreDecla = $moteurEssenceDecla->score ?? 0;
    $moteurElecScoreDecla = $moteurElecDecla->score ?? 0;
    $moteurThermiqueScoreDecla = $moteurThermiqueDecla->score ?? 0;
    $multiplexageScoreDecla = $multiplexageDecla->score ?? 0;
    $pneuScoreDecla = $pneuDecla->score ?? 0;
    $pontScoreDecla = $pontDecla->score ?? 0;
    $reducteurScoreDecla = $reducteurDecla->score ?? 0;
    $suspensionScoreDecla = $suspensionDecla->score ?? 0;
    $suspensionLameScoreDecla = $suspensionLameDecla->score ?? 0;
    $suspensionRessortScoreDecla = $suspensionRessortDecla->score ?? 0;
    $suspensionPneumatiqueScoreDecla = $suspensionPneumatiqueDecla->score ?? 0;
    $transversaleScoreDecla = $transversaleDecla->score;
    
    $transmissionScoreMa = $transmissionMa->score ?? 0;
    $assistanceConduiteScoreMa = $assistanceConduiteMa->score ?? 0;
    $transfertScoreMa = $transfertMa->score ?? 0;
    $boiteScoreMa = $boiteMa->score ?? 0;
    $boiteManScoreMa = $boiteManMa->score ?? 0;
    $boiteAutoScoreMa = $boiteAutoMa->score ?? 0;
    $boiteVaCoScoreMa = $boiteVaCoMa->score ?? 0;
    $climatisationScoreMa = $climatisationMa->score ?? 0;
    $demiScoreMa = $demiMa->score ?? 0;
    $directionScoreMa = $directionMa->score ?? 0;
    $electriciteScoreMa = $electriciteMa->score ?? 0;
    $freiScoreMa = $freiMa->score ?? 0;
    $freinageElecScoreMa = $freinageElecMa->score ?? 0;
    $freinageScoreMa = $freinageMa->score ?? 0;
    $freinScoreMa = $freinMa->score ?? 0;
    $hydrauliqueScoreMa = $hydrauliqueMa->score ?? 0;
    $moteurDieselScoreMa = $moteurDieselMa->score ?? 0;
    $moteurEssenceScoreMa = $moteurEssenceMa->score ?? 0;
    $moteurElecScoreMa = $moteurElecMa->score ?? 0;
    $moteurThermiqueScoreMa = $moteurThermiqueMa->score ?? 0;
    $multiplexageScoreMa = $multiplexageMa->score ?? 0;
    $pneuScoreMa = $pneuMa->score ?? 0;
    $pontScoreMa = $pontMa->score ?? 0;
    $reducteurScoreMa = $reducteurMa->score ?? 0;
    $suspensionScoreMa = $suspensionMa->score ?? 0;
    $suspensionLameScoreMa = $suspensionLameMa->score ?? 0;
    $suspensionRessortScoreMa = $suspensionRessortMa->score ?? 0;
    $suspensionPneumatiqueScoreMa = $suspensionPneumatiqueMa->score ?? 0;
    $transversaleScoreMa = $transversaleMa->score;

    if (isset($toyota) == 'TOYOTA') {
        $toyotaFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
        $toyotaDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
    
        $toyotaScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
        $toyotaScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
        $toyotaScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
    }
    if (isset($suzuki) == 'SUZUKI') {
        $suzukiFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $transversaleTotalFac;
        $suzukiDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $transversaleTotalDecla;
        
        $suzukiScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $transversaleScoreFac;
        $suzukiScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $transversaleScoreDecla;
        $suzukiScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $transversaleScoreMa;
    }
    if (isset($mercedes) == 'MERCEDES') {
        $mercedesFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
        $mercedesDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
        
        $mercedesScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
        $mercedesScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
        $mercedesScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
    }
    if (isset($peugeot) == 'PEUGEOT') {
        $peugeotFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
        $peugeotDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;

        $peugeotScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
        $peugeotScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
        $peugeotScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
    }
    if (isset($citroen) == 'CITROEN') {
        $citroenFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $boiteAutoTotalFac + $boiteVaCoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac + $moteurDieselTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionRessortTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
        $citroenDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $boiteAutoTotalDecla + $boiteVaCoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla + $moteurDieselTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionRessortTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;

        $citroenScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $boiteAutoScoreFac + $boiteVaCoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac + $moteurDieselScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionRessortScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
        $citroenScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $boiteAutoScoreDecla + $boiteVaCoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla + $moteurDieselScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionRessortScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
        $citroenScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $boiteAutoScoreMa + $boiteVaCoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa + $moteurDieselScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionRessortScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
    }
    if (isset($kingLong) == 'KING LONG') {
        $kingLongFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
        $kingLongDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
        
        $kingLongScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
        $kingLongScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
        $kingLongScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
    }
    if (isset($fuso) == 'FUSO') {
        $fusoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
        $fusoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
        
        $fusoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
        $fusoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
        $fusoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
    }
    if (isset($hino) == 'HINO') {
        $hinoFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $boiteTotalFac + $boiteManTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
        $hinoDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
        
        $hinoScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $boiteScoreFac + $boiteManScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
        $hinoScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
        $hinoScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $boiteScoreMa + $boiteManScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
    }
    if (isset($renalutTruck) == 'RENAULT TRUCK') {
        $renaultTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
        $renaultTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
        
        $renaultTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
        $renaultTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
        $renaultTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
    }
    if (isset($mercedesTruck) == 'MERCEDES TRUCK') {
        $mercedesTruckFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $suspensionPneumatiqueTotalFac + $transversaleTotalFac;
        $mercedesTruckDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $suspensionPneumatiqueTotalDecla + $transversaleTotalDecla;
        
        $mercedesTruckScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $suspensionPneumatiqueScoreFac + $transversaleScoreFac;
        $mercedesTruckScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $suspensionPneumatiqueScoreDecla + $transversaleScoreDecla;
        $mercedesTruckScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $suspensionPneumatiqueScoreMa + $transversaleScoreMa;
    }
    if (isset($sinotruk) == 'SINOTRUK') {
        $sinotrukFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $suspensionTotalFac + $suspensionLameTotalFac + $transversaleTotalFac;
        $sinotrukDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $suspensionTotalDecla + $suspensionLameTotalDecla + $transversaleTotalDecla;
        
        $sinotrukScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $suspensionScoreFac + $suspensionLameScoreFac + $transversaleScoreFac;
        $sinotrukScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $suspensionScoreDecla + $suspensionLameScoreDecla + $transversaleScoreDecla;
        $sinotrukScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $suspensionScoreMa + $suspensionLameScoreMa + $transversaleScoreMa;
    }
    if (isset($jcb) == 'JCB') {
        $jcbFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
        $jcbDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
        
        $jcbScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
        $jcbScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
        $jcbScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
    }
    if (isset($lovol) == 'LOVOL') {
        $lovolFac = $transmissionTotalFac + $assistanceConduiteTotalFac + $transfertTotalFac + $boiteTotalFac + $boiteManTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
        $lovolDecla = $transmissionTotalDecla + $assistanceConduiteTotalDecla + $transfertTotalDecla + $boiteTotalDecla + $boiteManTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
        
        $lovolScoreFac = $transmissionScoreFac + $assistanceConduiteScoreFac + $transfertScoreFac + $boiteScoreFac + $boiteManScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
        $lovolScoreDecla = $transmissionScoreDecla + $assistanceConduiteScoreDecla + $transfertScoreDecla + $boiteScoreDecla + $boiteManScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
        $lovolScoreMa = $transmissionScoreMa + $assistanceConduiteScoreMa + $transfertScoreMa + $boiteScoreMa + $boiteManScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
    }
    if (isset($toyotaBt) == 'TOYOTA BT') {
        $toyotaBtFac = $assistanceConduiteTotalFac + $boiteTotalFac + $climatisationTotalFac + $directionTotalFac + $electriciteTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurElecTotalFac + $multiplexageTotalFac + $pneuTotalFac + $reducteurTotalFac + $transversaleTotalFac;
        $toyotaBtDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $climatisationTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurElecTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
        
        $toyotaBtScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $climatisationScoreFac + $directionScoreFac + $electriciteScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurElecScoreFac + $multiplexageScoreFac + $pneuScoreFac + $reducteurScoreFac + $transversaleScoreFac;
        $toyotaBtScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $climatisationScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurElecScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
        $toyotaBtScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $climatisationScoreMa + $directionScoreMa + $electriciteScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurElecScoreMa + $multiplexageScoreMa + $pneuScoreMa + $reducteurScoreMa + $transversaleScoreMa;
    }
    if (isset($toyotaForflift) == 'TOYOTA FORKLIFT') {
        $toyotaForfliftFac = $assistanceConduiteTotalFac + $boiteTotalFac + $boiteAutoTotalFac + $climatisationTotalFac + $demiTotalFac + $directionTotalFac + $electriciteTotalFac + $freiTotalFac + $freinageElecTotalFac + $freinageTotalFac +  $hydrauliqueTotalFac + $moteurDieselTotalFac + $moteurElecTotalFac + $moteurEssenceTotalFac + $moteurThermiqueTotalFac + $multiplexageTotalFac + $pneuTotalFac + $pontTotalFac + $reducteurTotalFac + $transversaleTotalFac;
        $toyotaForfliftDecla = $assistanceConduiteTotalDecla + $boiteTotalDecla + $boiteAutoTotalDecla + $climatisationTotalDecla + $demiTotalDecla + $directionTotalDecla + $electriciteTotalDecla + $freiTotalDecla + $freinageElecTotalDecla + $freinageTotalDecla +  $hydrauliqueTotalDecla + $moteurDieselTotalDecla + $moteurElecTotalDecla + $moteurEssenceTotalDecla + $moteurThermiqueTotalDecla + $multiplexageTotalDecla + $pneuTotalDecla + $pontTotalDecla + $reducteurTotalDecla + $transversaleTotalDecla;
        
        $toyotaForfliftScoreFac = $assistanceConduiteScoreFac + $boiteScoreFac + $boiteAutoScoreFac + $climatisationScoreFac + $demiScoreFac + $directionScoreFac + $electriciteScoreFac + $freiScoreFac + $freinageElecScoreFac + $freinageScoreFac +  $hydrauliqueScoreFac + $moteurDieselScoreFac + $moteurElecScoreFac + $moteurEssenceScoreFac + $moteurThermiqueScoreFac + $multiplexageScoreFac + $pneuScoreFac + $pontScoreFac + $reducteurScoreFac + $transversaleScoreFac;
        $toyotaForfliftScoreDecla = $assistanceConduiteScoreDecla + $boiteScoreDecla + $boiteAutoScoreDecla + $climatisationScoreDecla + $demiScoreDecla + $directionScoreDecla + $electriciteScoreDecla + $freiScoreDecla + $freinageElecScoreDecla + $freinageScoreDecla +  $hydrauliqueScoreDecla + $moteurDieselScoreDecla + $moteurElecScoreDecla + $moteurEssenceScoreDecla + $moteurThermiqueScoreDecla + $multiplexageScoreDecla + $pneuScoreDecla + $pontScoreDecla + $reducteurScoreDecla + $transversaleScoreDecla;
        $toyotaForfliftScoreMa = $assistanceConduiteScoreMa + $boiteScoreMa + $boiteAutoScoreMa + $climatisationScoreMa + $demiScoreMa + $directionScoreMa + $electriciteScoreMa + $freiScoreMa + $freinageElecScoreMa + $freinageScoreMa +  $hydrauliqueScoreMa + $moteurDieselScoreMa + $moteurElecScoreMa + $moteurEssenceScoreMa + $moteurThermiqueScoreMa + $multiplexageScoreMa + $pneuScoreMa + $pontScoreMa + $reducteurScoreMa + $transversaleScoreMa;
    }

    $percentageFac = ($resultFac['score'] * 100) / $resultFac['total'];
    $percentageTechMa = ($resultTechMa['score'] * 100) / $resultTechMa['total'];
    include_once "language.php";
    ?>
<title><?php echo $result_tech; ?> | CFAO Mobility Academy</title>
<!--end::Title-->
<!-- Favicon -->
<link href="../public/images/logo-cfao.png" rel="icon">

<link rel="canonical" href="https://preview.keenthemes.com/craft" />
<link rel="shortcut icon" href="/images/logo-cfao.png" />
<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="../public/assets/plugins/custom/leaflet/leaflet.bundle.css" rel="stylesheet" type="text/css" />
<link href="../public/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="../public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
<link href="../public/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">
<!--end::Global Stylesheets Bundle-->

<!--begin::Body-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" data-select2-id="select2-data-kt_content">
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Chargement...</span>
        </div>
    </div>
    <!-- Spinner End -->
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1" style="font-size: 50px;">
                    <?php echo $result_by_brand ?>
                    <?php echo $technician->firstName; ?> <?php echo $technician->lastName; ?>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Export-->
                        <button type="button" id="excel" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                            data-bs-target="#kt_customers_export_modal">
                            <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span
                                    class="path2"></span></i> <?php echo $excel ?>
                        </button>
                        <!--end::Export-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsi">
                        <table aria-describedby=""
                            class="table align-middle table-bordered table-row-dashed gy-5 dataTable no-footer"
                            id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold text-uppercase gs-0">
                                    <th class="min-w-125px sorting bg-primary text-white text-center table-light"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="49"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; font-size: 20px; ">
                                        <?php echo $result_mesure ?></th>
                                <tr></tr>
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" rowspan="5"
                                    aria-label="Email: activate to sort column ascending">
                                    <?php echo $groupe_fonctionnel ?></th>
                                <!-- <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending">
                                    Bâteaux</th> -->
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending">
                                    <?php echo $bus ?></th>
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="15"
                                    aria-label="Email: activate to sort column ascending">
                                    <?php echo $camions ?></th>
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="6"
                                    aria-label="Email: activate to sort column ascending">
                                    <?php echo $chariots ?></th>
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="6"
                                    aria-label="Email: activate to sort column ascending">
                                    <?php echo $engins ?></th>
                                <!-- <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending">
                                    Moto</th> -->
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="15"
                                    aria-label="Email: activate to sort column ascending">
                                    <?php echo $vl ?></th>
                                    <tr></tr>
                                <!-- <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    Yamaha</th> -->
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $kingLong ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $fuso ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $hino ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.366px;">
                                    <?php echo $mercedesTruck ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $renaultTruck ?></th>
                                <th class="min-w-135px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $sinotruk ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $toyotaBt ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $toyotaForklift ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $jcb ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $lovol ?></th>
                                <!-- <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    Yamaha</th> -->
                                <!-- <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    BYD</th> -->
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.366px;">
                                    <?php echo $citroen ?></th>
                                <th class="min-w-135px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $mercedes ?></th>
                                <!-- <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    Mitsubishi</th> -->
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $peugeot ?></th>
                                <th class="min-w-135px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.366px;">
                                    <?php echo $suzuki ?></th>
                                <th class="min-w-135px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.366px;">
                                    <?php echo $toyota ?></th>
                                    <tr></tr>
                                <!-- <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    Connaissances</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Technicien</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Manager</th> -->
                                <!-- <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    Connaissances</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Technicien</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Manager</th> -->
                                <!-- <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    Connaissances</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Technicien</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Manager</th> -->
                                <!-- <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    Connaissances</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Technicien</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Tâches Professionnelles du Manager</th> -->
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 175.266px;">
                                    <?php echo $connaissances ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_tech ?></th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    <?php echo $tache_pro_manager ?></th>
                            </thead>
                            <tbody class="fw-semibold text-gray-600" id="table">
                                <?php if (
                                    $transmissionFac &&
                                    $transmissionDecla &&
                                    $transmissionMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $arbre ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($jcbFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $transmissionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $transmissionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $transmissionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transmissionMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $assistanceConduiteFac &&
                                    $assistanceConduiteDecla &&
                                    $assistanceConduiteMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $assistanceConduite ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $assistanceConduiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $assistanceConduiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $assistanceConduiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($assistanceConduiteMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $transfertFac &&
                                    $transfertDecla &&
                                    $transfertMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $transfert ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($mercedesTruckFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($jcbFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $transfertFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $transfertDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $transfertMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transfertMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <?php if (
                                    $boiteFac &&
                                    $boiteDecla &&
                                    $boiteMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $boite_vitesse ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $boiteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $boiteAutoFac &&
                                    $boiteAutoDecla &&
                                    $boiteAutoMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $boite_vitesse_auto ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $boiteAutoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $boiteAutoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $boiteAutoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($citroenFac) && $boiteAutoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteAutoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteAutoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $boiteAutoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteAutoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteAutoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $boiteAutoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteAutoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteAutoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $boiteAutoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteAutoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteAutoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $boiteAutoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteAutoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteAutoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteAutoMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $boiteManFac &&
                                    $boiteManDecla &&
                                    $boiteManMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $boite_vitesse_meca ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($jcbFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $boiteManFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteManDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteManMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteManMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $boiteVaCoFac &&
                                    $boiteVaCoDecla &&
                                    $boiteVaCoMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $boite_vitesse_VC ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($citroenFac) && $boiteVaCoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteVaCoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $boiteVaCoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $boiteVaCoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteVaCoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $boiteVaCoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $boiteVaCoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteVaCoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $boiteVaCoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $boiteVaCoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteVaCoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $boiteVaCoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $boiteVaCoFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteVaCoDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $boiteVaCoMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($boiteVaCoMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $climatisationFac &&
                                    $climatisationDecla &&
                                    $climatisationMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $clim ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($mercedesTruckFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $climatisationFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $climatisationDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $climatisationMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($climatisationMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $demiFac &&
                                    $demiDecla &&
                                    $demiMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $demi ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $demiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $demiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $demiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($demiMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $directionFac &&
                                    $directionDecla &&
                                    $directionMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $direction ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $directionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $directionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $directionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($directionMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $electriciteFac &&
                                    $electriciteDecla &&
                                    $electriciteMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $electricite ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $electriciteFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $electriciteDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $electriciteMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($electriciteMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $freiFac &&
                                    $freiDecla &&
                                    $freiMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $freinage ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $freiFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $freiDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $freiMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freiMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $freinageElecFac &&
                                    $freinageElecDecla &&
                                    $freinageElecMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $freinageElec ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaBtFac) && $freinageElecFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageElecFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $freinageElecDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageElecDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $freinageElecMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageElecMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $freinageElecFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageElecFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $freinageElecDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageElecDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $freinageElecMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageElecMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td> 
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $freinageFac &&
                                    $freinageDecla &&
                                    $freinageMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $freinageHydro ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaBtFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $freinageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $freinageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $freinageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinageMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $freinFac &&
                                    $freinDecla &&
                                    $freinMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $freinagePneu ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $freinFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $freinDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $freinMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $freinFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $freinDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $freinMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $freinFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $freinDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $freinMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $freinFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $freinDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $freinMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $freinFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $freinDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $freinMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $freinFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $freinDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $freinMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($freinMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $hydrauliqueFac &&
                                    $hydrauliqueDecla &&
                                    $hydrauliqueMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $hydraulique ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($mercedesTruckFac) && $hydrauliqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $hydrauliqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $hydrauliqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $hydrauliqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $hydrauliqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $hydrauliqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $hydrauliqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $hydrauliqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $hydrauliqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $hydrauliqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $hydrauliqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $hydrauliqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $hydrauliqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $hydrauliqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $hydrauliqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $hydrauliqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $hydrauliqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $hydrauliqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $hydrauliqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $hydrauliqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $hydrauliqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($hydrauliqueMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td> 
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $moteurDieselFac &&
                                    $moteurDieselDecla &&
                                    $moteurDieselMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $moteurDiesel ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaFac) && $moteurDieselFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $moteurDieselDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $moteurDieselMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurDieselMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $moteurElecFac &&
                                    $moteurElecDecla &&
                                    $moteurElecMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $moteurElectrique ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaBtFac) && $moteurElecFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurElecFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $moteurElecDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurElecDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $moteurElecMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurElecMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $moteurElecFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurElecFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurElecDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurElecDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurElecMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurElecMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $moteurEssenceFac &&
                                    $moteurEssenceDecla &&
                                    $moteurEssenceMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $moteurEssence ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $moteurEssenceFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $moteurEssenceDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $moteurEssenceMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurEssenceMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $moteurThermiqueFac &&
                                    $moteurThermiqueDecla &&
                                    $moteurThermiqueMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $moteurThermique ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $moteurThermiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $moteurThermiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $moteurThermiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($moteurThermiqueMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $multiplexageFac &&
                                    $multiplexageDecla &&
                                    $multiplexageMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $multiplexage ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $multiplexageFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $multiplexageDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $multiplexageMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($multiplexageMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $pneuFac &&
                                    $pneuDecla &&
                                    $pneuMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $pneu ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $pneuFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $pneuDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $pneuMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pneuMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $pontFac &&
                                    $pontDecla &&
                                    $pontMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $pont ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $pontFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $pontDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $pontMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($pontMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $reducteurFac &&
                                    $reducteurDecla &&
                                    $reducteurMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $reducteur ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $reducteurFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $reducteurDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $reducteurMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($reducteurMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $suspensionFac &&
                                    $suspensionDecla &&
                                    $suspensionMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $suspension ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($citroenFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $suspensionFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $suspensionLameFac &&
                                    $suspensionLameDecla &&
                                    $suspensionLameMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $suspensionLame ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($citroenFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $suspensionLameFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionLameDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionLameMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionLameMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $suspensionRessortFac &&
                                    $suspensionRessortDecla &&
                                    $suspensionRessortMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $suspensionRessort ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($citroenFac) && $suspensionRessortFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionRessortDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionRessortMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $suspensionRessortFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionRessortDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionRessortMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $suspensionRessortFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionRessortDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionRessortMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $suspensionRessortFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $suspensionRessortDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $suspensionRessortMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $suspensionRessortFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionRessortDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionRessortMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionRessortMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $suspensionPneumatiqueFac &&
                                    $suspensionPneumatiqueDecla &&
                                    $suspensionPneumatiqueMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $suspensionPneu ?>
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($mercedesTruckFac) && $suspensionPneumatiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $suspensionPneumatiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $suspensionPneumatiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $suspensionPneumatiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $suspensionPneumatiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $suspensionPneumatiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($citroenFac) && $suspensionPneumatiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionPneumatiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $suspensionPneumatiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $suspensionPneumatiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionPneumatiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $suspensionPneumatiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $suspensionPneumatiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionPneumatiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $suspensionPneumatiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php if (
                                        isset($toyotaFac) && $suspensionPneumatiqueFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionPneumatiqueDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $suspensionPneumatiqueMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($suspensionPneumatiqueMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php if (
                                    $transversaleFac &&
                                    $transversaleDecla &&
                                    $transversaleMa
                                ) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <?php echo $transversale ?>
                                    </td>
                                    <?php if (
                                        isset($kingLongFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $kingLongFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $kingLongDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $fusoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $fusoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $hinoFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $hinoDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $mercedesTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $mercedesTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $renaultTruckFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $renaultTruckDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $sinotrukFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinotrukDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $sinotrukDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $toyotaBtFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $toyotaBtDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $toyotaForfliftFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $toyotaForfliftDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $jcbFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $jcbDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $lovolFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $lovolDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $citroenFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $citroenDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $mercedesFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $mercedesDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $peugeotFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $peugeotDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $suzukiFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $suzukiDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac) && $transversaleFac
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleFac->score * 100) / $toyotaFac)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $transversaleDecla
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleDecla->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla) && $transversaleMa
                                    ) { ?>
                                    <td class="text-center">
                                    <?php echo
                                    ceil(($transversaleMa->score * 100) / $toyotaDecla)
                                    ?>%
                                    </td>
                                    <?php } else { ?>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <?php } ?>  
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <tr>
                                    <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo $result ?></th>
                                    <?php if (
                                        isset($kingLongFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($kingLongScoreFac * 100) / $kingLongFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($kingLongDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($kingLongScoreDecla * 100) / $kingLongDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($kingLongDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($kingLongScoreMa * 100) / $kingLongDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($fusoScoreFac * 100) / $fusoFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($fusoDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($fusoScoreDecla * 100) / $fusoDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($fusoDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($fusoScoreMa * 100) / $fusoDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($hinoScoreFac * 100) / $hinoFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($hinoDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($hinoScoreDecla * 100) / $hinoDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($hinoDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($hinoScoreMa * 100) / $hinoDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($mercedesTruckScoreFac * 100) / $mercedesTruckFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($mercedesTruckDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($mercedesTruckScoreDecla * 100) / $mercedesTruckDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesTruckDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($mercedesTruckScoreMa * 100) / $mercedesTruckDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($renaultTruckScoreFac * 100) / $renaultTruckFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($renaultTruckDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($renaultTruckScoreDecla * 100) / $renaultTruckDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($renaultTruckDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($renaultTruckScoreMa * 100) / $renaultTruckDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinoTrukFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($sinoTrukScoreFac * 100) / $sinoTrukFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($sinoTrukDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($sinoTrukScoreDecla * 100) / $sinoTrukDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($sinoTrukDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($sinoTrukScoreMa * 100) / $sinoTrukDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaBtScoreFac * 100) / $toyotaBtFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($toyotaBtDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaBtScoreDecla * 100) / $toyotaBtDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaBtDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaBtScoreMa * 100) / $toyotaBtDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaForfliftScoreFac * 100) / $toyotaForfliftFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($toyotaForfliftDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaForfliftScoreDecla * 100) / $toyotaForfliftDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaForfliftDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaForfliftScoreMa * 100) / $toyotaForfliftDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($jcbScoreFac * 100) / $jcbFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($jcbDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($jcbScoreDecla * 100) / $jcbDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($jcbDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($jcbScoreMa * 100) / $jcbDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($lovolScoreFac * 100) / $lovolFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($lovolDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($lovolScoreDecla * 100) / $lovolDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($lovolDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($lovolScoreMa * 100) / $lovolDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($citroenScoreFac * 100) / $citroenFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($citroenDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($citroenScoreDecla * 100) / $citroenDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($citroenDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($citroenScoreMa * 100) / $citroenDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($mercedesScoreFac * 100) / $mercedesFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($mercedesDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($mercedesScoreDecla * 100) / $mercedesDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($mercedesDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($mercedesScoreMa * 100) / $mercedesDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($peugeotScoreFac * 100) / $peugeotFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($peugeotDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($peugeotScoreDecla * 100) / $peugeotDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($peugeotDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($peugeotScoreMa * 100) / $peugeotDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($suzukiScoreFac * 100) / $suzukiFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($suzukiDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($suzukiScoreDecla * 100) / $suzukiDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($suzukiDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($suzukiScoreMa * 100) / $suzukiDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaFac)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaScoreFac * 100) / $toyotaFac)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?> 
                                    <?php if (
                                        isset($toyotaDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaScoreDecla * 100) / $toyotaDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                    <?php if (
                                        isset($toyotaDecla)
                                    ) { ?>
                                        <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                            tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                            aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                            <?php echo
                                            ceil(($toyotaScoreMa * 100) / $toyotaDecla)
                                            ?>%
                                        </th>
                                    <?php } else { ?>
                                    <th class="min-w-125px sorting bg-primary text-black text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        -
                                    </th>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js">
</script>
<script src="../public/js/main.js"></script>
<script>
    
$(document).ready(function() {
    $("#excel").on("click", function() {
        let table = document.getElementsByTagName("table");
        debugger;
        TableToExcel.convert(table[0], {
            name: `Table.xlsx`
        })
    });
});
</script>
<?php
} ?>
