<?php
session_start();

if ( !isset( $_SESSION[ 'id' ] ) ) {
    header( 'Location: ./index.php' );
    exit();
} else {
    require_once '../vendor/autoload.php';
        
    // Create connection
    $conn = new MongoDB\Client('mongodb://localhost:27017');
        
     // Connecting in database
     $academy = $conn->academy;
        
    // Connecting in collections
    $users = $academy->users;
    $results = $academy->results;

    $user = $_GET['user'];
    $level = $_GET['level'];

    $technician = $users->findOne(['_id' => new MongoDB\BSON\ObjectId( $user )]);
?>
<title>Résultat Technicien | CFAO Mobility Academy</title>
<!--end::Title-->
<!-- Favicon -->
<link href="../public/images/logo-cfao.png" rel="icon">

<meta charset="utf-8" />
<meta name="description"
    content="Craft admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
<meta name="keywords"
    content="Craft, bootstrap, bootstrap 5, admin themes, dark mode, free admin themes, bootstrap admin, bootstrap dashboard" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="Craft - Bootstrap 5 HTML Admin Dashboard Theme" />
<meta property="og:url" content="https://themes.getbootstrap.com/product/craft-bootstrap-5-admin-dashboard-theme" />
<meta property="og:site_name" content="Keenthemes | Craft" />
<link rel="canonical" href="https://preview.keenthemes.com/craft" />
<link rel="shortcut icon" href="/images/logo-cfao.png" />
<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="../public/assets/plugins/custom/leaflet/leaflet.bundle.css" rel="stylesheet" type="text/css" />
<link href="../public/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="../public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
<link href="../public/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag/dist/css/multi-select-tag.css">
<!--end::Global Stylesheets Bundle-->

<!--begin::Body-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" data-select2-id="select2-data-kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1" style="font-size: 50px;">
                    Résultat de
                    <?php echo $technician->firstName ?> <?php echo $technician->lastName ?>
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post" data-select2-id="select2-data-kt_post">
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
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Export-->
                        <button type="button" id="excel" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                            data-bs-target="#kt_customers_export_modal">
                            <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span
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
                <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsi">
                        <table aria-describedby=""
                            class="table align-middle table-bordered table-row-dashed gy-5 dataTable no-footer"
                            id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold text-uppercase gs-0">
                                    <th class="min-w-125px sorting bg-primary text-white text-center table-light"
                                        tabindex="0" aria-controls="kt_customers_table" colspan="8"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; font-size: 20px; ">
                                        Résultats de la mesure des savoirs
                                        et savoirs-faire (Compétences)</th>
                                <tr></tr>
                                <th class="min-w-10px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" rowspan="3"
                                    aria-label="Email: activate to sort column ascending">
                                    Groupe Fonctionnel</th>
                                <th class="min-w-400px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="2"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Test des savoirs (Factuel) </th>
                                <th class="min-w-800px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="4"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Mesure des savoirs-faire (Déclaratif)</th>
                                <th class="min-w-150px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" rowspan="3"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Synthèse</th>
                                <tr></tr>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Résultats</th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Décision</th>
                                <th class="min-w-120px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Résultats technicien</th>
                                <th class="min-w-125px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Résultats manager</th>
                                <th class="min-w-120px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Résultats</th>
                                <th class="min-w-120px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Décision</th>
                                <tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600" id="table">
                                <?php
                                    $assistanceConduiteFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'AssistanceConduite'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $assistanceConduiteDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'AssistanceConduite'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $assistanceConduiteMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'AssistanceConduite']
                                        ]
                                    ]);
                                ?>
                                <?php if ($assistanceConduiteFac && $assistanceConduiteDecla && $assistanceConduiteMa) { ?>
                                <?php for ($i = 0; $i < count($assistanceConduiteFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $assistanceConduiteFac->speciality?>&level=<?php echo $assistanceConduiteFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Assistance à la conduite
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sAssistanceConduite">
                                        <?php echo $assistanceConduiteFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $assistanceConduiteFac->score * 100 / $assistanceConduiteFac->total ?>%
                                    </td>
                                    <?php if ((($assistanceConduiteFac->score  * 100 ) / $assistanceConduiteFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facAssistanceConduite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($assistanceConduiteFac->score  * 100 ) / $assistanceConduiteFac->total) < 80)  { ?>
                                    <td class="text-center" id="facAssistanceConduite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $assistanceConduiteDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $assistanceConduiteDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($assistanceConduiteDecla->answers[$i] == "Oui" && $assistanceConduiteMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistanceConduite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($assistanceConduiteDecla->answers[$i] == "Non" && $assistanceConduiteMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistanceConduite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($assistanceConduiteDecla->answers[$i] != $assistanceConduiteMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistanceConduite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $assistanceConduiteDecla->score * 100 / $assistanceConduiteDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $assistanceConduiteMa->score * 100 / $assistanceConduiteMa->total ?>%
                                    </td>
                                    <?php if ($assistanceConduiteDecla->answers[$i] == "Je connais" && $assistanceConduiteMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistanceConduite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($assistanceConduiteDecla->answers[$i] == "Je connais pas" && $assistanceConduiteMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistanceConduite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($assistanceConduiteDecla->answers[$i] != $assistanceConduiteMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistanceConduite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfAssistanceConduite">

                                    </td>
                                    <td class="text-center" id="result-rAssistanceConduite">

                                    </td>
                                    <td class="text-center" id="synth-AssistanceConduite">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $climatisationFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Climatisation'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $climatisationDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Climatisation'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $climatisationMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Climatisation']
                                        ]
                                    ]);
                                ?>
                                <?php if ($climatisationFac && $climatisationDecla && $climatisationMa) { ?>
                                <?php for ($i = 0; $i < count($climatisationFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $climatisationFac->speciality?>&level=<?php echo $climatisationFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Climatisation
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sClimatisation">
                                        <?php echo $climatisationFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $climatisationFac->score * 100 / $climatisationFac->total ?>%
                                    </td>
                                    <?php if ((($climatisationFac->score  * 100 ) / $climatisationFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facClimatisation">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($climatisationFac->score  * 100 ) / $climatisationFac->total) < 80)  { ?>
                                    <td class="text-center" id="facClimatisation">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $climatisationDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $climatisationDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($climatisationDecla->answers[$i] == "Oui" && $climatisationMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfClimatisation">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($climatisationDecla->answers[$i] == "Non" && $climatisationMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfClimatisation">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($climatisationDecla->answers[$i] != $climatisationMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfClimatisation">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $climatisationDecla->score * 100 / $climatisationDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $climatisationMa->score * 100 / $climatisationMa->total ?>%
                                    </td>
                                    <?php if ($climatisationDecla->answers[$i] == "Je connais" && $climatisationMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfClimatisation">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($climatisationDecla->answers[$i] == "Je connais pas" && $climatisationMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfClimatisation">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($climatisationDecla->answers[$i] != $climatisationMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfClimatisation">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfClimatisation">

                                    </td>
                                    <td class="text-center" id="result-rClimatisation">

                                    </td>
                                    <td class="text-center" id="synth-Climatisation">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $directionFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Direction'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $directionDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Direction'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $directionMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Direction']
                                        ]
                                    ]);
                                ?>
                                <?php if ($directionFac && $directionDecla && $directionMa) { ?>
                                <?php for ($i = 0; $i < count($directionFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $directionFac->speciality?>&level=<?php echo $directionFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Direction
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sDirection">
                                        <?php echo $directionFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $directionFac->score * 100 / $directionFac->total ?>%
                                    </td>
                                    <?php if ((($directionFac->score  * 100 ) / $directionFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facDirection">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($directionFac->score  * 100 ) / $directionFac->total) < 80)  { ?>
                                    <td class="text-center" id="facDirection">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $directionDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $directionDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($directionDecla->answers[$i] == "Oui" && $directionMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfDirection">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($directionDecla->answers[$i] == "Non" && $directionMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfDirection">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($directionDecla->answers[$i] != $directionMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfDirection">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $directionDecla->score * 100 / $directionDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $directionMa->score * 100 / $directionMa->total ?>%
                                    </td>
                                    <?php if ($directionDecla->answers[$i] == "Je connais" && $directionMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfDirection">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($directionDecla->answers[$i] == "Je connais pas" && $directionMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfDirection">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($directionDecla->answers[$i] != $directionMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfDirection">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfDirection">

                                    </td>
                                    <td class="text-center" id="result-rDirection">

                                    </td>
                                    <td class="text-center" id="synth-Direction">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $electriciteFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Electricite'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $electriciteDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Electricite'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $electriciteMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Electricite']
                                        ]
                                    ]);
                                ?>
                                <?php if ($electriciteFac && $electriciteDecla && $electriciteMa) { ?>
                                <?php for ($i = 0; $i < count($electriciteFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $electriciteFac->speciality?>&level=<?php echo $electriciteFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Electricité
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sElectricite">
                                        <?php echo $electriciteFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $electriciteFac->score * 100 / $electriciteFac->total ?>%
                                    </td>
                                    <?php if ((($electriciteFac->score  * 100 ) / $electriciteFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facElectricite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($electriciteFac->score  * 100 ) / $electriciteFac->total) < 80)  { ?>
                                    <td class="text-center" id="facElectricite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $electriciteDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $electriciteDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($electriciteDecla->answers[$i] == "Oui" && $electriciteMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfElectricite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($electriciteDecla->answers[$i] == "Non" && $electriciteMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfElectricite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($electriciteDecla->answers[$i] != $electriciteMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfElectricite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $electriciteDecla->score * 100 / $electriciteDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $electriciteMa->score * 100 / $electriciteMa->total ?>%
                                    </td>
                                    <?php if ($electriciteDecla->answers[$i] == "Je connais" && $electriciteMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfElectricite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($electriciteDecla->answers[$i] == "Je connais pas" && $electriciteMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfElectricite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($electriciteDecla->answers[$i] != $electriciteMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfElectricite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfElectricite">

                                    </td>
                                    <td class="text-center" id="result-rElectricite">

                                    </td>
                                    <td class="text-center" id="synth-Electricite">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $freinageFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $freinageDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $freinageMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage']
                                        ]
                                    ]);
                                ?>
                                <?php if ($freinageFac && $freinageDecla && $freinageMa) { ?>
                                <?php for ($i = 0; $i < count($freinageFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $freinageFac->speciality?>&level=<?php echo $freinageFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Freinage
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sFreinage">
                                        <?php echo $freinageFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $freinageFac->score * 100 / $freinageFac->total ?>%
                                    </td>
                                    <?php if ((($freinageFac->score  * 100 ) / $freinageFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facFreinage">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($freinageFac->score  * 100 ) / $freinageFac->total) < 80)  { ?>
                                    <td class="text-center" id="facFreinage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $freinageDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $freinageDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($freinageDecla->answers[$i] == "Oui" && $freinageMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFreinage">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($freinageDecla->answers[$i] == "Non" && $freinageMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFreinage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($freinageDecla->answers[$i] != $freinageMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFreinage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $freinageDecla->score * 100 / $freinageDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $freinageMa->score * 100 / $freinageMa->total ?>%
                                    </td>
                                    <?php if ($freinageDecla->answers[$i] == "Je connais" && $freinageMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFreinage">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($freinageDecla->answers[$i] == "Je connais pas" && $freinageMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFreinage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($freinageDecla->answers[$i] != $freinageMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFreinage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfFreinage">

                                    </td>
                                    <td class="text-center" id="result-rFreinage">

                                    </td>
                                    <td class="text-center" id="synth-Freinage">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $hydrauliqueFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Hydraulique'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $hydrauliqueDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Hydraulique'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $hydrauliqueMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Hydraulique']
                                        ]
                                    ]);
                                ?>
                                <?php if ($hydrauliqueFac && $hydrauliqueDecla && $hydrauliqueMa) { ?>
                                <?php for ($i = 0; $i < count($hydrauliqueFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $hydrauliqueFac->speciality?>&level=<?php echo $hydrauliqueFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Hydraulique
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sHydraulique">
                                        <?php echo $hydrauliqueFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $hydrauliqueFac->score * 100 / $hydrauliqueFac->total ?>%
                                    </td>
                                    <?php if ((($hydrauliqueFac->score  * 100 ) / $hydrauliqueFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facHydraulique">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($hydrauliqueFac->score  * 100 ) / $hydrauliqueFac->total) < 80)  { ?>
                                    <td class="text-center" id="facHydraulique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $hydrauliqueDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $hydrauliqueDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($hydrauliqueDecla->answers[$i] == "Oui" && $hydrauliqueMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfHydraulique">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($hydrauliqueDecla->answers[$i] == "Non" && $hydrauliqueMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfHydraulique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($hydrauliqueDecla->answers[$i] != $hydrauliqueMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfHydraulique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $hydrauliqueDecla->score * 100 / $hydrauliqueDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $hydrauliqueMa->score * 100 / $hydrauliqueMa->total ?>%
                                    </td>
                                    <?php if ($hydrauliqueDecla->answers[$i] == "Je connais" && $hydrauliqueMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfHydraulique">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($hydrauliqueDecla->answers[$i] == "Je connais pas" && $hydrauliqueMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfHydraulique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($hydrauliqueDecla->answers[$i] != $hydrauliqueMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfHydraulique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfHydraulique">

                                    </td>
                                    <td class="text-center" id="result-rHydraulique">

                                    </td>
                                    <td class="text-center" id="synth-Hydraulique">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $moteurFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $moteurDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $moteurMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur']
                                        ]
                                    ]);
                                ?>
                                <?php if ($moteurFac && $moteurDecla && $moteurMa) { ?>
                                <?php for ($i = 0; $i < count($moteurFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $moteurFac->speciality?>&level=<?php echo $moteurFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Moteur
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sMoteur">
                                        <?php echo $moteurFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurFac->score * 100 / $moteurFac->total ?>%
                                    </td>
                                    <?php if ((($moteurFac->score  * 100 ) / $moteurFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facMoteur">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($moteurFac->score  * 100 ) / $moteurFac->total) < 80)  { ?>
                                    <td class="text-center" id="facMoteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $moteurDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $moteurDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($moteurDecla->answers[$i] == "Oui" && $moteurMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteur">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurDecla->answers[$i] == "Non" && $moteurMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurDecla->answers[$i] != $moteurMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $moteurDecla->score * 100 / $moteurDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurMa->score * 100 / $moteurMa->total ?>%
                                    </td>
                                    <?php if ($moteurDecla->answers[$i] == "Je connais" && $moteurMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteur">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurDecla->answers[$i] == "Je connais pas" && $moteurMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurDecla->answers[$i] != $moteurMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfMoteur">

                                    </td>
                                    <td class="text-center" id="result-rMoteur">

                                    </td>
                                    <td class="text-center" id="synth-Moteur">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $multiplexageFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Multiplexage & Electronique'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $multiplexageDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Multiplexage & Electronique'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $multiplexageMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Multiplexage & Electronique']
                                        ]
                                    ]);
                                ?>
                                <?php if ($multiplexageFac && $multiplexageDecla && $multiplexageMa) { ?>
                                <?php for ($i = 0; $i < count($multiplexageFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $multiplexageFac->speciality?>&level=<?php echo $multiplexageFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Multiplexage & Electronique
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sMultiplexage">
                                        <?php echo $multiplexageFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $multiplexageFac->score * 100 / $multiplexageFac->total ?>%
                                    </td>
                                    <?php if ((($multiplexageFac->score  * 100 ) / $multiplexageFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facMultiplexage">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($multiplexageFac->score  * 100 ) / $multiplexageFac->total) < 80)  { ?>
                                    <td class="text-center" id="facMultiplexage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $multiplexageDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $multiplexageDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($multiplexageDecla->answers[$i] == "Oui" && $multiplexageMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMultiplexage">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($multiplexageDecla->answers[$i] == "Non" && $multiplexageMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMultiplexage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($multiplexageDecla->answers[$i] != $multiplexageMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMultiplexage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $multiplexageDecla->score * 100 / $multiplexageDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $multiplexageMa->score * 100 / $multiplexageMa->total ?>%
                                    </td>
                                    <?php if ($multiplexageDecla->answers[$i] == "Je connais" && $multiplexageMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMultiplexage">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($multiplexageDecla->answers[$i] == "Je connais pas" && $multiplexageMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMultiplexage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($multiplexageDecla->answers[$i] != $multiplexageMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMultiplexage">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfMultiplexage">

                                    </td>
                                    <td class="text-center" id="result-rMultiplexage">

                                    </td>
                                    <td class="text-center" id="synth-Multiplexage">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $pneuFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Pneumatique'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $pneuDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Pneumatique'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $pneuMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Pneumatique']
                                        ]
                                    ]);
                                ?>
                                <?php if ($pneuFac && $pneuDecla && $pneuMa) { ?>
                                <?php for ($i = 0; $i < count($pneuFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $pneuFac->speciality?>&level=<?php echo $pneuFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Pneumatique
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sPneu">
                                        <?php echo $pneuFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $pneuFac->score * 100 / $pneuFac->total ?>%
                                    </td>
                                    <?php if ((($pneuFac->score  * 100 ) / $pneuFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facPneu">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($pneuFac->score  * 100 ) / $pneuFac->total) < 80)  { ?>
                                    <td class="text-center" id="facPneu">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $pneuDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $pneuDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($pneuDecla->answers[$i] == "Oui" && $pneuMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPneu">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($pneuDecla->answers[$i] == "Non" && $pneuMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPneu">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($pneuDecla->answers[$i] != $pneuMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPneu">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $pneuDecla->score * 100 / $pneuDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $pneuMa->score * 100 / $pneuMa->total ?>%
                                    </td>
                                    <?php if ($pneuDecla->answers[$i] == "Je connais" && $pneuMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPneu">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($pneuDecla->answers[$i] == "Je connais pas" && $pneuMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPneu">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($pneuDecla->answers[$i] != $pneuMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPneu">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfPneu">

                                    </td>
                                    <td class="text-center" id="result-rPneu">

                                    </td>
                                    <td class="text-center" id="synth-Pneu">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $suspensionFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $suspensionDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $suspensionMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension']
                                        ]
                                    ]);
                                ?>
                                <?php if ($suspensionFac && $suspensionDecla && $suspensionMa) { ?>
                                <?php for ($i = 0; $i < count($suspensionFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $suspensionFac->speciality?>&level=<?php echo $suspensionFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Suspension
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sSuspension">
                                        <?php echo $suspensionFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionFac->score * 100 / $suspensionFac->total ?>%
                                    </td>
                                    <?php if ((($suspensionFac->score  * 100 ) / $suspensionFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facSuspension">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($suspensionFac->score  * 100 ) / $suspensionFac->total) < 80)  { ?>
                                    <td class="text-center" id="facSuspension">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $suspensionDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $suspensionDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($suspensionDecla->answers[$i] == "Oui" && $suspensionMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspension">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionDecla->answers[$i] == "Non" && $suspensionMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspension">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionDecla->answers[$i] != $suspensionMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspension">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $suspensionDecla->score * 100 / $suspensionDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionMa->score * 100 / $suspensionMa->total ?>%
                                    </td>
                                    <?php if ($suspensionDecla->answers[$i] == "Je connais" && $suspensionMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspension">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionDecla->answers[$i] == "Je connais pas" && $suspensionMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspension">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionDecla->answers[$i] != $suspensionMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspension">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfSuspension">

                                    </td>
                                    <td class="text-center" id="result-rSuspension">

                                    </td>
                                    <td class="text-center" id="synth-Suspension">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $transmissionFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Transmission'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $transmissionDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Transmission'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $transmissionMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Transmission']
                                        ]
                                    ]);
                                ?>
                                <?php if ($transmissionFac && $transmissionDecla && $transmissionMa) { ?>
                                <?php for ($i = 0; $i < count($transmissionFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $transmissionFac->speciality?>&level=<?php echo $transmissionFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Transmission
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sTransmission">
                                        <?php echo $transmissionFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transmissionFac->score * 100 / $transmissionFac->total ?>%
                                    </td>
                                    <?php if ((($transmissionFac->score  * 100 ) / $transmissionFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facTransmission">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($transmissionFac->score  * 100 ) / $transmissionFac->total) < 80)  { ?>
                                    <td class="text-center" id="facTransmission">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $transmissionDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $transmissionDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($transmissionDecla->answers[$i] == "Oui" && $transmissionMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransmission">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transmissionDecla->answers[$i] == "Non" && $transmissionMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransmission">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transmissionDecla->answers[$i] != $transmissionMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransmission">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $transmissionDecla->score * 100 / $transmissionDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transmissionMa->score * 100 / $transmissionMa->total ?>%
                                    </td>
                                    <?php if ($transmissionDecla->answers[$i] == "Je connais" && $transmissionMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransmission">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transmissionDecla->answers[$i] == "Je connais pas" && $transmissionMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransmission">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transmissionDecla->answers[$i] != $transmissionMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransmission">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfTransmission">

                                    </td>
                                    <td class="text-center" id="result-rTransmission">

                                    </td>
                                    <td class="text-center" id="synth-Transmission">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $transversaleFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Transversale'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $transversaleDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Transversale'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $transversaleMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Transversale']
                                        ]
                                    ]);
                                ?>
                                <?php if ($transversaleFac && $transversaleDecla && $transversaleMa) { ?>
                                <?php for ($i = 0; $i < count($transversaleFac->questions); $i++) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $transversaleFac->speciality?>&level=<?php echo $transversaleFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Transversale
                                        </a>
                                    </td>
                                    <td class="text-center hidden" name="savoir" id="sTransversale">
                                        <?php echo $transversaleFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transversaleFac->score * 100 / $transversaleFac->total ?>%
                                    </td>
                                    <?php if ((($transversaleFac->score  * 100 ) / $transversaleFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facTransversale">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($transversaleFac->score  * 100 ) / $transversaleFac->total) < 80)  { ?>
                                    <td class="text-center" id="facTransversale">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center hidden" name="n">
                                        <?php echo $transversaleDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center hidden" name="n1">
                                        <?php echo $transversaleDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($transversaleDecla->answers[$i] == "Oui" && $transversaleMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransversale">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transversaleDecla->answers[$i] == "Non" && $transversaleMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransversale">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transversaleDecla->answers[$i] != $transversaleMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransversale">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $transversaleDecla->score * 100 / $transversaleDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transversaleMa->score * 100 / $transversaleMa->total ?>%
                                    </td>
                                    <?php if ($transversaleDecla->answers[$i] == "Je connais" && $transversaleMa->answers[$i] == "Je connais") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransversale">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transversaleDecla->answers[$i] == "Je connais pas" && $transversaleMa->answers[$i] == "Je connais pas") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransversale">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transversaleDecla->answers[$i] != $transversaleMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransversale">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfTransversale">

                                    </td>
                                    <td class="text-center" id="result-rTransversale">

                                    </td>
                                    <td class="text-center" id="synth-Transversale">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <!--end::Menu-->
                                <tr>
                                    <th id=""
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        Résultats</th>
                                    <th id="result-savoir"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    </th>
                                    <th id="decision-savoir"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    </th>
                                    <th id="result-n"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    </th>
                                    <th id="result-n1"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    </th>
                                    <th id="result-savoir-faire"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" colspan="1" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    </th>
                                    <th id="decision-savoirs-faire"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    </th>
                                    <th id="synthese"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        colspan="1" tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
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
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js">
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
const sfTransverse = []
const sfTransmission = []
const sfAssistance = []
const sfClimatisation = []
const sfDirection = []
const sfElectricite = []
const sfFreinage = []
const sfMoteur = []
const sfHydraulique = []
const sfPneu = []
const sfmultiplexage = []
const sfSuspension = []
const valueMaitrisé = "Maitrisé"
const valueOui = "Oui"
const tdSavoir = document.querySelectorAll("td[name='savoir']")
const tdSavoirFaire = document.querySelectorAll("td[name='savoirs-faire']")
const tdN = document.querySelectorAll("td[name='n']")
const tdN1 = document.querySelectorAll("td[name='n1']")
const tdCoh = document.querySelectorAll("td[name='coh']")
const tdValid = document.querySelectorAll("td[name='valid']")
const tdsfTransverse = document.querySelectorAll("#sfTransverse")
const tdsfTransmission = document.querySelectorAll("#sfTransmission")
const tdsfAssistance = document.querySelectorAll("#sfAssistance")
const tdsfClimatisation = document.querySelectorAll("#sfClimatisation")
const tdsfDirection = document.querySelectorAll("#sfDirection")
const tdsfElectricite = document.querySelectorAll("#sfElectricite")
const tdsfFreinage = document.querySelectorAll("#sfFreinage")
const tdsfHydraulique = document.querySelectorAll("#sfHydraulique")
const tdsfMoteur = document.querySelectorAll("#sfMoteur")
const tdsfPneu = document.querySelectorAll("#sfPneu")
const tdsfmultiplexage = document.querySelectorAll("#sfMultiplexage")
const tdsfSuspension = document.querySelectorAll("#sfSuspension")
const resultSavoir = document.querySelector("#result-savoir")
const resultSavoirFaire = document.querySelector("#result-savoir-faire")
const decisionSavoir = document.querySelector("#decision-savoir")
const decisionSavoirFaire = document.querySelector("#decision-savoirs-faire")
const synthese = document.querySelector("#synthese")
const resultN = document.querySelector("#result-n")
const resultN1 = document.querySelector("#result-n1")
const resultCoh = document.querySelector("#result-coh")
const resultValid = document.querySelector("#result-valid")
const resultsfTransverse = document.querySelector("#result-sfTransverse")
const synthTransversale = document.querySelector("#synth-Transversale")
const resultrTransversale = document.querySelector("#result-rTransversale")
const facTransversale = document.querySelector("#facTransversale")
const resultsfTransmission = document.querySelector("#result-sfTransmission")
const synthTransmission = document.querySelector("#synth-Transmission")
const resultrTransmission = document.querySelector("#result-rTransmission")
const facTransmission = document.querySelector("#facTransmission")
const resultsfAssistance = document.querySelector("#result-sfAssistance")
const synthAssistance = document.querySelector("#synth-Assistance")
const resultrAssistance = document.querySelector("#result-rAssistance")
const facAssistance = document.querySelector("#facAssistance")
const resultsfClimatisation = document.querySelector("#result-sfClimatisation")
const synthClimatisation = document.querySelector("#synth-Climatisation")
const resultrClimatisation = document.querySelector("#result-rClimatisation")
const facClimatisation = document.querySelector("#facClimatisation")
const resultsfDirection = document.querySelector("#result-sfDirection")
const synthDirection = document.querySelector("#synth-Direction")
const resultrDirection = document.querySelector("#result-rDirection")
const facDirection = document.querySelector("#facDirection")
const resultsfElectricite = document.querySelector("#result-sfElectricite")
const synthElectricite = document.querySelector("#synth-Electricite")
const resultrElectricite = document.querySelector("#result-rElectricite")
const facElectricite = document.querySelector("#facElectricite")
const resultsfFreinage = document.querySelector("#result-sfFreinage")
const synthFreinage = document.querySelector("#synth-Freinage")
const resultrFreinage = document.querySelector("#result-rFreinage")
const facFreinage = document.querySelector("#facFreinage")
const resultsfHydraulique = document.querySelector("#result-sfHydraulique")
const synthHydraulique = document.querySelector("#synth-Hydraulique")
const resultrHydraulique = document.querySelector("#result-rHydraulique")
const facHydraulique = document.querySelector("#facHydraulique")
const resultsfMoteur = document.querySelector("#result-sfMoteur")
const synthMoteur = document.querySelector("#synth-Moteur")
const resultrMoteur = document.querySelector("#result-rMoteur")
const facMoteur = document.querySelector("#facMoteur")
const resultsfMultiplexage = document.querySelector("#result-sfmultiplexage")
const synthMultiplexage = document.querySelector("#synth-Multiplexage")
const resultrMultiplexage = document.querySelector("#result-rMultiplexage")
const facMultiplexage = document.querySelector("#facMultiplexage")
const resultsfPneu = document.querySelector("#result-sfPneu")
const synthPneu = document.querySelector("#synth-Pneu")
const resultrPneu = document.querySelector("#result-rPneu")
const facPneu = document.querySelector("#facPneu")
const resultsfSuspension = document.querySelector("#result-sfSuspension")
const synthSuspension = document.querySelector("#synth-Suspension")
const resultrSuspension = document.querySelector("#result-rSuspension")
const facSuspension = document.querySelector("#facSuspension")

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
for (let i = 0; i < tdsfTransverse.length; i++) {
    sfTransverse.push(tdsfTransverse[i].innerHTML)
}
for (let i = 0; i < tdsfTransmission.length; i++) {
    sfTransmission.push(tdsfTransmission[i].innerHTML)
}
for (let i = 0; i < tdsfAssistance.length; i++) {
    sfAssistance.push(tdsfAssistance[i].innerHTML)
}
for (let i = 0; i < tdsfClimatisation.length; i++) {
    sfClimatisation.push(tdsfClimatisation[i].innerHTML)
}
for (let i = 0; i < tdsfDirection.length; i++) {
    sfDirection.push(tdsfDirection[i].innerHTML)
}
for (let i = 0; i < tdsfElectricite.length; i++) {
    sfElectricite.push(tdsfElectricite[i].innerHTML)
}
for (let i = 0; i < tdsfFreinage.length; i++) {
    sfFreinage.push(tdsfFreinage[i].innerHTML)
}
for (let i = 0; i < tdsfHydraulique.length; i++) {
    sfHydraulique.push(tdsfHydraulique[i].innerHTML)
}
for (let i = 0; i < tdsfMoteur.length; i++) {
    sfMoteur.push(tdsfMoteur[i].innerHTML)
}
for (let i = 0; i < tdsfmultiplexage.length; i++) {
    sfmultiplexage.push(tdsfmultiplexage[i].innerHTML)
}
for (let i = 0; i < tdsfPneu.length; i++) {
    sfPneu.push(tdsfPneu[i].innerHTML)
}
for (let i = 0; i < tdsfSuspension.length; i++) {
    sfSuspension.push(tdsfSuspension[i].innerHTML)
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
const maitrisesfTransverse = sfTransverse.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfTransmission = sfTransmission.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfAssistance = sfAssistance.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfClimatisation = sfClimatisation.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfDirection = sfDirection.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfElectricite = sfElectricite.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfFreinage = sfFreinage.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfHydraulique = sfHydraulique.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfMoteur = sfMoteur.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfmultiplexage = sfmultiplexage.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfPneu = sfPneu.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfSuspension = sfSuspension.filter(function(str) {
    return str.includes(valueMaitrisé)
})

const percentSavoir = ((maitriseSavoir.length * 100) / tdSavoir.length).toFixed(0)
const percentSavoirFaire = ((maitriseSavoirFaire.length * 100) / tdSavoirFaire.length).toFixed(0)
const percentN = ((ouiN.length * 100) / tdN.length).toFixed(0)
const percentN1 = ((ouiN1.length * 100) / tdN1.length).toFixed(0)
const percentCoh = ((ouiCoh.length * 100) / tdCoh.length).toFixed(0)
const percentValid = ((maitriseValid.length * 100) / tdValid.length).toFixed(0)
const percentsfTransverse = ((maitrisesfTransverse.length * 100) / tdsfTransverse.length).toFixed(0)
const percentsfTransmission = ((maitrisesfTransmission.length * 100) / tdsfTransmission.length).toFixed(0)
const percentsfAssistance = ((maitrisesfAssistance.length * 100) / tdsfAssistance.length).toFixed(0)
const percentsfClimatisation = ((maitrisesfClimatisation.length * 100) / tdsfClimatisation.length).toFixed(0)
const percentsfDirection = ((maitrisesfDirection.length * 100) / tdsfDirection.length).toFixed(0)
const percentsfElectricite = ((maitrisesfElectricite.length * 100) / tdsfElectricite.length).toFixed(0)
const percentsfFreinage = ((maitrisesfFreinage.length * 100) / tdsfFreinage.length).toFixed(0)
const percentsfHydraulique = ((maitrisesfHydraulique.length * 100) / tdsfHydraulique.length).toFixed(0)
const percentsfMoteur = ((maitrisesfMoteur.length * 100) / tdsfMoteur.length).toFixed(0)
const percentsfPneu = ((maitrisesfPneu.length * 100) / tdsfPneu.length).toFixed(0)
const percentsfmultiplexage = ((maitrisesfmultiplexage.length * 100) / tdsfmultiplexage.length).toFixed(0)
const percentsfSuspension = ((maitrisesfSuspension.length * 100) / tdsfSuspension.length).toFixed(0)

resultSavoir.innerHTML = percentSavoir + "%";
resultSavoirFaire.innerHTML = percentSavoirFaire + "%";
resultN.innerHTML = percentN + "%";
resultN1.innerHTML = percentN1 + "%";
// resultCoh.innerHTML = percentCoh + "%";
// resultValid.innerHTML = percentValid + "%";
if (resultsfTransverse) {
    resultsfTransverse.innerHTML = percentsfTransverse + "%";
}
if (resultsfTransmission) {
    resultsfTransmission.innerHTML = percentsfTransmission + "%";
}
if (resultsfAssistance) {
    resultsfAssistance.innerHTML = percentsfAssistance + "%";
}
if (resultsfClimatisation) {
    resultsfClimatisation.innerHTML = percentsfClimatisation + "%";
}
if (resultsfDirection) {
    resultsfDirection.innerHTML = percentsfDirection + "%";
}
if (resultsfElectricite) {
    resultsfElectricite.innerHTML = percentsfElectricite + "%";
}
if (resultsfFreinage) {
    resultsfFreinage.innerHTML = percentsfFreinage + "%";
}
if (resultsfHydraulique) {
    resultsfHydraulique.innerHTML = percentsfHydraulique + "%";
}
if (resultsfMoteur) {
    resultsfMoteur.innerHTML = percentsfMoteur + "%";
}
if (resultsfPneu) {
    resultsfPneu.innerHTML = percentsfPneu + "%";
}
if (resultsfMultiplexage) {
    resultsfMultiplexage.innerHTML = percentsfmultiplexage + "%";
}
if (resultsfSuspension) {
    resultsfSuspension.innerHTML = percentsfSuspension + "%";
}
const a = "80%";

if (resultsfTransverse && parseFloat(resultsfTransverse.innerHTML) >= parseFloat(a)) {
    resultrTransversale.innerHTML = "Maitrisé"
}
if (resultsfTransverse && parseFloat(resultsfTransverse.innerHTML) < parseFloat(a)) {
    resultrTransmission.innerHTML = "Non maitrisé"
}
if (resultsfTransmission && parseFloat(resultsfTransmission.innerHTML) >= parseFloat(a)) {
    resultrTransmission.innerHTML = "Maitrisé"
}
if (resultsfTransmission && parseFloat(resultsfTransmission.innerHTML) < parseFloat(a)) {
    resultrTransmission.innerHTML = "Non maitrisé"
}
if (resultsfAssistance && parseFloat(resultsfAssistance.innerHTML) >= parseFloat(a)) {
    resultrAssistance.innerHTML = "Maitrisé"
}
if (resultsfAssistance && parseFloat(resultsfAssistance.innerHTML) < parseFloat(a)) {
    resultrAssistance.innerHTML = "Non maitrisé"
}
if (resultsfClimatisation && parseFloat(resultsfClimatisation.innerHTML) >= parseFloat(a)) {
    resultrClimatisation.innerHTML = "Maitrisé"
}
if (resultsfClimatisation && parseFloat(resultsfClimatisation.innerHTML) < parseFloat(a)) {
    resultrClimatisation.innerHTML = "Non maitrisé"
}
if (resultsfDirection && parseFloat(resultsfDirection.innerHTML) >= parseFloat(a)) {
    resultrDirection.innerHTML = "Maitrisé"
}
if (resultsfDirection && parseFloat(resultsfDirection.innerHTML) < parseFloat(a)) {
    resultrDirection.innerHTML = "Non maitrisé"
}
if (resultsfElectricite && parseFloat(resultsfElectricite.innerHTML) >= parseFloat(a)) {
    resultrElectricite.innerHTML = "Maitrisé"
}
if (resultsfElectricite && parseFloat(resultsfElectricite.innerHTML) < parseFloat(a)) {
    resultrElectricite.innerHTML = "Non maitrisé"
}
if (resultsfFreinage && parseFloat(resultsfFreinage.innerHTML) >= parseFloat(a)) {
    resultrFreinage.innerHTML = "Maitrisé"
}
if (resultsfFreinage && parseFloat(resultsfFreinage.innerHTML) < parseFloat(a)) {
    resultrFreinage.innerHTML = "Non maitrisé"
}
if (resultsfHydraulique && parseFloat(resultsfHydraulique.innerHTML) >= parseFloat(a)) {
    resultrHydraulique.innerHTML = "Maitrisé"
}
if (resultsfHydraulique && parseFloat(resultsfHydraulique.innerHTML) < parseFloat(a)) {
    resultrHydraulique.innerHTML = "Non maitrisé"
}
if (resultsfMoteur && parseFloat(resultsfMoteur.innerHTML) >= parseFloat(a)) {
    resultrMoteur.innerHTML = "Maitrisé"
}
if (resultsfMoteur && parseFloat(resultsfMoteur.innerHTML) < parseFloat(a)) {
    resultrMoteur.innerHTML = "Non maitrisé"
}
if (resultsfMultiplexage && parseFloat(resultsfMultiplexage.innerHTML) >= parseFloat(a)) {
    resultrMultiplexage.innerHTML = "Maitrisé"
}
if (resultsfMultiplexage && parseFloat(resultsfMultiplexage.innerHTML) < parseFloat(a)) {
    resultrMultiplexage.innerHTML = "Non maitrisé"
}
if (resultsfSuspension && parseFloat(resultsfSuspension.innerHTML) >= parseFloat(a)) {
    resultrSuspension.innerHTML = "Maitrisé"
}
if (resultsfSuspension && parseFloat(resultsfSuspension.innerHTML) < parseFloat(a)) {
    resultrSuspension.innerHTML = "Non maitrisé"
}
if (resultsfPneu && parseFloat(resultsfPneu.innerHTML) >= parseFloat(a)) {
    resultrPneu.innerHTML = "Maitrisé"
}
if (resultsfPneu && parseFloat(resultsfPneu.innerHTML) < parseFloat(a)) {
    resultrPneu.innerHTML = "Non maitrisé"
}
if (parseFloat(resultSavoir.innerHTML) >= parseFloat(a)) {
    decisionSavoir.innerHTML = "Maitrisé"
}
if (parseFloat(resultSavoir.innerHTML) < parseFloat(a)) {
    decisionSavoir.innerHTML = "Non maitrisé"
}
if (parseFloat(resultSavoirFaire.innerHTML) >= parseFloat(a)) {
    decisionSavoirFaire.innerHTML = "Maitrisé"
}
if (parseFloat(resultSavoirFaire.innerHTML) < parseFloat(a)) {
    decisionSavoirFaire.innerHTML = "Non maitrisé"
}

if (facTransversale && facTransversale.innerHTML == "Maitrisé" && (resultrTransversale.innerHTML == "Maitrisé")) {
    synthTransversale.innerHTML = "Maitrisé"
}
if (facTransversale && facTransversale.innerHTML == "Non maitrisé" && (resultrTransversale.innerHTML ==
        "Non maitrisé")) {
    synthTransversale.innerHTML = "Non maitrisé"
}
if (facTransversale && facTransversale.innerHTML !== resultrTransversale.innerHTML) {
    synthTransversale.innerHTML = "Non maitrisé"
}
if (facTransmission && facTransmission.innerHTML == "Maitrisé" && (resultrTransmission.innerHTML == "Maitrisé")) {
    synthTransmission.innerHTML = "Maitrisé"
}
if (facTransmission && facTransmission.innerHTML == "Non maitrisé" && (resultrTransmission.innerHTML ==
        "Non maitrisé")) {
    synthTransmission.innerHTML = "Non maitrisé"
}
if (facTransmission && facTransmission.innerHTML !== resultrTransmission.innerHTML) {
    synthTransmission.innerHTML = "Non maitrisé"
}
if (facAssistance && facAssistance.innerHTML == "Maitrisé" && (resultrAssistance.innerHTML == "Maitrisé")) {
    synthAssistance.innerHTML = "Maitrisé"
}
if (facAssistance && facAssistance.innerHTML == "Non maitrisé" && (resultrAssistance.innerHTML == "Non maitrisé")) {
    synthAssistance.innerHTML = "Non maitrisé"
}
if (facAssistance && facAssistance.innerHTML !== resultrAssistance.innerHTML) {
    synthAssistance.innerHTML = "Non maitrisé"
}
if (facClimatisation && facClimatisation.innerHTML == "Maitrisé" && (resultrClimatisation.innerHTML == "Maitrisé")) {
    synthClimatisation.innerHTML = "Maitrisé"
}
if (facClimatisation && facClimatisation.innerHTML == "Non maitrisé" && (resultrClimatisation.innerHTML ==
        "Non maitrisé")) {
    synthClimatisation.innerHTML = "Non maitrisé"
}
if (facClimatisation && facClimatisation.innerHTML !== resultrClimatisation.innerHTML) {
    synthClimatisation.innerHTML = "Non maitrisé"
}
if (facDirection && facDirection.innerHTML == "Maitrisé" && (resultrDirection.innerHTML == "Maitrisé")) {
    synthDirection.innerHTML = "Maitrisé"
}
if (facDirection && facDirection.innerHTML == "Non maitrisé" && (resultrDirection.innerHTML == "Non maitrisé")) {
    synthDirection.innerHTML = "Non maitrisé"
}
if (facDirection && facDirection.innerHTML !== resultrDirection.innerHTML) {
    synthDirection.innerHTML = "Non maitrisé"
}
if (facElectricite && facElectricite.innerHTML == "Maitrisé" && (resultrElectricite.innerHTML == "Maitrisé")) {
    synthElectricite.innerHTML = "Maitrisé"
}
if (facElectricite && facElectricite.innerHTML == "Non maitrisé" && (resultrElectricite.innerHTML == "Non maitrisé")) {
    synthElectricite.innerHTML = "Non maitrisé"
}
if (facElectricite && facElectricite.innerHTML !== resultrElectricite.innerHTML) {
    synthElectricite.innerHTML = "Non maitrisé"
}
if (facFreinage && facFreinage.innerHTML == "Maitrisé" && (resultrFreinage.innerHTML == "Maitrisé")) {
    synthFreinage.innerHTML = "Maitrisé"
}
if (facFreinage && facFreinage.innerHTML == "Non maitrisé" && (resultrFreinage.innerHTML == "Non maitrisé")) {
    synthFreinage.innerHTML = "Non maitrisé"
}
if (facFreinage && facFreinage.innerHTML !== resultrFreinage.innerHTML) {
    synthFreinage.innerHTML = "Non maitrisé"
}
if (facHydraulique && facHydraulique.innerHTML == "Maitrisé" && (resultrHydraulique.innerHTML == "Maitrisé")) {
    synthHydraulique.innerHTML = "Maitrisé"
}
if (facHydraulique && facHydraulique.innerHTML == "Non maitrisé" && (resultrHydraulique.innerHTML == "Non maitrisé")) {
    synthHydraulique.innerHTML = "Non maitrisé"
}
if (facHydraulique && facHydraulique.innerHTML !== resultrHydraulique.innerHTML) {
    synthHydraulique.innerHTML = "Non maitrisé"
}
if (facMoteur && facMoteur.innerHTML == "Maitrisé" && (resultrMoteur.innerHTML == "Maitrisé")) {
    synthMoteur.innerHTML = "Maitrisé"
}
if (facMoteur && facMoteur.innerHTML == "Non maitrisé" && (resultrMoteur.innerHTML == "Non maitrisé")) {
    synthMoteur.innerHTML = "Non maitrisé"
}
if (facMoteur && facMoteur.innerHTML !== resultrMoteur.innerHTML) {
    synthMoteur.innerHTML = "Non maitrisé"
}
if (facMultiplexage && facMultiplexage.innerHTML == "Maitrisé" && (resultrMultiplexage.innerHTML == "Maitrisé")) {
    synthMultiplexage.innerHTML = "Maitrisé"
}
if (facMultiplexage && facMultiplexage.innerHTML == "Non maitrisé" && (resultrMultiplexage.innerHTML ==
        "Non maitrisé")) {
    synthMultiplexage.innerHTML = "Non maitrisé"
}
if (facMultiplexage && facMultiplexage.innerHTML !== resultrMultiplexage.innerHTML) {
    synthMultiplexage.innerHTML = "Non maitrisé"
}
if (facSuspension && facSuspension.innerHTML == "Maitrisé" && (resultrSuspension.innerHTML == "Maitrisé")) {
    synthSuspension.innerHTML = "Maitrisé"
}
if (facSuspension && facSuspension.innerHTML == "Non maitrisé" && (resultrSuspension.innerHTML == "Non maitrisé")) {
    synthSuspension.innerHTML = "Non maitrisé"
}
if (facSuspension && facSuspension.innerHTML !== resultrSuspension.innerHTML) {
    synthSuspension.innerHTML = "Non maitrisé"
}
if (facPneu && facPneu.innerHTML == "Maitrisé" && (resultrPneu.innerHTML == "Maitrisé")) {
    synthPneu.innerHTML = "Maitrisé"
}
if (facPneu && facPneu.innerHTML == "Non maitrisé" && (resultrPneu.innerHTML ==
        "Non maitrisé")) {
    synthPneu.innerHTML = "Non maitrisé"
}
if (facPneu && facPneu.innerHTML !== resultrPneu.innerHTML) {
    synthPneu.innerHTML = "Non maitrisé"
}
if (decisionSavoir.innerHTML == "Maitrisé" && (decisionSavoirFaire.innerHTML == "Maitrisé")) {
    synthese.innerHTML = "Maitrisé"
}
if (decisionSavoir.innerHTML == "Non maitrisé" && (decisionSavoirFaire.innerHTML == "Non maitrisé")) {
    synthese.innerHTML = "Non maitrisé"
}
if (decisionSavoir.innerHTML !== decisionSavoirFaire.innerHTML) {
    synthese.innerHTML = "Non maitrisé"
}
</script>
<?php } ?>