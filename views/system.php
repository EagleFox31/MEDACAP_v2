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
    $questions = $academy->questions;
    $results = $academy->results;

    $user = $_GET['user'];
    $level = $_GET['level'];
    $speciality = $_GET['speciality'];

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
                    Groupe Fonctionnel:
                    <?php echo $speciality ?>
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
                                        tabindex="0" aria-controls="kt_customers_table" colspan="6"
                                        aria-label="Email: activate to sort column ascending"
                                        style="width: 155.266px; font-size: 20px; ">
                                        Résultats de la mesure des savoirs
                                        et savoirs-faire (Compétences)</th>
                                <tr></tr>
                                <th class="min-w-400px sorting  bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="2"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Test des savoirs (Factuel) </th>
                                <th class="min-w-800px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table" colspan="4"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Mesure des savoirs-faire (Déclaratif)</th>
                                <tr></tr>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Questions</th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Résultats</th>
                                <th class="min-w-125px sorting bg-primary text-white text-center table-light fw-bold text-uppercase gs-0"
                                    tabindex="0" aria-controls="kt_customers_table"
                                    aria-label="Email: activate to sort column ascending" style="width: 155.266px;">
                                    Questions</th>
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
                                <tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600" id="table">
                                <?php
                                    $groupeFac = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => $speciality],
                                            ['type' => 'Factuel']
                                        ]
                                    ]);
                                    $groupeDecla = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['level' => $level],
                                            ['speciality' => $speciality],
                                            ['type' => 'Declaratif']
                                        ]
                                    ]);
                                    $groupeMa = $results->findOne([
                                        '$and' => [
                                            ['user' => new MongoDB\BSON\ObjectId($user)],
                                            ['manager' => new MongoDB\BSON\ObjectId($technician->manager)],
                                            ['level' => $level],
                                            ['speciality' => $speciality]
                                        ]
                                    ]);
                                ?>
                                <?php if ($groupeFac && $groupeDecla && $groupeMa) { ?>
                                <?php
                                    for ($i = 0; $i < count($groupeFac->questions); $i++) {
                                        $questionFac = $questions->findOne(['_id' => new MongoDB\BSON\ObjectId($groupeFac->questions[$i])]);
                                        $questionDecla = $questions->findOne(['_id' => new MongoDB\BSON\ObjectId($groupeDecla->questions[$i])]);
                                ?>
                                <tr class="odd" style="background-color: #a3f1ff;">
                                    <td class="text-center">
                                        <?php echo $questionFac->label ?>
                                    </td>
                                    <td class="text-center" name="savoir" id="sGroupe">
                                        <?php echo $groupeFac->answers[$i] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $questionDecla->label ?>
                                    </td>
                                    <td class="text-center" name="n">
                                        <?php echo $groupeDecla->answers[$i] ?>
                                    </td>
                                    <td class="text-center" name="n1">
                                        <?php echo $groupeDecla->answers[$i] ?>
                                    </td>
                                    <?php if ($groupeDecla->answers[$i] == "Oui" && $groupeMa->answers[$i] == "Oui") { ?>
                                    <td class="text-center" name="savoirs-faire" id="sfGroupe">
                                        Maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($groupeDecla->answers[$i] == "Non" && $groupeMa->answers[$i] == "Non") { ?>
                                    <td class="text-center" name="savoirs-faire" id="sfGroupe">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
                                    <?php if ($groupeDecla->answers[$i] != $groupeMa->answers[$i]) { ?>
                                    <td class="text-center" name="savoirs-faire" id="sfGroupe">
                                        Non maitrisé
                                    </td>
                                    <?php } ?>
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