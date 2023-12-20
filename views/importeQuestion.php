<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
?>
<?php
require_once '../vendor/autoload.php';

if ( isset( $_POST[ 'submit' ] ) ) {
    // Create connection
    $conn = new MongoDB\Client( 'mongodb://localhost:27017' );

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $questions = $academy->questions;
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;


    $filePath = $_FILES['excel']['tmp_name'];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $data = $spreadsheet->getActiveSheet()->toArray();

    $array = [];

    foreach ($data as $row) {
        $label = $row["0"];
        $proposal1 = $row["1"];
        $proposal2 = $row["2"];
        $proposal3 = $row["3"];
        $proposal4 = $row["4"];
        $answer = $row["5"];
        $speciality = $row["6"];
        $type = $row["7"];
        $image = $row["8"];
        $level = $row["9"];
        
        $exist = $questions->findOne([
            '$and' => [
                [ 'label' => $label ],
                [ 'speciality' => $speciality ],
                [ 'level' => $level ],
                [ 'type' => $type ],
            ],
        ]);
        
        if ( $exist) {
            $error_msg = 'Cette question '.$label.' existe déjà.';
        } elseif ($type == "Factuelle") {
            $question = [
                'image' => $image,
                'label' => ucfirst( $label ),
                'proposal1' => ucfirst( $proposal1 ),
                'proposal2' => ucfirst( $proposal2 ),
                'proposal3' => ucfirst( $proposal3 ),
                'proposal4' => ucfirst( $proposal4 ),
                'answer' => ucfirst( $answer ),
                'speciality' => ucfirst( $speciality ),
                'type' => $type,
                'level' => $level,
                'active' =>true,
                'created' => date("d-m-y")
            ];
            $result = $questions->insertOne($question);
            
            $quizz = $quizzes->findOne([
                '$and' => [
                    [ 'speciality' => $speciality ],
                    [ 'level' => $level ],
                    [ 'type' => 'Factuel' ],
                    [ 'active' => true ],
                ]
            ]);

            $bus = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Bus' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Factuel' ],
                    [ 'active' => true ],
                ],
            ]);
            $camions = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Camions' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Factuel' ],
                    [ 'active' => true ],
                ],
            ]);
            $chariots = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Chariots' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Factuel' ],
                    [ 'active' => true ],
                ],
            ]);
            $engins = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Engins' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Factuel' ],
                    [ 'active' => true ],
                ],
            ]);
            $voitures = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Voitures' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Factuel' ],
                    [ 'active' => true ],
                ],
            ]);
            
            if ($quizz) {
                $quizz->total++;
                $quizzes->updateOne(
                    [ '_id' => new MongoDB\BSON\ObjectId( $quizz->_id ) ],
                    [ '$set' => $quizz ]
                );
                $quizzes->updateOne(
                    [ '_id' => new MongoDB\BSON\ObjectId( $quizz->_id ) ],
                    [ '$push' => [ 'questions' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                );
            } else {
            array_push($array, $result->getInsertedId());
            $quiz = [
                'questions' => $array,
                'label' => 'QCM '.$speciality.'',
                'type' => 'Factuel',
                'speciality' => ucfirst( $speciality ),
                'level' => ucfirst( $level ),
                'number' => 2,
                'total' => count($array),
                'active' =>true,
                'created' => date("d-m-y")
            ];
            $insert = $quizzes->insertOne( $quiz );
            
            
            if ($speciality == "Arbre de Transmission") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Assistance à la Conduite") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Boite de Transfert") {
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Boite de Vitesse") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Climatisation") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Direction") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Electricité") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Freinage Hydraulique") {
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Freinage Pneumatique") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
            } elseif ($speciality == "Hydraulique") {
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
            } elseif ($speciality == "Moteur Diesel") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Moteur Electrique") {
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Moteur Essence") {
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Multiplexage") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
            } elseif ($speciality == "Pont") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots && $chariots->brand == "TOYOTA FORFLIT") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Reducteur") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
            } elseif ($speciality == "Pneumatique") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Suspension à Lame") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Suspension Ressort") {
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Suspension Pneumatique") {
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($voitures && $voitures->brand != "SUZUKI") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Transversale") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            }
        }
        $success_msg = "Questions ajoutées avec succès";
        } elseif ($type == "Declarative") {
            $question = [
                'image' => $image,
                'label' => ucfirst( $label ),
                'proposal1' => "1-".$speciality."-".$level."-".$label."-1",
                'proposal2' => "0-".$speciality."-".$level."-".$label."-0",
                'speciality' => ucfirst( $speciality ),
                'type' => $type,
                'level' => $level,
                'active' =>true,
                'created' => date("d-m-y")
            ];
            
            $result = $questions->insertOne($question);
            $quizz = $quizzes->findOne([
                '$and' => [
                    [ 'speciality' => $speciality ],
                    [ 'level' => $level ],
                    [ 'type' => 'Declaratif' ],
                    [ 'active' => true ],
                ]
            ]);

            $bus = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Bus' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Declaratif' ],
                    [ 'active' => true ],
                ],
            ]);
            $camions = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Camions' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Declaratif' ],
                    [ 'active' => true ],
                ],
            ]);
            $chariots = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Chariots' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Declaratif' ],
                    [ 'active' => true ],
                ],
            ]);
            $engins = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Engins' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Declaratif' ],
                    [ 'active' => true ],
                ],
            ]);
            $voitures = $vehicles->findOne([
                '$and' => [
                    [ 'label' => 'Voitures' ],
                    [ 'level' => $level ],
                    [ 'type' => 'Declaratif' ],
                    [ 'active' => true ],
                ],
            ]);
            
            if ($quizz) {
                $quizz->total++;
                $quizzes->updateOne(
                    [ '_id' => new MongoDB\BSON\ObjectId( $quizz->_id ) ],
                    [ '$set' => $quizz ]
                );
                $quizzes->updateOne(
                    [ '_id' => new MongoDB\BSON\ObjectId( $quizz->_id ) ],
                    [ '$push' => [ 'questions' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                );
            } else {
            array_push($array, $result->getInsertedId());
            $quiz = [
                'questions' => $array,
                'label' => 'Tâche '.$speciality.'',
                'type' => 'Declaratif',
                'speciality' => ucfirst( $speciality ),
                'level' => ucfirst( $level ),
                'number' => 2,
                'total' => count($array),
                'active' =>true,
                'created' => date("d-m-y")
            ];
            $insert = $quizzes->insertOne( $quiz );
            
            
            if ($speciality == "Arbre de Transmission") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Assistance à la Conduite") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Boite de Transfert") {
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Boite de Vitesse") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Climatisation") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Direction") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Electricité") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Freinage Hydraulique") {
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Freinage Pneumatique") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
            } elseif ($speciality == "Hydraulique") {
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
            } elseif ($speciality == "Moteur Diesel") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Moteur Electrique") {
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Moteur Essence") {
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Multiplexage") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
            } elseif ($speciality == "Pont") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots && $chariots->brand == "TOYOTA FORFLIT") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Reducteur") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
            } elseif ($speciality == "Pneumatique") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Suspension à Lame") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Suspension Ressort") {
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Suspension Pneumatique") {
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($voitures && $voitures->brand != "SUZUKI") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            } elseif ($speciality == "Transversale") {
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $bus['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$set' => $bus ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $camions['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$set' => $camions ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $chariots['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$set' => $chariots ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $engins['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$set' => $engins ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $insert->getInsertedId() ) ] ]
                    );
                    $voitures['total']++;
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$set' => $voitures ]
                    );
                }
            }
        }
            $success_msg = "Questions ajoutées avec succès";
        }
    }
}
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Importer Questions | CFAO Mobility Academy</title>
<!--end::Title-->


<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Modal body-->
            <div class="container mt-5 w-50">
                <img src="../public/images/logo.png" alt="10" height="170"
                    style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
                <h1 class="my-3 text-center">Importer des questions</h1>

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

                <form enctype="multipart/form-data" method="POST"><br>
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required form-label fw-bolder text-dark fs-6">Importer des questions via
                            Excel</label>
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