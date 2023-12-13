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
    $quizzes = $academy->quizzes;
    $questions = $academy->questions;
    $vehicles = $academy->vehicles;

    $label = $_POST[ 'label' ];
    $type = $_POST[ 'type' ];
    $speciality = $_POST[ 'speciality' ];
    $number = $_POST[ 'number' ];
    $level = $_POST[ 'level' ];
    $questionFacArr = [];
    $questionDeclaArr = [];

    $exist = $quizzes->findOne([
        '$and' => [
            [ 'speciality' => $speciality ],
            [ 'type' => $type ],
            [ 'level' => $level ],
        ],
    ]);

    if ( empty( $label ) ||
    empty( $type ) ||
    empty( $speciality ) ||
    empty( $level ) ||
    empty( $number ) ) {
        $error = 'Champ obligatoire';
    } elseif ( $exist ) {
        $error_msg = 'Ce questionnaire existe déjà.';
    } elseif ($type == 'Factuel') {
        $questionFac = $questions->find([
            '$and' => [
                [ 'speciality' => $speciality ],
                [ 'type' => 'Factuelle' ],
                [ 'level' => $level ],
            ],
        ])->toArray();
        foreach ($questionFac as $question) {
            array_push($questionFacArr, $question->_id);
        }
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
            
        if ($speciality == "Arbre de Transmission") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Assistance à la Conduite") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Boite de Transfert") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Boite de Vitesse") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Climatisation") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Direction") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Electricité") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Freinage Hydraulique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Freinage Pneumatique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Hydraulique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Moteur Diesel") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Moteur Electrique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Moteur Essence") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Multiplexage") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Pont") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots && $chariots->brand == "TOYOTA FORFLIT") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots && $chariots->brand == "TOYOTA FORFLIT") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Reducteur") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Pneumatique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Suspension à Lame") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Suspension Ressort") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Suspension Pneumatique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures && $voitures->brand != "SUZUKI") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures && $voitures->brand != "SUZUKI") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Transversale") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'QCM '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        }
    } elseif ($type == 'Declaratif') {
        $questionDecla = $questions->find([
            '$and' => [
                [ 'speciality' => $speciality ],
                [ 'type' => 'Declarative' ],
                [ 'level' => $level ],
            ],
        ])->toArray();
        foreach ($questionDecla as $question) {
            array_push($questionDeclaArr, $question->_id);
        }
        
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
        
        if ($speciality == "Arbre de Transmission") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Assistance à la Conduite") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Boite de Transfert") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Boite de Vitesse") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Climatisation") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Direction") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Electricité") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Freinage Hydraulique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Freinage Pneumatique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Hydraulique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK" || $camions->brand == "SINOTRUK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Moteur Diesel") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Moteur Electrique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Moteur Essence") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Multiplexage") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Pont") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots && $chariots->brand == "TOYOTA FORFLIT") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots && $chariots->brand == "TOYOTA FORFLIT") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Reducteur") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Pneumatique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Suspension à Lame") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Suspension Ressort") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Suspension Pneumatique") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures && $voitures->brand != "SUZUKI") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($camions && $camions->brand == "RENAULT TRUCK" ||  $camions->brand == "MERCEDES TRUCK") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures && $voitures->brand != "SUZUKI") {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        } elseif ($speciality == "Transversale") {
            if($questionFac) {
                $quiz = [
                    'questions' => $questionFacArr,
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => count($questionFacArr),
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès'; 
            } else {
                $quiz = [
                    'questions' => [],
                    'label' => 'Tâche '.$speciality.'',
                    'type' => $type,
                    'speciality' => ucfirst( $speciality ),
                    'level' => ucfirst( $level ),
                    'number' => +$number,
                    'total' => 0,
                    'active' => true,
                    'created' => date("d-m-Y")
                ];
                $result = $quizzes->insertOne( $quiz );
                if ($bus) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $bus->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($camions) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $camions->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($chariots) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $chariots->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($engins) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $engins->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                if ($voitures) {
                    $vehicles->updateOne(
                        [ '_id' => new MongoDB\BSON\ObjectId( $voitures->_id ) ],
                        [ '$push' => [ 'quizzes' => new MongoDB\BSON\ObjectId( $result->getInsertedId() ) ] ]
                    );
                }
                $success_msg = 'Questionnaire ajouté avec succès';
            }
        }
    }
}

?>
<?php
include_once 'partials/header.php'
?>

<!--begin::Title-->
<title>Ajouter Questionnaire | CFAO Mobility Academy</title>
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
                <h1 class='my-3 text-center'>Ajouter un questionnaire</h1>

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
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required form-label fw-bolder text-dark fs-6">Nom du questionnaire</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-solid" placeholder="" name="label" />
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
                            <select name="type" aria-label="Select a Country"
                                data-placeholder="Sélectionnez le type du questionnaire..."
                                data-dropdown-parent="#kt_modal_add_customer"
                                class="form-select form-select-solid fw-bold">
                                <option value="">Sélectionnez le
                                    type du questionniare...</option>
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
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required form-label fw-bolder text-dark fs-6">Nombre
                                de question à répondre</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-solid" placeholder="" name="number" />
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
                                data-placeholder="Sélectionnez le niveau du questionnaire..."
                                data-dropdown-parent="#kt_modal_add_customer"
                                class="form-select form-select-solid fw-bold">
                                <option value="">Sélectionnez le
                                    niveau du questionnaire...</option>
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