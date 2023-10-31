<!--begin::Title-->
<title>Questionnaires Factuels | CFAO Mobility Academy</title>
<!--end::Title-->

<link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap"
    rel="stylesheet" />
<link href="/css/userQuiz.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="heading">
        <h1 class="heading__text">Question à choix multiple <%= questions[0].speciality %></h1>
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

        <center>
            <%- include('partials/message') %>
        </center>

        <form class="quiz-form"
            action="/technician-quiz-factuel/<%= quiz._id %>"
            method="POST">
            <div class="quiz-form__quiz">
                <% for (let i = 0; i < questions.length; i++) {
                    const question = questions[i] %>
                <% if (question.image) { %>
                <div style="margin-top: 50px; display: flex; justify-content: center; margin-bottom: 30px;">
                    <img id="image" alt=""
                        src="/files/<%= question.image %>"> <br>
                </div>
                <% } %>
                <p class="required quiz-form__question fw-bold"
                    style="margin-top: 50px; font-size: large; margin-bottom: 20px;">
                    <%= i + 1 %>-
                    <%= question.label %>
                </p>
                <label class="quiz-form__ans">
                    <input type="radio"
                        id="proposal<%= i + 1 %>"
                        onclick="checkedRadio()"
                        name="answer<%= i + 1 %>"
                        value="<%= question.proposal1 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal1 %>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio"
                        id="proposal<%= i + 1 %>"
                        onclick="checkedRadio()"
                        name="answer<%= i + 1 %>"
                        value="<%= question.proposal2 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal2 %>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio"
                        id="proposal<%= i + 1 %>"
                        onclick="checkedRadio()"
                        name="answer<%= i + 1 %>"
                        value="<%= question.proposal3 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal3 %>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio"
                        id="proposal<%= i + 1 %>"
                        onclick="checkedRadio()"
                        name="answer<%= i + 1 %>"
                        value="<%= question.proposal4 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal4 %>
                    </span>
                </label>
                <% } %>
            </div>
            <button class="btn btn-primary submit"
                type="submit">Soumettre</button>
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