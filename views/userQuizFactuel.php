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
    $allocations = $academy->allocations;

    $id = $_GET["id"];
    $level = $_GET["level"];
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Questionnaires Factuels | CFAO Mobility Academy</title>
<!--end::Title-->

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" />
<link href="../public/css/userQuiz.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="heading">
        <h1 class="heading__text">Question à choix multiple</h1>
    </div>

    <!-- Quiz section -->
    <div class="quiz">
        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
            Vous devez repondre à toutes les questions avant
            de pouvoir valider le questionnaire.
        </p>
        <!-- <center>
            <div class="timer">
                <div class="time_left_txt">Temps Restant</div>
                <div class="timer_sec" name="time" id="timer_sec" value="1">
                </div>
            </div>
        </center> -->
        <form class="quiz-form" method="POST">
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Assistance à la conduite
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Climatisation
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Direction
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Electricité
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Freinage
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Hydraulique
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Moteur
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Multiplexage & Electronique
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Pneumatique
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Suspension
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Transmission
                </p>
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
                <?php
                    for ($i = 0; $i < count($arrQuestions); $i++) {
                        $question = $questions->findone([
                            '_id' => new MongoDB\BSON\ObjectId($arrQuestions[$i])
                        ]);
                ?>
                <p class="quiz-form__question fw-bold" style="margin-top: 50px; font-size: 30px; margin-bottom: 20px;">
                    Système Transversale
                </p>
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
                <?php } ?>
                <?php } ?>
            </div>
            <button class="btn btn-primary submit" type="submit">Valider</button>
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

let radio;
const ques = document.querySelectorAll("#question");
console.log(ques.length)
const submitBtn = document.querySelector("button")
submitBtn.classList.add("disabled")

function checkedRadio() {
    const radios = document.querySelectorAll("input[type='radio']:checked");
    console.log(radios)
    radios.forEach(async (rad, i) => {
        radio = i + 1
        console.log(radio)
    })
    if (ques.length == radio) {
        submitBtn.classList.remove("disabled");
    }
}
</script>
<?php
include_once 'partials/footer.php'
?>
<?php } ?>