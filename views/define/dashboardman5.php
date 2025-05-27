<?php
/**
 * dashboardman5.php
 *
 * Objectif :
 * 1) Vérifie la session et le profil
 * 2) Récupère le "managerId" (depuis la session ou GET si Super Admin)
 * 3) Charge le script getPureManagersAndTechScores.php pour obtenir les techniciens sous le manager connecté
 * 4) Affiche les techniciens et leurs scores dans un tableau avec rowspans et badges Bootstrap
 * 5) Ajoute une colonne "Recommandations" basée sur les scores factuels et déclaratifs
 * 6) Affiche le nombre de techniciens et la liste distincte des marques qu'ils touchent
 * 7) Ajoute des filtres de "marque" et de "level" au-dessus du tableau
 * 8) Ajoute des tableaux supplémentaires par niveau (Junior, Senior, Expert) affichant les moyennes par marque
 */

// Démarrer la session
session_start();

// Afficher les erreurs (à activer uniquement en développement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["profile"])) {
    header("Location: /");
    exit();
}

// Autoriser l'accès si l'utilisateur est Manager ou Super Admin
if ($_SESSION["profile"] !== 'Manager' && $_SESSION["profile"] !== 'Super Admin') {
    echo "Accès refusé.";
    exit();
}

// 1) Récupérer managerId
if ($_SESSION["profile"] === 'Super Admin') {
    if (!isset($_GET['managerId'])) {
        echo "Paramètre managerId requis pour les Super Admin.";
        exit();
    }
    $managerId = $_GET['managerId'];
} else {
    // Manager simple
    $managerId = $_SESSION["id"];
}
// 2) Charger les données des managers et techniciens
require_once __DIR__ . "/getPureManagersAndTechScores.php";

// Charger la configuration
$config = require_once __DIR__ . "/configGF2.php";

// Connexion MongoDB (assurez-vous que $academy est disponible)
require_once "../../vendor/autoload.php";
use MongoDB\Client;

try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $academy     = $mongoClient->selectDatabase('academy');
} catch (MongoDB\Exception\Exception $e) {
    echo "Erreur de connexion à MongoDB : " . htmlspecialchars($e->getMessage());
    exit();
}

// Obtenir les données
$pureManagersAndScores = getPureManagersAndScores($academy);

// 3) Filtrer pour trouver le manager connecté
$filteredResults = array_filter($pureManagersAndScores, function($m) use ($managerId) {
    return isset($m['managerId']) && (string)$m['managerId'] === (string)$managerId;
});

// Vérifier si le manager est dans la liste des managers purs
if (empty($filteredResults)) {
    echo "Ce manager n'apparaît pas dans la liste des managers purs (via getPureManagersAndScores).<br>";
    echo "Manager ID : " . htmlspecialchars($managerId);
    exit();
}

// Récupérer les données du manager
$managerScores = array_values($filteredResults)[0];
$managerName = htmlspecialchars($managerScores['managerName']);

// Récupérer la liste des techniciens
$technicians = $managerScores['technicians'];
// --- Lire les filtres depuis l'URL ---
$selectedLevel = isset($_GET['level']) ? $_GET['level'] : null;
$selectedMarque = isset($_GET['marque']) ? $_GET['marque'] : null;

// Fonction pour vérifier si un technicien correspond à la marque sélectionnée
function matchesMarque($tech, $selectedMarque) {
    if (!$selectedMarque) return true; // Aucun filtre appliqué
    // Vérifier si la marque sélectionnée est dans une des marques du technicien
    $brands = array_merge(
        isset($tech['brandJunior']) ? $tech['brandJunior'] : [],
        isset($tech['brandSenior']) ? $tech['brandSenior'] : [],
        isset($tech['brandExpert']) ? $tech['brandExpert'] : []
    );
    foreach ($brands as $brand) {
        if (strcasecmp($brand, $selectedMarque) == 0) {
            return true;
        }
    }
    return false;
}

// Filtrer les techniciens en fonction de la marque sélectionnée
if ($selectedMarque) {
    $technicians = array_filter($technicians, function($tech) use ($selectedMarque) {
        return matchesMarque($tech, $selectedMarque);
    });
}

// Calculer le nombre de techniciens après filtre marque
$numTechnicians = count($technicians);

