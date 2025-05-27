<?php
session_start();
include_once "../language.php";
include_once "getValidatedResults.php"; 
include_once "userFilters.php";

if (!isset($_SESSION["profile"])) {
    header("Location: ../../");
    exit();
} else {
    $level = isset($_GET['level']) ? $_GET['level'] : 'Total';
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;

    // Connexion à MongoDB
    $conn = new MongoDB\Client("mongodb://localhost:27017");
    $academy = $conn->academy;

    // Récupérer les résultats validés pour le niveau sélectionné
    if ($level !== 'Total') {
        $tableauResultats = getValidatedResults($level);
    } else {
        $tableauResultats = array_merge(
            getValidatedResults($junior),
            getValidatedResults($senior),
            getValidatedResults($expert)
        );
    }

    // Filtrer les techniciens
    $technicians = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, $level !== 'Total' ? $level : null);

    // Récupérer les questions correspondantes
    $questions = $academy->questions->find([
        "type" => "Declarative",
        "level" => $level !== 'Total' ? $level : ['$in' => ['Junior', 'Senior', 'Expert']],
        "active" => true
    ])->toArray();

    $filteredQuestions = [];

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

        // Filtrer en fonction du type sélectionné
        if (($type == 'nonMaitrise' && $totalMaitrise == 0) ||
            ($type == 'singleMaitrise' && $totalMaitrise == 1) ||
            ($type == 'doubleMaitrise' && $totalMaitrise == 2) ||
            ($type == 'others' && $totalMaitrise > 2)) {
            $filteredQuestions[] = $question;
        }
    }
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
         <!-- Inclure Bootstrap via CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <h1 class="my-4"><?php echo $tache_pro;?> <?php echo htmlspecialchars($level); ?></h1>
        <h2>
            <?php
            if ($type == 'nonMaitrise') {
                echo $Tasks_Not_Covered;
            } elseif ($type == 'singleMaitrise') {
                echo $Tasks_Critic;
            } elseif ($type == 'doubleMaitrise') {
                echo $Tasks_Two_Tech;
            } else {
                echo $Tasks_Covered;
            }
            ?>
        </h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th> <!-- En-tête pour la numérotation -->
                    <th><center><h3><b><?php echo $tache_pro ?></b></h3></center></th>
                    <!-- Ajoutez d'autres en-têtes si nécessaire -->
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
                        <td><?php echo htmlspecialchars($question['label']); ?></td>
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
