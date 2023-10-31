<title>Résultat Technicien | CFAO Mobility Academy</title>
<!--end::Title-->
<meta property="og:url"
    content="https://themes.getbootstrap.com/product/craft-bootstrap-5-admin-dashboard-theme" />
<meta property="og:site_name" content="Keenthemes | Craft" />
<link rel="canonical" href="https://preview.keenthemes.com/craft" />
<link rel="shortcut icon" href="/images/logo-cfao.png" />
<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="/assets/plugins/custom/leaflet/leaflet.bundle.css" rel="stylesheet"
    type="text/css" />
<link href="/assets/plugins/custom/datatables/datatables.bundle.css"
    rel="stylesheet" type="text/css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
    crossorigin="anonymous">
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet"
    type="text/css" />
<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">
<!--end::Global Stylesheets Bundle-->

<!--begin::Body-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content"
    data-select2-id="select2-data-kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div
            class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div
                class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1" style="font-size: 50px;">
                    Résultat
                    <%= user.firstName + " " + user.lastName %>
                </h1>
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
    <div class="post d-flex flex-column-fluid" id="kt_post"
        data-select2-id="select2-data-kt_post">
        <!--begin::Container-->
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end"
                        data-kt-customer-table-toolbar="base">
                        <!--begin::Export-->
                        <button type="button" id="excel"
                            class="btn btn-light-primary me-3"
                            data-bs-toggle="modal"
                            data-bs-target="#kt_customers_export_modal">
                            <i class="ki-duotone ki-exit-up fs-2"><span
                                    class="path1"></span><span
                                    class="path2"></span></i> Excel
                        </button>
                        <!--end::Export-->
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
                    <div class="table-responsi">
                        <table aria-describedby=""
                            class="table align-middle table-bordered table-row-dashed gy-5 dataTable no-footer"
                            id="kt_customers_table">
                            <thead>
                                <tr
                                    class="text-start text-gray-400 fw-bold text-uppercase gs-0">
                                    <th class="min-w-125px sorting bg-primary text-white text-center table-light"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        colspan="12"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; font-size: 20px; ">
                                        Résultats de la mesure des savoirs
                                        et savoirs-faire (Compétences)</th>
                                <tr></tr>
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    rowspan="3"
                                    aria-label="Email: activate to sort column ascending"
                                    >
                                    Groupe Fonctionnel</th>
                                <th class="min-w-500px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    colspan="3"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Test Factuel (Savoirs) </th>
                                <th class="min-w-800px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    colspan="6"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Test Déclaratif (Savoir-faire)</th>
                                <th class="min-w-100px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    colspan="2"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Validation</th>
                                <tr></tr>
                                <th class="min-w-300px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Savoirs</th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Résultats</th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Validation</th>
                                <th class="min-w-350px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Savoir-faire</th>
                                <th class="min-w-120px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Résultats technicien</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Résultats manager</th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Résultats</th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Validation</th>
                                <th class="min-w-100px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Fiabilité mesure</th>
                                <th class="min-w-100px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Compétence</th>
                                <th class="min-w-100px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0"
                                    aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending"
                                    style="width: 155.266px;">
                                    Métier</th>
                                <tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600" id="table">
                                <% if (transversaleFac && transversaleDecla && transversaleDeclaMa) { %>
                                <% for (let i = 0; i < transversaleFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase "
                                        tabindex="0"    
                                        aria-controls="kt_customers_table"
                                        rowspan='${i}'
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Transversale</td>
                                    <td>
                                        <%= transversaleFac.questions[i].label %>
                                    </td>
                                    <td  class="text-center" name="savoir" id="sTransverse">
                                        <%= transversaleFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sTransverse">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= transversaleDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= transversaleDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= transversaleDeclaMa.answers[i] %>
                                    </td>
                                    <% if (transversaleDecla.answers[i] == "Oui" && transversaleDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfTransverse">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transversaleDecla.answers[i] == "Non" && transversaleDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfTransverse">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transversaleDecla.answers[i] != transversaleDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfTransverse">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfTransverse">
                                        
                                    </td>
                                    <% if (transversaleDecla.answers[i] == transversaleDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (transversaleDecla.answers[i] != transversaleDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (transversaleFac.answers[i] == "Maitrisé" && transversaleDecla.answers[i] == "Oui" && transversaleDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vTransverse">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transversaleFac.answers[i] == "Non maitrisé" && transversaleDecla.answers[i] == "Non" && transversaleDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vTransverse">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transversaleFac.answers[i] == "Non maitrisé" && transversaleDecla.answers[i] == "Oui" && transversaleDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vTransverse">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transversaleFac.answers[i] == "Maitrisé" && transversaleDecla.answers[i] == "Non" && transversaleDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vTransverse">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transversaleFac.answers[i] == "Maitrisé" && transversaleDecla.answers[i] != transversaleDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vTransverse">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transversaleFac.answers[i] == "Non maitrisé" && transversaleDecla.answers[i] != transversaleDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vTransverse">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mTransverse">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (assistanceConduiteFac && assistanceConduiteDecla && assistanceConduiteDeclaMa) { %>
                                <% for (let i = 0; i < assistanceConduiteFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Assistance à la Conduite</td>
                                    <td>
                                        <%= assistanceConduiteFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sAssistance">
                                        <%= assistanceConduiteFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sAssistance">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= assistanceConduiteDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= assistanceConduiteDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= assistanceConduiteDeclaMa.answers[i] %>
                                    </td>
                                    <% if (assistanceConduiteDecla.answers[i] == "Oui" && assistanceConduiteDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfAssistance">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteDecla.answers[i] == "Non" && assistanceConduiteDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfAssistance">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteDecla.answers[i] != assistanceConduiteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfAssistance">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfAssistance">
                                    </td>
                                    <% if (assistanceConduiteDecla.answers[i] == assistanceConduiteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteDecla.answers[i] != assistanceConduiteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteFac.answers[i] == "Maitrisé" && assistanceConduiteDecla.answers[i] == "Oui" && assistanceConduiteDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vAssistance">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteFac.answers[i] == "Non maitrisé" && assistanceConduiteDecla.answers[i] == "Non" && assistanceConduiteDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vAssistance">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteFac.answers[i] == "Non maitrisé" && assistanceConduiteDecla.answers[i] == "Oui" && assistanceConduiteDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vAssistance">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteFac.answers[i] == "Maitrisé" && assistanceConduiteDecla.answers[i] == "Non" && assistanceConduiteDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vAssistance">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteFac.answers[i] == "Maitrisé" && assistanceConduiteDecla.answers[i] != assistanceConduiteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vAssistance">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (assistanceConduiteFac.answers[i] == "Non maitrisé" && assistanceConduiteDecla.answers[i] != assistanceConduiteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vAssistance">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mAssistance">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (climatisationFac && climatisationDecla && climatisationDeclaMa) { %>
                                <% for (let i = 0; i < climatisationFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Climatisation</td>
                                    <td>
                                        <%= climatisationFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sClimatisation">
                                        <%= climatisationFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sClimatisation">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= climatisationDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= climatisationDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= climatisationDeclaMa.answers[i] %>
                                    </td>
                                    <% if (climatisationDecla.answers[i] == "Oui" && climatisationDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfClimatisation">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (climatisationDecla.answers[i] == "Non" && climatisationDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfClimatisation">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (climatisationDecla.answers[i] != climatisationDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfClimatisation">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfClimatisation">
                                        
                                    </td>
                                    <% if (climatisationDecla.answers[i] == climatisationDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (climatisationDecla.answers[i] != climatisationDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (climatisationFac.answers[i] == "Maitrisé" && climatisationDecla.answers[i] == "Oui" && climatisationDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vClimatisation">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (climatisationFac.answers[i] == "Non maitrisé" && climatisationDecla.answers[i] == "Non" && climatisationDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vClimatisation">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (climatisationFac.answers[i] == "Non maitrisé" && climatisationDecla.answers[i] == "Oui" && climatisationDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vClimatisation">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (climatisationFac.answers[i] == "Maitrisé" && climatisationDecla.answers[i] == "Non" && climatisationDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vClimatisation">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (climatisationFac.answers[i] == "Maitrisé" && climatisationDecla.answers[i] != climatisationDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vClimatisation">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (climatisationFac.answers[i] == "Non maitrisé" && climatisationDecla.answers[i] != climatisationDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vClimatisation">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mClimatisation">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (directionFac && directionDecla && directionDeclaMa) { %>
                                <% for (let i = 0; i < directionFac.questions.length; i++) { %>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        Direction</td>
                                    <td>
                                        <%= directionFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sDirection">
                                        <%= directionFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sDirection">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= directionDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= directionDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= directionDeclaMa.answers[i] %>
                                    </td>
                                    <% if (directionDecla.answers[i] == "Oui" && directionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfDirection">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (directionDecla.answers[i] == "Non" && directionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfDirection">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (directionDecla.answers[i] != directionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfDirection">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfDirection">
                                        
                                    </td>
                                    <% if (directionDecla.answers[i] == directionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (directionDecla.answers[i] != directionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (directionFac.answers[i] == "Maitrisé" && directionDecla.answers[i] == "Oui" && directionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vDirection">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (directionFac.answers[i] == "Non maitrisé" && directionDecla.answers[i] == "Non" && directionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vDirection">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (directionFac.answers[i] == "Non maitrisé" && directionDecla.answers[i] == "Oui" && directionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vDirection">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (directionFac.answers[i] == "Maitrisé" && directionDecla.answers[i] == "Non" && directionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vDirection">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (directionFac.answers[i] == "Maitrisé" && directionDecla.answers[i] != directionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vDirection">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (directionFac.answers[i] == "Non maitrisé" && directionDecla.answers[i] != directionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vDirection">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mDirection">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (electriciteFac && electriciteDecla && electriciteDeclaMa) { %>
                                <% for (let i = 0; i < electriciteFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Electricté</th>
                                    <td>
                                        <%= electriciteFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sElectricite">
                                        <%= electriciteFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sElectricite">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= electriciteDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= electriciteDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= electriciteDeclaMa.answers[i] %>
                                    </td>
                                    <% if (electriciteDecla.answers[i] == "Oui" && electriciteDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfElectricite">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (electriciteDecla.answers[i] == "Non" && electriciteDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfElectricite">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (electriciteDecla.answers[i] != electriciteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfElectricite">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfElectricite">
                                        
                                    </td>
                                    <% if (electriciteDecla.answers[i] == electriciteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (electriciteDecla.answers[i] != electriciteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (electriciteFac.answers[i] == "Maitrisé" && electriciteDecla.answers[i] == "Oui" && electriciteDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vElectricite">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (electriciteFac.answers[i] == "Non maitrisé" && electriciteDecla.answers[i] == "Non" && electriciteDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vElectricite">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (electriciteFac.answers[i] == "Non maitrisé" && electriciteDecla.answers[i] == "Oui" && electriciteDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vElectricite">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (electriciteFac.answers[i] == "Maitrisé" && electriciteDecla.answers[i] == "Non" && electriciteDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vElectricite">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (electriciteFac.answers[i] == "Maitrisé" && electriciteDecla.answers[i] != electriciteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vElectricite">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (electriciteFac.answers[i] == "Non maitrisé" && electriciteDecla.answers[i] != electriciteDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vElectricite">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mElectricite">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (freinageFac && freinageDecla && freinageDeclaMa) { %>
                                <% for (let i = 0; i < freinageFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Freinage</th>
                                    <td>
                                        <%= freinageFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sFreinage">
                                        <%= freinageFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sFreinage">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= freinageDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= freinageDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= freinageDeclaMa.answers[i] %>
                                    </td>
                                    <% if (freinageDecla.answers[i] == "Oui" && freinageDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfFreinage">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (freinageDecla.answers[i] == "Non" && freinageDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfFreinage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (freinageDecla.answers[i] != freinageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfFreinage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfFreinage">
                                        
                                    </td>
                                    <% if (freinageDecla.answers[i] == freinageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (freinageDecla.answers[i] != freinageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (freinageFac.answers[i] == "Maitrisé" && freinageDecla.answers[i] == "Oui" && freinageDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vFreinage">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (freinageFac.answers[i] == "Non maitrisé" && freinageDecla.answers[i] == "Non" && freinageDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vFreinage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (freinageFac.answers[i] == "Non maitrisé" && freinageDecla.answers[i] == "Oui" && freinageDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vFreinage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (freinageFac.answers[i] == "Maitrisé" && freinageDecla.answers[i] == "Non" && freinageDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vFreinage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (freinageFac.answers[i] == "Maitrisé" && freinageDecla.answers[i] != freinageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vFreinage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (freinageFac.answers[i] == "Non maitrisé" && freinageDecla.answers[i] != freinageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vFreinage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mFreinage">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (hydrauliqueFac && hydrauliqueDecla && hydrauliqueDeclaMa) { %>
                                <% for (let i = 0; i < hydrauliqueFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Hydraulique</td>
                                    <td>
                                        <%= hydrauliqueFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sHydraulique">
                                        <%= hydrauliqueFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sHydraulique">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= hydrauliqueDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= hydrauliqueDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= hydrauliqueDeclaMa.answers[i] %>
                                    </td>
                                    <% if (hydrauliqueDecla.answers[i] == "Oui" && hydrauliqueDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfHydraulique">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueDecla.answers[i] == "Non" && hydrauliqueDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfHydraulique">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueDecla.answers[i] != hydrauliqueDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfHydraulique">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfHydraulique">
                                        
                                    </td>
                                    <% if (hydrauliqueDecla.answers[i] == hydrauliqueDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueDecla.answers[i] != hydrauliqueDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueFac.answers[i] == "Maitrisé" && hydrauliqueDecla.answers[i] == "Oui" && hydrauliqueDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vHydraulique">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueFac.answers[i] == "Non maitrisé" && hydrauliqueDecla.answers[i] == "Non" && hydrauliqueDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vHydraulique">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueFac.answers[i] == "Non maitrisé" && hydrauliqueDecla.answers[i] == "Oui" && hydrauliqueDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vHydraulique">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueFac.answers[i] == "Maitrisé" && hydrauliqueDecla.answers[i] == "Non" && hydrauliqueDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vHydraulique">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueFac.answers[i] == "Maitrisé" && hydrauliqueDecla.answers[i] != hydrauliqueDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vHydraulique">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (hydrauliqueFac.answers[i] == "Non maitrisé" && hydrauliqueDecla.answers[i] != hydrauliqueDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vHydraulique">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mHydraulique">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (moteurFac && moteurDecla && moteurDeclaMa) { %>
                                <% for (let i = 0; i < moteurFac.questions.length; i++) { %>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        Moteur</td>
                                    <td>
                                        <%= moteurFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sMoteur">
                                        <%= moteurFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sMoteur">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= moteurDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= moteurDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= moteurDeclaMa.answers[i] %>
                                    </td>
                                    <% if (moteurDecla.answers[i] == "Oui" && moteurDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfMoteur">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (moteurDecla.answers[i] == "Non" && moteurDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfMoteur">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (moteurDecla.answers[i] != moteurDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfMoteur">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfMoteur">
                                        
                                    </td>
                                    <% if (moteurDecla.answers[i] == moteurDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (moteurDecla.answers[i] != moteurDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (moteurFac.answers[i] == "Maitrisé" && moteurDecla.answers[i] == "Oui" && moteurDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vMoteur">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (moteurFac.answers[i] == "Non maitrisé" && moteurDecla.answers[i] == "Non" && moteurDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vMoteur">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (moteurFac.answers[i] == "Non maitrisé" && moteurDecla.answers[i] == "Oui" && moteurDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vMoteur">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (moteurFac.answers[i] == "Maitrisé" && moteurDecla.answers[i] == "Non" && moteurDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vMoteur">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (moteurFac.answers[i] == "Maitrisé" && moteurDecla.answers[i] != moteurDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vMoteur">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (moteurFac.answers[i] == "Non maitrisé" && moteurDecla.answers[i] != moteurDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vMoteur">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mMoteur">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                
                                <% } %><!--end::Menu-->
                                <% if (multiplexageFac && multiplexageDecla && multiplexageDeclaMa) { %>
                                <% for (let i = 0; i < multiplexageFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Multiplexage & Electronique</td>
                                    <td>
                                        <%= multiplexageFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="smultiplexage">
                                        <%= multiplexageFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-smultiplexage">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= multiplexageDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= multiplexageDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= multiplexageDeclaMa.answers[i] %>
                                    </td>
                                    <% if (multiplexageDecla.answers[i] == "Oui" && multiplexageDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfmultiplexage">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (multiplexageDecla.answers[i] == "Non" && multiplexageDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfmultiplexage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (multiplexageDecla.answers[i] != multiplexageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfmultiplexage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfmultiplexage">
                                        
                                    </td>
                                    <% if (multiplexageDecla.answers[i] == multiplexageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (multiplexageDecla.answers[i] != multiplexageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (multiplexageFac.answers[i] == "Maitrisé" && multiplexageDecla.answers[i] == "Oui" && multiplexageDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vmultiplexage">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (multiplexageFac.answers[i] == "Non maitrisé" && multiplexageDecla.answers[i] == "Non" && multiplexageDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vmultiplexage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (multiplexageFac.answers[i] == "Non maitrisé" && multiplexageDecla.answers[i] == "Oui" && multiplexageDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vmultiplexage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (multiplexageFac.answers[i] == "Maitrisé" && multiplexageDecla.answers[i] == "Non" && multiplexageDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vmultiplexage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (multiplexageFac.answers[i] == "Maitrisé" && multiplexageDecla.answers[i] != multiplexageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vmultiplexage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (multiplexageFac.answers[i] == "Non maitrisé" && multiplexageDecla.answers[i] != multiplexageDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vmultiplexage">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mmultiplexage">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (pneuFac && pneuDecla && pneuDeclaMa) { %>
                                <% for (let i = 0; i < pneuFac.questions.length; i++) { %>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        Pneumatique</td>
                                    <td>
                                        <%= pneuFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sPneu">
                                        <%= pneuFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sPneu">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= pneuDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= pneuDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= pneuDeclaMa.answers[i] %>
                                    </td>
                                    <% if (pneuDecla.answers[i] == "Oui" && pneuDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfPneu">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (pneuDecla.answers[i] == "Non" && pneuDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfPneu">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (pneuDecla.answers[i] != pneuDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfPneu">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfPneu">
                                        
                                    </td>
                                    <% if (pneuDecla.answers[i] == pneuDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (pneuDecla.answers[i] != pneuDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (pneuFac.answers[i] == "Maitrisé" && pneuDecla.answers[i] == "Oui" && pneuDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vPneu">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (pneuFac.answers[i] == "Non maitrisé" && pneuDecla.answers[i] == "Non" && pneuDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vPneu">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (pneuFac.answers[i] == "Non maitrisé" && pneuDecla.answers[i] == "Oui" && pneuDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vPneu">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (pneuFac.answers[i] == "Maitrisé" && pneuDecla.answers[i] == "Non" && pneuDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vPneu">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (pneuFac.answers[i] == "Maitrisé" && pneuDecla.answers[i] != pneuDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vPneu">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (pneuFac.answers[i] == "Non maitrisé" && pneuDecla.answers[i] != pneuDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vPneu">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mPneu">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (suspensionFac && suspensionDecla && suspensionDeclaMa) { %>
                                <% for (let i = 0; i < suspensionFac.questions.length; i++) { %>
                                <tr class="odd">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Suspension à Lame</td>
                                    <td>
                                        <%= suspensionFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sSuspension">
                                        <%= suspensionFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sSuspension">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= suspensionDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= suspensionDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= suspensionDeclaMa.answers[i] %>
                                    </td>
                                    <% if (suspensionDecla.answers[i] == "Oui" && suspensionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfSuspension">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (suspensionDecla.answers[i] == "Non" && suspensionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfSuspension">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (suspensionDecla.answers[i] != suspensionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfSuspension">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfSuspension">
                                        
                                    </td>
                                    <% if (suspensionDecla.answers[i] == suspensionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (suspensionDecla.answers[i] != suspensionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (suspensionFac.answers[i] == "Maitrisé" && suspensionDecla.answers[i] == "Oui" && suspensionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vSuspension">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (suspensionFac.answers[i] == "Non maitrisé" && suspensionDecla.answers[i] == "Non" && suspensionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vSuspension">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (suspensionFac.answers[i] == "Non maitrisé" && suspensionDecla.answers[i] == "Oui" && suspensionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vSuspension">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (suspensionFac.answers[i] == "Maitrisé" && suspensionDecla.answers[i] == "Non" && suspensionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vSuspension">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (suspensionFac.answers[i] == "Maitrisé" && suspensionDecla.answers[i] != suspensionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vSuspension">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (suspensionFac.answers[i] == "Non maitrisé" && suspensionDecla.answers[i] != suspensionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vSuspension">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mSuspension">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <% if (transmissionFac && transmissionDecla && transmissionDeclaMa) { %>
                                <% for (let i = 0; i < transmissionFac.questions.length; i++) { %>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        Transmission</td>
                                    <td>
                                        <%= transmissionFac.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="savoir" id="sTransmission">
                                        <%= transmissionFac.answers[i] %>
                                    </td>
                                    <td class="text-center" id="result-sTransmission">
                                        
                                    </td>
                                    <td data-filter="email">
                                        <%= transmissionDecla.questions[i].label %>
                                    </td>
                                    <td class="text-center" name="n">
                                        <%= transmissionDecla.answers[i] %>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <%= transmissionDeclaMa.answers[i] %>
                                    </td>
                                    <% if (transmissionDecla.answers[i] == "Oui" && transmissionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfTransmission">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transmissionDecla.answers[i] == "Non" && transmissionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="savoirs-faire" id="sfTransmission">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transmissionDecla.answers[i] != transmissionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="savoirs-faire" id="sfTransmission">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-sfTransmission">
                                        
                                    </td>
                                    <% if (transmissionDecla.answers[i] == transmissionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Oui
                                    </td>
                                    <% } %>
                                    <% if (transmissionDecla.answers[i] != transmissionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="coh">
                                        Non
                                    </td>
                                    <% } %>
                                    <% if (transmissionFac.answers[i] == "Maitrisé" && transmissionDecla.answers[i] == "Oui" && transmissionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vTransmission">
                                        Maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transmissionFac.answers[i] == "Non maitrisé" && transmissionDecla.answers[i] == "Non" && transmissionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vTransmission">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transmissionFac.answers[i] == "Non maitrisé" && transmissionDecla.answers[i] == "Oui" && transmissionDeclaMa.answers[i] == "Oui") { %>
                                    <td class="text-center" name="valid" id="vTransmission">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transmissionFac.answers[i] == "Maitrisé" && transmissionDecla.answers[i] == "Non" && transmissionDeclaMa.answers[i] == "Non") { %>
                                    <td class="text-center" name="valid" id="vTransmission">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transmissionFac.answers[i] == "Maitrisé" && transmissionDecla.answers[i] != transmissionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vTransmission">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <% if (transmissionFac.answers[i] == "Non maitrisé" && transmissionDecla.answers[i] != transmissionDeclaMa.answers[i]) { %>
                                    <td class="text-center" name="valid" id="vTransmission">
                                        Non maitrisé
                                    </td>
                                    <% } %>
                                    <td class="text-center" id="result-mTransmission">
                                        
                                    </td>
                                </tr>
                                <% } %>
                                <% } %><!--end::Menu-->
                                <tr>
                                    <th id=""
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        </th>
                                    <th id=""
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Résultats</th>
                                    <th id="result-savoir"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        colspan="2"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                    </th>
                                    <th id=""
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                        Résultats</th>
                                    <th id="result-n"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                    </th>
                                    <th id="result-n1"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                    </th>
                                    <th id="result-savoir-faire"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        colspan="2"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                    </th>
                                    <th id="result-coh"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                    </th>
                                    <th id="result-valid"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        colspan="2"
                                        tabindex="0"
                                        aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px;">
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Body-->
<script src="https://code.jquery.com/jquery-3.6.3.js"
    integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
crossorigin="anonymous"></script>
<script
    src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js">
</script>
<script>
    $(document).ready(function() {
        $("#excel").on("click", function() {
             let table = document.getElementsByTagName("table");
             debugger;
             TableToExcel.convert(table[0], {
                name: `Table.xlsx`
            })
        });
    });

    const savoir = []
    const savoirFaire = []
    const n = []
    const n1 = []
    const coh = []
    const valid = []
    const sTransverse = []
    const sfTransverse = []
    const vTransverse = []
    const sTransmission = []
    const sfTransmission = []
    const vTransmission = []
    const sAssistance = []
    const sfAssistance = []
    const vAssistance = []
    const sClimatisation = []
    const sfClimatisation = []
    const vClimatisation = []
    const sDirection = []
    const sfDirection = []
    const vDirection = []
    const sElectricite = []
    const sfElectricite = []
    const vElectricite = []
    const sFreinage = []
    const sfFreinage = []
    const vFreinage = []
    const sMoteur = []
    const sfMoteur = []
    const vMoteur = []
    const sHydraulique = []
    const sfHydraulique = []
    const vHydraulique = []
    const sPneu = []
    const sfPneu = []
    const vPneu = []
    const smultiplexage = []
    const sfmultiplexage = []
    const vmultiplexage = []
    const sSuspension = []
    const sfSuspension = []
    const vSuspension = []
    const valueMaitrisé = "Maitrisé"
    const valueOui = "Oui"
    const tdSavoir = document.querySelectorAll("td[name='savoir']")
    const tdSavoirFaire = document.querySelectorAll("td[name='savoirs-faire']")
    const tdN = document.querySelectorAll("td[name='n']")
    const tdN1 = document.querySelectorAll("td[name='n1']")
    const tdCoh = document.querySelectorAll("td[name='coh']")
    const tdValid = document.querySelectorAll("td[name='valid']")
    const tdsTransverse = document.querySelectorAll("#sTransverse")
    const tdsfTransverse = document.querySelectorAll("#sfTransverse")
    const tdvTransverse = document.querySelectorAll("#vTransverse")
    const tdsTransmission = document.querySelectorAll("#sTransmission")
    const tdsfTransmission = document.querySelectorAll("#sfTransmission")
    const tdvTransmission = document.querySelectorAll("#vTransmission")
    const tdsAssistance = document.querySelectorAll("#sAssistance")
    const tdsfAssistance = document.querySelectorAll("#sfAssistance")
    const tdvAssistance = document.querySelectorAll("#vAssistance")
    const tdsClimatisation = document.querySelectorAll("#sClimatisation")
    const tdsfClimatisation = document.querySelectorAll("#sfClimatisation")
    const tdvClimatisation = document.querySelectorAll("#vClimatisation")
    const tdsDirection = document.querySelectorAll("#sDirection")
    const tdsfDirection = document.querySelectorAll("#sfDirection")
    const tdvDirection = document.querySelectorAll("#vDirection")
    const tdsElectricite = document.querySelectorAll("#sElectricite")
    const tdsfElectricite = document.querySelectorAll("#sfElectricite")
    const tdvElectricite = document.querySelectorAll("#vElectricite")
    const tdsFreinage = document.querySelectorAll("#sFreinage")
    const tdsfFreinage = document.querySelectorAll("#sfFreinage")
    const tdvFreinage = document.querySelectorAll("#vFreinage")
    const tdsHydraulique = document.querySelectorAll("#sHydraulique")
    const tdsfHydraulique = document.querySelectorAll("#sfHydraulique")
    const tdvHydraulique = document.querySelectorAll("#vHydraulique")
    const tdsMoteur = document.querySelectorAll("#sMoteur")
    const tdsfMoteur = document.querySelectorAll("#sfMoteur")
    const tdvMoteur = document.querySelectorAll("#vMoteur")
    const tdsPneu = document.querySelectorAll("#sPneu")
    const tdsfPneu = document.querySelectorAll("#sfPneu")
    const tdvPneu = document.querySelectorAll("#vPneu")
    const tdsmultiplexage = document.querySelectorAll("#smultiplexage")
    const tdsfmultiplexage = document.querySelectorAll("#sfmultiplexage")
    const tdvmultiplexage = document.querySelectorAll("#vmultiplexage")
    const tdsSuspension = document.querySelectorAll("#sSuspension")
    const tdsfSuspension = document.querySelectorAll("#sfSuspension")
    const tdvSuspension = document.querySelectorAll("#vSuspension")
    const resultSavoir = document.querySelector("#result-savoir")
    const resultSavoirFaire = document.querySelector("#result-savoir-faire")
    const resultN = document.querySelector("#result-n")
    const resultN1 = document.querySelector("#result-n1")
    const resultCoh = document.querySelector("#result-coh")
    const resultValid = document.querySelector("#result-valid")
    const resultsTransverse = document.querySelector("#result-sTransverse")
    const resultsfTransverse = document.querySelector("#result-sfTransverse")
    const resultvTransverse = document.querySelector("#result-mTransverse")
    const resultsTransmission = document.querySelector("#result-sTransmission")
    const resultsfTransmission = document.querySelector("#result-sfTransmission")
    const resultvTransmission = document.querySelector("#result-mTransmission")
    const resultsAssistance = document.querySelector("#result-sAssistance")
    const resultsfAssistance = document.querySelector("#result-sfAssistance")
    const resultvAssistance = document.querySelector("#result-mAssistance")
    const resultsClimatisation= document.querySelector("#result-sClimatisation")
    const resultsfClimatisation = document.querySelector("#result-sfClimatisation")
    const resultvClimatisation = document.querySelector("#result-mClimatisation")
    const resultsDirection = document.querySelector("#result-sDirection")
    const resultsfDirection = document.querySelector("#result-sfDirection")
    const resultvDirection = document.querySelector("#result-mDirection")
    const resultsElectricite = document.querySelector("#result-sElectricite")
    const resultsfElectricite = document.querySelector("#result-sfElectricite")
    const resultvElectricite = document.querySelector("#result-mElectricite")
    const resultsFreinage = document.querySelector("#result-sFreinage")
    const resultsfFreinage = document.querySelector("#result-sfFreinage")
    const resultvFreinage = document.querySelector("#result-mFreinage")
    const resultsHydraulique= document.querySelector("#result-sHydraulique")
    const resultsfHydraulique= document.querySelector("#result-sfHydraulique")
    const resultvHydraulique = document.querySelector("#result-mHydraulique")
    const resultsMoteur = document.querySelector("#result-sMoteur")
    const resultsfMoteur = document.querySelector("#result-sfMoteur")
    const resultvMoteur = document.querySelector("#result-mMoteur")
    const resultsmultiplexage = document.querySelector("#result-smultiplexage")
    const resultsfmultiplexage = document.querySelector("#result-sfmultiplexage")
    const resultvmultiplexage = document.querySelector("#result-mmultiplexage")
    const resultsPneu = document.querySelector("#result-sPneu")
    const resultsfPneu = document.querySelector("#result-sfPneu")
    const resultvPneu = document.querySelector("#result-mPneu")
    const resultsSuspension = document.querySelector("#result-sSuspension")
    const resultsfSuspension = document.querySelector("#result-sfSuspension")
    const resultvSuspension = document.querySelector("#result-mSuspension")

    for (let i = 0; i < tdSavoir.length; i++) {
        savoir.push(tdSavoir[i].innerHTML)
    }
    for (let i = 0; i < tdSavoirFaire.length; i++) {
        savoirFaire.push(tdSavoirFaire[i].innerHTML)
    }
    for (let i = 0; i < tdN.length; i++) {
        n.push(tdN[i].innerHTML)
    }
    for (let i = 0; i < tdN1.length; i++) {
        n1.push(tdN1[i].innerHTML)
    }
    for (let i = 0; i < tdCoh.length; i++) {
        coh.push(tdCoh[i].innerHTML)
    }
    for (let i = 0; i < tdValid.length; i++) {
        valid.push(tdValid[i].innerHTML)
    }
    for (let i = 0; i < tdsTransverse.length; i++) {
        sTransverse.push(tdsTransverse[i].innerHTML)
    }
    for (let i = 0; i < tdsfTransverse.length; i++) {
        sfTransverse.push(tdsfTransverse[i].innerHTML)
    }
    for (let i = 0; i < tdvTransverse.length; i++) {
        vTransverse.push(tdvTransverse[i].innerHTML)
    }
    for (let i = 0; i < tdsTransmission.length; i++) {
        sTransmission.push(tdsTransmission[i].innerHTML)
    }
    for (let i = 0; i < tdsfTransmission.length; i++) {
        sfTransmission.push(tdsfTransmission[i].innerHTML)
    }
    for (let i = 0; i < tdvTransmission.length; i++) {
        vTransmission.push(tdvTransmission[i].innerHTML)
    }
    for (let i = 0; i < tdsAssistance.length; i++) {
        sAssistance.push(tdsAssistance[i].innerHTML)
    }
    for (let i = 0; i < tdsfAssistance.length; i++) {
        sfAssistance.push(tdsfAssistance[i].innerHTML)
    }
    for (let i = 0; i < tdvAssistance.length; i++) {
        vAssistance.push(tdvAssistance[i].innerHTML)
    }
    for (let i = 0; i < tdsClimatisation.length; i++) {
        sClimatisation.push(tdsClimatisation[i].innerHTML)
    }
    for (let i = 0; i < tdsfClimatisation.length; i++) {
        sfClimatisation.push(tdsfClimatisation[i].innerHTML)
    }
    for (let i = 0; i < tdvClimatisation.length; i++) {
        vClimatisation.push(tdvClimatisation[i].innerHTML)
    }
    for (let i = 0; i < tdsDirection.length; i++) {
        sDirection.push(tdsDirection[i].innerHTML)
    }
    for (let i = 0; i < tdsfDirection.length; i++) {
        sfDirection.push(tdsfDirection[i].innerHTML)
    }
    for (let i = 0; i < tdvDirection.length; i++) {
        vDirection.push(tdvDirection[i].innerHTML)
    }
    for (let i = 0; i < tdsElectricite.length; i++) {
        sElectricite.push(tdsElectricite[i].innerHTML)
    }
    for (let i = 0; i < tdsfElectricite.length; i++) {
        sfElectricite.push(tdsfElectricite[i].innerHTML)
    }
    for (let i = 0; i < tdvElectricite.length; i++) {
        vElectricite.push(tdvElectricite[i].innerHTML)
    }
    for (let i = 0; i < tdsFreinage.length; i++) {
        sFreinage.push(tdsFreinage[i].innerHTML)
    }
    for (let i = 0; i < tdsfFreinage.length; i++) {
        sfFreinage.push(tdsfFreinage[i].innerHTML)
    }
    for (let i = 0; i < tdvFreinage.length; i++) {
        vFreinage.push(tdvFreinage[i].innerHTML)
    }
    for (let i = 0; i < tdsHydraulique.length; i++) {
        sHydraulique.push(tdsHydraulique[i].innerHTML)
    }
    for (let i = 0; i < tdsfHydraulique.length; i++) {
        sfHydraulique.push(tdsfHydraulique[i].innerHTML)
    }
    for (let i = 0; i < tdvHydraulique.length; i++) {
        vHydraulique.push(tdvHydraulique[i].innerHTML)
    }
    for (let i = 0; i < tdsMoteur.length; i++) {
        sMoteur.push(tdsMoteur[i].innerHTML)
    }
    for (let i = 0; i < tdsfMoteur.length; i++) {
        sfMoteur.push(tdsfMoteur[i].innerHTML)
    }
    for (let i = 0; i < tdvMoteur.length; i++) {
        vMoteur.push(tdvMoteur[i].innerHTML)
    }
    for (let i = 0; i < tdsmultiplexage.length; i++) {
        smultiplexage.push(tdsmultiplexage[i].innerHTML)
    }
    for (let i = 0; i < tdsfmultiplexage.length; i++) {
        sfmultiplexage.push(tdsfmultiplexage[i].innerHTML)
    }
    for (let i = 0; i < tdvmultiplexage.length; i++) {
        vmultiplexage.push(tdvmultiplexage[i].innerHTML)
    }
    for (let i = 0; i < tdsPneu.length; i++) {
        sPneu.push(tdsPneu[i].innerHTML)
    }
    for (let i = 0; i < tdsfPneu.length; i++) {
        sfPneu.push(tdsfPneu[i].innerHTML)
    }
    for (let i = 0; i < tdvPneu.length; i++) {
        vPneu.push(tdvPneu[i].innerHTML)
    }
    for (let i = 0; i < tdsSuspension.length; i++) {
        sSuspension.push(tdsSuspension[i].innerHTML)
    }
    for (let i = 0; i < tdsfSuspension.length; i++) {
        sfSuspension.push(tdsfSuspension[i].innerHTML)
    }
    for (let i = 0; i < tdvSuspension.length; i++) {
        vSuspension.push(tdvSuspension[i].innerHTML)
    }

    const maitriseSavoir = savoir.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitriseSavoirFaire = savoirFaire.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const ouiN = n.filter(function(str) {
        return str.includes(valueOui)
    })
    const ouiN1 = n1.filter(function(str) {
        return str.includes(valueOui)
    })
    const ouiCoh = coh.filter(function(str) {
        return str.includes(valueOui)
    })
    const maitriseValid = valid.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesTransverse = sTransverse.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfTransverse = sfTransverse.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevTransverse = vTransverse.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesTransmission = sTransmission.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfTransmission = sfTransmission.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevTransmission = vTransmission.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesAssistance = sAssistance.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfAssistance = sfAssistance.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevAssistance = vAssistance.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesClimatisation = sClimatisation.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfClimatisation = sfClimatisation.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevClimatisation = vClimatisation.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesDirection = sDirection.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfDirection = sfDirection.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevDirection = vDirection.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesElectricite = sElectricite.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfElectricite = sfElectricite.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevElectricite = vElectricite.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesFreinage = sFreinage.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfFreinage = sfFreinage.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevFreinage = vFreinage.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesHydraulique = sHydraulique.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfHydraulique = sfHydraulique.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevHydraulique = vHydraulique.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesMoteur = sMoteur.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfMoteur = sfMoteur.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevMoteur = vMoteur.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesmultiplexage = smultiplexage.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfmultiplexage = sfmultiplexage.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevmultiplexage = vmultiplexage.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesPneu = sPneu.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfPneu = sfPneu.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevPneu = vPneu.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesSuspension = sSuspension.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisesfSuspension = sfSuspension.filter(function(str) {
        return str.includes(valueMaitrisé)
    })
    const maitrisevSuspension = vSuspension.filter(function(str) {
        return str.includes(valueMaitrisé)
    })

    const percentSavoir = ((maitriseSavoir.length * 100) / tdSavoir.length).toFixed(2)
    const percentSavoirFaire = ((maitriseSavoirFaire.length * 100) / tdSavoirFaire.length).toFixed(2)
    const percentN = ((ouiN.length * 100) / tdN.length).toFixed(2)
    const percentN1 = ((ouiN1.length * 100) / tdN1.length).toFixed(2)
    const percentCoh = ((ouiCoh.length * 100) / tdCoh.length).toFixed(2)
    const percentValid = ((maitriseValid.length * 100) / tdValid.length).toFixed(2)
    const percentsTransverse = ((maitrisesTransverse.length * 100) / tdsTransverse.length).toFixed(2)
    const percentsfTransverse = ((maitrisesfTransverse.length * 100) / tdsfTransverse.length).toFixed(2)
    const percentvTransverse = ((maitrisevTransverse.length * 100) / tdvTransverse.length).toFixed(2)
    const percentsTransmission = ((maitrisesTransmission.length * 100) / tdsTransmission.length).toFixed(2)
    const percentsfTransmission = ((maitrisesfTransmission.length * 100) / tdsfTransmission.length).toFixed(2)
    const percentvTransmission = ((maitrisevTransmission.length * 100) / tdvTransmission.length).toFixed(2)
    const percentsAssistance = ((maitrisesAssistance.length * 100) / tdsAssistance.length).toFixed(2)
    const percentsfAssistance = ((maitrisesfAssistance.length * 100) / tdsfAssistance.length).toFixed(2)
    const percentvAssistance = ((maitrisevAssistance.length * 100) / tdvAssistance.length).toFixed(2)
    const percentsClimatisation = ((maitrisesClimatisation.length * 100) / tdsClimatisation.length).toFixed(2)
    const percentsfClimatisation = ((maitrisesfClimatisation.length * 100) / tdsfClimatisation.length).toFixed(2)
    const percentvClimatisation = ((maitrisevClimatisation.length * 100) / tdvClimatisation.length).toFixed(2)
    const percentsDirection = ((maitrisesDirection.length * 100) / tdsDirection.length).toFixed(2)
    const percentsfDirection = ((maitrisesfDirection.length * 100) / tdsfDirection.length).toFixed(2)
    const percentvDirection = ((maitrisevDirection.length * 100) / tdvDirection.length).toFixed(2)
    const percentsElectricite = ((maitrisesElectricite.length * 100) / tdsElectricite.length).toFixed(2)
    const percentsfElectricite = ((maitrisesfElectricite.length * 100) / tdsfElectricite.length).toFixed(2)
    const percentvElectricite = ((maitrisevElectricite.length * 100) / tdvElectricite.length).toFixed(2)
    const percentsFreinage = ((maitrisesFreinage.length * 100) / tdsFreinage.length).toFixed(2)
    const percentsfFreinage = ((maitrisesfFreinage.length * 100) / tdsfFreinage.length).toFixed(2)
    const percentvFreinage = ((maitrisevFreinage.length * 100) / tdvFreinage.length).toFixed(2)
    const percentsHydraulique = ((maitrisesHydraulique.length * 100) / tdsHydraulique.length).toFixed(2)
    const percentsfHydraulique = ((maitrisesfHydraulique.length * 100) / tdsfHydraulique.length).toFixed(2)
    const percentvHydraulique = ((maitrisevHydraulique.length * 100) / tdvHydraulique.length).toFixed(2)
    const percentsMoteur = ((maitrisesMoteur.length * 100) / tdsMoteur.length).toFixed(2)
    const percentsfMoteur = ((maitrisesfMoteur.length * 100) / tdsfMoteur.length).toFixed(2)
    const percentvMoteur = ((maitrisevMoteur.length * 100) / tdvMoteur.length).toFixed(2)
    const percentsPneu = ((maitrisesPneu.length * 100) / tdsPneu.length).toFixed(2)
    const percentsfPneu = ((maitrisesfPneu.length * 100) / tdsfPneu.length).toFixed(2)
    const percentvPneu = ((maitrisevPneu.length * 100) / tdvPneu.length).toFixed(2)
    const percentsmultiplexage = ((maitrisesmultiplexage.length * 100) / tdsmultiplexage.length).toFixed(2)
    const percentsfmultiplexage = ((maitrisesfmultiplexage.length * 100) / tdsfmultiplexage.length).toFixed(2)
    const percentvmultiplexage = ((maitrisevmultiplexage.length * 100) / tdvmultiplexage.length).toFixed(2)
    const percentsSuspension = ((maitrisesSuspension.length * 100) / tdsSuspension.length).toFixed(2)
    const percentsfSuspension = ((maitrisesfSuspension.length * 100) / tdsfSuspension.length).toFixed(2)
    const percentvSuspension = ((maitrisevSuspension.length * 100) / tdvSuspension.length).toFixed(2)

    resultSavoir.innerHTML = percentSavoir + "%";
    resultSavoirFaire.innerHTML = percentSavoirFaire + "%";
    resultN.innerHTML = percentN + "%";
    resultN1.innerHTML = percentN1 + "%";
    resultCoh.innerHTML = percentCoh + "%";
    resultValid.innerHTML = percentValid + "%";
    resultsTransverse.innerHTML = percentsTransverse + "%";
    resultsfTransverse.innerHTML = percentsfTransverse + "%";
    resultvTransverse.innerHTML = percentvTransverse + "%";
    resultsTransmission.innerHTML = percentsTransmission + "%";
    resultsfTransmission.innerHTML = percentsfTransmission + "%";
    resultvTransmission.innerHTML = percentvTransmission + "%";
    resultsAssistance.innerHTML = percentsAssistance + "%";
    resultsfAssistance.innerHTML = percentsfAssistance + "%";
    resultvAssistance.innerHTML = percentvAssistance + "%";
    resultsClimatisation.innerHTML = percentsClimatisation + "%";
    resultvClimatisation.innerHTML = percentvClimatisation + "%";
    resultsDirection.innerHTML = percentsDirection + "%";
    resultsfDirection.innerHTML = percentsfDirection + "%";
    resultvDirection.innerHTML = percentvDirection + "%";
    resultsElectricite.innerHTML = percentsElectricite + "%";
    resultsfElectricite.innerHTML = percentsfElectricite + "%";
    resultvElectricite.innerHTML = percentvElectricite + "%";
    resultsFreinage.innerHTML = percentsFreinage + "%";
    resultsfFreinage.innerHTML = percentsfFreinage + "%";
    resultvFreinage.innerHTML = percentvFreinage + "%";
    resultsHydraulique.innerHTML = percentsHydraulique + "%";
    resultsfHydraulique.innerHTML = percentsfHydraulique + "%";
    resultvHydraulique.innerHTML = percentvHydraulique + "%";
    resultsMoteur.innerHTML = percentsMoteur + "%";
    resultsfMoteur.innerHTML = percentsfMoteur + "%";
    resultvMoteur.innerHTML = percentvMoteur + "%";
    resultsPneu.innerHTML = percentsPneu + "%";
    resultsfPneu.innerHTML = percentsfPneu + "%";
    resultvPneu.innerHTML = percentvPneu + "%";
    resultsmultiplexage.innerHTML = percentsmultiplexage + "%";
    resultsfmultiplexage.innerHTML = percentsfmultiplexage + "%";
    resultvmultiplexage.innerHTML = percentvmultiplexage + "%";
    resultsSuspension.innerHTML = percentsSuspension + "%";
    resultsfSuspension.innerHTML = percentsfSuspension + "%";
    resultvSuspension.innerHTML = percentvSuspension + "%";
</script>