// Obtenir les marques distinctes
function getDistinctBrands($technicians) {
    $allBrands = [];
    $juniorBrands = [];
    $seniorBrands = [];
    $expertBrands = [];

    foreach ($technicians as $tech) {
        // Récupérer les marques pour chaque niveau, en ignorant les valeurs vides
        if (isset($tech['brandJunior']) && !empty($tech['brandJunior'])) {
            foreach ($tech['brandJunior'] as $brand) {
                if (is_string($brand)) {
                    $brand = trim($brand);
                    if ($brand !== '') {
                        $juniorBrands[] = $brand;
                        $allBrands[] = $brand;
                    }
                } else {
                    // Si ce n'est pas une chaîne, essayez de le convertir
                    $brandStr = (string)$brand;
                    $brandStr = trim($brandStr);
                    if ($brandStr !== '') {
                        $juniorBrands[] = $brandStr;
                        $allBrands[] = $brandStr;
                    }
                }
            }
        }

        if (isset($tech['brandSenior']) && !empty($tech['brandSenior'])) {
            foreach ($tech['brandSenior'] as $brand) {
                if (is_string($brand)) {
                    $brand = trim($brand);
                    if ($brand !== '') {
                        $seniorBrands[] = $brand;
                        $allBrands[] = $brand;
                    }
                } else {
                    // Si ce n'est pas une chaîne, essayez de le convertir
                    $brandStr = (string)$brand;
                    $brandStr = trim($brandStr);
                    if ($brandStr !== '') {
                        $seniorBrands[] = $brandStr;
                        $allBrands[] = $brandStr;
                    }
                }
            }
        }

        if (isset($tech['brandExpert']) && !empty($tech['brandExpert'])) {
            foreach ($tech['brandExpert'] as $brand) {
                if (is_string($brand)) {
                    $brand = trim($brand);
                    if ($brand !== '') {
                        $expertBrands[] = $brand;
                        $allBrands[] = $brand;
                    }
                } else {
                    // Si ce n'est pas une chaîne, essayez de le convertir
                    $brandStr = (string)$brand;
                    $brandStr = trim($brandStr);
                    if ($brandStr !== '') {
                        $expertBrands[] = $brandStr;
                        $allBrands[] = $brandStr;
                    }
                }
            }
        }
    }

    // Supprimer les doublons
    $uniqueAllBrands = array_unique($allBrands);
    $uniqueJuniorBrands = array_unique($juniorBrands);
    $uniqueSeniorBrands = array_unique($seniorBrands);
    $uniqueExpertBrands = array_unique($expertBrands);

    // Trier les listes par ordre alphabétique (optionnel)
    sort($uniqueAllBrands);
    sort($uniqueJuniorBrands);
    sort($uniqueSeniorBrands);
    sort($uniqueExpertBrands);

    return [
        'allBrands'      => $uniqueAllBrands,
        'juniorBrands'   => $uniqueJuniorBrands,
        'seniorBrands'   => $uniqueSeniorBrands,
        'expertBrands'   => $uniqueExpertBrands
    ];
}

$distinctBrands = getDistinctBrands($technicians);
$uniqueMarquesTotal = $distinctBrands['allBrands'];
$uniqueMarquesJunior = $distinctBrands['juniorBrands'];
$uniqueMarquesSenior = $distinctBrands['seniorBrands'];
$uniqueMarquesExpert = $distinctBrands['expertBrands'];
// --- Déterminer les niveaux uniques présents dans l'équipe ---
$uniqueLevels = [];

foreach ($technicians as $tech) {
    foreach ($tech['scores'] as $levelData) {
        $uniqueLevels[] = $levelData['level'];
    }
}

$uniqueLevels = array_unique($uniqueLevels);
sort($uniqueLevels);

// --- Calculer les moyennes des scores factuels et déclaratifs par technicien et par niveau ---
$averages = [];

// Fonction pour obtenir les recommandations basées sur les scores
function getTrainingRecommendation($knowledgeScore, $taskScore) {
    if ($knowledgeScore <= 60) {
        if ($taskScore <= 60) {
            return "Niveau Critique (Tous les types de formations recommandées)";
        } elseif ($taskScore <= 80) {
            return "Niveau connaissance à améliorer, tâches presque bonnes";
        } else { // taskScore > 80
            return "Connaissances en retard par rapport aux tâches pro (Distancielle et E-learning)";
        }
    } elseif ($knowledgeScore <= 80) {
        // 60 < knowledgeScore <= 80
        if ($taskScore <= 60) {
            return "Attention à la Malfaçon (Présentielle, E-learning, Coaching, Mentoring)";
        } elseif ($taskScore <= 80) {
            return "Niveau général moyen (Présentielle, E-learning, Coaching)";
        } else { // taskScore > 80
            return "Niveau global intermédiaire, E-learning conseillé";
        }
    } else {
        // knowledgeScore > 80
        if ($taskScore <= 60) {
            return "Pratique à bosser (Présentielle, Coaching, Mentoring)";
        } elseif ($taskScore <= 80) {
            return "Besoin de pratique et de coaching";
        } else { // taskScore > 80
            return "Technicien Performant (aucun besoin particulier)";
        }
    }
}

