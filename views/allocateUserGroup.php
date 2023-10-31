<!--begin::Title-->
<title>Affecter un Technicien | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class="container mt-5 w-50">
    <img src="images/logo.png" alt="10" height="170"
        style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    <h1 class="my-3 text-center">Affecter un technicien à un groupe</h1>
    <center>
        <%- include('partials/message') %>
    </center>
    <form action="/allocate-technician-group" method="POST"><br>
        <!--begin::Input group-->
        <div class="row fv-row mb-7">
            <!--begin::Col-->
            <div class="col-xl-6">
                <div class="d-flex flex-column mb-7 fv-row">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6">
                        <span class="required">Groupe</span>

                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Veuillez choisir le groupe">
                            <i class="ki-duotone ki-information fs-7"><span
                                    class="path1"></span><span
                                    class="path2"></span><span
                                    class="path3"></span></i> </span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <select name="group" aria-label="Select a Country"
                        data-control="select2"
                        data-placeholder="Sélectionnez le groupe..."
                        class="form-select form-select-solid fw-bold">
                        <option value="">Sélectionnez le groupe...
                        </option>
                        <% groups.forEach((group) => { %>
                        <option value="<%= group.id %>">
                            <%= group.name %>
                        </option>
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
                        <span class="required">Technicien</span>

                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Veuillez choisir le technicien">
                            <i class="ki-duotone ki-information fs-7"><span
                                    class="path1"></span><span
                                    class="path2"></span><span
                                    class="path3"></span></i> </span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <select name="users" multiple aria-label="Select a Country"
                        data-control="select2"
                        data-placeholder="Sélectionnez le technicien..."
                        class="form-select form-select-solid fw-bold">
                        <option value="">Sélectionnez le technicien...</option>
                        <% users.forEach((user) => { %>
                        <option value="<%= user.id %>">
                            <%= user.firstName + " " + user.lastName %>
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