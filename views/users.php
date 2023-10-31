<!--begin::Title-->
<title>Listes des Utilisateurs | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Body-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div
            class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div
                class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Listes des utilisateurs </h1>
                <!--end::Title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div
                        class="d-flex align-items-center position-relative my-1">
                        <i
                            class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" id="search"
                            class="form-control form-control-solid w-250px ps-12"
                            placeholder="Recherche...">
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Info-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center flex-nowrap text-nowrap py-1">
                <div class="d-flex justify-content-end align-items-center"
                    style="margin-left: 10px;">
                    <button type="button" id="users" data-bs-toggle="modal"
                        class="btn btn-primary">
                        Liste subordonnés
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center"
                    style="margin-left: 10px;">
                    <button type="button" id="edit"
                        title="Cliquez ici pour modifier le technicien"
                        data-bs-toggle="modal" class="btn btn-primary">
                        Modifier
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center"
                    style="margin-left: 10px;">
                    <button type="button" id="password" data-bs-toggle="modal"
                        title="Cliquez ici pour modifier le mot de passe du technicien"
                        class="btn btn-primary">
                        Modifier mot de passe
                    </button>
                </div>
                <div class="d-flex justify-content-end align-items-center"
                    style="margin-left: 10px;">
                    <button type="button" id="delete"
                        title="Cliquez ici pour supprimer le technicien"
                        data-bs-toggle="modal" class="btn btn-danger">
                        Supprimer
                    </button>
                </div>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <center>
        <%- include('partials/message') %>
    </center>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post"
        data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <div class=" container-xxl " data-select2-id="select2-data-194-27hh">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <!-- <div class="card-header border-0 pt-6"> -->
                <!--begin::Card title-->
                <!-- <div class="card-title"> -->
                <!--begin::Search-->
                <!-- <div
                            class="d-flex align-items-center position-relative my-1">
                            <i
                                class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                    class="path1"></span><span
                                    class="path2"></span></i>
                            <input type="text" id="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Recherche">
                        </div> -->
                <!--end::Search-->
                <!-- </div> -->
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <!-- <div class="card-toolbar"> -->
                <!--begin::Toolbar-->
                <!-- <div class="d-flex justify-content-end"
                            data-kt-customer-table-toolbar="base"> -->
                <!--begin::Filter-->
                <!-- <div class="w-150px me-3" id="etat"> -->
                <!--begin::Select2-->
                <!-- <select id="select"
                                    class="form-select form-select-solid"
                                    data-control="select2"
                                    data-hide-search="true"
                                    data-placeholder="Etat"
                                    data-kt-ecommerce-order-filter="etat">
                                    <option></option>
                                    <option value="tous">Tous
                                    </option>
                                    <option value="true">
                                        Active</option>
                                    <option value="false">
                                        Supprimé</option>
                                </select> -->
                <!--end::Select2-->
                <!-- </div> -->
                <!--end::Filter-->
                <!--begin::Export dropdown-->
                <!-- <button type="button" id="excel"
                                class="btn btn-light-primary">
                                <i class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i>
                                Excel
                            </button> -->
                <!--end::Export dropdown-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="edit"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Modifier
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="password"
                                    data-bs-toggle="modal"
                                    class="btn btn-primary">
                                    Modifier mot de passe
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!--begin::Group actions-->
                <!-- <div class="d-flex justify-content-end align-items-center"
                                style="margin-left: 10px;">
                                <button type="button" id="delete"
                                    data-bs-toggle="modal"
                                    class="btn btn-danger">
                                    Supprimer
                                </button>
                            </div> -->
                <!--end::Group actions-->
                <!-- </div> -->
                <!--end::Toolbar-->
                <!-- </div> -->
                <!--end::Card toolbar-->
                <!-- </div> -->
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div id="kt_customers_table_wrapper"
                        class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table aria-describedby=""
                                class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="kt_customers_table">
                                <thead>
                                    <tr
                                        class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2 sorting_disabled"
                                            rowspan="1" colspan="1"
                                            aria-label=""
                                            style="width: 29.8906px;">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input"
                                                    type="checkbox" value="1">
                                            </div>
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Identifiant
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Matricule
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Prénoms et
                                            noms
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending"
                                            style="width: 155.266px;">Email</th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;">Numéro de
                                            téléphone
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Sexe</th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Date de
                                            naissance</th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Profile
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Métier
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Pays</th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Diplôme
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Payment Method: activate to sort column ascending"
                                            style="width: 126.516px;">Filiale
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">
                                            Departement</th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Fonction Principale
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Fonction Secondaire
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Created Date: activate to sort column ascending"
                                            style="width: 152.719px;">Date de
                                            recrutement</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600"
                                    id="table">
                                    <% for (let i = 0; i < users.length; i++) {
                                        const user = users[i] %>
                                    <% if (currentUser.profile == "Admin") { %>
                                    <% if (user.profile != "Admin" && user.profile != "Super Admin") { %>
                                    <tr class="odd"
                                        etat="<%= user.active %>">
                                        <td>
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input"
                                                    id="checkbox"
                                                    type="checkbox"
                                                    onclick="enable()"
                                                    value="<%= user.id %>">
                                            </div>
                                        </td>
                                        <td data-filter="email">
                                            <%= user.identifiant%>
                                        </td>
                                        <td data-filter="email">
                                            <%= user.matricule %>
                                        </td>
                                        <td data-filter="search">
                                            <%= user.firstName + " " + user.lastName %>
                                        </td>
                                        <td data-filter="email">
                                            <%= user.email %>
                                        </td>
                                        <td data-filter="phone">
                                            <%= user.phone %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.gender %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= birthdate[i] %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.profile %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.level %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.country %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.certificate %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.subsidiary %>
                                        </td>
                                        <td data-order="department">
                                            <%= user.department %>
                                        </td>
                                        <td data-order="department">
                                            <%= user.mainRole %>
                                        </td>
                                        <td data-order="department">
                                            <%= user.subRole %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= recrutment[i] %>
                                        </td>
                                    </tr>
                                    <% } %>
                                    <% } %>
                                    <% if (user.profile != "Super Admin") { %>
                                    <tr class="odd"
                                        etat="<%= user.active %>">
                                        <td>
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input"
                                                    id="checkbox"
                                                    type="checkbox"
                                                    onclick="enable()"
                                                    value="<%= user.id %>">
                                            </div>
                                        </td>
                                        <td data-filter="email">
                                            <%= user.identifiant%>
                                        </td>
                                        <td data-filter="email">
                                            <%= user.matricule %>
                                        </td>
                                        <td data-filter="search">
                                            <%= user.firstName + " " + user.lastName %>
                                        </td>
                                        <td data-filter="email">
                                            <%= user.email %>
                                        </td>
                                        <td data-filter="phone">
                                            <%= user.phone %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.gender %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= birthdate[i] %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.profile %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.level %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.country %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.certificate %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= user.subsidiary %>
                                        </td>
                                        <td data-order="department">
                                            <%= user.department %>
                                        </td>
                                        <td data-order="department">
                                            <%= user.mainRole %>
                                        </td>
                                        <td data-order="department">
                                            <%= user.subRole %>
                                        </td>
                                        <td data-order="subsidiary">
                                            <%= recrutment[i] %>
                                        </td>
                                    </tr>
                                    <% } %>
                                    <!-- begin:: Modal - Confirm suspend -->
                                    <div class="modal"
                                        id="kt_modal_desactivate<%= user.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div
                                            class="modal-dialog modal-dialog-centered mw-450px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/suspendre/<%= user.id %>/?_method=PUT"
                                                    method="POST"
                                                    id="kt_modal_update_user_form">
                                                    <input type="hidden"
                                                        name="_method"
                                                        value="PUT">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header"
                                                        id="kt_modal_update_user_header">
                                                        <!--begin::Modal title-->
                                                        <h2
                                                            class="fs-2 fw-bolder">
                                                            Désactivation
                                                        </h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close"
                                                            data-bs-dismiss="modal">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span
                                                                class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="24"
                                                                    height="24"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <rect
                                                                        opacity="0.5"
                                                                        x="6"
                                                                        y="17.3137"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
                                                                    <rect
                                                                        x="7.41422"
                                                                        y="6"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(45 7.41422 6)"
                                                                        fill="black" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->
                                                    <!--begin::Modal body-->
                                                    <div
                                                        class="modal-body py-10 px-lg-17">
                                                        <h4>
                                                            Voulez-vous vraiment
                                                            supprimer cet
                                                            utilisateur?
                                                        </h4>
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div
                                                        class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset"
                                                            class="btn btn-light me-3"
                                                            id="closeDesactivate"
                                                            data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel">
                                                            Non
                                                        </button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit"
                                                            class="btn btn-danger">
                                                            Oui
                                                        </button>
                                                        <!--end::Button-->
                                                    </div>
                                                    <!--end::Modal footer-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                        <!-- end Modal dialog -->
                                    </div>
                                    <!-- end:: Modal - Confirm suspend -->
                                    <!-- begin:: Modal - Confirm suspend -->
                                    <div class="modal"
                                        id="kt_modal_activate<%= user.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div
                                            class="modal-dialog modal-dialog-centered mw-450px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/active/<%= user.id %>/?_method=PUT"
                                                    method="POST"
                                                    id="kt_modal_update_user_form">
                                                    <input type="hidden"
                                                        name="_method"
                                                        value="PUT">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header"
                                                        id="kt_modal_update_user_header">
                                                        <!--begin::Modal title-->
                                                        <h2
                                                            class="fs-2 fw-bolder">
                                                            Activation
                                                        </h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close"
                                                            data-bs-dismiss="modal">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span
                                                                class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="24"
                                                                    height="24"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <rect
                                                                        opacity="0.5"
                                                                        x="6"
                                                                        y="17.3137"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
                                                                    <rect
                                                                        x="7.41422"
                                                                        y="6"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(45 7.41422 6)"
                                                                        fill="black" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->
                                                    <!--begin::Modal body-->
                                                    <div
                                                        class="modal-body py-10 px-lg-17">
                                                        <h4>
                                                            Voulez-vous vraiment
                                                            activer cet
                                                            utilisateur?
                                                        </h4>
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div
                                                        class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset"
                                                            class="btn btn-light me-3"
                                                            id="closeDesactivate"
                                                            data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel">
                                                            Non
                                                        </button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit"
                                                            class="btn btn-primary">
                                                            Oui
                                                        </button>
                                                        <!--end::Button-->
                                                    </div>
                                                    <!--end::Modal footer-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                        <!-- end Modal dialog -->
                                    </div>
                                    <!-- end:: Modal - Confirm suspend -->
                                    <!--begin::Modal - Update user password-->
                                    <div class="modal"
                                        id="kt_modal_update_password<%= user.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div
                                            class="modal-dialog modal-dialog-centered mw-450px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/password/<%= user.id %>"
                                                    method="POST">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header"
                                                        id="kt_modal_update_user_header">
                                                        <!--begin::Modal title-->
                                                        <h2
                                                            class="fs-2 fw-bolder">
                                                            Modification du mot
                                                            de passe
                                                        </h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close"
                                                            data-bs-dismiss="modal">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span
                                                                class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="24"
                                                                    height="24"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <rect
                                                                        opacity="0.5"
                                                                        x="6"
                                                                        y="17.3137"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
                                                                    <rect
                                                                        x="7.41422"
                                                                        y="6"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(45 7.41422 6)"
                                                                        fill="black" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->
                                                    <!--begin::Modal body-->
                                                    <div
                                                        class="modal-body py-10 px-lg-17">
                                                        <div
                                                            class="fv-row mb-7">
                                                            <!--begin::Label-->
                                                            <label
                                                                class="fs-6 fw-bold mb-2">Mot
                                                                de
                                                                passe</label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <input
                                                                type="password"
                                                                class="form-control form-control-solid"
                                                                placeholder=""
                                                                name="password"
                                                                value="" />
                                                            <!--end::Input-->
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div
                                                        class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset"
                                                            class="btn btn-light me-3"
                                                            data-bs-dismiss="modal"
                                                            id="closeDesactivate"
                                                            data-kt-users-modal-action="cancel">
                                                            Annuler
                                                        </button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit"
                                                            class="btn btn-primary">
                                                            Valider
                                                        </button>
                                                        <!--end::Button-->
                                                    </div>
                                                    <!--end::Modal footer-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                        <!-- end Modal dialog -->
                                    </div>
                                    <!--end::Modal - Update user password-->
                                    <!--begin::Modal - Update user details-->
                                    <div class="modal"
                                        id="kt_modal_update_details<%= user.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div
                                            class="modal-dialog modal-dialog-centered mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/update/<%= user.id %>?_method=PUT"
                                                    method="POST"
                                                    id="kt_modal_update_user_form">
                                                    <input type="hidden"
                                                        name="_method"
                                                        value="PUT">
                                                    <!--begin::Modal header-->
                                                    <div class="modal-header"
                                                        id="kt_modal_update_user_header">
                                                        <!--begin::Modal title-->
                                                        <h2
                                                            class="fs-2 fw-bolder">
                                                            Modification des
                                                            informations</h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                            data-kt-users-modal-action="close"
                                                            data-bs-dismiss="modal"
                                                            data-kt-menu-dismiss="true">
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                            <span
                                                                class="svg-icon svg-icon-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="24"
                                                                    height="24"
                                                                    viewBox="0 0 24 24"
                                                                    fill="none">
                                                                    <rect
                                                                        opacity="0.5"
                                                                        x="6"
                                                                        y="17.3137"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(-45 6 17.3137)"
                                                                        fill="black" />
                                                                    <rect
                                                                        x="7.41422"
                                                                        y="6"
                                                                        width="16"
                                                                        height="2"
                                                                        rx="1"
                                                                        transform="rotate(45 7.41422 6)"
                                                                        fill="black" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->
                                                    <!--begin::Modal body-->
                                                    <div
                                                        class="modal-body py-10 px-lg-17">
                                                        <!--begin::Scroll-->
                                                        <div class="d-flex flex-column scroll-y me-n7 pe-7"
                                                            id="kt_modal_update_user_scroll"
                                                            data-kt-scroll="true"
                                                            data-kt-scroll-activate="{default: false, lg: true}"
                                                            data-kt-scroll-max-height="auto"
                                                            data-kt-scroll-dependencies="#kt_modal_update_user_header"
                                                            data-kt-scroll-wrappers="#kt_modal_update_user_scroll"
                                                            data-kt-scroll-offset="300px">
                                                            <!--begin::User toggle-->
                                                            <div
                                                                class="fw-boldest fs-3 rotate collapsible mb-7">
                                                                Informations
                                                            </div>
                                                            <!--end::User toggle-->
                                                            <!--begin::User form-->
                                                            <div id="kt_modal_update_user_user_info"
                                                                class="collapse show">
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Matricule</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="matricule"
                                                                        value="<%= user.matricule %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="row g-9 mb-7">
                                                                    <!--begin::Col-->
                                                                    <div
                                                                        class="col-md-6 fv-row">
                                                                        <!--begin::Label-->
                                                                        <label
                                                                            class="form-label fw-bolder text-dark fs-6">Noms</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Input-->
                                                                        <input
                                                                            class="form-control form-control-solid"
                                                                            placeholder=""
                                                                            name="lastName"
                                                                            value="<%= user.lastName %>" />
                                                                        <!--end::Input-->
                                                                    </div>
                                                                    <!--end::Col-->
                                                                    <!--begin::Col-->
                                                                    <div
                                                                        class="col-md-6 fv-row">
                                                                        <!--begin::Label-->
                                                                        <label
                                                                            class="form-label fw-bolder text-dark fs-6">Prénoms</label>
                                                                        <!--end::Label-->
                                                                        <!--begin::Input-->
                                                                        <input
                                                                            class="form-control form-control-solid"
                                                                            placeholder=""
                                                                            name="firstName"
                                                                            value="<%= user.firstName %>" />
                                                                        <!--end::Input-->
                                                                    </div>
                                                                    <!--end::Col-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">
                                                                        <span>Email</span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="email"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="email"
                                                                        value="<%= user.email %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Numéro
                                                                        de
                                                                        téléphone</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="phone"
                                                                        value="<%= user.phone %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Date
                                                                        de
                                                                        naissance</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="birthdate"
                                                                        value="<%= birthdate[i] %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Métier</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="level"
                                                                        value="<%= user.level %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Pays</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="country"
                                                                        value="<%= user.country %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Certificat plus élévé</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="certificate"
                                                                        value="<%= user.certificate %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Filiale</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="subsidiary"
                                                                        value="<%= user.subsidiary %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Département</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="department"
                                                                        value="<%= user.department %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Fonction Principale</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="mainRole"
                                                                        value="<%= user.mainRole %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Fonction Secondaire</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="subRole"
                                                                        value="<%= user.subRole %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Date
                                                                        de
                                                                        recrutement</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="recrutmentDate"
                                                                        value="<%= recrutment[i] %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <% if(user.profile == "Technicien" ) { %>
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="d-flex flex-column mb-7 fv-row">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="form-label fw-bolder text-dark fs-6">
                                                                        <span>Manager</span>
                                                                        <span
                                                                            class="ms-1"
                                                                            data-bs-toggle="tooltip"
                                                                            title="Choississez le manager de cet technicien">
                                                                            <i
                                                                                class="ki-duotone ki-information fs-7"><span
                                                                                    class="path1"></span><span
                                                                                    class="path2"></span><span
                                                                                    class="path3"></span></i>
                                                                        </span>
                                                                    </label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <select
                                                                        name="manager"
                                                                        aria-label="Select a Country"
                                                                        data-control="select2"
                                                                        data-placeholder="Sélectionnez votre manager..."
                                                                        class="form-select form-select-solid fw-bold">
                                                                        <% if (user.manager) { %>
                                                                        <option
                                                                            value="<%= user.manager.id %>">
                                                                            <%= user.manager.firstName + " " + user.manager.lastName %>
                                                                        </option>
                                                                        <% } %>
                                                                        <option
                                                                            value="">
                                                                        </option>
                                                                        <% managers.forEach((manager) => { %>
                                                                        <option
                                                                            value="<%= manager.id %>">
                                                                            <%= manager.firstName + " " + manager.lastName %>
                                                                        </option>
                                                                        <% }) %>
                                                                    </select>
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <% } %>
                                                            </div>
                                                            <!--end::User form-->
                                                        </div>
                                                        <!--end::Scroll-->
                                                    </div>
                                                    <!--end::Modal body-->
                                                    <!--begin::Modal footer-->
                                                    <div
                                                        class="modal-footer flex-center">
                                                        <!--begin::Button-->
                                                        <button type="reset"
                                                            class="btn btn-light me-3"
                                                            data-kt-menu-dismiss="true"
                                                            data-bs-dismiss="modal"
                                                            data-kt-users-modal-action="cancel">Annuler</button>
                                                        <!--end::Button-->
                                                        <!--begin::Button-->
                                                        <button type="submit"
                                                            class="btn btn-primary">
                                                            Valider
                                                        </button>
                                                        <!--end::Button-->
                                                    </div>
                                                    <!--end::Modal footer-->
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Modal - Update user details-->
                                    <!--begin::Modal - Invite Friends-->
                                    <div class="modal fade"
                                        id="kt_modal_invite_users<%= user.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div class="modal-dialog mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Modal header-->
                                                <div
                                                    class="modal-header pb-0 border-0 justify-content-end">
                                                    <!--begin::Close-->
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                        data-bs-dismiss="modal">
                                                        <i
                                                            class="ki-duotone ki-cross fs-1"><span
                                                                class="path1"></span><span
                                                                class="path2"></span></i>
                                                    </div>
                                                    <!--end::Close-->
                                                </div>
                                                <!--begin::Modal header-->
                                                <!--begin::Modal body-->
                                                <div
                                                    class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                                                    <!--begin::Heading-->
                                                    <div
                                                        class="text-center mb-13">
                                                        <!--begin::Title-->
                                                        <h1 class="mb-3">
                                                            Liste des
                                                            techniciens
                                                        </h1>
                                                        <!--end::Title-->
                                                    </div>
                                                    <!--end::Heading-->
                                                    <!--begin::Users-->
                                                    <div class="mb-10">
                                                        <!--begin::List-->
                                                        <div
                                                            class="mh-300px scroll-y me-n7 pe-7">
                                                            <!--begin::User-->
                                                            <% user.users.forEach((user) => { %>
                                                            <div
                                                                class="d-flex flex-stack py-4 border-bottom border-gray-300 border-bottom-dashed">
                                                                <!--begin::Details-->
                                                                <div
                                                                    class="d-flex align-items-center">
                                                                    <!--begin::Avatar-->
                                                                    <div
                                                                        class="symbol symbol-35px symbol-circle">
                                                                        <img alt="Pic"
                                                                            src="/assets/media/avatars/300-1.jpg" />
                                                                    </div>
                                                                    <!--end::Avatar -->
                                                                    <!--begin::Details-->
                                                                    <div
                                                                        class="ms-5">
                                                                        <a href="#"
                                                                            class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                                                            <%= user.firstName + " " + user.lastName %>
                                                                        </a>
                                                                        <div
                                                                            class="fw-semibold text-muted">
                                                                            <%= user.email %>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Details-->
                                                                </div>
                                                                <!--end::Details-->
                                                                <!--begin::Access menu-->
                                                                <div
                                                                    data-kt-menu-trigger="click">
                                                                    <form
                                                                        action="/retire-technician-manager/<%= user.id %>/?_method=PUT"
                                                                        method="POST">
                                                                        <input
                                                                            type="hidden"
                                                                            name="_method"
                                                                            value="PUT">
                                                                        <button
                                                                            class="btn btn-light btn-active-light-primary btn-sm">Supprimer</button>
                                                                    </form>
                                                                </div>
                                                                <!--end::Access menu-->
                                                            </div>
                                                            <!--end::User-->
                                                            <% }) %>
                                                        </div>
                                                        <!--end::List-->
                                                    </div>
                                                    <!--end::Users-->
                                                </div>
                                                <!--end::Modal body-->
                                            </div>
                                            <!--end::Modal content-->
                                        </div>
                                        <!--end::Modal dialog-->
                                    </div>
                                    <!--end::Modal - Invite Friend-->

                                    
                                    <% } %>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_length">
                                    <label><select
                                            id="kt_customers_table_length"
                                            name="kt_customers_table_length"
                                            class="form-select form-select-sm form-select-solid">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select></label>
                                </div>
                            </div>
                            <div
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div
                                    class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination"
                                        id="kt_customers_table_paginate">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
            <!--begin::Export dropdown-->
            <div class="d-flex justify-content-end align-items-center"
                style="margin-top: 20px;">
                <button type="button" id="excel"
                    title="Cliquez ici pour importer la table"
                    class="btn btn-primary">
                    <i class="ki-duotone ki-exit-up fs-2"><span
                            class="path1"></span><span class="path2"></span></i>
                    Excel
                </button>
            </div>
            <!--end::Export dropdown-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->