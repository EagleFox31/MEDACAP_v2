<?php
include_once "language.php";
include_once "getValidatedResults.php"; 
include_once "userFilters.php";

require_once "../vendor/autoload.php";
// Liste des pays disponibles pour la sélection
    $countries = [
        "Burkina Faso", "Cameroun", "Cote d'Ivoire", "Gabon", "Mali", "RCA", "RDC", "Senegal"
    ];

    // Récupérer les paramètres depuis l'URL
    $selectedCountry = isset($_GET['country']) ? $_GET['country'] : null;
    $selectedAgency = isset($_GET['agency']) ? $_GET['agency'] : null;

    // Connexion à MongoDB
    $conn = new MongoDB\Client("mongodb://localhost:27017");
    $academy = $conn->academy;

    // Récupérer les agences du pays sélectionné si le profil est Directeur de Filiale ou Super Admin
    if ($_SESSION['profile'] == 'Directeur de Filiale' || $_SESSION['profile'] == 'Super Admin') {
        // Si aucun pays n'est sélectionné, utiliser le pays de l'utilisateur
        if (!$selectedCountry) {
            $selectedCountry = $_SESSION['country'];
        }

        // Récupérer les agences du pays sélectionné
        $agencies = $academy->agencies->find([
            'country' => $selectedCountry
        ])->toArray();
    }

    // Fonction pour calculer le pourcentage de non-maîtrise
    function calculateQuestionMasteryStats($academy, $level, $country, $tableauResultats, $agency = null) {
        // Récupérer les questions "Déclaratives" actives pour le niveau donné
        $questions = $academy->questions->find([
            "type" => "Declarative",
            "level" => $level,
            "active" => true
        ])->toArray();
        
        // Filtrer les techniciens en fonction du profil, du pays, du niveau et de l'agence
        $technicians = filterUsersByProfile($academy, $_SESSION['profile'], $country, $level, $agency);
        $totalQuestions = count($questions);
        $totalNonMaitriseQuestions = 0;
        $totalSingleMaitriseQuestions = 0;
        $totalDoubleMaitriseQuestions = 0; // Nouveau compteur pour totalMaitrise == 2
    
        foreach ($questions as $question) { 
            $totalMaitrise = 0;
            $questionId = (string)$question['_id']; 
            
            foreach ($technicians as $technician) {
                $techId = (string)$technician['_id'];
    
                // Vérifier si le résultat est disponible dans le tableau des résultats
                $status = isset($tableauResultats[$techId][$questionId]) ? $tableauResultats[$techId][$questionId] : 'Non maîtrisé';
                
                // Compter le nombre de "Maîtrisé"
                if ($status == "Maîtrisé") {
                    $totalMaitrise++;
                }
            }
    
            // Compter les questions en fonction du nombre de techniciens qui les maîtrisent
            if ($totalMaitrise == 0) {
                $totalNonMaitriseQuestions++;
            } elseif ($totalMaitrise == 1) {
                $totalSingleMaitriseQuestions++;
            } elseif ($totalMaitrise == 2) {
                $totalDoubleMaitriseQuestions++; // Incrémenter le compteur pour 2 techniciens
            }
        }
    
        // Retourner les statistiques
        return [
            'totalQuestions' => $totalQuestions,
            'nonMaitrise' => $totalNonMaitriseQuestions,
            'singleMaitrise' => $totalSingleMaitriseQuestions,
            'doubleMaitrise' => $totalDoubleMaitriseQuestions // Ajouter ce champ
        ];
    }
    
    // Récupérer les résultats validés pour chaque niveau
    $junior = 'Junior';
    $senior = 'Senior';
    $expert = 'Expert';
    $total = 'Total';

    $tableauResultatsJunior = getValidatedResults($junior);
    $tableauResultatsSenior = getValidatedResults($senior);
    $tableauResultatsExpert = getValidatedResults($expert);

    // Calcul des statistiques pour chaque niveau
    $statsJunior = calculateQuestionMasteryStats($academy, $junior, $selectedCountry, $tableauResultatsJunior, $selectedAgency);
    $statsSenior = calculateQuestionMasteryStats($academy, $senior, $selectedCountry, $tableauResultatsSenior, $selectedAgency);
    $statsExpert = calculateQuestionMasteryStats($academy, $expert, $selectedCountry, $tableauResultatsExpert, $selectedAgency);

    // Calcul des pourcentages pour chaque niveau
    function calculatePercentages($stats) {
        $totalQuestions = $stats['totalQuestions'];
        $percentages = [
            'nonMaitrise' => ($totalQuestions > 0) ? round(($stats['nonMaitrise'] / $totalQuestions) * 100) : 0,
            'singleMaitrise' => ($totalQuestions > 0) ? round(($stats['singleMaitrise'] / $totalQuestions) * 100) : 0,
            'doubleMaitrise' => ($totalQuestions > 0) ? round(($stats['doubleMaitrise'] / $totalQuestions) * 100) : 0,
        ];
        // Calculer le pourcentage restant pour les autres tâches
        $percentages['others'] = 100 - ($percentages['nonMaitrise'] + $percentages['singleMaitrise'] + $percentages['doubleMaitrise']);
        return $percentages;
    }

    // Calcul des pourcentages pour chaque niveau
    $percentagesJunior = calculatePercentages($statsJunior);
    $percentagesSenior = calculatePercentages($statsSenior);
    $percentagesExpert = calculatePercentages($statsExpert);

    // Calcul du total pour tous les niveaux
    $totalQuestionsAllLevels = $statsJunior['totalQuestions'] + $statsSenior['totalQuestions'] + $statsExpert['totalQuestions'];
    $totalNonMaitriseAllLevels = $statsJunior['nonMaitrise'] + $statsSenior['nonMaitrise'] + $statsExpert['nonMaitrise'];
    $totalSingleMaitriseAllLevels = $statsJunior['singleMaitrise'] + $statsSenior['singleMaitrise'] + $statsExpert['singleMaitrise'];
    $totalDoubleMaitriseAllLevels = $statsJunior['doubleMaitrise'] + $statsSenior['doubleMaitrise'] + $statsExpert['doubleMaitrise'];

    $statsTotal = [
        'totalQuestions' => $totalQuestionsAllLevels,
        'nonMaitrise' => $totalNonMaitriseAllLevels,
        'singleMaitrise' => $totalSingleMaitriseAllLevels,
        'doubleMaitrise' => $totalDoubleMaitriseAllLevels
    ];

    $statsJunior['othersCount'] = $statsJunior['totalQuestions'] - ($statsJunior['nonMaitrise'] + $statsJunior['singleMaitrise'] + $statsJunior['doubleMaitrise']);
    $statsSenior['othersCount'] = $statsSenior['totalQuestions'] - ($statsSenior['nonMaitrise'] + $statsSenior['singleMaitrise'] + $statsSenior['doubleMaitrise']);
    $statsExpert['othersCount'] = $statsExpert['totalQuestions'] - ($statsExpert['nonMaitrise'] + $statsExpert['singleMaitrise'] + $statsExpert['doubleMaitrise']);
    $statsTotal['othersCount'] = $statsTotal['totalQuestions'] - ($statsTotal['nonMaitrise'] + $statsTotal['singleMaitrise'] + $statsTotal['doubleMaitrise']);

    $percentagesTotal = calculatePercentages($statsTotal);

    // Récupérer les techniciens pour chaque niveau
    $techniciansJunior = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Junior', $selectedAgency);
    $numberOfTechniciansJunior = count($techniciansJunior);

    $techniciansSenior = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Senior', $selectedAgency);
    $numberOfTechniciansSenior = count($techniciansSenior);

    $techniciansExpert = filterUsersByProfile($academy, $_SESSION['profile'], $selectedCountry, 'Expert', $selectedAgency);
    $numberOfTechniciansExpert = count($techniciansExpert);

    // Calculer le nombre total de techniciens uniques
    $allTechnicians = array_merge($techniciansJunior, $techniciansSenior, $techniciansExpert);

    // Extraire les IDs des techniciens
    $technicianIds = array();
    foreach ($allTechnicians as $technician) {
        $technicianIds[] = (string)$technician['_id'];
    }

    // Obtenir les IDs uniques
    $uniqueTechnicianIds = array_unique($technicianIds);
    $numberOfTechniciansTotal = count($uniqueTechnicianIds);

    // Nombre total de tâches pour chaque niveau
    $numberOfTasksJunior = $statsJunior['totalQuestions'];
    $numberOfTasksSenior = $statsSenior['totalQuestions'];
    $numberOfTasksExpert = $statsExpert['totalQuestions'];
    $numberOfTasksTotal = $statsTotal['totalQuestions'];

    // Messages pour les légendes
    $legend_zero_mastery = "Aucun technicien maîtrise";
    $legend_one_mastery = "1 seul technicien maîtrise";
    $legend_two_mastery = "Seuls 2 techniciens maîtrisent";
    $legend_more_mastery = "Plus de 3 techniciens maîtrisent";

    // Messages pour les tooltips
    $Tasks_Professional_Singular = "tâche";
    $Tasks_Professional_Plural = "tâches";
    $Maitrise_technicien = "Maîtrise des techniciens";
    $nbreTechie = "Nombre de techniciens";

?>