// Calculer les moyennes pour chaque technicien et niveau
foreach ($technicians as $tech) {
    $techId = $tech['technicianId'];
    foreach ($tech['scores'] as $levelData) {
        $level = $levelData['level'];
        // Appliquer le filtre de niveau
        if ($selectedLevel && strcasecmp($level, $selectedLevel) !== 0) {
            continue;
        }

        // Vérifier si cette marque et ce niveau ont des spécialités non supportées
        $unsupportedSpecialities = [];
        if ($selectedMarque && isset($config['nonSupportedGroupsByBrandAndLevel'][$selectedMarque][$level])) {
            $unsupportedSpecialities = $config['nonSupportedGroupsByBrandAndLevel'][$selectedMarque][$level];
        }

        $totalFactuel = 0;
        $totalDeclaratif = 0;
        $count = 0;
        foreach ($levelData['specialities'] as $spec) {
            $speciality = $spec['speciality'];
            // Vérifier si la spécialité est non supportée
            if (in_array($speciality, $unsupportedSpecialities)) {
                continue; // Exclure de la moyenne
            }

            if (is_numeric($spec['factuelScore'])) {
                $totalFactuel += $spec['factuelScore'];
            }
            if (is_numeric($spec['declaratifScore'])) {
                $totalDeclaratif += $spec['declaratifScore'];
            }
            $count++;
        }
        if ($count > 0) {
            $avgFactuel = $totalFactuel / $count;
            $avgDeclaratif = $totalDeclaratif / $count;
            $avgMoyenne = ($avgFactuel + $avgDeclaratif) / 2;
            $averages[$techId][$level] = [
                'factuel'    => $avgFactuel,
                'declaratif' => $avgDeclaratif,
                'moyenne'    => $avgMoyenne
            ];
        }
    }
}

// --- Déclaration unique de la fonction calculateBrandAverage ---
if (!function_exists('calculateBrandAverage')) {
    /**
     * Calcule la moyenne des scores factuels et déclaratifs pour une marque et un niveau donné.
     *
     * @param array  $tech    Les données du technicien.
     * @param string $brand   La marque (ex. "HINO").
     * @param string $level   Le niveau (Junior, Senior, Expert).
     * @param array  $config  Le tableau de configuration depuis configGF2.php.
     *
     * @return float|null La moyenne globale ou null si aucune donnée valide.
     */
    // Fonctions de calcul
function getSupportedSpecialitiesForBrandAndLevel($brand, $level, $config)
{
    if (!isset($config['supportedGroupsByLevel'][$level])) {
        return [];
    }
    $supported = $config['supportedGroupsByLevel'][$level];
    $nonSupported = isset($config['nonSupportedGroupsByBrandAndLevel'][$brand][$level]) 
        ? $config['nonSupportedGroupsByBrandAndLevel'][$brand][$level]
        : [];
    $supportedSpecialities = array_diff($supported, $nonSupported);
    return $supportedSpecialities;
}

function calculateBrandAverages($tech, $brand, $level, $config)
{
    $supportedSpecialities = getSupportedSpecialitiesForBrandAndLevel($brand, $level, $config);

    if (empty($supportedSpecialities)) {
        return ['factuel' => null, 'declaratif' => null];
    }

    $totalFactuel = 0;
    $totalDeclaratif = 0;
    $count = 0;

    foreach ($tech['scores'] as $levelData) {
        if (strcasecmp($levelData['level'], $level) !== 0) {
            continue;
        }

        foreach ($levelData['specialities'] as $spec) {
            $speciality = $spec['speciality'];

            if (!in_array($speciality, $supportedSpecialities)) {
                continue;
            }

            if (is_numeric($spec['factuelScore'])) {
                $totalFactuel += $spec['factuelScore'];
            }

            if (is_numeric($spec['declaratifScore'])) {
                $totalDeclaratif += $spec['declaratifScore'];
            }

            $count++;
        }
    }

    if ($count > 0) {
        $avgFactuel = $totalFactuel / $count;
        $avgDeclaratif = $totalDeclaratif / $count;
        return ['factuel' => $avgFactuel, 'declaratif' => $avgDeclaratif];
    } else {
        return ['factuel' => null, 'declaratif' => null];
    }
}
}

