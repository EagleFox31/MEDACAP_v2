<?php
/**
 * Système de rappels automatiques pour les tests planifiés dans le calendrier
 * 
 * Ce script est conçu pour être exécuté quotidiennement via cron
 * Il vérifie les tests planifiés et envoie des rappels automatiques:
 * - À la création (confirmation)
 * - La veille du test (J-1)
 * - Le jour du test (jour J)
 * - Le lendemain si test non réalisé (retard)
 */

require_once "../vendor/autoload.php";
require_once "../views/sendMail.php";

// Connexion à MongoDB
$conn = new MongoDB\Client("mongodb://localhost:27017");
$academy = $conn->academy;
$calendars = $academy->calendars;
$users = $academy->users;
$tests = $academy->tests;

// Date du jour
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$yesterday = date('Y-m-d', strtotime('-1 day'));

// Log fonction
function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    echo "[$timestamp] $message\n";
    // Écrire dans un fichier de log aussi
    file_put_contents(
        __DIR__ . '/logs/test_reminders.log', 
        "[$timestamp] $message\n", 
        FILE_APPEND
    );
}

// Fonction pour vérifier si un test est complété
function isTestCompleted($testIds, $technicianIds, $mongo) {
    $tests = $mongo->academy->tests;
    $exams = $mongo->academy->exams;
    
    // Pour chaque test et chaque technicien, vérifier s'il est terminé
    foreach ($testIds as $testId) {
        foreach ($technicianIds as $techId) {
            $test = $tests->findOne([
                '_id' => new MongoDB\BSON\ObjectId($testId),
                'active' => true
            ]);
            
            if (!$test) continue;
            
            // Vérifier si un examen existe et est terminé
            $exam = $exams->findOne([
                'test' => new MongoDB\BSON\ObjectId($testId),
                'user' => new MongoDB\BSON\ObjectId($techId),
                'active' => false // Si active=false, l'examen est terminé
            ]);
            
            // Si un seul test n'est pas terminé, retourner false
            if (!$exam) {
                return false;
            }
        }
    }
    
    // Tous les tests sont terminés
    return true;
}

// 1. Rappels J-1 (pour les tests prévus demain)
logMessage("Traitement des rappels J-1 pour les tests de demain ($tomorrow)");
$tomorrowEvents = $calendars->find([
    'type' => 'test',
    'startDate' => $tomorrow,
    'active' => true,
    'reminderStatus.dayBefore' => false
]);

foreach ($tomorrowEvents as $event) {
    logMessage("Envoi rappel J-1 pour l'événement: " . $event['name']);
    
    // Récupérer les techniciens et admins concernés
    $technicianDocs = getTechnicianDocs($event['technicians'], $conn);
    $adminDocs = getAdminsDocs($technicianDocs[0], $conn); // Utiliser le premier technicien pour déterminer les admins
    
    // Envoyer le rappel
    $success = sendTestDayBeforeMail($adminDocs, $technicianDocs, $event);
    
    if ($success) {
        // Mettre à jour le statut du rappel
        $calendars->updateOne(
            ['_id' => $event['_id']],
            ['$set' => ['reminderStatus.dayBefore' => true]]
        );
        logMessage("Rappel J-1 envoyé avec succès");
    } else {
        logMessage("ERREUR: Échec de l'envoi du rappel J-1");
    }
}

// 2. Rappels jour J (pour les tests prévus aujourd'hui)
logMessage("Traitement des rappels jour J pour les tests d'aujourd'hui ($today)");
$todayEvents = $calendars->find([
    'type' => 'test',
    'startDate' => $today,
    'active' => true,
    'reminderStatus.dayOf' => false
]);

foreach ($todayEvents as $event) {
    logMessage("Envoi rappel jour J pour l'événement: " . $event['name']);
    
    // Récupérer les techniciens et admins concernés
    $technicianDocs = getTechnicianDocs($event['technicians'], $conn);
    $adminDocs = getAdminsDocs($technicianDocs[0], $conn);
    
    // Envoyer le rappel
    $success = sendTestDayOfMail($adminDocs, $technicianDocs, $event);
    
    if ($success) {
        // Mettre à jour le statut du rappel
        $calendars->updateOne(
            ['_id' => $event['_id']],
            ['$set' => ['reminderStatus.dayOf' => true]]
        );
        logMessage("Rappel jour J envoyé avec succès");
    } else {
        logMessage("ERREUR: Échec de l'envoi du rappel jour J");
    }
}

// 3. Rappels de retard (pour les tests prévus hier et non complétés)
logMessage("Traitement des rappels de retard pour les tests d'hier ($yesterday)");
$yesterdayEvents = $calendars->find([
    'type' => 'test',
    'startDate' => $yesterday,
    'active' => true,
    'reminderStatus.dayAfter' => false
]);

foreach ($yesterdayEvents as $event) {
    // Vérifier si les tests liés sont complétés
    $testsCompleted = isTestCompleted($event['tests'] ?? [], $event['technicians'] ?? [], $conn);
    
    // Si les tests sont terminés, ne pas envoyer de rappel de retard
    if ($testsCompleted) {
        logMessage("Tests terminés pour l'événement: " . $event['name'] . ", pas de rappel de retard nécessaire");
        $calendars->updateOne(
            ['_id' => $event['_id']],
            ['$set' => [
                'reminderStatus.dayAfter' => true,
                'status' => 'completed'
            ]]
        );
        continue;
    }
    
    logMessage("Envoi rappel de retard pour l'événement: " . $event['name']);
    
    // Récupérer les techniciens et admins concernés
    $technicianDocs = getTechnicianDocs($event['technicians'], $conn);
    $adminDocs = getAdminsDocs($technicianDocs[0], $conn);
    
    // Envoyer le rappel
    $success = sendTestDelayedMail($adminDocs, $technicianDocs, $event);
    
    if ($success) {
        // Mettre à jour le statut du rappel
        $calendars->updateOne(
            ['_id' => $event['_id']],
            ['$set' => [
                'reminderStatus.dayAfter' => true,
                'status' => 'delayed'
            ]]
        );
        logMessage("Rappel de retard envoyé avec succès");
    } else {
        logMessage("ERREUR: Échec de l'envoi du rappel de retard");
    }
}

logMessage("Traitement des rappels terminé");