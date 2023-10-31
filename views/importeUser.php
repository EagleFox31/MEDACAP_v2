<!--begin::Title-->
<title>Importe Utilisateurs | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Modal body-->
<div class="container mt-5 w-50">
    <img src="images/logo.png" alt="10" height="170"
        style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    <h1 class="my-3 text-center">Importer des utilisateurs</h1>
    <center>
        <%- include('partials/message') %>
    </center>
    <form action="/add-import-user" enctype="multipart/form-data" method="POST"><br>
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <!--begin::Label-->
            <label
                class="required form-label fw-bolder text-dark fs-6">Importer des utilisateurs via Excel</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="file" class="form-control form-control-solid"
                placeholder="" name="excel" />
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <div class="modal-footer flex-center">
            <!--begin::Button-->
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">
                    Valider
                </span>
                <span class="indicator-progress">
                    Patientez... <span
                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
            <!--end::Button-->
        </div>
        <!--end::Modal footer-->
    </form>
</div>
<!--end::Modal body-->