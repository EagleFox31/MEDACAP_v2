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
    $resultFac = $results->aggregate([
        [
            '$match' => [
                '$and' => [
                    [
                        'user' => new MongoDB\BSON\ObjectId( $user ),
                        'level' => $level,
                        'type' => 'Factuel',
                    ],
                ],
            ],
        ],
        [
            '$group' => [
                '_id' => '$user',
                'total' => ['$sum' => '$total'],
                'score' => ['$sum' => '$score'],
            ],
        ],
        [
            '$project' => [
                '_id' => 0,
                'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
            ],
        ],
    ]);
    $resultDecla = $results->aggregate([
        [
            '$match' => [
                '$and' => [
                    [
                        'user' => new MongoDB\BSON\ObjectId( $user ),
                        'level' => $level,
                        'typeR' => 'Technicien',
                    ],
                ],
            ],
        ],
        [
            '$group' => [
                '_id' => '$user',
                'total' => ['$sum' => '$total'],
                'score' => ['$sum' => '$score'],
            ],
        ],
        [
            '$project' => [
                '_id' => 0,
                'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
            ],
        ],
    ]);
    $resultMa = $results->aggregate([
        [
            '$match' => [
                '$and' => [
                    [
                        'user' => new MongoDB\BSON\ObjectId( $user ),
                        'manager' => new MongoDB\BSON\ObjectId( $technician->manager ),
                        'level' => $level,
                        'typeR' => 'Manager',
                    ],
                ],
            ],
        ],
        [
            '$group' => [
                '_id' => '$user',
                'total' => ['$sum' => '$total'],
                'score' => ['$sum' => '$score'],
            ],
        ],
        [
            '$project' => [
                '_id' => 0,
                'percentage' => ['$multiply' => [['$divide' => ['$score', '$total']], 100]],
            ],
        ],
    ]);
    $arrResultFac = iterator_to_array($resultFac);
    $arrResultDecla = iterator_to_array($resultDecla);
    $arrResultMa = iterator_to_array($resultMa);
