<?php
/**
 * Vue du dashboard manager/directeur
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MEDACAP - <?php echo $tableau; ?></title>
    <?php include '../views/define/partials/header.php'; ?>
    <style>
        .card-brand {
            border: 1px solid #eee;
            border-radius: 5px;
            margin-bottom: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-in-out;
        }
        .card-brand:hover {
            transform: translateY(-5px);
        }
        .brand-logo {
            max-height: 50px;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }
        .brand-stats {
            font-size: 0.9rem;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .circle-stat {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .bg-junior {
            background-color: #17a2b8;
        }
        .bg-senior {
            background-color: #6c757d;
        }
        .bg-expert {
            background-color: #343a40;
        }
        .technician-badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .brand-progress {
            height: 8px;
            margin-bottom: 10px;
        }
        .technician-card {
            margin-bottom: 15px;
            transition: all 0.2s ease;
        }
        .technician-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .brand-pill {
            display: inline-block;
            padding: 3px 8px;
            margin-right: 5px;
            margin-bottom: 5px;
            border-radius: 20px;
            font-size: 0.8rem;
            background-color: #e9ecef;
        }
        .competency-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            margin-right: 3px;
        }
    </style>
</head>
<body>
    <?php include '../views/define/partials/navbar.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><?php echo $tableau; ?></h1>
                    
                    <!-- Filtres -->
                    <div class="filter-section p-3 rounded">
                        <form method="GET" action="" class="row g-3">
                            <!-- Filtre Filiale (pour Directeur Groupe) -->
                            <?php if ($_SESSION['profile'] === 'Directeur Groupe' || $_SESSION['profile'] === 'Super Admin'): ?>
                            <div class="col-auto">
                                <select class="form-select" name="filiale" id="filialeSelect">
                                    <option value="all" <?php echo $selectedFiliale === 'all' ? 'selected' : ''; ?>>Toutes les filiales</option>
                                    <?php 
                                    $clefsFiliales = array_filter(array_keys($fullData), function($cle) {
                                        return $cle !== 'ALL_FILIALES' && $cle !== 'CFR';
                                    });
                                    sort($clefsFiliales);
                                    foreach ($clefsFiliales as $filiale): 
                                    ?>
                                        <option value="<?php echo htmlspecialchars($filiale); ?>" <?php echo $selectedFiliale === $filiale ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($filiale); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <!-- Filtre Agence -->
                            <div class="col-auto">
                                <select class="form-select" name="agence" id="agenceSelect">
                                    <option value="all" <?php echo $selectedAgence === 'all' ? 'selected' : ''; ?>>Toutes les agences</option>
                                    <?php 
                                    if (isset($fullData[$selectedFiliale]['agencies'])) {
                                        $agenceNames = array_keys($fullData[$selectedFiliale]['agencies']);
                                        sort($agenceNames);
                                        foreach ($agenceNames as $agence): 
                                    ?>
                                        <option value="<?php echo htmlspecialchars($agence); ?>" <?php echo $selectedAgence === $agence ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($agence); ?>
                                        </option>
                                    <?php 
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Filtre Manager -->
                            <?php if (in_array($_SESSION['profile'], ['Super Admin', 'Admin', 'Directeur Groupe', 'Directeur Général', 'Directeur Pièce et Service', 'Directeur des Opérations'])): ?>
                            <div class="col-auto">
                                <select class="form-select" name="managerId" id="managerSelect">
                                    <option value="all" <?php echo $managerId === 'all' ? 'selected' : ''; ?>>Tous les managers</option>
                                    <?php foreach ($managersList as $mId => $mName): ?>
                                        <option value="<?php echo htmlspecialchars($mId); ?>" <?php echo $managerId === $mId ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($mName); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <!-- Filtre Niveau -->
                            <div class="col-auto">
                                <select class="form-select" name="level" id="levelSelect">
                                    <?php foreach ($levelsToShow as $lvl): ?>
                                        <option value="<?php echo htmlspecialchars($lvl); ?>" <?php echo $filterLevel === $lvl ? 'selected' : ''; ?>>
                                            <?php echo $lvl === 'all' ? 'Tous les niveaux' : htmlspecialchars($lvl); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Filtre Marque -->
                            <div class="col-auto">
                                <select class="form-select" name="brand" id="brandSelect">
                                    <option value="all" <?php echo $filterBrand === 'all' ? 'selected' : ''; ?>>Toutes les marques</option>
                                    <?php foreach ($teamBrands as $brand): ?>
                                        <option value="<?php echo htmlspecialchars($brand); ?>" <?php echo $filterBrand === $brand ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($brand); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Bouton de filtrage -->
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cartes statistiques -->
                <div class="row mb-4">
                    <!-- Carte 1: Techniciens -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="circle-stat bg-junior">
                                    <?php echo $numTechniciens; ?>
                                </div>
                                <h5 class="card-title mt-3"><?php echo $technicienss; ?></h5>
                                <p class="card-text text-muted"><?php echo $Subsidiary; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 2: Techniciens mesurés -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="circle-stat bg-senior">
                                    <?php echo $doneTest; ?>
                                </div>
                                <h5 class="card-title mt-3"><?php echo $tech_mesure; ?></h5>
                                <p class="card-text text-muted"><?php echo $Subsidiary; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 3: Techniciens avec PIF -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="circle-stat bg-expert">
                                    <?php echo $techWithTraining; ?>
                                </div>
                                <h5 class="card-title mt-3"><?php echo $tech_pif; ?></h5>
                                <p class="card-text text-muted"><?php echo $Subsidiary; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 4: PIF validés -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="circle-stat" style="background-color: #28a745;">
                                    <?php echo $techWithTrainingSelected; ?>
                                </div>
                                <h5 class="card-title mt-3"><?php echo $pif_filiale; ?></h5>
                                <p class="card-text text-muted"><?php echo $Subsidiary; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row mb-4">
                    <!-- Graphique des scores par marque -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Scores par marque</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="brandScoresChart" width="100%" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique des formations -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo $recommaded_training; ?></h5>
                            </div>
                            <div class="card-body">
                                <canvas id="trainingChart" width="100%" height="300"></canvas>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h3><?php echo $numTrainings; ?></h3>
                                        <small class="text-muted"><?php echo $recommaded_training; ?></small>
                                    </div>
                                    <div class="col-6">
                                        <h3><?php echo $numDays; ?></h3>
                                        <small class="text-muted"><?php echo $training_duration; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte des marques -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Marques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($teamBrands as $brand): ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                    <div class="card-brand p-3 text-center">
                                        <?php if (isset($brandLogos[$brand])): ?>
                                            <img src="<?php echo htmlspecialchars($brandLogos[$brand]); ?>" alt="<?php echo htmlspecialchars($brand); ?>" class="brand-logo mb-2">
                                        <?php else: ?>
                                            <div class="brand-logo-placeholder mb-2">
                                                <h5><?php echo htmlspecialchars($brand); ?></h5>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <h6><?php echo htmlspecialchars($brand); ?></h6>
                                        
                                        <?php 
                                        // Récupérer le score moyen pour cette marque
                                        $avgScore = 0;
                                        foreach ($brandScores as $score) {
                                            if ($score['x'] === $brand) {
                                                $avgScore = $score['y'];
                                                break;
                                            }
                                        }
                                        
                                        // Déterminer la couleur selon le score
                                        if ($avgScore >= 80) {
                                            $progressColor = "bg-success";
                                        } elseif ($avgScore >= 60) {
                                            $progressColor = "bg-warning";
                                        } else {
                                            $progressColor = "bg-danger";
                                        }
                                        ?>
                                        
                                        <div class="progress brand-progress">
                                            <div class="progress-bar <?php echo $progressColor; ?>" role="progressbar" style="width: <?php echo $avgScore; ?>%" aria-valuenow="<?php echo $avgScore; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="brand-stats">
                                            <div class="row">
                                                <div class="col-6 text-start">Score</div>
                                                <div class="col-6 text-end"><?php echo number_format($avgScore, 1); ?>%</div>
                                            </div>
                                            <?php
                                            // Nombre de techniciens pour cette marque
                                            $techCount = 0;
                                            foreach ($technicians as $tech) {
                                                if (in_array($brand, $tech['brands'])) {
                                                    $techCount++;
                                                }
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-6 text-start">Techniciens</div>
                                                <div class="col-6 text-end"><?php echo $techCount; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Liste des techniciens -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Techniciens (<?php echo count($technicians); ?>)</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" id="expandAllBtn">Tout développer</button>
                            <button class="btn btn-sm btn-outline-secondary" id="collapseAllBtn">Tout réduire</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="techniciansList">
                            <?php foreach ($technicians as $index => $tech): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card technician-card shadow-sm">
                                        <div class="card-header d-flex justify-content-between align-items-center" 
                                             data-bs-toggle="collapse" 
                                             href="#collapseTech<?php echo $index; ?>" 
                                             role="button" 
                                             aria-expanded="false">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($tech['name']); ?></h6>
                                            <span class="badge <?php 
                                                if ($tech['level'] === 'Expert') echo 'bg-dark';
                                                elseif ($tech['level'] === 'Senior') echo 'bg-secondary';
                                                else echo 'bg-info';
                                            ?>"><?php echo htmlspecialchars($tech['level']); ?></span>
                                        </div>
                                        <div class="collapse" id="collapseTech<?php echo $index; ?>">
                                            <div class="card-body">
                                                <!-- Marques du technicien -->
                                                <div class="mb-3">
                                                    <h6>Marques:</h6>
                                                    <div>
                                                        <?php foreach ($tech['brands'] as $brand): ?>
                                                            <span class="brand-pill"><?php echo htmlspecialchars($brand); ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Scores par niveau et marque -->
                                                <?php if (!empty($tech['scoresLevels'])): ?>
                                                    <h6>Compétences:</h6>
                                                    <?php foreach ($tech['scoresLevels'] as $level => $brandScores): ?>
                                                        <div class="mb-2">
                                                            <small class="text-muted"><?php echo htmlspecialchars($level); ?>:</small>
                                                            <?php foreach ($brandScores as $brand => $score): ?>
                                                                <?php 
                                                                $scoreClass = "bg-danger";
                                                                if ($score >= 80) $scoreClass = "bg-success";
                                                                elseif ($score >= 60) $scoreClass = "bg-warning";
                                                                ?>
                                                                <span class="competency-badge badge <?php echo $scoreClass; ?>" 
                                                                    title="<?php echo htmlspecialchars($brand); ?>: <?php echo number_format($score, 1); ?>%">
                                                                    <?php echo htmlspecialchars($brand); ?>: <?php echo number_format($score, 1); ?>%
                                                                </span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="alert alert-warning py-2">Aucun score disponible</div>
                                                <?php endif; ?>
                                                
                                                <!-- Lien vers le profil détaillé -->
                                                <div class="mt-3">
                                                    <a href="../define/dashboard_new.php?technicianId=<?php echo htmlspecialchars($tech['id']); ?>" class="btn btn-sm btn-outline-primary">
                                                        Voir profil complet
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if (empty($technicians)): ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        Aucun technicien ne correspond aux critères de filtre sélectionnés.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuration du graphique des scores par marque
        const brandScoresChart = new Chart(
            document.getElementById('brandScoresChart'),
            {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Score moyen (%)',
                        data: <?php echo json_encode($brandScores); ?>,
                        backgroundColor: function(context) {
                            return context.raw.fillColor;
                        },
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            }
        );

        // Configuration du graphique des formations
        const trainingChart = new Chart(
            document.getElementById('trainingChart'),
            {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($trainingsCountsForGraph2)); ?>,
                    datasets: [
                        {
                            label: 'Formations recommandées',
                            data: <?php echo json_encode(array_values($trainingsCountsForGraph2)); ?>,
                            backgroundColor: '#17a2b8',
                            borderColor: '#138496',
                            borderWidth: 1
                        },
                        {
                            label: 'Formations validées',
                            data: <?php echo json_encode(array_values($validationsCountsForGraph2)); ?>,
                            backgroundColor: '#28a745',
                            borderColor: '#218838',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );

        // Gestion des boutons développer/réduire
        document.getElementById('expandAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.collapse').forEach(function(el) {
                new bootstrap.Collapse(el, { toggle: true });
            });
        });

        document.getElementById('collapseAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.collapse.show').forEach(function(el) {
                new bootstrap.Collapse(el, { toggle: true });
            });
        });

        // Gestion des filtres
        document.getElementById('filialeSelect')?.addEventListener('change', function() {
            document.getElementById('agenceSelect').value = 'all';
            document.getElementById('managerSelect').value = 'all';
        });

        document.getElementById('agenceSelect').addEventListener('change', function() {
            document.getElementById('managerSelect').value = 'all';
        });
    </script>
</body>
</html>