<!--
Vue du dashboard pour les techniciens
Cette vue est incluse par TechnicianDashboardController
-->

<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder my-1 fs-1">
                    <?php echo $tableau ?? "Tableau de Bord"; ?>
                </h1>
                <h3 class="text-dark fw-bolder my-1 fs-1">
                    <?php echo "Présentation des Plans de Formations" ?>
                </h3>
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
                    <!--begin::Filtres -->
                    <div class="container my-4">
                        <div class="row g-3 align-items-center">
                            <!-- Filtre Level -->
                            <div class="col-md-6">
                                <label for="level-filter" class="form-label d-flex align-items-center">
                                    <i class="bi bi-bar-chart-fill fs-2 me-2 text-warning"></i> Niveau
                                </label>
                                <select id="level-filter" class="form-select">
                                    <option value="all" <?php if (($levelFilter ?? 'all') === 'all') echo 'selected'; ?>>Tous les niveaux</option>
                                    <?php
                                    // Ajuster la liste de niveaux possibles
                                    $lvlsAvailable = [];
                                    if ($techLevel === 'Expert') {
                                        $lvlsAvailable = ['Junior', 'Senior', 'Expert'];
                                    } elseif ($techLevel === 'Senior') {
                                        $lvlsAvailable = ['Junior', 'Senior'];
                                    } else {
                                        $lvlsAvailable = ['Junior'];
                                    }
                                    foreach ($lvlsAvailable as $lvl) {
                                        $selected = ($lvl === ($levelFilter ?? 'all')) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($lvl) . "' $selected>" . htmlspecialchars($lvl) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Filtre Marques -->
                            <div class="col-md-6">
                                <label for="brand-filter" class="form-label d-flex align-items-center">
                                    <i class="bi bi-car-front-fill fs-2 me-2 text-danger"></i> Marques
                                </label>
                                <select id="brand-filter" class="form-select">
                                    <option value="all" <?= (($filterBrand ?? 'all') === 'all') ? 'selected' : '' ?>>Toutes les marques</option>
                                    <?php
                                    foreach ($allBrands as $b) {
                                        $sel = (strcasecmp(($filterBrand ?? 'all'), $b) === 0) ? 'selected' : '';
                                        echo "<option value=\"" . htmlspecialchars($b) . "\" $sel>" . htmlspecialchars($b) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end::Filtres -->

                    <!--begin::Title-->
                    <div style="margin-top: 55px; margin-bottom : 25px">
                        <div>
                            <h6 class="text-dark fw-bold my-1 fs-2">
                                <?php echo sprintf("%02d", count($brandsToShow)) . ' Marques sur lesquelles j\'interviens en Atelier'; ?>
                            </h6>
                        </div>
                    </div>
                    <!--end::Title-->

                    <!-- begin:: Marques du Technicien -->
                    <?php include __DIR__ . "/brand_carousel.php"; ?>
                    <!-- end::Marques du Technicien -->

                    <!--begin::Title-->
                    <div style="margin-top: 55px; margin-bottom : 25px">
                        <div>
                            <h6 class="text-dark fw-bold my-1 fs-2">
                                <?php echo 'Mon Plan de Formation par Marque' ?>
                            </h6>
                        </div>
                    </div>
                    <!--end::Title-->
                    
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <i class="fas fa-book fs-2 text-primary mb-2"></i>
                                <div class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="<?php echo count($numRecommended); ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $recommaded_training ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <i class="fas fa-book-open fs-2 text-success mb-2"></i>
                                <div class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="<?php echo count($numCompleted) ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $apply_training ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-4 col-xl-2.5">
                        <!--begin::Card-->
                        <div class="card h-100 ">
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                <!--begin::Name-->
                                <!--begin::Animation-->
                                <i class="fas fa-calendar-alt fs-2 text-warning mb-2"></i>
                                <div class="fs-lg-1hx fs-2x fw-bold text-gray-800 d-flex justify-content-center text-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="<?php echo $brandHoursMap ?>">
                                    </div>
                                </div>
                                <!--end::Animation-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    <?php echo $training_duration ?> </div>
                                <!--end::Title-->
                                <!--end::Name-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->

                    <!-- begin::Row -->
                    <div class="post fs-6 d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class=" container-xxl ">
                            <!--begin::Layout Builder Notice-->
                            <div class="card mb-10">
                                <div class="card-body d-flex align-items-center">
                                    <!--begin::Card body-->
                                    <div id="chart-container" class="responsive-chart-container">
                                        <canvas id="chartjs-container"></canvas>
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
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des filtres
    var levelFilter = document.getElementById('level-filter');
    var brandFilter = document.getElementById('brand-filter');

    function getParameterByName(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || 'all';
    }

    // Appliquer les filtres lorsque le niveau ou la marque change
    brandFilter.addEventListener('change', applyFilters);
    levelFilter.addEventListener('change', applyFilters);

    function applyFilters() {
        var brand = brandFilter.value;
        var level = levelFilter.value;

        var params = new URLSearchParams();
        params.append('brand', brand);
        params.append('level', level);

        // Recharger la page avec les nouveaux paramètres
        window.location.search = params.toString();
    }

    // Enregistrer le plugin Chart.js Datalabels
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }

    // Initialisation du graphique
    const brandScores = <?php echo json_encode($brandScores); ?>;
    const brandLogos = <?php echo json_encode($brandLogos); ?>;
    const brandFormationsMap = <?php echo json_encode($brandFormationsMap ?? []); ?>;

    // Initialisation du Scatter Chart "Mon Plan de Formation"
    if (document.getElementById('chartjs-container')) {
        const ctx = document.getElementById('chartjs-container').getContext('2d');
        
        // Extraction des données pour le graphique
        const labels = brandScores.map(d => d.x); // Noms des marques
        const dataValues = brandScores.map(d => d.y); // Scores
        const colors = brandScores.map(d => d.fillColor); // Couleurs des cercles
        const urls = brandScores.map(d => d.url || '#'); // URLs pour les clics
        
        // Fonction pour dessiner les logos
        function drawLogos(chart) {
            // Supprimer les anciens conteneurs de logos
            const oldDiv = document.getElementById('chart-container-logo-container');
            if (oldDiv) oldDiv.remove();

            // Créer un conteneur DIV pour les logos
            const logoContainer = document.createElement('div');
            logoContainer.id = 'chart-container-logo-container';
            logoContainer.style.position = 'absolute';
            logoContainer.style.top = '0';
            logoContainer.style.left = '0';
            logoContainer.style.width = '100%';
            logoContainer.style.height = '100%';
            logoContainer.style.pointerEvents = 'none'; // Permettre les événements de souris à travers

            // Obtenir les échelles du graphique
            const xScale = chart.scales.x;
            const chartArea = chart.chartArea;

            // Boucler sur les labels pour placer les logos
            labels.forEach((label, index) => {
                const xPos = xScale.getPixelForValue(index);
                const yPos = chartArea.bottom + 15; // Ajuster selon les besoins

                // Créer l'élément image
                const img = document.createElement('img');
                img.src = brandLogos[label] ? `../../public/images/${brandLogos[label]}` : `../../public/images/default.png`;
                img.style.position = 'absolute';
                img.style.left = (xPos - 25) + 'px'; // Centrer l'image (ajusté pour 50px de largeur)
                img.style.top = yPos + 'px';
                img.style.width = '55px';
                img.style.height = '30px';
                img.onerror = function() {
                    console.error(`Erreur de chargement de l'image : ${img.src}`);
                    img.src = '../../public/images/default.png';
                };

                // Ajouter l'image au conteneur
                logoContainer.appendChild(img);
            });

            // Ajouter le conteneur au parent
            const chartContainer = document.getElementById('chart-container');
            chartContainer.appendChild(logoContainer);
        }
        
        // Définir le plugin pour afficher les logos
        const imagePluginScatter = {
            id: 'imagePluginScatter',
            afterRender: (chart) => drawLogos(chart),
            afterResize: (chart) => {
                const logoContainer = document.getElementById('chart-container-logo-container');
                if (logoContainer) {
                    logoContainer.remove();
                }
                drawLogos(chart);
            }
        };
        
        // Création du graphique
        if (typeof Chart !== 'undefined') {
            const scatterChart = new Chart(ctx, {
                type: 'scatter',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Formations Recommandées : ',
                        data: labels.map((brand, i) => ({
                            x: i,
                            y: dataValues[i]
                        })),
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 2,
                        pointRadius: 50, // Ajuster la taille des points
                        pointHoverRadius: 60,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                const i = ctx.dataIndex;
                                const brand = labels[ctx.dataIndex];
                                const modulesCount = brandFormationsMap[brand] !== undefined ? brandFormationsMap[brand] : '0';
                                const score = dataValues[i] !== null ? dataValues[i] : 'N/A';
                                return ` ${modulesCount} \n Modules \n(${score}%)`;
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const i = context.dataIndex;
                                    const brand = labels[i];
                                    const score = dataValues[i] !== null ? dataValues[i] : 'N/A';
                                    const modulesCount = brandFormationsMap[brand] !== undefined ? brandFormationsMap[brand] : '0';
                                    return [
                                        `Marque: ${brand}`,
                                        `Modules de Formations: ${modulesCount}`,
                                        `Résultat de la mesure: ${score}%`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'category',
                            labels: labels,
                            ticks: {
                                display: false // Masquer les labels textuels
                            }
                        },
                        y: {
                            type: 'linear',
                            min: 0,
                            max: 100
                        }
                    }
                },
                plugins: [imagePluginScatter]
            });
        }
    }
});
</script>