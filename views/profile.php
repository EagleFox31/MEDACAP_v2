<!--begin::Title-->
<title>Profile | CFAO Mobility Academy</title>
<!--end::Title-->
<center>
    <%- include('partials/message') %>
</center>
<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div
                class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder my-1 fs-2">
                    Détails
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class="container-xxl">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-50 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body">
                            <% if(currentUser.profile == "Admin" || currentUser.profile == "Manager" || currentUser.profile == "Technicien") { %>
                            <div class="mt-5" style="margin-right: -10px">
                                <span data-bs-toggle="tooltip"
                                    data-bs-trigger="hover"
                                    style="margin-right: 40px;">
                                    <a href="#"
                                        class="btn btn-sm text-white fs-6 float-end"
                                        style="background: #225e41;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_update_details">Modifier</a>
                                </span>
                            </div>
                            <% } %>
                            <!--begin::Summary-->
                            <!--begin::User Info-->
                            <div class="d-flex flex-center flex-column py-5">
                                <!--begin::Avatar-->
                                <div
                                    class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="/assets/media/avatars/300-1.jpg" alt="image" />
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#"
                                    class="fs-1 text-gray-800 text-hover-success fw-bolder mb-3">
                                    <%= user.firstName + " " + user.lastName %>
                                </a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="mb-9">
                                    <!--begin::Badge-->
                                    <div
                                        class="fs-4 badge badge-lg badge-light-success d-inline">
                                        <%= user.profile %>
                                    </div>
                                    <!--begin::Badge-->
                                </div>
                                <!--end::Position-->
                            </div>
                            <!--end::User Info-->
                            <!--end::Summary-->
                            <div class="separator"></div>
                            <!--end::Summary-->
                            <!--begin::Details content-->
                            <div id="kt_user_view_details"
                                class="collapse show">
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Matricule
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.matricule %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Email
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.email %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Numéro de téléphone
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.phone %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Sexe
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.gender %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Pays
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.country %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Date de naissance
                                    </div>
                                    <div class="text-gray-600">
                                        <%= birthdate %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Diplôme
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.certificate %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Filiale
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.subsidiary %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Departement
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.department %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Fonction
                                    </div>
                                    <div class="text-gray-600">
                                        <%= user.role %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">
                                        Date de recrutement
                                    </div>
                                    <div class="text-gray-600">
                                        <%= recrutment %>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->  
                                    <div class="fw-bolder mt-5">
                                        Etat
                                    </div>
                                    <% if(user.active == true) { %>
                                    <div class="text-gray-600">
                                        Activé
                                    </div>
                                    <% } %> 
                                    <% if(user.active == false) { %>
                                    <div class="text-gray-600">
                                        Désactivé
                                    </div>
                                    <% } %> 
                                    <!--begin::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin:::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <!--begin:::Tab pane-->
                        <div class="tab-pane show active"
                            id="kt_user_view_overview_tab" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card card-flush mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header mt-6">
                                    <!--begin::Card title-->
                                    <div class="card-title flex-column">
                                        <h2 class="mb-1">
                                            Autres
                                        </h2>
                                    </div>
                                    <!--end::Card title-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body p-9 pt-4">
                                    <!--begin::Tab Content-->
                                    <div class="tab-content">
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_1"
                                            class="tab-pane show active">
                                            <!--begin::Activity items-->
                                            <!-- begin::Activity item -->
                                            <div
                                                class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div
                                                    class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                                </div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Title-->
                                                    <h4>
                                                        Changer mot de passe
                                                    </h4>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_update_password"
                                                    class="btn btn-light bnt-active-light-primary btn-sm">
                                                    Changer
                                                </a>
                                                <!--end::Action-->
                                            </div>
                                            <!-- end::Activity item -->
                                            <!--end::Activity items-->
                                        </div>
                                        <!--end::Day-->
                                    </div>
                                    <!--end::Tab Content-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end:::Tab pane-->
                    </div>
                    <!--end:::Tab content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->
            <!-- begin:: Modal - Confirm suspend -->
            <div class="modal" id="kt_modal_desactivate" tabindex="-1"
                aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-450px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Form-->
                        <form class="form"
                            action="/suspendre/<%= user.id %>/?_method=PUT"
                            method="POST" id="kt_modal_update_user_form">
                            <input type="hidden" name="_method" value="PUT">
                            <!--begin::Modal header-->
                            <div class="modal-header"
                                id="kt_modal_update_user_header">
                                <!--begin::Modal title-->
                                <h2 class="fs-2 fw-bolder">
                                    Suspension
                                </h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                    data-kt-users-modal-action="close"
                                      data-bs-dismiss="modal">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6"
                                                y="17.3137" width="16"
                                                height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)"
                                                fill="black" />
                                            <rect x="7.41422" y="6" width="16"
                                                height="2" rx="1"
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
                            <div class="modal-body py-10 px-lg-17">
                                <h4>
                                    Voulez-vous vraiment suspendre cette
                                    personne?
                                </h4>
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3"
                                    id="closeDesactivate"
                                    data-bs-dismiss="modal"
                                    data-kt-users-modal-action="cancel">
                                    Non
                                </button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-danger">
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
            <div class="modal" id="kt_modal_activate" tabindex="-1"
                aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-450px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Form-->
                        <form class="form"
                            action="/active/<%= user.id %>/?_method=PUT"
                            method="POST" id="kt_modal_update_user_form">
                            <input type="hidden" name="_method" value="PUT">
                            <!--begin::Modal header-->
                            <div class="modal-header"
                                id="kt_modal_update_user_header">
                                <!--begin::Modal title-->
                                <h2 class="fs-2 fw-bolder">
                                    Activation
                                </h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                    data-kt-users-modal-action="close"
                                    data-bs-dismiss="modal">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6"
                                                y="17.3137" width="16"
                                                height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)"
                                                fill="black" />
                                            <rect x="7.41422" y="6" width="16"
                                                height="2" rx="1"
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
                            <div class="modal-body py-10 px-lg-17">
                                <h4>
                                    Voulez-vous vraiment activer cette personne?
                                </h4>
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3"
                                    id="closeDesactivate"
                                    data-bs-dismiss="modal"
                                    data-kt-users-modal-action="cancel">
                                    Non
                                </button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-primary">
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
            <div class="modal" id="kt_modal_update_details" tabindex="-1"
                aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Form-->
                        <form class="form"
                            action="/update/<%= user.id %>?_method=PUT"
                            method="POST" id="kt_modal_update_user_form">
                            <input type="hidden" name="_method" value="PUT">
                            <!--begin::Modal header-->
                            <div class="modal-header"
                                id="kt_modal_update_user_header">
                                <!--begin::Modal title-->
                                <h2 class="fs-2 fw-bolder">Modification des
                                    informations</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                    data-kt-users-modal-action="close"
                                    data-bs-dismiss="modal"
                                    data-kt-menu-dismiss="true">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6"
                                                y="17.3137" width="16"
                                                height="2" rx="1"
                                                transform="rotate(-45 6 17.3137)"
                                                fill="black" />
                                            <rect x="7.41422" y="6" width="16"
                                                height="2" rx="1"
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
                            <div class="modal-body py-10 px-lg-17">
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
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Matricule</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="matricule"
                                                value="<%= user.matricule %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Prénoms</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="firstName"
                                                value="<%= user.firstName %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Noms</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="lastName"
                                                value="<%= user.lastName %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-bold mb-2">
                                                <span>Email</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="email"
                                                class="form-control form-control-solid"
                                                placeholder="" name="email"
                                                value="<%= user.email %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Numéro
                                                de téléphone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="phone"
                                                value="<%= user.phone %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Date
                                                de naissance</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="birthdate"
                                                value="<%= birthdate %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Pays</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="country"
                                                value="<%= user.country %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Diplôme</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder=""
                                                name="certificate"
                                                value="<%= user.certificate %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Filiale</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="subsidiary"
                                                value="<%= user.subsidiary %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Département</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="department"
                                                value="<%= user.department %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Fonction</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder="" name="role"
                                                value="<%= user.role %>" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label
                                                class="fs-6 fw-bold mb-2">Date
                                                de recrutement</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text"
                                                class="form-control form-control-solid"
                                                placeholder=""
                                                name="recrutmentDate"
                                                value="<%= recrutment %>" />
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
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-menu-dismiss="true"
                                    data-bs-dismiss="modal"
                                    data-kt-users-modal-action="cancel">Annuler</button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-primary">
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
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<div class="modal" id="kt_modal_update_password" tabindex="-1"
    aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form"
                action="/changePassword/<%= user.id %>"
                method="POST">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_update_user_header">
                    <!--begin::Modal title-->
                    <h2 class="fs-2 fw-bolder">
                        Changement du mot de passe
                    </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                    data-bs-dismiss="modal"
                        data-kt-users-modal-action="close">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16"
                                    height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)"
                                    fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2"
                                    rx="1" transform="rotate(45 7.41422 6)"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Ancien mot de
                            passe</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="password"
                            class="form-control form-control-solid"
                            placeholder="" name="oldPassword" value="" />
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Nouveau mot de
                            passe</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="password"
                            class="form-control form-control-solid"
                            placeholder="" name="newPassword" value="" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" class="btn btn-light me-3"
                        id="closeDesactivate"
                        data-bs-dismiss="modal"
                        data-kt-users-modal-action="cancel">
                        Annuler
                    </button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
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
<!--end::Modal - Update user details-->