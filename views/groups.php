<!--begin::Title-->
<title>Listes des Groupes | CFAO Mobility Academy</title>
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
                    Listes des groupes </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
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
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div
                            class="d-flex align-items-center position-relative my-1">
                            <i
                                class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span
                                    class="path1"></span><span
                                    class="path2"></span></i> <input type="text"
                                data-kt-customer-table-filter="search" id="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Recherche">
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end"
                            data-kt-customer-table-toolbar="base">
                            <!--begin::Filter-->
                            <div class="w-150px me-3">
                                <!--begin::Select2-->
                                <select
                                    id="select"
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
                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--end::Filter-->
                            <!--begin::Export dropdown-->
                            <button type="button" id="excel"
                                class="btn btn-light-primary">
                                <i
                                    class="ki-duotone ki-exit-up fs-2"><span
                                        class="path1"></span><span
                                        class="path2"></span></i>
                                Excel
                            </button>
                            <!--end::Export dropdown-->
                            <!--begin::Add customer-->
                            <button type="button" class="btn btn-primary"
                                data-bs-toggle="modal"
                                style="margin-left: 10px;"
                                data-bs-target="#kt_modal_add_customer">
                                Ajoute un groupe
                            </button>
                            <!--end::Add customer-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
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
                                                    type="checkbox"
                                                    data-kt-check="true"
                                                    data-kt-check-target="#kt_customers_table .form-check-input"
                                                    value="1">
                                            </div>
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Customer Name: activate to sort column ascending"
                                            style="width: 125px;">Nom du Groupe
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;">
                                            Description
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;">Type
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;">Nombre de
                                            personnes
                                        </th>
                                        <th class="min-w-125px sorting"
                                            tabindex="0"
                                            aria-controls="kt_customers_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Company: activate to sort column ascending"
                                            style="width: 134.188px;">Etat
                                        </th>
                                        <th class="text-end min-w-70px sorting_disabled"
                                            rowspan="1" colspan="1"
                                            aria-label="Actions"
                                            style="width: 101.922px;">Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600" id="table">
                                    <% groups.forEach((group) => { %>
                                    <tr class="odd" etat="<%= group.active %>">
                                        <td>
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input"
                                                    type="checkbox" value="1">
                                            </div>
                                        </td>
                                        <td data-filter="search">
                                            <%= group.name %>
                                        </td>
                                        <td data-filter="description">
                                            <%= group.description %>
                                        </td>
                                        <td data-filter="type">
                                            <%= group.type %>
                                        </td>
                                        <td data-filter="total">
                                            <%= group.total %>
                                        </td>
                                        <% if (group.active == true) { %>
                                        <td>
                                            <div class="badge badge-light-success">
                                                Active
                                            </div>
                                        </td>
                                        <% } %>
                                        <% if (group.active == false) { %>
                                        <td>
                                            <div class="badge badge-light-danger">
                                                Supprimé
                                            </div>
                                        </td>
                                        <% } %>
                                        <td class="text-end">
                                            <a href="#"
                                                class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                data-kt-menu-trigger="click"
                                                data-kt-menu-placement="bottom-end">
                                                Actions
                                                <i
                                                    class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_edit_customer<%= group.id %>"
                                                        class="menu-link px-3">
                                                        Modifier
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_membres<%= group.id %>"
                                                        class="menu-link px-3">
                                                        Membres
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <% if (group.active == true) { %>
                                                <div class="menu-item px-3">
                                                    <a href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_desactivate<%= group.id %>"
                                                        class="menu-link px-3"
                                                        data-kt-customer-table-filter="delete_row">
                                                        Supprimer
                                                    </a>
                                                </div>
                                                <% } %>
                                                <% if (group.active == false) { %>
                                                <div class="menu-item px-3">
                                                    <a href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_activate<%= group.id %>"
                                                        class="menu-link px-3"
                                                        data-kt-customer-table-filter="delete_row">
                                                        Activer
                                                    </a>
                                                </div>
                                                <% } %>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                    <!-- begin:: Modal - Confirm suspend -->
                                    <div class="modal"
                                        id="kt_modal_desactivate<%= group.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div
                                            class="modal-dialog modal-dialog-centered mw-450px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/suspendre-group/<%= group.id %>/?_method=PUT"
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
                                                            supprimer ce
                                                            groupe?
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
                                        id="kt_modal_activate<%= group.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div
                                            class="modal-dialog modal-dialog-centered mw-450px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/active-group/<%= group.id %>/?_method=PUT"
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
                                                            activer ce groupe?
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
                                    <!--begin::Modal - Update user details-->
                                    <div class="modal"
                                        id="kt_modal_edit_customer<%= group.id %>"
                                        tabindex="-1" aria-hidden="true">
                                        <!--begin::Modal dialog-->
                                        <div
                                            class="modal-dialog modal-dialog-centered mw-650px">
                                            <!--begin::Modal content-->
                                            <div class="modal-content">
                                                <!--begin::Form-->
                                                <form class="form"
                                                    action="/update-group/<%= group.id %>?_method=PUT"
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
                                                            data-kt-menu-dismiss="true" data-bs-dismiss="modal">
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
                                                                        class="fs-6 fw-bold mb-2">Nom
                                                                        du
                                                                        groupe</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="name"
                                                                        value="<%= group.name %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
                                                                <!--begin::Input group-->
                                                                <div
                                                                    class="fv-row mb-7">
                                                                    <!--begin::Label-->
                                                                    <label
                                                                        class="fs-6 fw-bold mb-2">Description</label>
                                                                    <!--end::Label-->
                                                                    <!--begin::Input-->
                                                                    <input
                                                                        type="text"
                                                                        class="form-control form-control-solid"
                                                                        placeholder=""
                                                                        name="description"
                                                                        value="<%= group.description %>" />
                                                                    <!--end::Input-->
                                                                </div>
                                                                <!--end::Input group-->
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
                                        id="kt_modal_membres<%= group.id %>" tabindex="-1"
                                        aria-hidden="true">
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
                                                        <h1 class="mb-3">Membres
                                                            du groupe</h1>
                                                        <!--end::Title-->
                                                    </div>
                                                    <!--end::Heading-->
                                                    <!--begin::Users-->
                                                    <div class="mb-10">
                                                        <!--begin::List-->
                                                        <div
                                                            class="mh-300px scroll-y me-n7 pe-7">
                                                            <% group.users.forEach((group) => { %>
                                                            <!--begin::User-->
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
                                                                    <!--end::Avatar-->
                                                                    <!--begin::Details-->
                                                                    <div
                                                                        class="ms-5">
                                                                        <span
                                                                            class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                                                            <%= group.firstName + " " + group.lastName %>
                                                                        </span>
                                                                        <div
                                                                            class="fw-semibold text-muted">
                                                                            <%= group.email %>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Details-->
                                                                </div>
                                                                <!--end::Details-->
                                                                <!--begin::Access menu-->
                                                                <form
                                                                    class="form"
                                                                    action="/retire/<%= group.id %>/?_method=PUT"
                                                                    method="POST"
                                                                    id="kt_modal_update_user_form">
                                                                    <input
                                                                        type="hidden"
                                                                        name="_method"
                                                                        value="PUT">
                                                                    <button
                                                                        type="submit"
                                                                        class="btn btn-light bnt-active-light-primary btn-sm">
                                                                        Retirer
                                                                    </button>
                                                                </form>
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
                                    <% }) %>
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
                                <div class="dataTables_paginate paging_simple_numbers">
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
            <!--begin::Modals-->
            <!--begin::Modal - Customers - Add-->
            <div class="modal fade" id="kt_modal_add_customer" tabindex="-1"
                aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Form-->
                        <form class="form" action="/create-group" method="POST"
                            id="kt_modal_add_customer_form">
                            <!--begin::Modal header-->
                            <div class="modal-header"
                                id="kt_modal_add_customer_header">
                                <!--begin::Modal title-->
                                <h2 class="fw-bold">Ajout d'un groupe</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div id="kt_modal_add_customer_close"
                                        data-bs-dismiss="modal"
                                    class="btn btn-icon btn-sm btn-active-icon-primary">
                                    <i class="ki-duotone ki-cross fs-1"><span
                                            class="path1"></span><span
                                            class="path2"></span></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body py-10 px-lg-17">
                                <!--begin::Scroll-->
                                <div class="scroll-y me-n7 pe-7"
                                    id="kt_modal_add_customer_scroll"
                                    data-kt-scroll="true"
                                    data-kt-scroll-activate="{default: false, lg: true}"
                                    data-kt-scroll-max-height="auto"
                                    data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                                    data-kt-scroll-wrappers="#kt_modal_add_customer_scroll"
                                    data-kt-scroll-offset="300px">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label
                                            class="required form-label fw-bolder text-dark fs-6">Nom
                                            du groupe</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text"
                                            class="form-control form-control-solid"
                                            placeholder="" name="name" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label
                                            class="required form-label fw-bolder text-dark fs-6">Description</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text"
                                            class="form-control form-control-solid"
                                            placeholder="" name="description" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column mb-7 fv-row">
                                        <!--begin::Label-->
                                        <label
                                            class="form-label fw-bolder text-dark fs-6">
                                            <span class="required">Type</span>
                                            </span>
                                        </label>
                                        <!--end::Label-->

                                        <!--begin::Input-->
                                        <select name="type"
                                            aria-label="Select a Country"
                                            data-placeholder="Sélectionnez le type du groupe..."
                                            data-dropdown-parent="#kt_modal_add_customer"
                                            class="form-select form-select-solid fw-bold">
                                            <option>Sélectionnez le type du
                                                groupe...</option>
                                            <option value="Manager">
                                                Manager
                                            </option>
                                            <option value="Utilisateur">
                                                Utilisateur
                                            </option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Scroll-->
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" 
                                         data-bs-dismiss="modal" 
                                         class="btn btn-light me-3">
                                    Annuler
                                </button>
                                <!--end::Button-->
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
                        <!--end::Form-->
                    </div>
                </div>
            </div>
            <!--end::Modal - Customers - Add-->
            <!--begin::Modal - Adjust Balance-->
            <div class="modal fade" id="kt_customers_export_modal" tabindex="-1"
                style="display: none;"
                data-select2-id="select2-data-kt_customers_export_modal"
                aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px"
                    data-select2-id="select2-data-193-d5wv">
                    <!--begin::Modal content-->
                    <div class="modal-content"
                        data-select2-id="select2-data-192-ros3">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Export Customers</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div id="kt_customers_export_close"
                                class="btn btn-icon btn-sm btn-active-icon-primary">
                                <i class="ki-duotone ki-cross fs-1"><span
                                        class="path1"></span><span
                                        class="path2"></span></i>
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7"
                            data-select2-id="select2-data-191-2kn1">
                            <!--begin::Form-->
                            <form id="kt_customers_export_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                action="#"
                                data-select2-id="select2-data-kt_customers_export_form">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label
                                        class="fs-5 fw-semibold form-label mb-5">Select
                                        Export Format:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="country"
                                        data-control="select2"
                                        data-placeholder="Select a format"
                                        data-hide-search="true"
                                        class="form-select form-select-solid select2-hidden-accessible"
                                        data-select2-id="select2-data-13-97mz"
                                        tabindex="-1" aria-hidden="true"
                                        data-kt-initialized="1">
                                        <option value="excell"
                                            data-select2-id="select2-data-15-hqr3">
                                            Excel</option>
                                        <option value="pdf"
                                            data-select2-id="select2-data-198-4yra">
                                            PDF</option>
                                        <option value="cvs"
                                            data-select2-id="select2-data-199-zajh">
                                            CVS</option>
                                        <option value="zip"
                                            data-select2-id="select2-data-200-ia0z">
                                            ZIP</option>
                                    </select><span
                                        class="select2 select2-container select2-container--bootstrap5 select2-container--below"
                                        dir="ltr"
                                        data-select2-id="select2-data-14-2494"
                                        style="width: 100%;"><span
                                            class="selection"><span
                                                class="select2-selection select2-selection--single form-select form-select-solid"
                                                role="combobox"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                                tabindex="0"
                                                aria-disabled="false"
                                                aria-labelledby="select2-country-zk-container"
                                                aria-controls="select2-country-zk-container"><span
                                                    class="select2-selection__rendered"
                                                    id="select2-country-zk-container"
                                                    role="textbox"
                                                    aria-readonly="true"
                                                    title="Excel">Excel</span><span
                                                    class="select2-selection__arrow"
                                                    role="presentation"><b
                                                        role="presentation"></b></span></span></span><span
                                            class="dropdown-wrapper"
                                            aria-hidden="true"></span></span>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div
                                    class="fv-row mb-10 fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
                                    <!--begin::Label-->
                                    <label
                                        class="fs-5 fw-semibold form-label mb-5">Select
                                        Date Range:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input
                                        class="form-control form-control-solid flatpickr-input"
                                        placeholder="Pick a date" name="date"
                                        type="hidden"
                                        value="2023-07-02 to 2023-07-14"><input
                                        class="form-control form-control-solid input"
                                        placeholder="Pick a date" tabindex="0"
                                        type="text" readonly="readonly">
                                    <!--end::Input-->
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Row-->
                                <div class="row fv-row mb-15">
                                    <!--begin::Label-->
                                    <label
                                        class="fs-5 fw-semibold form-label mb-5">Payment
                                        Type:</label>
                                    <!--end::Label-->
                                    <!--begin::Radio group-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Radio button-->
                                        <label
                                            class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input"
                                                type="checkbox" value="1"
                                                checked="checked"
                                                name="payment_type">
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold">
                                                All
                                            </span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label
                                            class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input"
                                                type="checkbox" value="2"
                                                checked="checked"
                                                name="payment_type">
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold">
                                                Visa
                                            </span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label
                                            class="form-check form-check-custom form-check-sm form-check-solid mb-3">
                                            <input class="form-check-input"
                                                type="checkbox" value="3"
                                                name="payment_type">
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold">
                                                Mastercard
                                            </span>
                                        </label>
                                        <!--end::Radio button-->
                                        <!--begin::Radio button-->
                                        <label
                                            class="form-check form-check-custom form-check-sm form-check-solid">
                                            <input class="form-check-input"
                                                type="checkbox" value="4"
                                                name="payment_type">
                                            <span
                                                class="form-check-label text-gray-600 fw-semibold">
                                                American Express
                                            </span>
                                        </label>
                                        <!--end::Radio button-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Actions-->
                                <div class="text-center">
                                    <button type="reset"
                                        id="kt_customers_export_cancel"
                                        class="btn btn-light me-3">
                                        Discard
                                    </button>
                                    <button type="submit"
                                        id="kt_customers_export_submit"
                                        class="btn btn-primary">
                                        <span class="indicator-label">
                                            Submit
                                        </span>
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - New Card--><!--end::Modals-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->