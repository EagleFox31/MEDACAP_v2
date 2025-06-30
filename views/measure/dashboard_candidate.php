<?php
session_start();
include_once "../language.php";

if (!isset($_SESSION["id"])) {
    header("Location: ../../");
    exit();
}

if ($_SESSION["profile"] !== "Candidat") {
    header("Location: dashboard");
    exit();
}

require_once "../../vendor/autoload.php";
$client = new MongoDB\Client("mongodb://localhost:27017");
$academy = $client->academy;
$allocations = $academy->allocations;

$objId = new MongoDB\BSON\ObjectId($_SESSION["id"]);

// Récupérer tous les tests factuels du candidat
$factuels = $allocations->find([
    'user' => $objId,
    'type' => 'Factuel'
]);

// Fonction pour analyser les données par niveau
function getTestsByLevel($allocations, $user, $level) {
    $query = [
        'user' => $user,
        'type' => 'Factuel',
        'level' => $level
    ];
    
    $tests = $allocations->find($query);
    $done = 0;
    $total = 0;
    
    foreach ($tests as $test) {
        $total++;
        if ($test['active'] == true) {
            $done++;
        }
    }
    
    return [
        'done' => $done,
        'total' => $total,
        'pending' => $total - $done
    ];
}

// Analyser les données par niveau
$juniorStats = getTestsByLevel($allocations, $objId, 'Junior');
$seniorStats = getTestsByLevel($allocations, $objId, 'Senior');
$expertStats = getTestsByLevel($allocations, $objId, 'Expert');

// Calculer les totaux
$totalTests = $juniorStats['total'] + $seniorStats['total'] + $expertStats['total'];
$totalDone = $juniorStats['done'] + $seniorStats['done'] + $expertStats['done'];
$totalPending = $totalTests - $totalDone;

// Calculer le pourcentage global
$globalPercentage = $totalTests > 0 ? round(($totalDone * 100) / $totalTests) : 0;

include "./partials/header.php";
?>

<!--begin::Title-->
<title><?php echo $candidate_dashboard_title ?> | CFAO Mobility Academy</title>
<!--end::Title-->

