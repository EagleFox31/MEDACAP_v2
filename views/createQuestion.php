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
$questions = $academy->questions;
$quizzes = $academy->quizzes;
$vehicles = $academy->vehicles ;
$allocations = $academy->allocations;

if ( isset( $_POST[ 'submit' ] ) ) {
    $label = $_POST[ 'label' ];
    $proposal1 = $_POST[ 'proposal1' ];
    $proposal2 = $_POST[ 'proposal2' ];
    $proposal3 = $_POST[ 'proposal3' ];
    $proposal4 = $_POST[ 'proposal4' ];
    $answer = $_POST[ 'answer' ];
    $speciality = $_POST[ 'speciality' ];
    $type = $_POST[ 'type' ];
    $level = $_POST[ 'level' ];
    $image = $_FILES[ 'image' ][ 'name' ];
    $tmp_name = $_FILES[ 'image' ][ 'tmp_name' ];
    $folder = '../public/files/'.$image;
    move_uploaded_file( $tmp_name, $folder );

    $exist = $questions->findOne([
        '$and' => [
            [ 'label' => $label ],
            [ 'speciality' => $speciality ],
            [ 'level' => $level ],
            [ 'type' => $type ],
        ],
    ]);
    
    if ( empty( $label ) ||
    empty( $type ) ||
    empty( $level ) ||
    empty( $speciality ) ) {
        $error = 'Champ obligatoire';
    } elseif ( $exist ) {
        $error_msg = 'Cette question existe déjà.';
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
        $success_msg = "Question ajoutée avec succès";
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
            $success_msg = "Question ajoutée avec succès";
        }
}
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Ajouter Question | CFAO Mobility Academy</title>
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
                <h1 class='my-3 text-center'>Ajouter une question</h1>

                <?php
                    if ( isset( $success_msg ) ) {
                        ?>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <center><strong><?php echo $success_msg ?></strong></center>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;
                        </span>
                    </button>
                </div>
                <?php
                    }
                    ?>
                <?php
                    if ( isset( $error_msg ) ) {
                        ?>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <center><strong><?php echo $error_msg ?></strong></center>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;
                        </span>
                    </button>
                </div>
                <?php
                    }
                    ?>

                <form enctype='multipart/form-data' method='POST'>
                    <br>
                    <!--begin::Input group-->
                    <div class='fv-row mb-7'>
                        <!--begin::Label-->
                        <label class='required form-label fw-bolder text-dark fs-6'>Libellé de la question</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type='text' class='form-control form-control-solid' placeholder='' name='label' />
                        <!--end::Input-->
                        <?php
                    if ( isset( $error ) ) {
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
                    <div class='fv-row mb-7'>
                        <!--begin::Label-->
                        <label class='form-label fw-bolder text-dark fs-6'>Image</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type='file' class='form-control form-control-solid' placeholder='' name='image' />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class='d-flex flex-column mb-7 fv-row'>
                        <!--begin::Label-->
                        <label class='form-label fw-bolder text-dark fs-6'>
                            <span class='required'>Type</span>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select name='type' onchange='selectif()' aria-label='Select a Country' data-control='select2'
                            data-placeholder='Sélectionnez votre sexe...' class='form-select form-select-solid fw-bold'>
                            <option>Sélectionnez le type de
                                question...</option>
                            <option value='Declarative'>
                                Declarative
                            </option>
                            <option value='Factuelle'>
                                Factuelle
                            </option>
                        </select>
                        <!--end::Input-->
                        <?php
                        if ( isset( $error ) ) {
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
                    <div class='row g-9 mb-7' id='prop'>
                        <!--begin::Col-->
                        <div class='col-md-6 fv-row'>
                            <!--begin::Label-->
                            <label class='required form-label fw-bolder text-dark fs-6'>Proposition
                                1</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input class='form-control form-control-solid' placeholder='' name='proposal1' />
                            <!--end::Input-->
                            <?php
                                if ( isset( $error ) ) {
                                   ?>
                            <span class='text-danger'>
                                <?php echo $error ?>
                            </span>
                            <?php
                               }
                               ?>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class='col-md-6 fv-row'>
                            <!--begin::Label-->
                            <label class='required form-label fw-bolder text-dark fs-6'>Proposition
                                2</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input class='form-control form-control-solid' placeholder='' name='proposal2' />
                            <!--end::Input-->
                            <?php
                               if ( isset( $error ) ) {
                                   ?>
                            <span class='text-danger'>
                                <?php echo $error ?>
                            </span>
                            <?php
                               }
                               ?>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class='row g-9 mb-7' id='prop1'>
                        <!--begin::Col-->
                        <div class='col-md-6 fv-row'>
                            <!--begin::Label-->
                            <label class='form-label fw-bolder text-dark fs-6'>Proposition
                                3</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input class='form-control form-control-solid' placeholder='' name='proposal3' />
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class='col-md-6 fv-row'>
                            <!--begin::Label-->
                            <label class='form-label fw-bolder text-dark fs-6'>Proposition
                                4</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input class='form-control form-control-solid' placeholder='' name='proposal4' />
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class='fv-row mb-7' id='answer'>
                        <!--begin::Label-->
                        <label class='form-label fw-bolder text-dark fs-6'>Réponses</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type='text' class='form-control form-control-solid' placeholder='' name='answer' />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="d-flex flex-column mb-7 fv-row">
                        <!--begin::Label-->
                        <label class="form-label fw-bolder text-dark fs-6">
                            <span class="required">Spécialité</span>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select name="speciality" aria-label="Select a Country" data-control="select2"
                            data-placeholder="Sélectionnez la spécialité..."
                            class="form-select form-select-solid fw-bold">
                            <option>Sélectionnez la
                                spécialité...</option>
                            <option value="Arbre de Transmission">
                                Arbre de Transmission
                            </option>
                            <option value="Assistance à la Conduite">
                                Assistance à la Conduite
                            </option>
                            <option value="Boite de Transfert">
                                Boite de Transfert
                            </option>
                            <option value="Boite de Vitesse">
                                Boite de Vitesse
                            </option>
                            <option value="Climatisation">
                                Climatisation
                            </option>
                            <option value="Direction">
                                Direction
                            </option>
                            <option value="Electricité">
                                Electricité
                            </option>
                            <option value="Freinage Hydraulique">
                                Freinage Hydraulique
                            </option>
                            <option value="Freinage Pneumatique">
                                Freinage Pneumatique
                            </option>
                            <option value="Hydraulique">
                                Hydraulique
                            </option>
                            <option value="Moteur Diesel">
                                Moteur Diesel
                            </option>
                            <option value="Moteur Essence">
                                Moteur Essence
                            </option>
                            <option value="Moteur Electrique">
                                Moteur Electrique
                            </option>
                            <option value="Multiplexage">
                                Multiplexage
                            </option>
                            <option value="Pneumatique">
                                Pneumatique
                            </option>
                            <option value="Pont">
                                Pont
                            </option>
                            <option value="Réducteur">
                                Réducteur
                            </option>
                            <option value="Suspension à Lame">
                                Suspension à Lame
                            </option>
                            <option value="Suspension Ressort">
                                Suspension Ressort
                            </option>
                            <option value="Suspension Pneumatique">
                                Suspension Pneumatique
                            </option>
                            <option value="Transversale">
                                Transversale
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
                    <div class='d-flex flex-column mb-7 fv-row'>
                        <!--begin::Label-->
                        <label class='form-label fw-bolder text-dark fs-6'>
                            <span class='required'>Niveau</span>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <select name='level' aria-label='Select a Country'
                            data-placeholder='Sélectionnez le niveau du questionnaire...'
                            data-dropdown-parent='#kt_modal_add_customer' class='form-select form-select-solid fw-bold'>
                            <option value=''>Sélectionnez le
                                niveau de la question...</option>
                            <option value='Junior'>
                                Junior
                            </option>
                            <option value='Senior'>
                                Senior
                            </option>
                            <option value='Expert'>
                                Expert
                            </option>
                        </select>
                        <!--end::Input-->
                        <?php
                        if ( isset( $error ) ) {
                            ?>
                        <span class='text-danger'>
                            <?php echo $error ?>
                        </span>
                        <?php
                        }
                        ?>
                    </div>
                    <!--end::Input group-->
                    <div class='modal-footer flex-center'>
                        <!--begin::Button-->
                        <button type='submit' name='submit' class='btn btn-primary'>
                            <span class='indicator-label'>
                                Valider
                            </span>
                            <span class='indicator-progress'>
                                Patientez... <span class='spinner-border spinner-border-sm align-middle ms-2'></span>
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

<script>
const prop = document.querySelector('#prop')
const prop1 = document.querySelector('#prop1')
const answer = document.querySelector('#answer')

function selectif() {
    const type = document.querySelector("select[name='type']").value
    if (type == 'Declarative') {
        prop.classList.add('hidden')
        prop1.classList.add('hidden')
        answer.classList.add('hidden')
    } else {
        prop.classList.remove('hidden')
        prop1.classList.remove('hidden')
        answer.classList.remove('hidden')
    }
}
</script>

<?php
    include_once 'partials/footer.php'
    ?>
<?php } ?>