<?php
session_start();
include_once "language.php";
include_once "score_decla.php"; // Inclusion du fichier pour les scores déclaratifs
include_once "score_fact.php";  // Inclusion du fichier pour les scores factuels
include_once "userFilters.php"; // Inclusion du fichier contenant les fonctions de filtrage

use MongoDB\Client;

if (!isset($_SESSION["profile"])) {
    header("Location: ../");
    exit();
} else {

   // Récupérer les paramètres depuis l'URL
   $selectedLevel = isset($_GET['level']) ? $_GET['level'] : 'Junior';
   $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;
   $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null;
   $selectedUser = isset($_GET['user']) ? $_GET['user'] : null;

   // Créer une connexion
   $conn = new Client("mongodb://localhost:27017");
   $academy = $conn->academy;

   // Connexion à la collection des questions
   $questionsCollection = $academy->questions;

   // Récupérer les questions déclaratives actives pour le niveau sélectionné
   $questionDeclaCursor = $questionsCollection->find([
       '$and' => [
           ["type" => "Declarative"],
           ["level" => $selectedLevel],
           ["active" => true]
       ],
   ]);

   $questionDecla = iterator_to_array($questionDeclaCursor);

   $questionDeclaCursorFact = $questionsCollection->find([
        '$and' => [
            ["type" => "Factuelle"],
            ["level" => $selectedLevel],
            ["active" => true]
        ],
    ]);

    $questionDeclaF = iterator_to_array($questionDeclaCursorFact);

   // Récupérer toutes les questions pour créer une liste d'ID de questions
   $allQuestionIds = [];
   foreach ($questionDecla as $question) {
       $allQuestionIds[] = (string)$question['_id'];
   }

   $allQuestionIdsF = [];
   foreach ($questionDeclaF as $questionF) {
       $allQuestionIdsF[] = (string)$questionF['_id'];
   }

   // Récupérer le profil utilisateur de la session
   $profile = $_SESSION['profile'];
   $userCountry = isset($_SESSION['country']) ? $_SESSION['country'] : null;

   // Filtrer pour obtenir uniquement les techniciens selon le profil de l'utilisateur
   $technicians = filterUsersByProfile($academy, $profile, $selectedCountry, $selectedLevel, $selectedAgency);
   $techniciansF = filterUsersByProfile($academy, $profile, $selectedCountry, $selectedLevel, $selectedAgency);

   // Définir le titre en fonction du niveau sélectionné
   $taux_de_couverture = "";
   switch ($selectedLevel) {
       case 'Junior':
           $taux_de_couverture = $taux_de_couverture_ju;
           break;
       case 'Senior':
           $taux_de_couverture = $taux_de_couverture_se;
           break;
       case 'Expert':
           $taux_de_couverture = $taux_de_couverture_ex;
           break;
       default:
           $taux_de_couverture = $taux_de_couverture_ju;
           break;
   }

   // Connexion aux collections nécessaires
   $resultsCollection = $academy->results;

   // Récupérer les résultats validés par technicien et par question
   $tableauResultats = \ScoreDecla\getTechnicianResults($selectedLevel);
   $tableauResultatsF = getTechnicianResults3($selectedLevel);

   $agencies = [
       "Burkina Faso" => ["Ouaga"],
       "Cameroun" => ["Bafoussam","Bertoua", "Douala", "Garoua", "Ngaoundere", "Yaoundé"],
       "Cote d'Ivoire" => ["Vridi - Equip"],
       "Gabon" => ["Libreville"],
       "Mali" => ["Bamako"],
       "RCA" => ["Bangui"],
       "RDC" => ["Kinshasa", "Kolwezi", "Lubumbashi"],
       "Senegal" => ["Dakar"],
       // Ajoutez d'autres pays et agences ici
   ];
   $countries = array_keys($agencies);  // Extraction des pays

   $levels = ['Junior', 'Senior', 'Expert'];

   // Initialiser les tableaux pour stocker les informations
   $technicianPercentagesByLevel = [];
   $technicianCountsByLevel = [];
   $totalTechniciansByLevel = [];

   $technicianPercentagesByLevelF = [];
   $technicianCountsByLevelF = [];
   $totalTechniciansByLevelF = [];

   // Boucles pour chaque niveau (Questions Déclaratives)
   foreach ($levels as $level) {
       // Récupérer les techniciens par niveau
       $technicians = filterUsersByProfile($academy, "Directeur Groupe", null, $level, null);
       $totalTechniciansByLevel[$level] = count($technicians);
       $tableauResultats = \ScoreDecla\getTechnicianResults($level);

       $totalPercentage = 0;
       $count = 0;

       // Calculer les pourcentages de maîtrise
       foreach ($technicians as $technician) {
           $techId = (string)$technician['_id'];
           if (isset($tableauResultats[$techId])) {
               $totalPercentage += $tableauResultats[$techId];
               $count++;
           }
       }

       $technicianPercentagesByLevel[$level] = $count > 0 ? ($totalPercentage / $count) : 0;
       $technicianCountsByLevel[$level] = $count;
   }

   // Calculer les moyennes par niveau (Questions Déclaratives)
   $averageMasteryByLevel = [];
   foreach ($levels as $level) {
       $averageMasteryByLevel[$level] = round($technicianPercentagesByLevel[$level]);
   }

   // Calculer les totaux pour 'Total' (Questions Déclaratives)
   $totalTechnicians = array_sum($totalTechniciansByLevel);
   $techniciansWhoTookTest = array_sum($technicianCountsByLevel);
   $totalPercentage = array_sum($averageMasteryByLevel);

   $totalTechniciansByLevel['Total'] = $totalTechnicians;
   $technicianCountsByLevel['Total'] = $techniciansWhoTookTest;
   $averageMasteryByLevel['Total'] = $techniciansWhoTookTest > 0 ? round($totalPercentage / count($levels)) : 0;

   // Boucles pour chaque niveau (Questions Factuelles)
   foreach ($levels as $levelF) {
       // Récupérer les techniciens par niveau
       $techniciansF = filterUsersByProfile($academy, $profile, $selectedCountry, $levelF, $selectedAgency);
       $totalTechniciansByLevelF[$levelF] = count($techniciansF);
       $tableauResultatsF = getTechnicianResults3($levelF);

       $totalPercentageF = 0;
       $countF = 0;

       // Calculer les pourcentages de maîtrise
       foreach ($techniciansF as $technicianF) {
           $techIdF = (string)$technicianF['_id'];
           if (isset($tableauResultatsF[$techIdF])) {
               $totalPercentageF += $tableauResultatsF[$techIdF];
               $countF++;
           }
       }

       $technicianPercentagesByLevelF[$levelF] = $countF > 0 ? ($totalPercentageF / $countF) : 0;
       $technicianCountsByLevelF[$levelF] = $countF;
   }

   // Calculer les moyennes par niveau (Questions Factuelles)
   $averageMasteryByLevelF = [];
   foreach ($levels as $levelF) {
       $averageMasteryByLevelF[$levelF] = round($technicianPercentagesByLevelF[$levelF]);
   }

   // Calculer les totaux pour 'Total' (Questions Factuelles)
   $totalTechniciansF = array_sum($totalTechniciansByLevelF);
   $techniciansWhoTookTestF = array_sum($technicianCountsByLevelF);
   $totalPercentageF = array_sum($averageMasteryByLevelF);

   $totalTechniciansByLevelF['Total'] = $totalTechniciansF;
   $technicianCountsByLevelF['Total'] = $techniciansWhoTookTestF;
   $averageMasteryByLevelF['Total'] = $techniciansWhoTookTestF > 0 ? round($totalPercentageF / count($levels)) : 0;

   // Passer les données au JavaScript
   ?>
   <?php include "./partials/header.php"; ?>
<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <!--begin::Title-->
            <?php if ( $_SESSION["profile"] == "Super Admin") { ?>
                <h1 class="text-dark fw-bold my-1 fs-2">
                    <?php echo $etat_avanacement_qcm_agences ?> 
                </h1>
            <?php } else { ?>

            <?php } ?>
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
            <!--end::Layout Builder Notice-->
            <!--begin::Row-->
            <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    <!--begin::Toolbar-->
                    <div class="toolbar" id="kt_toolbar" style="margin-bottom: -50px">
                        <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                            <!--begin::Info-->
                            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                                <!--begin::Title-->
                                <!--end::Title-->
                            </div>
                            <!--end::Info-->
                        </div>
                    </div>
                    <!--end::Toolbar-->
                <!--begin::Title-->
                <div style="margin-top: 55px; margin-bottom : 25px">
                    <div>
                        <h6 class="text-dark fw-bold my-1 fs-2">
                            <?php echo $mesure_tp ?>
                        </h6>
                    </div>
                </div>
                <!--end::Title-->
                <!-- begin::Row -->
                <div>
                    <div id="chartTP" class="row">
                        <!-- Dynamic cards will be appended here -->
                    </div>
                    <div style="display: flex; justify-content: center; margin-top: -30px; transform: scale(0.75);">
                        <fieldset style="display: flex; gap: 20px;">
                            <!-- Group 1 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c1' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_0_60 ?></h4>
                            </div>

                            <!-- Group 2 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c2' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_60_80 ?></h4>
                            </div>

                            <!-- Group 3 -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <canvas id='c3' width="75" height="37.5"></canvas>
                                <h4 style="opacity: 0.60; margin-top: 30px;"><?php echo $result_entre_80_100 ?></h4>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <!-- end::Row -->
                <!--begin::Title-->
                <div style="margin-top: 55px; margin-bottom : 25px">
                    <div>
                        <h6 class="text-dark fw-bold my-1 fs-2">
                            <?php echo $mesure_tc ?>
                        </h6>
                    </div>
                </div>
                <!--end::Title-->
                <!-- begin::Row -->
                <div>
                    <div id="chartTC" class="row">
                        <!-- Dynamic cards will be appended here -->
                    </div>
                </div>
                <!-- endr::Row -->
                <!-- Dropdown Toggle Button -->
                <div class="dropdown-container">
                    
                    
 
                </div>
                <!-- Dropdown Toggle Button -->
            </div>
            <!--end:Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    </div>
    <!--end:Row-->

   <!-- Vos scripts JavaScript -->
   <script>
       // Définir les données passées depuis PHP
       var tpMasteryData = <?php echo json_encode($averageMasteryByLevel); ?>;
       var totalTechniciansByLevel = <?php echo json_encode($totalTechniciansByLevel); ?>;
       var technicianCountsByLevel = <?php echo json_encode($technicianCountsByLevel); ?>;

       var factMasteryData = <?php echo json_encode($averageMasteryByLevelF); ?>;
       var totalTechniciansByLevelF = <?php echo json_encode($totalTechniciansByLevelF); ?>;
       var technicianCountsByLevelF = <?php echo json_encode($technicianCountsByLevelF); ?>;
   </script>

   <!-- Inclure vos fichiers JavaScript nécessaires, par exemple Chart.js -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <!-- Votre code JavaScript pour générer les graphiques -->
   <script>
       document.addEventListener('DOMContentLoaded', function() {
           // Fonction pour déterminer la couleur en fonction du pourcentage
           const getColorForCompletion = (percentage) => {
               if (percentage >= 81) return '#6CF95D'; // Vert
               if (percentage >= 61) return '#FAF75A'; // Jaune
               return '#FB9258'; // Orange
           };

           // Fonction pour déterminer le fond du graphique
           const getBackgroundColor = (percentage) => {
               if (percentage === 0) return ['#FFFFFF']; // Tout blanc si 0%
               return [
                   getColorForCompletion(percentage), // Couleur pour la partie complétée
                   '#DCDCDC' // Couleur grise pour la partie restante
               ];
           };

           // Données pour les questions déclaratives
           const chartDataM = [
               {
                   title: 'Résultat de ' + technicianCountsByLevel['Junior'] + '/' + totalTechniciansByLevel['Junior'] + ' Niveau Junior',
                   total: 100,
                   completed: tpMasteryData.Junior,
                   data: [tpMasteryData.Junior, 100 - tpMasteryData.Junior],
                   labels: [`${tpMasteryData.Junior}% des tâches pro acquises`, `${100 - tpMasteryData.Junior}% des tâches pro à acquérir`],
                   backgroundColor: getBackgroundColor(tpMasteryData.Junior)
               },
               {
                   title: 'Résultat de ' + technicianCountsByLevel['Senior'] + '/' + totalTechniciansByLevel['Senior'] + ' Niveau Senior',
                   total: 100,
                   completed: tpMasteryData.Senior,
                   data: [tpMasteryData.Senior, 100 - tpMasteryData.Senior],
                   labels: [`${tpMasteryData.Senior}% des tâches pro acquises`, `${100 - tpMasteryData.Senior}% des tâches pro à acquérir`],
                   backgroundColor: getBackgroundColor(tpMasteryData.Senior)
               },
               {
                   title: 'Résultat de ' + technicianCountsByLevel['Expert'] + '/' + totalTechniciansByLevel['Expert'] + ' Niveau Expert',
                   total: 100,
                   completed: tpMasteryData.Expert,
                   data: [tpMasteryData.Expert, 100 - tpMasteryData.Expert],
                   labels: [`${tpMasteryData.Expert}% des tâches pro acquises`, `${100 - tpMasteryData.Expert}% des tâches pro à acquérir`],
                   backgroundColor: getBackgroundColor(tpMasteryData.Expert)
               }
           ];

           // Calcul du total
           const validData = chartDataM.filter(chart => chart.completed > 0);
           const averageCompleted = validData.length > 0 ?
               Math.round(validData.reduce((acc, chart) => acc + chart.completed, 0) / validData.length) :
               0;
           const averageData = [averageCompleted, 100 - averageCompleted];

           const totalColor = getColorForCompletion(averageCompleted);
           const totalBackgroundColor = getBackgroundColor(averageCompleted);

           chartDataM.push({
               title: 'Résultat de ' + technicianCountsByLevel['Total'] + '/' + totalTechniciansByLevel['Total'] + ' au Total',
               total: 100,
               completed: averageCompleted,
               data: averageData,
               labels: [`${averageCompleted}% des tâches pro acquises`, `${100 - averageCompleted}% des tâches pro à acquérir`],
               backgroundColor: totalBackgroundColor
           });

           const containerM = document.getElementById('chartTP');

           // Générer les graphiques
           chartDataM.forEach((data, index) => {
               const cardHtml = `
                   <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                       <div class="card h-100">
                           <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                               <canvas id="doughnutChart${index}" width="200" height="200"></canvas>
                               <h5 class="mt-2">${data.title}</h5>
                           </div>
                       </div>
                   </div>
               `;

               containerM.insertAdjacentHTML('beforeend', cardHtml);

               const ctx = document.getElementById(`doughnutChart${index}`).getContext('2d');

               new Chart(ctx, {
                   type: 'doughnut',
                   data: {
                       labels: data.labels,
                       datasets: [{
                           label: 'Data',
                           data: data.data,
                           backgroundColor: data.backgroundColor,
                           borderWidth: 0
                       }]
                   },
                   options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            datalabels: {
                                formatter: (value, ctx) => {
                                    let sum = ctx.chart.data.datasets[0].data
                                        .reduce((a, b) => a + b, 0);
                                    let percentage = Math.round((value / sum) * 100); // Round up to the nearest whole number
                                    return percentage + '%';
                                },
                                color: '#fff',
                                display: true,
                                anchor: 'center',
                                align: 'center',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let sum = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = Math.round((tooltipItem.raw / 100) * 100);
                                        return `Tâches Pro acquises: ${percentage}%`;
                                    }
                                }
                            }
                        }
                    }

               });
           });

            let canvas1 = document.getElementById('c1');
            let ctx1 = canvas1.getContext('2d');
            ctx1.fillStyle = '#f9945e'; //Nuance de bleu
            ctx1.fillRect(50, 25, 200, 100);
            
            let canvas2 = document.getElementById('c2');
            let ctx2 = canvas2.getContext('2d');
            ctx2.fillStyle = '#f9f75e'; //Nuance de bleu
            ctx2.fillRect(50, 25, 200, 100);
            
            let canvas3 = document.getElementById('c3');
            let ctx3 = canvas3.getContext('2d');
            ctx3.fillStyle = '#6cf95e'; //Nuance de bleu
            ctx3.fillRect(50, 25, 200, 100);


           // Répétez le même processus pour les questions factuelles (chartTest)
           const chartDataF = [
               {
                   title: 'Résultat de ' + technicianCountsByLevelF['Junior'] + '/' + totalTechniciansByLevelF['Junior'] + ' Niveau Junior',
                   total: 100,
                   completed: factMasteryData.Junior,
                   data: [factMasteryData.Junior, 100 - factMasteryData.Junior],
                   labels: [`${factMasteryData.Junior}% des connaissances acquises`, `${100 - factMasteryData.Junior}% des connaissances à acquérir`],
                   backgroundColor: getBackgroundColor(factMasteryData.Junior)
               },
               {
                   title: 'Résultat de ' + technicianCountsByLevelF['Senior'] + '/' + totalTechniciansByLevelF['Senior'] + ' Niveau Senior',
                   total: 100,
                   completed: factMasteryData.Senior,
                   data: [factMasteryData.Senior, 100 - factMasteryData.Senior],
                   labels: [`${factMasteryData.Senior}% des connaissances acquises`, `${100 - factMasteryData.Senior}% des connaissances à acquérir`],
                   backgroundColor: getBackgroundColor(factMasteryData.Senior)
               },
               {
                   title: 'Résultat de ' + technicianCountsByLevelF['Expert'] + '/' + totalTechniciansByLevelF['Expert'] + ' Niveau Expert',
                   total: 100,
                   completed: factMasteryData.Expert,
                   data: [factMasteryData.Expert, 100 - factMasteryData.Expert],
                   labels: [`${factMasteryData.Expert}% des connaissances acquises`, `${100 - factMasteryData.Expert}% des connaissances à acquérir`],
                   backgroundColor: getBackgroundColor(factMasteryData.Expert)
               }
           ];

           // Calcul du total pour les questions factuelles
           const validDataF = chartDataF.filter(chart => chart.completed > 0);
           const averageCompletedF = validDataF.length > 0 ?
               Math.round(validDataF.reduce((acc, chart) => acc + chart.completed, 0) / validDataF.length) :
               0;
           const averageDataF = [averageCompletedF, 100 - averageCompletedF];

           const totalColorF = getColorForCompletion(averageCompletedF);
           const totalBackgroundColorF = getBackgroundColor(averageCompletedF);

           chartDataF.push({
               title: 'Résultat de ' + technicianCountsByLevelF['Total'] + '/' + totalTechniciansByLevelF['Total'] + ' au Total',
               total: 100,
               completed: averageCompletedF,
               data: averageDataF,
               labels: [`${averageCompletedF}% des connaissances acquises`, `${100 - averageCompletedF}% des connaissances à acquérir`],
               backgroundColor: totalBackgroundColorF
           });

           const containerF = document.getElementById('chartTC');

           // Générer les graphiques pour les questions factuelles
           chartDataF.forEach((data, index) => {
               const cardHtml = `
                   <div class="col-md-6 col-lg-3 col-xl-2.5 mb-4">
                       <div class="card h-100">
                           <div class="card-body d-flex justify-content-center text-center flex-column p-4">
                               <canvas id="doughnutFactChart${index}" width="200" height="200"></canvas>
                               <h5 class="mt-2">${data.title}</h5>
                           </div>
                       </div>
                   </div>
               `;

               containerF.insertAdjacentHTML('beforeend', cardHtml);

               const ctx = document.getElementById(`doughnutFactChart${index}`).getContext('2d');

               new Chart(ctx, {
                   type: 'doughnut',
                   data: {
                       labels: data.labels,
                       datasets: [{
                           label: 'Data',
                           data: data.data,
                           backgroundColor: data.backgroundColor,
                           borderWidth: 0
                       }]
                   },
                   options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            datalabels: {
                                formatter: (value, ctx) => {
                                    let sum = ctx.chart.data.datasets[0].data
                                        .reduce((a, b) => a + b, 0);
                                    let percentage = Math.round((value / sum) * 100); // Round up to the nearest whole number
                                    return percentage + '%';
                                },
                                color: '#fff',
                                display: true,
                                anchor: 'center',
                                align: 'center',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let sum = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = Math.round((tooltipItem.raw / 100) * 100);
                                        return `Connaissances acquises: ${percentage}%`;
                                    }
                                }
                            }
                        }
                    }
               });
           });
       });
   </script>

   <?php include "./partials/footer.php"; ?>
   <?php } ?>
