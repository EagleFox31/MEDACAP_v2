<!--begin::Title-->
<title>Affecter une Question | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class="container mt-5 w-50">
    <img src="images/logo.png" alt="10" height="170"
        style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    <h1 class="my-3 text-center">Affecter une question à un questionnaire</h1>
    <center>
        <%- include('partials/message') %>
    </center>
    <form action="/allocate-question-quiz" method="POST"><br>
        <!--begin::Input group-->
        <div class="row fv-row mb-7">
            <!--begin::Col-->
            <div class="col-xl-6">
                <div class="d-flex flex-column mb-7 fv-row">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6">
                        <span class="required">Questionnaire</span>

                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Veuillez choisir le questionnaire">
                            <i class="ki-duotone ki-information fs-7"><span
                                    class="path1"></span><span
                                    class="path2"></span><span
                                    class="path3"></span></i> </span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <select name="quiz" aria-label="Select a Country"
                        data-control="select2"
                        data-placeholder="Sélectionnez le questionnaire..."
                        class="form-select form-select-solid fw-bold">
                        <option value="">Sélectionnez le quesionnaire...
                        </option>
                        <% quizzes.forEach((quiz) => { %>
                        <option value="<%= quiz.id %>">
                            <%= quiz.label %>
                            <% }) %>
                    </select>
                    <!--end::Input-->
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-6">
                <div class="d-flex flex-column mb-7 fv-row">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6">
                        <span class="required">Question</span>

                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Veuillez choisir le question">
                            <i class="ki-duotone ki-information fs-7"><span
                                    class="path1"></span><span
                                    class="path2"></span><span
                                    class="path3"></span></i> </span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <select name="questions" multiple aria-label="Select a Country"
                        data-control="select2"
                        data-placeholder="Sélectionnez le question..."
                        class="form-select form-select-solid fw-bold">
                        <option value="">Sélectionnez le question...</option>
                        <% questions.forEach((question) => { %>
                        <option value="<%= question.id %>">
                            <%= question.label %>
                        </option>
                        <% }) %>
                    </select>
                    <!--end::Input-->
                </div>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <div class="text-center" style="margin-bottom: 50px;">
            <button type="submit" class="btn btn-lg btn-primary">
                Valider
            </button>
        </div>
    </form>
</div>
<!--end::Modal body-->