// --- Déclaration unique de la fonction addAverage ---
if (!function_exists('addAverage')) {
    /**
     * Ajoute les moyennes d'une marque et niveau à l'accumulateur.
     *
     * @param array &$brandLevelAverages Référence vers le tableau d'accumulation.
     * @param array $brands Liste des marques.
     * @param string $level Le niveau (Junior, Senior, Expert).
     * @param array $tech Les données du technicien.
     * @param array $config La configuration.
     *
     * @return void
     */
    function addAverage(&$brandLevelAverages, $brands, $level, $tech, $config) {
        foreach ($brands as $brand) {
            $brand = trim($brand);
            if ($brand === '') continue;

            // Initialiser les sous-tableaux si nécessaire
            if (!isset($brandLevelAverages[$brand])) {
                $brandLevelAverages[$brand] = [];
            }
            if (!isset($brandLevelAverages[$brand][$level])) {
                $brandLevelAverages[$brand][$level] = ['total' => 0, 'count' => 0];
            }

            // Calculer la moyenne pour cette marque et niveau
            $brandAverage = calculateBrandAverages($tech, $brand, $level, $config);
            if ($brandAverage['factuel'] !== null && $brandAverage['declaratif'] !== null) {
                // Calculer la moyenne globale
                $globalAvg = ($brandAverage['factuel'] + $brandAverage['declaratif']) / 2;
                $brandLevelAverages[$brand][$level]['total'] += $globalAvg;
                $brandLevelAverages[$brand][$level]['count'] += 1;
            }
        }
    }
}


// --- Calcul des moyennes par marque et niveau ---
$brandLevelAverages = [];

// Déterminer les marques à inclure dans les per-level tables
// Si une marque est sélectionnée, n'inclure que cette marque
// Sinon, inclure toutes les marques
$brandsToDisplayForLevels = $selectedMarque ? [$selectedMarque] : $uniqueMarquesTotal;

// Parcourir chaque technicien
foreach ($technicians as $tech) {
    // Récupérer les marques par niveau pour le technicien
    $brandsJunior = isset($tech['brandJunior']) ? $tech['brandJunior'] : [];
    $brandsSenior = isset($tech['brandSenior']) ? $tech['brandSenior'] : [];
    $brandsExpert = isset($tech['brandExpert']) ? $tech['brandExpert'] : [];

    // Pour chaque niveau, ajouter les moyennes
    addAverage($brandLevelAverages, $brandsJunior, 'Junior', $tech, $config);
    addAverage($brandLevelAverages, $brandsSenior, 'Senior', $tech, $config);
    addAverage($brandLevelAverages, $brandsExpert, 'Expert', $tech, $config);
}