<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bold my-1 fs-1">
                    <?php echo $candidate_dashboard_title ?>
                </h1>
                <!--end::Title-->
                <!--begin::Subtitle-->
                <div class="text-muted fs-6 fw-semibold">
                    <?php echo $candidate_only_factual ?>
                </div>
                <!--end::Subtitle-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Post-->
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div class="container-xxl">
            
            <!--begin::Progress Overview-->
            <div class="row mb-8">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center p-8">
                            <h2 class="text-primary fw-bold mb-4"><?php echo $etat_avancement_qcm ?></h2>
                            <div class="d-flex justify-content-center align-items-center mb-4">
                                <div class="position-relative">
                                    <canvas id="globalProgressChart" width="200" height="200"></canvas>
                                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                                        <div class="fs-2x fw-bold text-primary"><?php echo $globalPercentage ?>%</div>
                                        <div class="fs-6 text-muted"><?php echo $qcm_connaissance_realise ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="fs-2x fw-bold text-success"><?php echo $totalDone ?></div>
                                    <div class="fs-6 text-muted"><?php echo $active ?></div>
                                </div>
                                <div class="col-4">
                                    <div class="fs-2x fw-bold text-warning"><?php echo $totalPending ?></div>
                                    <div class="fs-6 text-muted"><?php echo $desactive ?></div>
                                </div>
                                <div class="col-4">
                                    <div class="fs-2x fw-bold text-primary"><?php echo $totalTests ?></div>
                                    <div class="fs-6 text-muted"><?php echo $nbre_test ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Progress Overview-->

            <!--begin::Stats by Level-->
            <div class="row g-6 g-xl-9 mb-6">
                <!--begin::Junior-->
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <div class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="<?php echo $juniorStats['total'] ?>">
                                </div>
                            </div>
                            <div class="fs-5 fw-bold mb-2 text-primary">
                                <?php echo $nbre_qcm_junior ?>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex justify-content-between">
                                <span class="text-success"><?php echo $active ?>: <?php echo $juniorStats['done'] ?></span>
                                <span class="text-warning"><?php echo $desactive ?>: <?php echo $juniorStats['pending'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Junior-->

                <!--begin::Senior-->
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <div class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="<?php echo $seniorStats['total'] ?>">
                                </div>
                            </div>
                            <div class="fs-5 fw-bold mb-2 text-primary">
                                <?php echo $nbre_qcm_senior ?>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex justify-content-between">
                                <span class="text-success"><?php echo $active ?>: <?php echo $seniorStats['done'] ?></span>
                                <span class="text-warning"><?php echo $desactive ?>: <?php echo $seniorStats['pending'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Senior-->

                <!--begin::Expert-->
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <div class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="<?php echo $expertStats['total'] ?>">
                                </div>
                            </div>
                            <div class="fs-5 fw-bold mb-2 text-primary">
                                <?php echo $nbre_qcm_expert ?>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex justify-content-between">
                                <span class="text-success"><?php echo $active ?>: <?php echo $expertStats['done'] ?></span>
                                <span class="text-warning"><?php echo $desactive ?>: <?php echo $expertStats['pending'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Expert-->
            </div>
            <!--end::Stats by Level-->

            <!--begin::Detailed Progress Charts-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $etat_avancement_qcm ?> - <?php echo $level ?></h3>
                        </div>
                        <div class="card-body">
                            <div id="levelChartsContainer" class="row">
                                <!-- Charts will be dynamically generated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Detailed Progress Charts-->

            <!--begin::Tests Table-->
            <div class="row mt-8">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $Type ?> - <?php echo $nbre_qcm_connaissance ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-bordered gy-5 gs-7">
                                    <thead>
                                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="text-center"><?php echo $Type ?></th>
                                            <th class="text-center"><?php echo $level ?></th>
                                            <th class="text-center"><?php echo $brand ?></th>
                                            <th class="text-center"><?php echo $status ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $factuels->rewind(); // Reset cursor
                                        foreach ($factuels as $test) { 
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge badge-light-primary"><?php echo htmlspecialchars($test['type']); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light-info"><?php echo htmlspecialchars($test['level']); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php echo is_array($test['brand']) ? implode(', ', $test['brand']) : htmlspecialchars($test['brand']); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($test['active']) { ?>
                                                    <span class="badge badge-light-success"><?php echo $active ?></span>
                                                <?php } else { ?>
                                                    <span class="badge badge-light-warning"><?php echo $desactive ?></span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Tests Table-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<?php include "./partials/footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global progress chart
    const globalCtx = document.getElementById('globalProgressChart').getContext('2d');
    new Chart(globalCtx, {
        type: 'doughnut',
        data: {
            labels: ['<?php echo $active ?>', '<?php echo $desactive ?>'],
            datasets: [{
                data: [<?php echo $totalDone ?>, <?php echo $totalPending ?>],
                backgroundColor: ['#50CD89', '#FFC700'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '75%'
        }
    });

    // Level-specific charts
    const levelData = [
        {
            level: '<?php echo $nbre_qcm_junior ?>',
            done: <?php echo $juniorStats['done'] ?>,
            total: <?php echo $juniorStats['total'] ?>,
            pending: <?php echo $juniorStats['pending'] ?>
        },
        {
            level: '<?php echo $nbre_qcm_senior ?>',
            done: <?php echo $seniorStats['done'] ?>,
            total: <?php echo $seniorStats['total'] ?>,
            pending: <?php echo $seniorStats['pending'] ?>
        },
        {
            level: '<?php echo $nbre_qcm_expert ?>',
            done: <?php echo $expertStats['done'] ?>,
            total: <?php echo $expertStats['total'] ?>,
            pending: <?php echo $expertStats['pending'] ?>
        }
    ];

    const container = document.getElementById('levelChartsContainer');

    levelData.forEach((data, index) => {
        if (data.total > 0) {
            const percentage = Math.round((data.done / data.total) * 100);
            
            const cardHtml = `
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center p-6">
                            <h5 class="mb-4">${data.level}</h5>
                            <canvas id="levelChart${index}" width="150" height="150"></canvas>
                            <div class="mt-3">
                                <div class="fs-2x fw-bold text-primary">${percentage}%</div>
                                <div class="text-muted">${data.done}/${data.total} <?php echo $qcm_connaissance_realise ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', cardHtml);
            
            // Create chart
            const ctx = document.getElementById(`levelChart${index}`).getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['<?php echo $active ?>', '<?php echo $desactive ?>'],
                    datasets: [{
                        data: [data.done, data.pending],
                        backgroundColor: ['#4303ec', '#D3D3D3'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>