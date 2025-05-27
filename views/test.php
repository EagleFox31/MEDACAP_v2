<?php
require_once "../vendor/autoload.php"; // Charger les dépendances nécessaires
include_once "getValidatedResultsOld.php";
include_once "getValidatedResults.php";



// Chronométrage de l'ancienne méthode
$startTimeOld = microtime(true);
$resultsOld = getValidatedResultsOld("Expert");
$endTimeOld = microtime(true);
$elapsedTimeOld = $endTimeOld - $startTimeOld;
echo "Temps écoulé pour l'ancienne méthode : " . $elapsedTimeOld . " secondes.\n";

// Chronométrage de la nouvelle méthode optimisée
$startTimeNew = microtime(true);
$resultsNew = getValidatedResults("Expert");
$endTimeNew = microtime(true);
$elapsedTimeNew = $endTimeNew - $startTimeNew;
echo "Temps écoulé pour la nouvelle méthode : " . $elapsedTimeNew . " secondes.\n";

// Comparaison des résultats
$improvement = (($elapsedTimeOld - $elapsedTimeNew) / $elapsedTimeOld) * 100;
echo "Amélioration de la performance : " . $improvement . "%.\n";
?>