// Calculer les moyennes finales
foreach ($brandLevelAverages as $brand => &$levels) {
    foreach ($levels as $level => &$data) {
        if ($data['count'] > 0) {
            $data['average'] = $data['total'] / $data['count'];
        } else {
            $data['average'] = 0;
        }
        // Formater la moyenne
        $data['averageDisplay'] = number_format($data['average'], 2) . '%';
    }
}
unset($levels);
unset($data);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Manager Pur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (icônes) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables (optionnel) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        /* Optionnel : Styles personnalisés */
        .badge {
            font-size: 0.9em;
        }
        .marque-list {
            list-style-type: disc;
            padding-left: 20px;
        }
        .marque-sublist {
            list-style-type: circle;
            padding-left: 20px;
        }
        /* Styles pour les filtres */
        .filter-container {
            margin-bottom: 20px;
        }
        .filter-label {
            margin-right: 10px;
            font-weight: bold;
        }
        /* Style pour barrer les spécialités non supportées */
        .unsupported {
            text-decoration: line-through;
            color: #6c757d;
        }
        /* Assurer l'alignement des colonnes */
        table th, table td {
            vertical-align: middle;
            text-align: center;
        }
        /* Styles pour les moyennes par marque */
        .brand-average {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .brand-average .badge {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

<?php include "./partials/header.php"; ?>

<div class="container my-4">
    <h1>Dashboard Manager Pur : <?php echo $managerName; ?></h1>
    <p class="text-muted">Managers sans sous-managers, uniquement des techniciens.</p>
    <hr>

    <!-- Informations sur les Techniciens -->
    <div class="mb-4">
        <p><strong>Nombre de techniciens :</strong> <?php echo $numTechnicians; ?></p>
        
        <p><strong>Liste des marques (tous niveaux) :</strong></p>
        <?php if (!empty($uniqueMarquesTotal)): ?>
            <ul class="marque-list">
                <?php foreach ($uniqueMarquesTotal as $marque): ?>
                    <li><?php echo htmlspecialchars($marque); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune marque associée.</p>
        <?php endif; ?>

        <?php if (!$selectedMarque): // If no marque filter is applied, show per level marques ?>
            <p><strong>Liste des marques par niveau :</strong></p>
            <ul class="marque-list">
                <li><strong>Junior :</strong>
                    <?php if (!empty($uniqueMarquesJunior)): ?>
                        <ul class="marque-sublist">
                            <?php foreach ($uniqueMarquesJunior as $marque): ?>
                                <li><?php echo htmlspecialchars($marque); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucune marque associée.</p>
                    <?php endif; ?>
                </li>
                <li><strong>Senior :</strong>
                    <?php if (!empty($uniqueMarquesSenior)): ?>
                        <ul class="marque-sublist">
                            <?php foreach ($uniqueMarquesSenior as $marque): ?>
                                <li><?php echo htmlspecialchars($marque); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucune marque associée.</p>
                    <?php endif; ?>
                </li>
                <li><strong>Expert :</strong>
                    <?php if (!empty($uniqueMarquesExpert)): ?>
                        <ul class="marque-sublist">
                            <?php foreach ($uniqueMarquesExpert as $marque): ?>
                                <li><?php echo htmlspecialchars($marque); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucune marque associée.</p>
                    <?php endif; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Filtres -->
    <div class="filter-container">
        <!-- Marque Filter -->
        <div class="mb-2">
            <span class="filter-label">Filtrer par Marque:</span>
            <select id="marqueFilter" class="form-select" style="width: 200px; display: inline-block;">
                <option value="">Toutes les Marques</option>
                <?php foreach ($uniqueMarquesTotal as $marque): ?>
                    <option value="<?php echo htmlspecialchars($marque); ?>" <?php if ($selectedMarque == $marque) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($marque); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Level Filter -->
        <div>
            <span class="filter-label">Filtrer par Niveau:</span>
            <select id="levelFilter" class="form-select" style="width: 200px; display: inline-block;">
                <option value="">Tous les Niveaux</option>
                <?php foreach ($uniqueLevels as $level): ?>
                    <option value="<?php echo htmlspecialchars($level); ?>" <?php if ($selectedLevel == $level) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($level); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Tableau des Techniciens -->
    <h3>Liste des Techniciens</h3>
    <table id="scoresTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nom du Technicien</th>
                <th>Niveau</th>
                <th>Spécialité</th>
                <th>Score Factuel</th>
                <th>Score Déclaratif</th>
                <th>Recommandations</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($technicians as $tech): ?>
                <?php
                    $techId = $tech['technicianId'];
                    $techName = htmlspecialchars($tech['technicianName']);
                    $scores = $tech['scores'];

                    // Calculer le nombre total de lignes pour ce technicien (selon le niveau filter)
                    $techRowCount = 0;
                    foreach ($scores as $levelData) {
                        // Appliquer le filtre de niveau
                        if ($selectedLevel && strcasecmp($levelData['level'], $selectedLevel) !== 0) {
                            continue;
                        }
                        $techRowCount += count($levelData['specialities']);
                    }

                    if ($techRowCount == 0) {
                        // Ce technicien n'a pas de données pour le niveau sélectionné
                        continue;
                    }

                    // Initialisation pour le technicien
                    $firstTechRow = true;
                ?>
                <?php foreach ($scores as $levelData): ?>
                    <?php
                        $level = htmlspecialchars($levelData['level']);

                        // Appliquer le filtre de niveau
                        if ($selectedLevel && strcasecmp($level, $selectedLevel) !== 0) {
                            continue;
                        }

                        $specialities = $levelData['specialities'];
                        $levelRowCount = count($specialities);

                        // Vérifier si cette marque et ce niveau ont des spécialités non supportées
                        $unsupportedSpecialities = [];
                        if ($selectedMarque && isset($config['nonSupportedGroupsByBrandAndLevel'][$selectedMarque][$level])) {
                            $unsupportedSpecialities = $config['nonSupportedGroupsByBrandAndLevel'][$selectedMarque][$level];
                        }

                        // Calculer les moyennes pour ce niveau et technicien
                        $totalFactuel = 0;
                        $totalDeclaratif = 0;
                        $count = 0;
                        foreach ($specialities as $spec) {
                            $speciality = $spec['speciality'];
                            // Vérifier si la spécialité est non supportée
                            if (in_array($speciality, $unsupportedSpecialities)) {
                                continue; // Exclure de la moyenne
                            }

                            if (is_numeric($spec['factuelScore'])) {
                                $totalFactuel += $spec['factuelScore'];
                            }
                            if (is_numeric($spec['declaratifScore'])) {
                                $totalDeclaratif += $spec['declaratifScore'];
                            }
                            $count++;
                        }
                        if ($count > 0) {
                            $avgFactuel = $totalFactuel / $count;
                            $avgDeclaratif = $totalDeclaratif / $count;
                            $avgMoyenne = ($avgFactuel + $avgDeclaratif) / 2;
                        } else {
                            $avgFactuel = $avgDeclaratif = $avgMoyenne = 0;
                        }

                        // Préparer l'affichage des moyennes
                        if ($count > 0) {
                            // Déterminer la classe pour la moyenne totale
                            if ($avgMoyenne > 80) {
                                $avgMoyenneClass = 'bg-success';
                            } elseif ($avgMoyenne >= 50) {
                                $avgMoyenneClass = 'bg-warning';
                            } else {
                                $avgMoyenneClass = 'bg-danger';
                            }

                            // Formater les moyennes
                            $avgFactuelDisplay = number_format($avgFactuel, 2) . '%';
                            $avgDeclaratifDisplay = number_format($avgDeclaratif, 2) . '%';
                            $avgMoyenneDisplay = number_format($avgMoyenne, 2) . '%';

                            // Créer les tooltips
                            $avgFactuelTooltip = "Factuel: {$avgFactuelDisplay}";
                            $avgDeclaratifTooltip = "Déclaratif: {$avgDeclaratifDisplay}";

                            // Préparer l'affichage
                            $moyennesDisplay = "<span class='badge bg-info' data-bs-toggle='tooltip' title='{$avgFactuelTooltip}'>{$avgFactuelDisplay}</span> &amp; <span class='badge bg-secondary' data-bs-toggle='tooltip' title='{$avgDeclaratifTooltip}'>{$avgDeclaratifDisplay}</span> = <span class='badge {$avgMoyenneClass}' data-bs-toggle='tooltip' title='Moyenne: {$avgMoyenneDisplay}'>{$avgMoyenneDisplay}</span>";
                        } else {
                            // Si aucune moyenne n'est disponible
                            $moyennesDisplay = "<span class='badge bg-secondary'>N/A</span>";
                        }

                        // Initialiser $firstLevelRow pour chaque niveau
                        $firstLevelRow = true;
                    ?>
                    <?php foreach ($specialities as $spec): ?>
                        <?php
                            $speciality = htmlspecialchars($spec['speciality']);
                            $isUnsupported = in_array($spec['speciality'], $unsupportedSpecialities);

                            // Traitement des scores factuel et déclaratif
                            $factuelScoreRaw = $spec['factuelScore'];
                            $declaratifScoreRaw = $spec['declaratifScore'];

                            // Initialiser $factuelScore et $declaratifScore pour éviter les notices
                            $factuelScore = is_numeric($factuelScoreRaw) ? $factuelScoreRaw : 0;
                            $declaratifScore = is_numeric($declaratifScoreRaw) ? $declaratifScoreRaw : 0;

                            // Déterminer la classe Bootstrap pour les badges Factuel
                            if (is_numeric($factuelScoreRaw)) {
                                if ($factuelScoreRaw > 80) {
                                    $factuelClass = 'bg-success';
                                } elseif ($factuelScoreRaw >= 50) {
                                    $factuelClass = 'bg-warning';
                                } else {
                                    $factuelClass = 'bg-danger';
                                }
                                $factuelDisplay = htmlspecialchars($factuelScoreRaw) . '%';
                                $factuelTooltip = '';
                            } else {
                                $factuelClass = 'bg-secondary';
                                $factuelDisplay = '0%';
                                $factuelTooltip = 'data-bs-toggle="tooltip" title="Pas évalué"';
                            }

                            // Déterminer la classe Bootstrap pour les badges Déclaratif
                            if (is_numeric($declaratifScoreRaw)) {
                                if ($declaratifScoreRaw > 80) {
                                    $declaratifClass = 'bg-success';
                                } elseif ($declaratifScoreRaw >= 50) {
                                    $declaratifClass = 'bg-warning';
                                } else {
                                    $declaratifClass = 'bg-danger';
                                }
                                $declaratifDisplay = htmlspecialchars($declaratifScoreRaw) . '%';
                                $declaratifTooltip = '';
                            } else {
                                $declaratifClass = 'bg-secondary';
                                $declaratifDisplay = '0%';
                                $declaratifTooltip = 'data-bs-toggle="tooltip" title="Pas évalué"';
                            }

                            // Obtenir la recommandation basée sur les scores
                            $recommandation = getTrainingRecommendation($factuelScore, $declaratifScore);
                        ?>
                        <tr>
                            <?php if ($firstTechRow && $firstLevelRow): ?>
                                <td rowspan="<?php echo $techRowCount; ?>"><?php echo $techName; ?></td>
                            <?php endif; ?>

                            <?php if ($firstLevelRow): ?>
                                <td rowspan="<?php echo $levelRowCount; ?>">
                                    <?php echo $level; ?>
                                    <br>
                                    <hr>
                                    <br>
                                    <?php echo $moyennesDisplay; ?>
                                </td>
                                <?php $firstLevelRow = false; ?>
                            <?php endif; ?>

                            <td<?php if ($isUnsupported) echo ' class="unsupported"'; ?>><?php echo $speciality; ?></td>
                            <td>
                                <span class="badge <?php echo $factuelClass; ?>" <?php echo $factuelTooltip; ?>><?php echo $factuelDisplay; ?></span>
                            </td>
                            <td>
                                <span class="badge <?php echo $declaratifClass; ?>" <?php echo $declaratifTooltip; ?>><?php echo $declaratifDisplay; ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($recommandation); ?></td>
                        </tr>
                    <?php endforeach; // foreach ($specialities as $spec) ?>
                <?php endforeach; // foreach ($scores as $levelData) ?>
            <?php endforeach; // foreach ($technicians as $tech) ?>
        </tbody>
    </table>

    <!-- Section des Cartes par Marque et Niveau -->
    <div class="container my-5">
        <h3>Statistiques par Marque et Niveau</h3>
        <div class="row">
            <?php foreach ($brandLevelAverages as $brand => $levels): ?>
                <?php foreach ($levels as $level => $data): ?>
                    <?php 
                        // Si un filtre de marque est appliqué, n'afficher que cette marque
                        if ($selectedMarque && strcasecmp($brand, $selectedMarque) !== 0) {
                            continue;
                        }

                        // Si un filtre de niveau est appliqué, n'afficher que ce niveau
                        if ($selectedLevel && strcasecmp($level, $selectedLevel) !== 0) {
                            continue;
                        }
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <?php echo htmlspecialchars($brand); ?> - <?php echo htmlspecialchars($level); ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Moyenne des Scores</h5>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($data['averageDisplay']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

<!-- Nouveaux Tableaux par Niveau avec Moyennes par Marque -->
<div class="container my-5">
    <h3>Statistiques par Niveau</h3>

    <?php
        // Définir les niveaux
        $levelsToDisplay = ['Junior', 'Senior', 'Expert'];
    ?>

    <?php foreach ($levelsToDisplay as $levelToDisplay): ?>
        <?php
            // Filtrer les techniciens qui ont des moyennes pour ce niveau
            $techniciansForLevel = array_filter($technicians, function($tech) use ($levelToDisplay, $averages) {
                return isset($averages[$tech['technicianId']][$levelToDisplay]);
            });

            if (!$selectedMarque) {
                // Si aucun filtre de marque n'est appliqué, afficher toutes les marques
                $marquesToDisplay = $uniqueMarquesTotal;
            } else {
                // Si un filtre de marque est appliqué, n'afficher que cette marque
                $marquesToDisplay = [$selectedMarque];
            }
        ?>
        <?php if (!empty($techniciansForLevel)): ?>
            <h4><?php echo htmlspecialchars($levelToDisplay); ?></h4>
            <table class="table table-striped table-bordered mb-4">
                <thead>
                    <tr>
                        <th>Nom du Technicien</th>
                        <th>Moyenne Score Factuel</th>
                        <th>Moyenne Score Déclaratif</th>
                        <th>Moyenne Globale</th>
                        <?php foreach ($marquesToDisplay as $marque): ?>
                            <th>Moyenne <?php echo htmlspecialchars($marque); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($techniciansForLevel as $tech): ?>
                        <?php
                            $techId = $tech['technicianId'];
                            $techName = htmlspecialchars($tech['technicianName']);

                            $factuelAvg = isset($averages[$techId][$levelToDisplay]['factuel']) ? $averages[$techId][$levelToDisplay]['factuel'] : 0;
                            $declaratifAvg = isset($averages[$techId][$levelToDisplay]['declaratif']) ? $averages[$techId][$levelToDisplay]['declaratif'] : 0;
                            $globalAvg = isset($averages[$techId][$levelToDisplay]['moyenne']) ? $averages[$techId][$levelToDisplay]['moyenne'] : 0;

                            // Déterminer la classe Bootstrap pour les badges
                            // Factuel
                            if ($factuelAvg > 80) {
                                $factuelClass = 'bg-success';
                            } elseif ($factuelAvg >= 50) {
                                $factuelClass = 'bg-warning';
                            } else {
                                $factuelClass = 'bg-danger';
                            }
                            $factuelDisplay = number_format($factuelAvg, 2) . '%';

                            // Déclaratif
                            if ($declaratifAvg > 80) {
                                $declaratifClass = 'bg-success';
                            } elseif ($declaratifAvg >= 50) {
                                $declaratifClass = 'bg-warning';
                            } else {
                                $declaratifClass = 'bg-danger';
                            }
                            $declaratifDisplay = number_format($declaratifAvg, 2) . '%';

                            // Globale
                            if ($globalAvg > 80) {
                                $globalClass = 'bg-success';
                            } elseif ($globalAvg >= 50) {
                                $globalClass = 'bg-warning';
                            } else {
                                $globalClass = 'bg-danger';
                            }
                            $globalDisplay = number_format($globalAvg, 2) . '%';
                        ?>
                        <tr>
                            <td><?php echo $techName; ?></td>
                            <td><span class="badge <?php echo $factuelClass; ?>"><?php echo $factuelDisplay; ?></span></td>
                            <td><span class="badge <?php echo $declaratifClass; ?>"><?php echo $declaratifDisplay; ?></span></td>
                            <td><span class="badge <?php echo $globalClass; ?>"><?php echo $globalDisplay; ?></span></td>
                            <?php foreach ($marquesToDisplay as $marque): ?>
                                <?php
                                    // Calculer les moyennes pour la marque et le niveau
                                    $brandAverages = calculateBrandAverages($tech, $marque, $levelToDisplay, $config);

                                    if ($brandAverages['factuel'] !== null && $brandAverages['declaratif'] !== null) {
                                        // Calculer la moyenne globale pour la marque
                                        $brandGlobalAvg = ($brandAverages['factuel'] + $brandAverages['declaratif']) / 2;

                                        // Déterminer la classe Bootstrap pour la moyenne de la marque
                                        if ($brandGlobalAvg > 80) {
                                            $brandClass = 'bg-success';
                                        } elseif ($brandGlobalAvg >= 50) {
                                            $brandClass = 'bg-warning';
                                        } else {
                                            $brandClass = 'bg-danger';
                                        }
                                        // Formater l'affichage
                                        $brandDisplay = "<span class='badge {$brandClass}'>" . number_format($brandGlobalAvg, 2) . "%</span>";
                                    } else {
                                        // Si aucune moyenne n'est disponible pour cette marque
                                        $brandDisplay = "/";
                                    }
                                ?>
                                <td><?php echo $brandDisplay; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

</div>

<?php include "./partials/footer.php"; ?>

<!-- Scripts JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){

 

    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Handle Marque filter change
    $('#marqueFilter').on('change', function(){
        var selectedMarque = $(this).val();
        var selectedLevel = $('#levelFilter').val();

        // Construire la nouvelle URL
        var url = new URL(window.location.href);
        if(selectedMarque){
            url.searchParams.set('marque', selectedMarque);
        } else {
            url.searchParams.delete('marque');
        }

        if(selectedLevel){
            url.searchParams.set('level', selectedLevel);
        } else {
            url.searchParams.delete('level');
        }

        window.location.href = url.toString();
    });

    // Handle Level filter change
    $('#levelFilter').on('change', function(){
        var selectedLevel = $(this).val();
        var selectedMarque = $('#marqueFilter').val();

        // Construire la nouvelle URL
        var url = new URL(window.location.href);
        if(selectedLevel){
            url.searchParams.set('level', selectedLevel);
        } else {
            url.searchParams.delete('level');
        }

        if(selectedMarque){
            url.searchParams.set('marque', selectedMarque);
        } else {
            url.searchParams.delete('marque');
        }

        window.location.href = url.toString();
    });
});
</script>
</body>
</html>
