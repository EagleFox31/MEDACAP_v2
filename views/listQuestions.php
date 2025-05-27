<?php
session_start();
include_once "language.php";
include_once "getValidatedResults.php"; 
include_once "userFilters.php";

if (!isset($_SESSION["profile"])) {
    header("Location: ../");
    exit();
} else {

    // Définition des fonctions

    function getFilteredQuestions($academy, $level, $segmentType, $technicians, $tableauResultats)
    {
        // Récupérer les questions en fonction du niveau
        if ($level !== 'Total') {
            $questions = $academy->questions->find([
                "type" => "Declarative",
                "level" => $level,
                "active" => true
            ])->toArray();
        } else {
            $questions = $academy->questions->find([
                "type" => "Declarative",
                "level" => ['$in' => ['Junior', 'Senior', 'Expert']],
                "active" => true
            ])->toArray();
        }

        $filteredQuestions = [];
        $numTechnicians = count($technicians);

        foreach ($questions as $question) {
            $totalMaitrise = 0;
            $questionId = (string)$question['_id'];

            foreach ($technicians as $technician) {
                $techId = (string)$technician['_id'];
                $status = isset($tableauResultats[$techId][$questionId]) ? $tableauResultats[$techId][$questionId] : 'Non maîtrisé';

                if ($status == "Maîtrisé") {
                    $totalMaitrise++;
                }
            }

            // Vérifier si la question correspond au segmentType
            if (doesQuestionMatchSegment($segmentType, $totalMaitrise, $numTechnicians)) {
                // Conserver le niveau de la question
                $question['level'] = $question['level'];
                $filteredQuestions[] = $question;
            }
        }

        return $filteredQuestions;
    }

    function doesQuestionMatchSegment($segmentType, $totalMaitrise, $numTechnicians)
    {
        switch ($numTechnicians) {
            case 1:
                if ($segmentType == 'nonMaitrise' && $totalMaitrise == 0) {
                    return true;
                } elseif ($segmentType == 'singleMaitrise' && $totalMaitrise == 1) {
                    return true;
                }
                break;
            case 2:
                if ($segmentType == 'nonMaitrise' && $totalMaitrise == 0) {
                    return true;
                } elseif ($segmentType == 'singleMaitrise' && $totalMaitrise == 1) {
                    return true;
                } elseif (($segmentType == 'allMaitrise' || $segmentType == 'others') && $totalMaitrise == 2) {
                    return true;
                }
                break;
            case 3:
                if ($segmentType == 'nonMaitrise' && $totalMaitrise == 0) {
                    return true;
                } elseif ($segmentType == 'singleMaitrise' && $totalMaitrise == 1) {
                    return true;
                } elseif ($segmentType == 'doubleMaitrise' && $totalMaitrise == 2) {
                    return true;
                } elseif (($segmentType == 'allMaitrise' || $segmentType == 'others') && $totalMaitrise == 3) {
                    return true;
                }
                break;
            default:
                // Plus de 3 techniciens
                if ($segmentType == 'nonMaitrise' && $totalMaitrise == 0) {
                    return true;
                } elseif ($segmentType == 'singleMaitrise' && $totalMaitrise == 1) {
                    return true;
                } elseif ($segmentType == 'doubleMaitrise' && $totalMaitrise == 2) {
                    return true;
                } elseif (($segmentType == 'moreMaitrise' || $segmentType == 'others') && $totalMaitrise >= 3) {
                    return true;
                }
                break;
        }
        return false;
    }

    // Récupération des paramètres GET
    $level = isset($_GET['level']) ? $_GET['level'] : 'Total';
    $segmentType = isset($_GET['type']) ? $_GET['type'] : '';
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;
    $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null;

    // Connexion à MongoDB
    $conn = new MongoDB\Client("mongodb://localhost:27017");
    $academy = $conn->academy;

    // Récupérer les techniciens
    if ($level !== 'Total') {
        $technicians = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, $level, $selectedAgency);
    } else {
        $techniciansJunior = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Junior', $selectedAgency);
        $techniciansSenior = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Senior', $selectedAgency);
        $techniciansExpert = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Expert', $selectedAgency);

        $technicians = array_merge($techniciansJunior, $techniciansSenior, $techniciansExpert);
        // Éliminer les doublons de techniciens
        $technicians = array_unique($technicians, SORT_REGULAR);
    }

    // Récupérer les résultats validés
    if ($level !== 'Total') {
        $tableauResultats = getValidatedResults($level);
    } else {
        $tableauResultatsJunior = getValidatedResults('Junior');
        $tableauResultatsSenior = getValidatedResults('Senior');
        $tableauResultatsExpert = getValidatedResults('Expert');

        // Fusionner les résultats
        $tableauResultats = array_replace_recursive($tableauResultatsJunior, $tableauResultatsSenior, $tableauResultatsExpert);
    }

    // Obtenir les questions filtrées
    $filteredQuestions = getFilteredQuestions($academy, $level, $segmentType, $technicians, $tableauResultats);

    // Trier les questions par spécialité
    usort($filteredQuestions, function($a, $b) {
        return strcmp($a['speciality'], $b['speciality']);
    });

    // Afficher les questions filtrées
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $tache_pro;?></title>
        <!-- Inclure Bootstrap ou autre si nécessaire -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <h1 class="my-4"><?php echo $tache_pro;?> <?php echo htmlspecialchars($level); ?></h1>
        <h2>
            <?php
            if ($segmentType == 'nonMaitrise') {
                echo $Tasks_Not_Covered;
            } elseif ($segmentType == 'singleMaitrise') {
                echo $Tasks_Critic;
            } elseif ($segmentType == 'doubleMaitrise') {
                echo $Tasks_Two_Tech;
            } elseif ($segmentType == 'allMaitrise' || $segmentType == 'moreMaitrise' || $segmentType == 'others') {
                echo $Tasks_Covered;
            }
            ?>
        </h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th><center><h3><b><?php echo $tache_pro ?></b></h3></center></th>
                    <th><center><h4><b><?php echo $groupe_fonctionnel ?></b></h4></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $counter = 1;
                $currentSpeciality = '';
                foreach ($filteredQuestions as $question) {
                    // Vérifier si la spécialité a changé
                    if ($currentSpeciality !== $question['speciality']) {
                        $currentSpeciality = $question['speciality'];
                        // Afficher une ligne de regroupement
                        echo '<tr><td colspan="3" class="table-primary"><strong>' . htmlspecialchars($currentSpeciality) . '</strong></td></tr>';
                    }
                ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <td>
                            <?php echo htmlspecialchars($question['label']); ?>
                            <?php if ($level === 'Total') {
                                // Afficher le niveau entre parenthèses
                                echo ' (' . htmlspecialchars($question['level']) . ')';
                            } ?>
                        </td>
                        <td><?php echo htmlspecialchars($question['speciality']); ?></td>
                    </tr>
                <?php
                    $counter++;
                } ?>
            </tbody>
        </table>
    </div>
    </body>
    </html>
    <?php
}
?>