?>
<title>Résultat Technicien | CFAO Mobility Academy</title>
<!--end::Title-->
<!-- Favicon -->
<link href="../public/images/logo-cfao.png" rel="icon">

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
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Chargement...</span>
        </div>
    </div>
    <!-- Spinner End -->
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
                                    $transmissionFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Arbre de Transmission'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $transmissionDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Arbre de Transmission'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $transmissionMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Arbre de Transmission']
                                        ]
                                    ]);
                                ?>
                                <?php if ($transmissionFac && $transmissionDecla && $transmissionMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $transmissionFac->speciality?>&level=<?php echo $transmissionFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Arbre de Transmission
                                        </a>
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
                                    <td class="text-center">
                                        <?php echo $transmissionDecla->score * 100 / $transmissionDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transmissionMa->score * 100 / $transmissionMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($transmissionDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfTransmission">

                                    </td>
                                    <td class="text-center" id="result-rTransmission">

                                    </td>
                                    <td class="text-center" id="synth-Transmission">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $assistanceConduiteFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Assistance à la Conduite'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $assistanceConduiteDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Assistance à la Conduite'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $assistanceConduiteMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Assistance à la Conduite']
                                        ]
                                    ]);
                                ?>
                                <?php if ($assistanceConduiteFac && $assistanceConduiteDecla && $assistanceConduiteMa) { ?>
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
                                    <td class="text-center">
                                        <?php echo $assistanceConduiteFac->score * 100 / $assistanceConduiteFac->total ?>%
                                    </td>
                                    <?php if ((($assistanceConduiteFac->score  * 100 ) / $assistanceConduiteFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facAssistance">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($assistanceConduiteFac->score  * 100 ) / $assistanceConduiteFac->total) < 80)  { ?>
                                    <td class="text-center" id="facAssistance">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $assistanceConduiteDecla->score * 100 / $assistanceConduiteDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $assistanceConduiteMa->score * 100 / $assistanceConduiteMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($assistanceConduiteDecla->questions); $i++) { ?>
                                    <?php if ($assistanceConduiteDecla->answers[$i] == "Oui" && $assistanceConduiteMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistance">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($assistanceConduiteDecla->answers[$i] == "Non" && $assistanceConduiteMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistance">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($assistanceConduiteDecla->answers[$i] != $assistanceConduiteMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfAssistance">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfAssistance">

                                    </td>
                                    <td class="text-center" id="result-rAssistance">

                                    </td>
                                    <td class="text-center" id="synth-Assistance">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $transfertFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Boite de Transfert'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $transfertDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Boite de Transfert'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $transfertMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Boite de Transfert']
                                        ]
                                    ]);
                                ?>
                                <?php if ($transfertFac && $transfertDecla && $transfertMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $transfertFac->speciality?>&level=<?php echo $transfertFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            boite
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transfertFac->score * 100 / $transfertFac->total ?>%
                                    </td>
                                    <?php if ((($transfertFac->score  * 100 ) / $transfertFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facTransfert">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($transfertFac->score  * 100 ) / $transfertFac->total) < 80)  { ?>
                                    <td class="text-center" id="facTransfert">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $transfertDecla->score * 100 / $transfertDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transfertMa->score * 100 / $transfertMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($transfertDecla->questions); $i++) { ?>
                                    <?php if ($transfertDecla->answers[$i] == "Oui" && $transfertMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransfert">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transfertDecla->answers[$i] == "Non" && $transfertMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransfert">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($transfertDecla->answers[$i] != $transfertMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfTransfert">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfTransfert">

                                    </td>
                                    <td class="text-center" id="result-rTransfert">

                                    </td>
                                    <td class="text-center" id="synth-Transfert">

                                    </td>
                                </tr>
                                <?php } ?>
                                <?php
                                    $boiteFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Boite de Vitesse'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $boiteDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Boite de Vitesse'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $boiteMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Boite de Vitesse']
                                        ]
                                    ]);
                                ?>
                                <?php if ($boiteFac && $boiteDecla && $boiteMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $boiteFac->speciality?>&level=<?php echo $boiteFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            boite
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $boiteFac->score * 100 / $boiteFac->total ?>%
                                    </td>
                                    <?php if ((($boiteFac->score  * 100 ) / $boiteFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facBoite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($boiteFac->score  * 100 ) / $boiteFac->total) < 80)  { ?>
                                    <td class="text-center" id="facBoite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $boiteDecla->score * 100 / $boiteDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $boiteMa->score * 100 / $boiteMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($boiteDecla->questions); $i++) { ?>
                                    <?php if ($boiteDecla->answers[$i] == "Oui" && $boiteMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfBoite">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($boiteDecla->answers[$i] == "Non" && $boiteMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfBoite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($boiteDecla->answers[$i] != $boiteMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfBoite">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfBoite">

                                    </td>
                                    <td class="text-center" id="result-rBoite">

                                    </td>
                                    <td class="text-center" id="synth-Boite">

                                    </td>
                                </tr>
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
                                    <td class="text-center">
                                        <?php echo $climatisationDecla->score * 100 / $climatisationDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $climatisationMa->score * 100 / $climatisationMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($climatisationDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfClimatisation">

                                    </td>
                                    <td class="text-center" id="result-rClimatisation">

                                    </td>
                                    <td class="text-center" id="synth-Climatisation">

                                    </td>
                                </tr>
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
                                    <td class="text-center">
                                        <?php echo $directionDecla->score * 100 / $directionDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $directionMa->score * 100 / $directionMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($directionDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfDirection">

                                    </td>
                                    <td class="text-center" id="result-rDirection">

                                    </td>
                                    <td class="text-center" id="synth-Direction">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $electriciteFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Electricité'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $electriciteDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Electricité'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $electriciteMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Electricité']
                                        ]
                                    ]);
                                ?>
                                <?php if ($electriciteFac && $electriciteDecla && $electriciteMa) { ?>
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
                                    <td class="text-center">
                                        <?php echo $electriciteDecla->score * 100 / $electriciteDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $electriciteMa->score * 100 / $electriciteMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($electriciteDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfElectricite">

                                    </td>
                                    <td class="text-center" id="result-rElectricite">

                                    </td>
                                    <td class="text-center" id="synth-Electricite">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $freinageFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage Hydraulique'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $freinageDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage Hydraulique'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $freinageMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage Hydraulique']
                                        ]
                                    ]);
                                ?>
                                <?php if ($freinageFac && $freinageDecla && $freinageMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $freinageFac->speciality?>&level=<?php echo $freinageFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Freinage Hydraulique
                                        </a>
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
                                    <td class="text-center">
                                        <?php echo $freinageDecla->score * 100 / $freinageDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $freinageMa->score * 100 / $freinageMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($freinageDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfFreinage">

                                    </td>
                                    <td class="text-center" id="result-rFreinage">

                                    </td>
                                    <td class="text-center" id="synth-Freinage">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $freinFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage Pneumatique'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $freinDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage Pneumatique'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $freinMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Freinage Pneumatique']
                                        ]
                                    ]);
                                ?>
                                <?php if ($freinFac && $freinDecla && $freinMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $freinFac->speciality?>&level=<?php echo $freinFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Freinage Pneumatique
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $freinFac->score * 100 / $freinFac->total ?>%
                                    </td>
                                    <?php if ((($freinFac->score  * 100 ) / $freinFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facFrein">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($freinFac->score  * 100 ) / $freinFac->total) < 80)  { ?>
                                    <td class="text-center" id="facFrein">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $freinDecla->score * 100 / $freinDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $freinMa->score * 100 / $freinMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($freinDecla->questions); $i++) { ?>
                                    <?php if ($freinDecla->answers[$i] == "Oui" && $freinMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFrein">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($freinDecla->answers[$i] == "Non" && $freinMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFrein">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($freinDecla->answers[$i] != $freinMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfFrein">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfFrein">

                                    </td>
                                    <td class="text-center" id="result-rFrein">

                                    </td>
                                    <td class="text-center" id="synth-Frein">

                                    </td>
                                </tr>
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
                                    <td class="text-center">
                                        <?php echo $hydrauliqueDecla->score * 100 / $hydrauliqueDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $hydrauliqueMa->score * 100 / $hydrauliqueMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($hydrauliqueDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfHydraulique">

                                    </td>
                                    <td class="text-center" id="result-rHydraulique">

                                    </td>
                                    <td class="text-center" id="synth-Hydraulique">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $moteurDieselFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Diesel'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $moteurDieselDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Diesel'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $moteurDieselMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Diesel']
                                        ]
                                    ]);
                                ?>
                                <?php if ($moteurDieselFac && $moteurDieselDecla && $moteurDieselMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $moteurDieselFac->speciality?>&level=<?php echo $moteurDieselFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Moteur Diesel
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurDieselFac->score * 100 / $moteurDieselFac->total ?>%
                                    </td>
                                    <?php if ((($moteurDieselFac->score  * 100 ) / $moteurDieselFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facMoteurDiesel">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($moteurDieselFac->score  * 100 ) / $moteurDieselFac->total) < 80)  { ?>
                                    <td class="text-center" id="facMoteurDiesel">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $moteurDieselDecla->score * 100 / $moteurDieselDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurDieselMa->score * 100 / $moteurDieselMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($moteurDieselDecla->questions); $i++) { ?>
                                    <?php if ($moteurDieselDecla->answers[$i] == "Oui" && $moteurDieselMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurDiesel">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurDieselDecla->answers[$i] == "Non" && $moteurDieselMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurDiesel">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurDieselDecla->answers[$i] != $moteurDieselMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurDiesel">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfMoteurDiesel">

                                    </td>
                                    <td class="text-center" id="result-rMoteurDiesel">

                                    </td>
                                    <td class="text-center" id="synth-MoteurDiesel">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $moteurElecFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Electrique'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $moteurElecDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Electrique'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $moteurElecMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Electrique']
                                        ]
                                    ]);
                                ?>
                                <?php if ($moteurElecFac && $moteurElecDecla && $moteurElecMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $moteurElecFac->speciality?>&level=<?php echo $moteurElecFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Moteur Electrique
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurElecFac->score * 100 / $moteurElecFac->total ?>%
                                    </td>
                                    <?php if ((($moteurElecFac->score  * 100 ) / $moteurElecFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facMoteurElec">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($moteurElecFac->score  * 100 ) / $moteurElecFac->total) < 80)  { ?>
                                    <td class="text-center" id="facMoteurElec">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $moteurElecDecla->score * 100 / $moteurElecDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurElecMa->score * 100 / $moteurElecMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($moteurElecDecla->questions); $i++) { ?>
                                    <?php if ($moteurElecDecla->answers[$i] == "Oui" && $moteurElecMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurElec">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurElecDecla->answers[$i] == "Non" && $moteurElecMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurElec">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurElecDecla->answers[$i] != $moteurElecMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurElec">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfMoteurElec">

                                    </td>
                                    <td class="text-center" id="result-rMoteurElec">

                                    </td>
                                    <td class="text-center" id="synth-MoteurElec">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $moteurEssenceFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Essence'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $moteurEssenceDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Essence'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $moteurEssenceMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Moteur Essence']
                                        ]
                                    ]);
                                ?>
                                <?php if ($moteurEssenceFac && $moteurEssenceDecla && $moteurEssenceMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $moteurEssenceFac->speciality?>&level=<?php echo $moteurEssenceFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Moteur Essence
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurEssenceFac->score * 100 / $moteurEssenceFac->total ?>%
                                    </td>
                                    <?php if ((($moteurEssenceFac->score  * 100 ) / $moteurEssenceFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facMoteurEssence">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($moteurEssenceFac->score  * 100 ) / $moteurEssenceFac->total) < 80)  { ?>
                                    <td class="text-center" id="facMoteurEssence">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $moteurEssenceDecla->score * 100 / $moteurEssenceDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $moteurEssenceMa->score * 100 / $moteurEssenceMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($moteurEssenceDecla->questions); $i++) { ?>
                                    <?php if ($moteurEssenceDecla->answers[$i] == "Oui" && $moteurEssenceMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurEssence">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurEssenceDecla->answers[$i] == "Non" && $moteurEssenceMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurEssence">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($moteurEssenceDecla->answers[$i] != $moteurEssenceMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfMoteurEssence">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfMoteurEssence">

                                    </td>
                                    <td class="text-center" id="result-rMoteurEssence">

                                    </td>
                                    <td class="text-center" id="synth-MoteurEssence">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $multiplexageFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Multiplexage'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $multiplexageDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Multiplexage'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $multiplexageMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Multiplexage']
                                        ]
                                    ]);
                                ?>
                                <?php if ($multiplexageFac && $multiplexageDecla && $multiplexageMa) { ?>
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
                                    <td class="text-center">
                                        <?php echo $multiplexageDecla->score * 100 / $multiplexageDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $multiplexageMa->score * 100 / $multiplexageMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($multiplexageDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfMultiplexage">

                                    </td>
                                    <td class="text-center" id="result-rMultiplexage">

                                    </td>
                                    <td class="text-center" id="synth-Multiplexage">

                                    </td>
                                </tr>
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
                                    <td class="text-center">
                                        <?php echo $pneuDecla->score * 100 / $pneuDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $pneuMa->score * 100 / $pneuMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($pneuDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfPneu">

                                    </td>
                                    <td class="text-center" id="result-rPneu">

                                    </td>
                                    <td class="text-center" id="synth-Pneu">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $pontFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Pont'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $pontDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Pont'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $pontMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Pont']
                                        ]
                                    ]);
                                ?>
                                <?php if ($pontFac && $pontDecla && $pontMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $pontFac->speciality?>&level=<?php echo $pontFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Pont
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $pontFac->score * 100 / $pontFac->total ?>%
                                    </td>
                                    <?php if ((($pontFac->score  * 100 ) / $pontFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facPont">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($pontFac->score  * 100 ) / $pontFac->total) < 80)  { ?>
                                    <td class="text-center" id="facPont">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $pontDecla->score * 100 / $pontDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $pontMa->score * 100 / $pontMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($pontDecla->questions); $i++) { ?>
                                    <?php if ($pontDecla->answers[$i] == "Oui" && $pontMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPont">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($pontDecla->answers[$i] == "Non" && $pontMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPont">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($pontDecla->answers[$i] != $pontMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfPont">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfPont">

                                    </td>
                                    <td class="text-center" id="result-rPont">

                                    </td>
                                    <td class="text-center" id="synth-Pont">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $reducteurFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Réducteur'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $reducteurDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Réducteur'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $reducteurMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Réducteur']
                                        ]
                                    ]);
                                ?>
                                <?php if ($reducteurFac && $reducteurDecla && $reducteurMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $reducteurFac->speciality?>&level=<?php echo $reducteurFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Réducteur
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $reducteurFac->score * 100 / $reducteurFac->total ?>%
                                    </td>
                                    <?php if ((($reducteurFac->score  * 100 ) / $reducteurFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facReducteur">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($reducteurFac->score  * 100 ) / $reducteurFac->total) < 80)  { ?>
                                    <td class="text-center" id="facReducteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $reducteurDecla->score * 100 / $reducteurDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $reducteurMa->score * 100 / $reducteurMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($reducteurDecla->questions); $i++) { ?>
                                    <?php if ($reducteurDecla->answers[$i] == "Oui" && $reducteurMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfReducteur">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($reducteurDecla->answers[$i] == "Non" && $reducteurMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfReducteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($reducteurDecla->answers[$i] != $reducteurMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfReducteur">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfReducteur">

                                    </td>
                                    <td class="text-center" id="result-rReducteur">

                                    </td>
                                    <td class="text-center" id="synth-Reducteur">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $suspensionLameFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension à Lame'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $suspensionLameDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension à Lame'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $suspensionLameMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension à Lame']
                                        ]
                                    ]);
                                ?>
                                <?php if ($suspensionLameFac && $suspensionLameDecla && $suspensionLameMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $suspensionLameFac->speciality?>&level=<?php echo $suspensionLameFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Suspension à Lame
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionLameFac->score * 100 / $suspensionLameFac->total ?>%
                                    </td>
                                    <?php if ((($suspensionLameFac->score  * 100 ) / $suspensionLameFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facSuspensionLame">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($suspensionLameFac->score  * 100 ) / $suspensionLameFac->total) < 80)  { ?>
                                    <td class="text-center" id="facSuspensionLame">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $suspensionLameDecla->score * 100 / $suspensionLameDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionLameMa->score * 100 / $suspensionLameMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($suspensionLameDecla->questions); $i++) { ?>
                                    <?php if ($suspensionLameDecla->answers[$i] == "Oui" && $suspensionLameMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionLame">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionLameDecla->answers[$i] == "Non" && $suspensionLameMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionLame">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionLameDecla->answers[$i] != $suspensionLameMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionLame">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfSuspensionLame">

                                    </td>
                                    <td class="text-center" id="result-rSuspensionLame">

                                    </td>
                                    <td class="text-center" id="synth-SuspensionLame">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $suspensionRessortFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension Ressort'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $suspensionRessortDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension Ressort'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $suspensionRessortMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension Ressort']
                                        ]
                                    ]);
                                ?>
                                <?php if ($suspensionRessortFac && $suspensionRessortDecla && $suspensionRessortMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $suspensionRessortFac->speciality?>&level=<?php echo $suspensionRessortFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Suspension Ressort
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionRessortFac->score * 100 / $suspensionRessortFac->total ?>%
                                    </td>
                                    <?php if ((($suspensionRessortFac->score  * 100 ) / $suspensionRessortFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facSuspensionRessort">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($suspensionRessortFac->score  * 100 ) / $suspensionRessortFac->total) < 80)  { ?>
                                    <td class="text-center" id="facSuspensionRessort">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $suspensionRessortDecla->score * 100 / $suspensionRessortDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionRessortMa->score * 100 / $suspensionRessortMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($suspensionRessortDecla->questions); $i++) { ?>
                                    <?php if ($suspensionRessortDecla->answers[$i] == "Oui" && $suspensionRessortMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionRessort">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionRessortDecla->answers[$i] == "Non" && $suspensionRessortMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionRessort">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionRessortDecla->answers[$i] != $suspensionRessortMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionRessort">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfSuspensionRessort">

                                    </td>
                                    <td class="text-center" id="result-rSuspensionRessort">

                                    </td>
                                    <td class="text-center" id="synth-SuspensionRessort">

                                    </td>
                                </tr>
                                <?php } ?>
                                <!--end::Menu-->
                                <?php
                                    $suspensionPneumatiqueFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension Pneumatique'],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $suspensionPneumatiqueDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension Pneumatique'],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $suspensionPneumatiqueMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => 'Suspension Pneumatique']
                                        ]
                                    ]);
                                ?>
                                <?php if ($suspensionPneumatiqueFac && $suspensionPneumatiqueDecla && $suspensionPneumatiqueMa) { ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="min-w-125px sorting text-white text-center table-light text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table" rowspan=`${i}`
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; background-color: #a3f1ff;">
                                        <a href="./system.php?speciality=<?php echo $suspensionPneumatiqueFac->speciality?>&level=<?php echo $suspensionPneumatiqueFac->level?>&user=<?php echo $technician->_id ?>"
                                            class="btn btn-light btn-active-light-primary text-primary btn-sm"
                                            title="Cliquez ici pour voir le résultat du technicien pour le niveau senior"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Suspension Pneumatique
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionPneumatiqueFac->score * 100 / $suspensionPneumatiqueFac->total ?>%
                                    </td>
                                    <?php if ((($suspensionPneumatiqueFac->score  * 100 ) / $suspensionPneumatiqueFac->total) >= 80)  { ?>
                                    <td class="text-center" id="facSuspensionPneumatique">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ((($suspensionPneumatiqueFac->score  * 100 ) / $suspensionPneumatiqueFac->total) < 80)  { ?>
                                    <td class="text-center" id="facSuspensionPneumatique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php echo $suspensionPneumatiqueDecla->score * 100 / $suspensionPneumatiqueDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $suspensionPneumatiqueMa->score * 100 / $suspensionPneumatiqueMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($suspensionPneumatiqueDecla->questions); $i++) { ?>
                                    <?php if ($suspensionPneumatiqueDecla->answers[$i] == "Oui" && $suspensionPneumatiqueMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionPneumatique">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionPneumatiqueDecla->answers[$i] == "Non" && $suspensionPneumatiqueMa->answers[$i] == "Non") { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionPneumatique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($suspensionPneumatiqueDecla->answers[$i] != $suspensionPneumatiqueMa->answers[$i]) { ?>
                                    <td class="text-center hidden" name="savoirs-faire" id="sfSuspensionPneumatique">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td class="text-center" id="result-sfSuspensionPneumatique">

                                    </td>
                                    <td class="text-center" id="result-rSuspensionPneumatique">

                                    </td>
                                    <td class="text-center" id="synth-SuspensionPneumatique">

                                    </td>
                                </tr>
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
                                    <td class="text-center">
                                        <?php echo $transversaleDecla->score * 100 / $transversaleDecla->total ?>%
                                    </td>
                                    <td class="text-center">
                                        <?php echo $transversaleMa->score * 100 / $transversaleMa->total ?>%
                                    </td>
                                    <?php for ($i = 0; $i < count($transversaleDecla->questions); $i++) { ?>
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
                                    <?php } ?>
                                    <td class="text-center" id="result-sfTransverse">

                                    </td>
                                    <td class="text-center" id="result-rTransversale">

                                    </td>
                                    <td class="text-center" id="synth-Transversale">

                                    </td>
                                </tr>
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
                                        <?php echo round($arrResultFac[0]->percentage ?? "0", 0) ?>%
                                    </th>
                                    <th id="decision-savoir"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    </th>
                                    <th id="result-n1"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo round($arrResultDecla[0]->percentage ?? "0", 0) ?>%
                                    </th>
                                    <th id="result-n1"
                                        class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                        tabindex="0" aria-controls="kt_customers_table"
                                        aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                        <?php echo round($arrResultMa[0]->percentage ?? "0", 0) ?>%
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
<script src="../public/js/main.js"></script>
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

// const savoir = []
const savoirFaire = []
// const n = []
// const n1 = []
const sfTransfert = []
const sfTransversale = []
const sfTransmission = []
const sfBoite = []
const sfAssistance = []
const sfClimatisation = []
const sfDirection = []
const sfElectricite = []
const sfFreinage = []
const sfFrein = []
const sfMoteurDiesel = []
const sfMoteurElec = []
const sfMoteurEssence = []
const sfHydraulique = []
const sfPneu = []
const sfPont = []
const sfReducteur = []
const sfmultiplexage = []
const sfSuspensionLame = []
const sfSuspensionRessort = []
const sfSuspensionPneumatique = []
const valueMaitrisé = "Maitrisé"
const valueOui = "Oui"
// const tdSavoir = document.querySelectorAll("td[name='savoir']")
const tdSavoirFaire = document.querySelectorAll("td[name='savoirs-faire']")
// const tdN = document.querySelectorAll("td[name='n']")
// const tdN1 = document.querySelectorAll("td[name='n1']")
const tdsfTransversale = document.querySelectorAll("#sfTransversale")
const tdsfTransfert = document.querySelectorAll("#sfTransfert")
const tdsfTransmission = document.querySelectorAll("#sfTransmission")
const tdsfBoite = document.querySelectorAll("#sfBoite")
const tdsfAssistance = document.querySelectorAll("#sfAssistance")
const tdsfClimatisation = document.querySelectorAll("#sfClimatisation")
const tdsfDirection = document.querySelectorAll("#sfDirection")
const tdsfElectricite = document.querySelectorAll("#sfElectricite")
const tdsfFreinage = document.querySelectorAll("#sfFreinage")
const tdsfFrein = document.querySelectorAll("#sfFrein")
const tdsfHydraulique = document.querySelectorAll("#sfHydraulique")
const tdsfMoteurDiesel = document.querySelectorAll("#sfMoteurDiesel")
const tdsfMoteurElec = document.querySelectorAll("#sfMoteurElec")
const tdsfMoteurEssence = document.querySelectorAll("#sfMoteurEssence")
const tdsfPneu = document.querySelectorAll("#sfPneu")
const tdsfPont = document.querySelectorAll("#sfPont")
const tdsfReducteur = document.querySelectorAll("#sfReducteur")
const tdsfmultiplexage = document.querySelectorAll("#sfMultiplexage")
const tdsfSuspensionLame = document.querySelectorAll("#sfSuspensionLame")
const tdsfSuspensionRessort = document.querySelectorAll("#sfSuspensionRessort")
const tdsfSuspensionPneumatique = document.querySelectorAll("#sfSuspensionPneumatique")
const resultSavoir = document.querySelector("#result-savoir")
const resultSavoirFaire = document.querySelector("#result-savoir-faire")
const decisionSavoir = document.querySelector("#decision-savoir")
const decisionSavoirFaire = document.querySelector("#decision-savoirs-faire")
const synthese = document.querySelector("#synthese")
// const resultN = document.querySelector("#result-n")
// const resultN1 = document.querySelector("#result-n1")
const resultsfTransversale = document.querySelector("#result-sfTransverse")
const synthTransversale = document.querySelector("#synth-Transversale")
const resultrTransversale = document.querySelector("#result-rTransversale")
const facTransversale = document.querySelector("#facTransversale")
const resultsfTransfert = document.querySelector("#result-sfTransfert")
const synthTransfert = document.querySelector("#synth-Transfert")
const resultrTransfert = document.querySelector("#result-rTransfert")
const facTransfert = document.querySelector("#facTransfert")
const resultsfTransmission = document.querySelector("#result-sfTransmission")
const synthTransmission = document.querySelector("#synth-Transmission")
const resultrTransmission = document.querySelector("#result-rTransmission")
const facTransmission = document.querySelector("#facTransmission")
const resultsfBoite = document.querySelector("#result-sfBoite")
const synthBoite = document.querySelector("#synth-Boite")
const resultrBoite = document.querySelector("#result-rBoite")
const facBoite = document.querySelector("#facBoite")
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
const resultsfFrein = document.querySelector("#result-sfFrein")
const synthFrein = document.querySelector("#synth-Frein")
const resultrFrein = document.querySelector("#result-rFrein")
const facFrein = document.querySelector("#facFrein")
const resultsfHydraulique = document.querySelector("#result-sfHydraulique")
const synthHydraulique = document.querySelector("#synth-Hydraulique")
const resultrHydraulique = document.querySelector("#result-rHydraulique")
const facHydraulique = document.querySelector("#facHydraulique")
const resultsfMoteurDiesel = document.querySelector("#result-sfMoteurDiesel")
const synthMoteurDiesel = document.querySelector("#synth-MoteurDiesel")
const resultrMoteurDiesel = document.querySelector("#result-rMoteurDiesel")
const facMoteurDiesel = document.querySelector("#facMoteurDiesel")
const resultsfMoteurElec = document.querySelector("#result-sfMoteurElec")
const synthMoteurElec = document.querySelector("#synth-MoteurElec")
const resultrMoteurElec = document.querySelector("#result-rMoteurElec")
const facMoteurElec = document.querySelector("#facMoteurElec")
const resultsfMoteurEssence = document.querySelector("#result-sfMoteurEssence")
const synthMoteurEssence = document.querySelector("#synth-MoteurEssence")
const resultrMoteurEssence = document.querySelector("#result-rMoteurEssence")
const facMoteurEssence = document.querySelector("#facMoteurEssence")
const resultsfMultiplexage = document.querySelector("#result-sfmultiplexage")
const synthMultiplexage = document.querySelector("#synth-Multiplexage")
const resultrMultiplexage = document.querySelector("#result-rMultiplexage")
const facMultiplexage = document.querySelector("#facMultiplexage")
const resultsfPneu = document.querySelector("#result-sfPneu")
const synthPneu = document.querySelector("#synth-Pneu")
const resultrPneu = document.querySelector("#result-rPneu")
const facPneu = document.querySelector("#facPneu")
const resultsfPont = document.querySelector("#result-sfPont")
const synthPont = document.querySelector("#synth-Pont")
const resultrPont = document.querySelector("#result-rPont")
const facPont = document.querySelector("#facPont")
const resultsfReducteur = document.querySelector("#result-sfReducteur")
const synthReducteur = document.querySelector("#synth-Reducteur")
const resultrReducteur = document.querySelector("#result-rReducteur")
const facReducteur = document.querySelector("#facReducteur")
const resultsfSuspensionLame = document.querySelector("#result-sfSuspensionLame")
const synthSuspensionLame = document.querySelector("#synth-SuspensionLame")
const resultrSuspensionLame = document.querySelector("#result-rSuspensionLame")
const facSuspensionLame = document.querySelector("#facSuspensionLame")
const resultsfSuspensionRessort = document.querySelector("#result-sfSuspensionRessort")
const synthSuspensionRessort = document.querySelector("#synth-SuspensionRessort")
const resultrSuspensionRessort = document.querySelector("#result-rSuspensionRessort")
const facSuspensionRessort = document.querySelector("#facSuspensionRessort")
const resultsfSuspensionPneumatique = document.querySelector("#result-sfSuspensionPneumatique")
const synthSuspensionPneumatique = document.querySelector("#synth-SuspensionPneumatique")
const resultrSuspensionPneumatique = document.querySelector("#result-rSuspensionPneumatique")
const facSuspensionPneumatique = document.querySelector("#facSuspensionPneumatique")

// for (let i = 0; i < tdSavoir.length; i++) {
//     savoir.push(tdSavoir[i].innerHTML)
// }
for (let i = 0; i < tdSavoirFaire.length; i++) {
    savoirFaire.push(tdSavoirFaire[i].innerHTML)
}
// for (let i = 0; i < tdN.length; i++) {
//     n.push(tdN[i].innerHTML)
// }
// for (let i = 0; i < tdN1.length; i++) {
//     n1.push(tdN1[i].innerHTML)
// }
for (let i = 0; i < tdsfTransversale.length; i++) {
    sfTransversale.push(tdsfTransversale[i].innerHTML)
}
for (let i = 0; i < tdsfTransfert.length; i++) {
    sfTransfert.push(tdsfTransfert[i].innerHTML)
}
for (let i = 0; i < tdsfTransmission.length; i++) {
    sfTransmission.push(tdsfTransmission[i].innerHTML)
}
for (let i = 0; i < tdsfBoite.length; i++) {
    sfBoite.push(tdsfBoite[i].innerHTML)
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
for (let i = 0; i < tdsfFrein.length; i++) {
    sfFrein.push(tdsfFrein[i].innerHTML)
}
for (let i = 0; i < tdsfHydraulique.length; i++) {
    sfHydraulique.push(tdsfHydraulique[i].innerHTML)
}
for (let i = 0; i < tdsfMoteurDiesel.length; i++) {
    sfMoteurDiesel.push(tdsfMoteurDiesel[i].innerHTML)
}
for (let i = 0; i < tdsfMoteurElec.length; i++) {
    sfMoteurElec.push(tdsfMoteurElec[i].innerHTML)
}
for (let i = 0; i < tdsfMoteurEssence.length; i++) {
    sfMoteurEssence.push(tdsfMoteurEssence[i].innerHTML)
}
for (let i = 0; i < tdsfmultiplexage.length; i++) {
    sfmultiplexage.push(tdsfmultiplexage[i].innerHTML)
}
for (let i = 0; i < tdsfPneu.length; i++) {
    sfPneu.push(tdsfPneu[i].innerHTML)
}
for (let i = 0; i < tdsfPont.length; i++) {
    sfPont.push(tdsfPont[i].innerHTML)
}
for (let i = 0; i < tdsfReducteur.length; i++) {
    sfReducteur.push(tdsfReducteur[i].innerHTML)
}
for (let i = 0; i < tdsfSuspensionLame.length; i++) {
    sfSuspensionLame.push(tdsfSuspensionLame[i].innerHTML)
}
for (let i = 0; i < tdsfSuspensionRessort.length; i++) {
    sfSuspensionRessort.push(tdsfSuspensionRessort[i].innerHTML)
}
for (let i = 0; i < tdsfSuspensionPneumatique.length; i++) {
    sfSuspensionPneumatique.push(tdsfSuspensionPneumatique[i].innerHTML)
}

// const maitriseSavoir = savoir.filter(function(str) {
//     return str.includes(valueMaitrisé)
// })
const maitriseSavoirFaire = savoirFaire.filter(function(str) {
    return str.includes(valueMaitrisé)
})
// const ouiN = n.filter(function(str) {
//     return str.includes(valueOui)
// })
// const ouiN1 = n1.filter(function(str) {
//     return str.includes(valueOui)
// })
const maitrisesfTransversale = sfTransversale.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfTransfert = sfTransfert.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfTransmission = sfTransmission.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfBoite = sfBoite.filter(function(str) {
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
const maitrisesfFrein = sfFrein.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfHydraulique = sfHydraulique.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfMoteurDiesel = sfMoteurDiesel.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfMoteurElec = sfMoteurElec.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfMoteurEssence = sfMoteurEssence.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfmultiplexage = sfmultiplexage.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfPneu = sfPneu.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfPont = sfPont.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfReducteur = sfReducteur.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfSuspensionLame = sfSuspensionLame.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfSuspensionRessort = sfSuspensionRessort.filter(function(str) {
    return str.includes(valueMaitrisé)
})
const maitrisesfSuspensionPneumatique = sfSuspensionPneumatique.filter(function(str) {
    return str.includes(valueMaitrisé)
})

// const percentSavoir = ((maitriseSavoir.length * 100) / tdSavoir.length).toFixed(0)
const percentSavoirFaire = ((maitriseSavoirFaire.length * 100) / tdSavoirFaire.length).toFixed(0)
// const percentN = ((ouiN.length * 100) / tdN.length).toFixed(0)
// const percentN1 = ((ouiN1.length * 100) / tdN1.length).toFixed(0)
const percentsfTransversale = ((maitrisesfTransversale.length * 100) / tdsfTransversale.length).toFixed(0)
const percentsfTransfert = ((maitrisesfTransfert.length * 100) / tdsfTransfert.length).toFixed(0)
const percentsfTransmission = ((maitrisesfTransmission.length * 100) / tdsfTransmission.length).toFixed(0)
const percentsfBoite = ((maitrisesfBoite.length * 100) / tdsfBoite.length).toFixed(0)
const percentsfAssistance = ((maitrisesfAssistance.length * 100) / tdsfAssistance.length).toFixed(0)
const percentsfClimatisation = ((maitrisesfClimatisation.length * 100) / tdsfClimatisation.length).toFixed(0)
const percentsfDirection = ((maitrisesfDirection.length * 100) / tdsfDirection.length).toFixed(0)
const percentsfElectricite = ((maitrisesfElectricite.length * 100) / tdsfElectricite.length).toFixed(0)
const percentsfFreinage = ((maitrisesfFreinage.length * 100) / tdsfFreinage.length).toFixed(0)
const percentsfFrein = ((maitrisesfFrein.length * 100) / tdsfFrein.length).toFixed(0)
const percentsfHydraulique = ((maitrisesfHydraulique.length * 100) / tdsfHydraulique.length).toFixed(0)
const percentsfMoteurDiesel = ((maitrisesfMoteurDiesel.length * 100) / tdsfMoteurDiesel.length).toFixed(0)
const percentsfMoteurElec = ((maitrisesfMoteurElec.length * 100) / tdsfMoteurElec.length).toFixed(0)
const percentsfMoteurEssence = ((maitrisesfMoteurEssence.length * 100) / tdsfMoteurEssence.length).toFixed(0)
const percentsfPneu = ((maitrisesfPneu.length * 100) / tdsfPneu.length).toFixed(0)
const percentsfPont = ((maitrisesfPont.length * 100) / tdsfPont.length).toFixed(0)
const percentsfReducteur = ((maitrisesfReducteur.length * 100) / tdsfReducteur.length).toFixed(0)
const percentsfmultiplexage = ((maitrisesfmultiplexage.length * 100) / tdsfmultiplexage.length).toFixed(0)
const percentsfSuspensionLame = ((maitrisesfSuspensionLame.length * 100) / tdsfSuspensionLame.length).toFixed(0)
const percentsfSuspensionRessort = ((maitrisesfSuspensionRessort.length * 100) / tdsfSuspensionRessort.length).toFixed(
    0)
const percentsfSuspensionPneumatique = ((maitrisesfSuspensionPneumatique.length * 100) / tdsfSuspensionPneumatique
    .length).toFixed(
    0)

// resultSavoir.innerHTML = percentSavoir + "%";
resultSavoirFaire.innerHTML = percentSavoirFaire + "%";
// resultN.innerHTML = percentN + "%";
// resultN1.innerHTML = percentN1 + "%";
if (resultsfTransversale) {
    resultsfTransversale.innerHTML = percentsfTransversale + "%";
}
if (resultsfTransfert) {
    resultsfTransfert.innerHTML = percentsfTransfert + "%";
}
if (resultsfTransmission) {
    resultsfTransmission.innerHTML = percentsfTransmission + "%";
}
if (resultsfBoite) {
    resultsfBoite.innerHTML = percentsfBoite + "%";
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
if (resultsfFrein) {
    resultsfFrein.innerHTML = percentsfFrein + "%";
}
if (resultsfHydraulique) {
    resultsfHydraulique.innerHTML = percentsfHydraulique + "%";
}
if (resultsfMoteurDiesel) {
    resultsfMoteurDiesel.innerHTML = percentsfMoteurDiesel + "%";
}
if (resultsfMoteurElec) {
    resultsfMoteurElec.innerHTML = percentsfMoteurElec + "%";
}
if (resultsfMoteurEssence) {
    resultsfMoteurEssence.innerHTML = percentsfMoteurEssence + "%";
}
if (resultsfPneu) {
    resultsfPneu.innerHTML = percentsfPneu + "%";
}
if (resultsfPont) {
    resultsfPont.innerHTML = percentsfPont + "%";
}
if (resultsfReducteur) {
    resultsfReducteur.innerHTML = percentsfReducteur + "%";
}
if (resultsfMultiplexage) {
    resultsfMultiplexage.innerHTML = percentsfmultiplexage + "%";
}
if (resultsfSuspensionLame) {
    resultsfSuspensionLame.innerHTML = percentsfSuspensionLame + "%";
}
if (resultsfSuspensionRessort) {
    resultsfSuspensionRessort.innerHTML = percentsfSuspensionRessort + "%";
}
if (resultsfSuspensionPneumatique) {
    resultsfSuspensionPneumatique.innerHTML = percentsfSuspensionPneumatique + "%";
}
const a = "80%";

if (resultsfTransversale && parseFloat(resultsfTransversale.innerHTML) >= parseFloat(a)) {
    resultrTransversale.innerHTML = "Maitrisé"
}
if (resultsfTransversale && parseFloat(resultsfTransversale.innerHTML) < parseFloat(a)) {
    resultrTransversale.innerHTML = "Non maitrisé"
}
if (resultsfTransfert && parseFloat(resultsfTransfert.innerHTML) >= parseFloat(a)) {
    resultrTransfert.innerHTML = "Maitrisé"
}
if (resultsfTransfert && parseFloat(resultsfTransfert.innerHTML) < parseFloat(a)) {
    resultrTransfert.innerHTML = "Non maitrisé"
}
if (resultsfTransmission && parseFloat(resultsfTransmission.innerHTML) >= parseFloat(a)) {
    resultrTransmission.innerHTML = "Maitrisé"
}
if (resultsfTransmission && parseFloat(resultsfTransmission.innerHTML) < parseFloat(a)) {
    resultrTransmission.innerHTML = "Non maitrisé"
}
if (resultsfBoite && parseFloat(resultsfBoite.innerHTML) >= parseFloat(a)) {
    resultrBoite.innerHTML = "Maitrisé"
}
if (resultsfBoite && parseFloat(resultsfBoite.innerHTML) < parseFloat(a)) {
    resultrBoite.innerHTML = "Non maitrisé"
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
if (resultsfFrein && parseFloat(resultsfFrein.innerHTML) >= parseFloat(a)) {
    resultrFrein.innerHTML = "Maitrisé"
}
if (resultsfFrein && parseFloat(resultsfFrein.innerHTML) < parseFloat(a)) {
    resultrFrein.innerHTML = "Non maitrisé"
}
if (resultsfHydraulique && parseFloat(resultsfHydraulique.innerHTML) >= parseFloat(a)) {
    resultrHydraulique.innerHTML = "Maitrisé"
}
if (resultsfHydraulique && parseFloat(resultsfHydraulique.innerHTML) < parseFloat(a)) {
    resultrHydraulique.innerHTML = "Non maitrisé"
}
if (resultsfMoteurDiesel && parseFloat(resultsfMoteurDiesel.innerHTML) >= parseFloat(a)) {
    resultrMoteurDiesel.innerHTML = "Maitrisé"
}
if (resultsfMoteurDiesel && parseFloat(resultsfMoteurDiesel.innerHTML) < parseFloat(a)) {
    resultrMoteurDiesel.innerHTML = "Non maitrisé"
}
if (resultsfMoteurElec && parseFloat(resultsfMoteurElec.innerHTML) >= parseFloat(a)) {
    resultrMoteurElec.innerHTML = "Maitrisé"
}
if (resultsfMoteurElec && parseFloat(resultsfMoteurElec.innerHTML) < parseFloat(a)) {
    resultrMoteurElec.innerHTML = "Non maitrisé"
}
if (resultsfMoteurEssence && parseFloat(resultsfMoteurEssence.innerHTML) >= parseFloat(a)) {
    resultrMoteurEssence.innerHTML = "Maitrisé"
}
if (resultsfMoteurEssence && parseFloat(resultsfMoteurEssence.innerHTML) < parseFloat(a)) {
    resultrMoteurEssence.innerHTML = "Non maitrisé"
}
if (resultsfMultiplexage && parseFloat(resultsfMultiplexage.innerHTML) >= parseFloat(a)) {
    resultrMultiplexage.innerHTML = "Maitrisé"
}
if (resultsfMultiplexage && parseFloat(resultsfMultiplexage.innerHTML) < parseFloat(a)) {
    resultrMultiplexage.innerHTML = "Non maitrisé"
}
if (resultsfSuspensionLame && parseFloat(resultsfSuspensionLame.innerHTML) >= parseFloat(a)) {
    resultrSuspensionLame.innerHTML = "Maitrisé"
}
if (resultsfSuspensionLame && parseFloat(resultsfSuspensionLame.innerHTML) < parseFloat(a)) {
    resultrSuspensionLame.innerHTML = "Non maitrisé"
}
if (resultsfSuspensionRessort && parseFloat(resultsfSuspensionRessort.innerHTML) >= parseFloat(a)) {
    resultrSuspensionRessort.innerHTML = "Maitrisé"
}
if (resultsfSuspensionRessort && parseFloat(resultsfSuspensionRessort.innerHTML) < parseFloat(a)) {
    resultrSuspensionRessort.innerHTML = "Non maitrisé"
}
if (resultsfSuspensionPneumatique && parseFloat(resultsfSuspensionPneumatique.innerHTML) >= parseFloat(a)) {
    resultrSuspensionPneumatique.innerHTML = "Maitrisé"
}
if (resultsfSuspensionPneumatique && parseFloat(resultsfSuspensionPneumatique.innerHTML) < parseFloat(a)) {
    resultrSuspensionPneumatique.innerHTML = "Non maitrisé"
}
if (resultsfPneu && parseFloat(resultsfPneu.innerHTML) >= parseFloat(a)) {
    resultrPneu.innerHTML = "Maitrisé"
}
if (resultsfPont && parseFloat(resultsfPont.innerHTML) < parseFloat(a)) {
    resultrPont.innerHTML = "Non maitrisé"
}
if (resultsfReducteur && parseFloat(resultsfReducteur.innerHTML) < parseFloat(a)) {
    resultrReducteur.innerHTML = "Non maitrisé"
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

if (facTransversale && facTransversale.innerText == "Maitrisé" && (resultrTransversale.innerText == "Maitrisé")) {
    synthTransversale.innerHTML = "Maitrisé"
}
if (facTransversale && facTransversale.innerText == "Non maitrisé" && (resultrTransversale.innerText ==
        "Non maitrisé")) {
    synthTransversale.innerHTML = "Non maitrisé"
}
if (facTransversale && facTransversale.innerText != resultrTransversale.innerText) {
    synthTransversale.innerHTML = "Non maitrisé"
}
if (facTransfert && facTransfert.innerText == "Maitrisé" && (resultrTransfert.innerText == "Maitrisé")) {
    synthTransfert.innerHTML = "Maitrisé"
}
if (facTransfert && facTransfert.innerText == "Non maitrisé" && (resultrTransfert.innerText ==
        "Non maitrisé")) {
    synthTransfert.innerHTML = "Non maitrisé"
}
if (facTransversale && facTransfert.innerText != resultrTransfert.innerText) {
    synthTransfert.innerHTML = "Non maitrisé"
}
if (facTransmission && facTransmission.innerText == "Maitrisé" && (resultrTransmission.innerText == "Maitrisé")) {
    synthTransmission.innerHTML = "Maitrisé"
}
if (facTransmission && facTransmission.innerText == "Non maitrisé" && (resultrTransmission.innerText ==
        "Non maitrisé")) {
    synthTransmission.innerHTML = "Non maitrisé"
}
if (facTransmission && facTransmission.innerText != resultrTransmission.innerText) {
    synthTransmission.innerHTML = "Non maitrisé"
}
if (facBoite && facBoite.innerText == "Maitrisé" && (resultrBoite.innerText == "Maitrisé")) {
    synthBoite.innerHTML = "Maitrisé"
}
if (facBoite && facBoite.innerText == "Non maitrisé" && (resultrBoite.innerText ==
        "Non maitrisé")) {
    synthBoite.innerHTML = "Non maitrisé"
}
if (facBoite && facBoite.innerText != resultrBoite.innerText) {
    synthBoite.innerHTML = "Non maitrisé"
}
if (facAssistance && facAssistance.innerText == "Maitrisé" && (resultrAssistance.innerText == "Maitrisé")) {
    synthAssistance.innerHTML = "Maitrisé"
}
if (facAssistance && facAssistance.innerText == "Non maitrisé" && (resultrAssistance.innerText == "Non maitrisé")) {
    synthAssistance.innerHTML = "Non maitrisé"
}
if (facAssistance && facAssistance.innerText != resultrAssistance.innerText) {
    synthAssistance.innerHTML = "Non maitrisé"
}
if (facClimatisation && facClimatisation.innerText == "Maitrisé" && (resultrClimatisation.innerText == "Maitrisé")) {
    synthClimatisation.innerHTML = "Maitrisé"
}
if (facClimatisation && facClimatisation.innerText == "Non maitrisé" && (resultrClimatisation.innerText ==
        "Non maitrisé")) {
    synthClimatisation.innerHTML = "Non maitrisé"
}
if (facClimatisation && facClimatisation.innerText != resultrClimatisation.innerText) {
    synthClimatisation.innerHTML = "Non maitrisé"
}
if (facDirection && facDirection.innerText == "Maitrisé" && (resultrDirection.innerText == "Maitrisé")) {
    synthDirection.innerHTML = "Maitrisé"
}
if (facDirection && facDirection.innerText == "Non maitrisé" && (resultrDirection.innerText == "Non maitrisé")) {
    synthDirection.innerHTML = "Non maitrisé"
}
if (facDirection && facDirection.innerText != resultrDirection.innerText) {
    synthDirection.innerHTML = "Non maitrisé"
}
if (facElectricite && facElectricite.innerText == "Maitrisé" && (resultrElectricite.innerText == "Maitrisé")) {
    synthElectricite.innerHTML = "Maitrisé"
}
if (facElectricite && facElectricite.innerText == "Non maitrisé" && (resultrElectricite.innerText == "Non maitrisé")) {
    synthElectricite.innerHTML = "Non maitrisé"
}
if (facElectricite && facElectricite.innerText != resultrElectricite.innerText) {
    synthElectricite.innerHTML = "Non maitrisé"
}
if (facFreinage && facFreinage.innerText == "Maitrisé" && (resultrFreinage.innerText == "Maitrisé")) {
    synthFreinage.innerHTML = "Maitrisé"
}
if (facFreinage && facFreinage.innerText == "Non maitrisé" && (resultrFreinage.innerText == "Non maitrisé")) {
    synthFreinage.innerHTML = "Non maitrisé"
}
if (facFreinage && facFreinage.innerText != resultrFreinage.innerText) {
    synthFreinage.innerHTML = "Non maitrisé"
}
if (facFrein && facFrein.innerText == "Maitrisé" && (resultrFrein.innerText == "Maitrisé")) {
    synthFrein.innerHTML = "Maitrisé"
}
if (facFrein && facFrein.innerText == "Non maitrisé" && (resultrFrein.innerText == "Non maitrisé")) {
    synthFrein.innerHTML = "Non maitrisé"
}
if (facFrein && facFrein.innerText != resultrFrein.innerText) {
    synthFrein.innerHTML = "Non maitrisé"
}
if (facHydraulique && facHydraulique.innerText == "Maitrisé" && (resultrHydraulique.innerText == "Maitrisé")) {
    synthHydraulique.innerHTML = "Maitrisé"
}
if (facHydraulique && facHydraulique.innerText == "Non maitrisé" && (resultrHydraulique.innerText ==
        "Non maitrisé")) {
    synthHydraulique.innerHTML = "Non maitrisé"
}
if (facHydraulique && facHydraulique.innerText != resultrHydraulique.innerText) {
    synthHydraulique.innerHTML = "Non maitrisé"
}
if (facMoteurDiesel && facMoteurDiesel.innerText == "Maitrisé" && (resultrMoteurDiesel.innerText == "Maitrisé")) {
    synthMoteurDiesel.innerHTML = "Maitrisé"
}
if (facMoteurDiesel && facMoteurDiesel.innerText == "Non maitrisé" && (resultrMoteurDiesel.innerText ==
        "Non maitrisé")) {
    synthMoteurDiesel.innerHTML = "Non maitrisé"
}
if (facMoteurDiesel && facMoteurDiesel.innerText != resultrMoteurDiesel.innerText) {
    synthMoteurDiesel.innerHTML = "Non maitrisé"
}
if (facMoteurElec && facMoteurElec.innerText == "Maitrisé" && (resultrMoteurElec.innerText == "Maitrisé")) {
    synthMoteurElec.innerHTML = "Maitrisé"
}
if (facMoteurElec && facMoteurElec.innerText == "Non maitrisé" && (resultrMoteurElec.innerText == "Non maitrisé")) {
    synthMoteurElec.innerHTML = "Non maitrisé"
}
if (facMoteurElec && facMoteurElec.innerText != resultrMoteurElec.innerText) {
    synthMoteurElec.innerHTML = "Non maitrisé"
}
if (facMoteurEssence && facMoteurEssence.innerText == "Maitrisé" && (resultrMoteurEssence.innerText == "Maitrisé")) {
    synthMoteurEssence.innerHTML = "Maitrisé"
}
if (facMoteurEssence && facMoteurEssence.innerText == "Non maitrisé" && (resultrMoteurEssence.innerText ==
        "Non maitrisé")) {
    synthMoteurEssence.innerHTML = "Non maitrisé"
}
if (facMoteurEssence && facMoteurEssence.innerText != resultrMoteurEssence.innerText) {
    synthMoteurEssence.innerHTML = "Non maitrisé"
}
if (facMultiplexage && facMultiplexage.innerText == "Maitrisé" && (resultrMultiplexage.innerText == "Maitrisé")) {
    synthMultiplexage.innerHTML = "Maitrisé"
}
if (facMultiplexage && facMultiplexage.innerText == "Non maitrisé" && (resultrMultiplexage.innerText ==
        "Non maitrisé")) {
    synthMultiplexage.innerHTML = "Non maitrisé"
}
if (facMultiplexage && facMultiplexage.innerText != resultrMultiplexage.innerText) {
    synthMultiplexage.innerHTML = "Non maitrisé"
}
if (facSuspensionLame && facSuspensionLame.innerText == "Maitrisé" && (resultrSuspensionLame.innerText == "Maitrisé")) {
    synthSuspension.innerHTML = "Maitrisé"
}
if (facSuspensionLame && facSuspensionLame.innerText == "Non maitrisé" && (resultrSuspensionLame.innerText ==
        "Non maitrisé")) {
    synthSuspensionLame.innerHTML = "Non maitrisé"
}
if (facSuspensionLame && facSuspensionLame.innerText != resultrSuspensionLame.innerText) {
    synthSuspensionLame.innerHTML = "Non maitrisé"
}
if (facSuspensionRessort && facSuspensionRessort.innerText == "Maitrisé" && (resultrSuspensionRessort.innerText ==
        "Maitrisé")) {
    synthSuspensionRessort.innerHTML = "Maitrisé"
}
if (facSuspensionRessort && facSuspensionRessort.innerText == "Non maitrisé" && (resultrSuspensionRessort.innerText ==
        "Non maitrisé")) {
    synthSuspensionRessort.innerHTML = "Non maitrisé"
}
if (facSuspensionRessort && facSuspensionRessort.innerText != resultrSuspensionRessort.innerText) {
    synthSuspensionRessort.innerHTML = "Non maitrisé"
}
if (facSuspensionPneumatique && facSuspensionPneumatique.innerText == "Maitrisé" && (resultrSuspensionPneumatique
        .innerText ==
        "Maitrisé")) {
    synthSuspensionPneumatique.innerHTML = "Maitrisé"
}
if (facSuspensionPneumatique && facSuspensionPneumatique.innerText == "Non maitrisé" && (resultrSuspensionPneumatique
        .innerText ==
        "Non maitrisé")) {
    synthSuspensionPneumatique.innerHTML = "Non maitrisé"
}
if (facSuspensionPneumatique && facSuspensionPneumatique.innerText != resultrSuspensionPneumatique.innerText) {
    synthSuspensionPneumatique.innerHTML = "Non maitrisé"
}
if (facPneu && facPneu.innerText == "Maitrisé" && (resultrPneu.innerText == "Maitrisé")) {
    synthPneu.innerHTML = "Maitrisé"
}
if (facPneu && facPneu.innerText == "Non maitrisé" && (resultrPneu.innerText ==
        "Non maitrisé")) {
    synthPneu.innerHTML = "Non maitrisé"
}
if (facPneu && facPneu.innerText != resultrPneu.innerText) {
    synthPneu.innerHTML = "Non maitrisé"
}
if (facPont && facPont.innerText == "Maitrisé" && (resultrPont.innerText == "Maitrisé")) {
    synthPont.innerHTML = "Maitrisé"
}
if (facPont && facPont.innerText == "Non maitrisé" && (resultrPont.innerText ==
        "Non maitrisé")) {
    synthPont.innerHTML = "Non maitrisé"
}
if (facPont && facPont.innerText != resultrPont.innerText) {
    synthPont.innerHTML = "Non maitrisé"
}
if (facReducteur && facReducteur.innerText == "Maitrisé" && (resultrReducteur.innerText == "Maitrisé")) {
    synthReducteur.innerHTML = "Maitrisé"
}
if (facReducteur && facReducteur.innerText == "Non maitrisé" && (resultrReducteur.innerText ==
        "Non maitrisé")) {
    synthReducteur.innerHTML = "Non maitrisé"
}
if (facReducteur && facReducteur.innerText != resultrReducteur.innerText) {
    synthReducteur.innerHTML = "Non maitrisé"
}
if (decisionSavoir.innerHTML == "Maitrisé" && (decisionSavoirFaire.innerHTML == "Maitrisé")) {
    synthese.innerHTML = "Maitrisé"
}
if (decisionSavoir.innerHTML == "Non maitrisé" && (decisionSavoirFaire.innerHTML == "Non maitrisé")) {
    synthese.innerHTML = "Non maitrisé"
}
if (decisionSavoir.innerHTML != decisionSavoirFaire.innerHTML) {
    synthese.innerHTML = "Non maitrisé"
}
</script>
<?php } ?>