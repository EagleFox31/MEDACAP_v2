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
    $questions = $academy->questions;
    $quizzes = $academy->quizzes;

    $id = $_SESSION[ 'id' ];
    $quiz = $quizzes->findOne(['_id' => $id]);
    $questions = $quiz['questions']
?>
<?php
include_once 'partials/header.php'
?>
<!--begin::Title-->
<title>Questionnaires Déclaratifs | CFAO Mobility Academy</title>
<!--end::Title-->

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet" />
<link href="../public/css/userQuiz.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="heading">
        <h1 class="heading__text">Tâches professionnelles <%= questions[0].speciality %></h1>
    </div>

    <!-- Quiz section -->
    <div class="quiz">
        <!-- <center>
            <div class="timer">
                <div class="time_left_txt">Temps Restant</div>
                <div class="timer_sec" name="time" id="timer_sec" value="1">
                </div>
            </div>
        </center> -->
        <form class="quiz-form" action="/technician-quiz-declaratif/<%= quiz._id %>" method="POST">
            <div class="quiz-form__quiz">
                <?php for ($i = 0; $i < count($questions); $i++) {
                    $question = $questions->findOne(['_id' => new MongoDB\BSON\ObjectId($questions[$i])]) ?>
                <div style="margin-top: 50px; display: flex; justify-content: center;  margin-bottom: 30px;">
                    <img id="image" alt="" src="../public/files/<%= question.image %>"> <br>
                </div>
                <p class="required quiz-form__question fw-bold"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <?php echo $i+1 ?>-
                    <?php echo $quiz->label ?>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal1" name="answer<%= i + 1 %>" onclick="checkedRadio()"
                        value="<%= question.proposal1 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal1 %>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal2" name="answer<%= i + 1 %>" onclick="checkedRadio()"
                        value="<%= question.proposal2 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal2 %>
                    </span>
                </label>
                <?php } ?>
            </div>
            <button class="btn btn-primary submit" type="submit">Soumettre</button>
        </form>
    </div>
</div>
<script>
let radio;
const ques = document.querySelectorAll("p");
const submitBtn = document.querySelector("button")
submitBtn.classList.add("disabled")

function checkedRadio() {
    const radios = document.querySelectorAll("input[type='radio']:checked");
    radios.forEach(async (rad, i) => {
        radio = i + 1
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