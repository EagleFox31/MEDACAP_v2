<!--begin::Title-->
<title>Evaluation Technicien | CFAO Mobility Academy</title>
<!--end::Title-->

<link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap"
    rel="stylesheet" />
<link href="/css/userQuiz.css" rel="stylesheet" type="text/css" />
<div class="container">
    <div class="heading">
        <h1 class="heading__text">Evaluation de
            <%= user.firstName + " " + user.lastName %>
            sur <%= quiz.speciality %>
        </h1>
    </div>

    <!-- Quiz section -->
    <div class="quiz">

        <center>
            <%- include('partials/message') %>
        </center>

        <form class="quiz-form"
            action="/manager-quiz-declaratif/<%= user.id %>"
            method="POST">
            <div class="quiz-form__quiz">
                <% for (let i = 0; i < questions.length; i++) {
                    const question = questions[i] %>
                <% if (question.image) { %>
                <div style="margin-top: 50px; display: flex; justify-content: center;  margin-bottom: 30px;">
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
                    <input type="radio" id="proposal1"
                        name="answer<%= i + 1 %>"
                        onclick="checkedRadio()"
                        value="<%= question.proposal1 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal1 %>
                    </span>
                </label>
                <label class="quiz-form__ans">
                    <input type="radio" id="proposal2"
                        name="answer<%= i + 1 %>"
                        onclick="checkedRadio()"
                        value="<%= question.proposal2 %>" />
                    <span class="design"></span>
                    <span class="text">
                        <%= question.proposal2 %>
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