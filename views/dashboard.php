<?php
session_start();

if (!isset($_SESSION["profile"])) {
    header("Location: ./index.php");
    exit();
} else {

    require_once "../vendor/autoload.php";

    // Create connection
    $conn = new MongoDB\Client("mongodb://localhost:27017");

    // Connecting in database
    $academy = $conn->academy;

    // Connecting in collections
    $users = $academy->users;
    $quizzes = $academy->quizzes;
    $vehicles = $academy->vehicles;
    $tests = $academy->tests;
    $exams = $academy->exams;
    $results = $academy->results;
    $allocations = $academy->allocations;

    $countUser = $users->find(["profile" => "Technicien"])->toArray();
    $countUsers = count($countUser);
    $countManager = $users->find(["profile" => "Manager"])->toArray();
    $countManagers = count($countManager);
    $countAdmin = $users->find(["profile" => "Admin"])->toArray();
    $countAdmins = count($countAdmin);
    $countVehicle = $vehicles->find()->toArray();
    $countVehicles = count($countVehicle);
    $totalSavoir = $allocations->find(["type" => "Factuel"])->toArray();
    if (count($totalSavoir) != 0) {
        $totalSavoirs = count($totalSavoir);
    } else {
        $totalSavoirs = 1;
    }
    $totalSavoirFaire = $allocations->find(["type" => "Declaratif"])->toArray();
    if (count($totalSavoirFaire) != 0) {
        $totalSavoirFaires = count($totalSavoirFaire);
    } else {
        $totalSavoirFaires = 1;
    }
    $countSavoir = $allocations
        ->find([
            '$and' => [
                [
                    "type" => "Factuel",
                    "active" => true,
                ],
            ],
        ])
        ->toArray();
    $countSavoirs = count($countSavoir);
    $percentageSavoir = ($countSavoirs * 100) / $totalSavoirs;
    $countMaSavFai = $allocations
        ->find([
            '$and' => [
                [
                    "type" => "Declaratif",
                    "active" => true,
                ],
            ],
        ])
        ->toArray();
    $countMaSavFais = count($countMaSavFai);
    $percentageMaSavoirFaire = ($countMaSavFais * 100) / $totalSavoirFaires;
    $countTechSavFai = $allocations
        ->find([
            '$and' => [
                [
                    "type" => "Declaratif",
                    "activeManager" => true,
                ],
            ],
        ])
        ->toArray();
    $countTechSavFais = count($countTechSavFai);
    $percentageTechSavoirFaire = ($countTechSavFais * 100) / $totalSavoirFaires;

    $resultFac = $results
        ->aggregate([
            [
                '$match' => [
                    '$and' => [
                        [
                            "typeR" => "Technicien",
                            "type" => "Factuel",
                        ],
                    ],
                ],
            ],
            [
                '$group' => [
                    "_id" => '$level',
                    "total" => ['$sum' => '$total'],
                    "score" => ['$sum' => '$score'],
                ],
            ],
            [
                '$project' => [
                    "_id" => 0,
                    "level" => '$_id',
                    "percentage" => [
                        '$multiply' => [
                            ['$divide' => ['$score', '$total']],
                            100,
                        ],
                    ],
                ],
            ],
        ])
        ->toArray();
    ?>
<?php include "./partials/header.php"; ?>
<!--begin::Title-->
<title>Tableau de Bord | CFAO Mobility Academy</title>
<!--end::Title-->
<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <?php if ($_SESSION["profile"] == "Manager") { ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Introduction
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
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <div class="card-body d-flex align-items-center p-5 p-lg-8">
                    <!--begin::Icon-->
                    <div
                        class="d-flex h-50px w-50px h-lg-80px w-lg-80px flex-shrink-0 flex-center position-relative align-self-start align-self-lg-center mt-3 mt-lg-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="text-primary fw-bolder h-75px w-75px h-lg-100px w-lg-100px position-absolute opacity-5">
                            <path fill="currentColor"
                                d="M10.2,21.23,4.91,18.17a3.58,3.58,0,0,1-1.8-3.11V8.94a3.58,3.58,0,0,1,1.8-3.11L10.2,2.77a3.62,3.62,0,0,1,3.6,0l5.29,3.06a3.58,3.58,0,0,1,1.8,3.11v6.12a3.58,3.58,0,0,1-1.8,3.11L13.8,21.23A3.62,3.62,0,0,1,10.2,21.23Z" />
                        </svg>
                        <i class="ki-duotone ki-user fs-2x fs-lg-3x text-primary fw-bolder position-absolute"><span
                                class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Description-->
                    <div class="ms-6">
                        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                            Bienvenue sur votre espace de developpement des
                            compétences de CFAO Mobility Academy Panafrican. <br><br>
                            CFAO souhaite créer et adapter un parcours de
                            développement individuel des compétences pour chacun des techniciens,
                            afin de leurs proposer des formations correspondant à vos besoins
                            et ceux de l'entreprise.<br><br>
                            Pour élaborer ce parcours, nous avons besoin d'identifier les
                            compétences actuelles des techniciens sur les trois niveaux (Junior, Senior et Expert) et
                            nous leurs
                            proposons de repondre
                            aux questionnaires suivants: <br>
                            - Questionnaires sur vos connaissances techniques, <br>
                            - Questionnaires sur la maîtrise de vos tâches professionnelles. <br> <br>
                            Pour s'assurer de la certitude des reponses des techniciens concernant
                            la maitrise des tâches professionnelles, nous vous demandons d'évaluer vos techniciens
                            sur la maitrises des tâches professionnelles.
                        </p>
                    </div>
                    <!--end::Description-->
                </div>
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <!--begin::Illustration-->
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-150px min-h-lg-350px" style="background-image: url(../public/images/IMG-20230627-WA0084.jpg)">
                </div>
                <!--end::Illustration-->
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <?php } ?>
    <?php if ($_SESSION["profile"] == "Technicien") { ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Introduction
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
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <div class="card-body d-flex align-items-center p-5 p-lg-8">
                    <!--begin::Icon-->
                    <div
                        class="d-flex h-50px w-50px h-lg-80px w-lg-80px flex-shrink-0 flex-center position-relative align-self-start align-self-lg-center mt-3 mt-lg-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="text-primary fw-bolder h-75px w-75px h-lg-100px w-lg-100px position-absolute opacity-5">
                            <path fill="currentColor"
                                d="M10.2,21.23,4.91,18.17a3.58,3.58,0,0,1-1.8-3.11V8.94a3.58,3.58,0,0,1,1.8-3.11L10.2,2.77a3.62,3.62,0,0,1,3.6,0l5.29,3.06a3.58,3.58,0,0,1,1.8,3.11v6.12a3.58,3.58,0,0,1-1.8,3.11L13.8,21.23A3.62,3.62,0,0,1,10.2,21.23Z" />
                        </svg>
                        <i class="ki-duotone ki-user fs-2x fs-lg-3x text-primary fw-bolder position-absolute"><span
                                class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Description-->
                    <div class="ms-6">
                        <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                            Bienvenue sur votre espace de developpement des
                            compétences de CFAO Mobility Academy Panafrican. <br><br>
                            CFAO souhaite créer et adapter un parcours de
                            développement individuel des compétences pour chacun des techniciens,
                            afin de vous proposer des formations correspondant à vos besoins
                            et ceux de l'entreprise.<br><br>
                            Pour élaborer ce parcours, nous avons besoin d'identifier vos
                            compétences actuelles sur les trois niveaux (Junior, Senior et Expert) et nous vous
                            proposons de repondre
                            aux questionnaires suivants: <br>
                            - Questionnaires sur vos connaissances théoriques, <br>
                            - Questionnaires sur la maîtrise de vos tâches professionnelles. <br> <br>
                            Merci de repondre intégralement à tous les questionnaires ci-dessous.
                        </p>
                    </div>
                    <!--end::Description-->
                </div>
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class=" container-xxl ">
            <!--begin::Layout Builder Notice-->
            <div class="card mb-10">
                <!--begin::Illustration-->
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-150px min-h-lg-350px" style="background-image: url(../public/images/IMG-20230627-WA0093.jpg)">
                </div>
                <!--end::Illustration-->
            </div>
            <!--end::Layout Builder Notice-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    <?php } ?>
    <?php if (
        $_SESSION["profile"] == "Super Admin" ||
        $_SESSION["profile"] == "Admin"
    ) { ?>
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-2">
                    Tableau de bord
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div class=" container-xxl ">
                <!--begin::Row-->
                <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countUsers; ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Techniciens </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countManagers; ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Managers </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <?php if ($_SESSION["profile"] == "Super Admin") { ?>
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countAdmins; ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Administrateurs
                                </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <?php } ?>
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <div
                                    class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true"
                                        data-kt-countup-value="<?php echo $countVehicles; ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    Véhicules </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end:Row-->
                <!-- begin::Row -->
                <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
                    <!--begin::Container-->
                    <div class=" container-xxl ">
                        <!--begin::Layout Builder Notice-->
                        <div class="card mb-10">
                            <div class="card-body d-flex align-items-center p-5 p-lg-8">
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <!--begin::Header-->
                                    <div class="card-header border-0 pt-5 pb-3">
                                        <!--begin::Heading-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder text-gray-800 fs-2">Taux de réalisation des différents questionnaires</span>
                                        </h3>
                                        <!--end::Heading-->
                                    </div>
                                    <!--end::Header-->
                                    <div>
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                                <!--end::Card body-->
                            </div>
                        </div>
                        <!--end::Layout Builder Notice-->
                    </div>
                    <!--end::Container-->
                </div>
                <!-- end::Row -->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
    <?php } ?>
</div>
<!--end::Content-->
<?php include "./partials/footer.php"; ?>
<?php
}
?>
<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Connaissances Théoriques', 'Connaissances Pratiques (Techniciens)', 'Connaissances Pratiques (Managers)'],
      datasets: [{
        label: 'Pourcentage de questionnaires réalisés',
        data: [<?php echo $percentageSavoir; ?>, <?php echo $percentageTechSavoirFaire; ?>, <?php echo $percentageMaSavoirFaire; ?>],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>