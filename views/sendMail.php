<?php
use MongoDB\Client;

/* =========================================================================
   CONFIGURATION GLOBALE
   ========================================================================= */
const FROM_NAME  = 'CFAO Mobility Academy';
const FROM_EMAIL = 'helpdeskcm@cfao.com';

const CC_STATIC = [
  'aybokally@cfao.com',
  'lgonin@cfao.com',
  'jakaa-ext@cfao.com',   // Jennifer
];

const SIGNATURE_HTML = '<p style="margin-top:50px;font-size:20px">
  Cordialement&nbsp;|&nbsp;Best&nbsp;Regards&nbsp;|&nbsp;よろしくお願いしま。
</p>';

/**
 * Construit les en-têtes HTML standard.
 * @param array $extraCc  éventuelles adresses supplémentaires en copie
 */
function buildHeaders(array $extraCc = []): string
{
    $cc = array_unique(array_merge(CC_STATIC, $extraCc));

    return implode("\r\n", [
        'From: ' . FROM_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To: ' . FROM_EMAIL,
        empty($cc) ? '' : 'Cc: ' . implode(', ', $cc),
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion(),
    ]);
}

/* =========================================================================
   HELPERS MONGO : QUI DOIT RECEVOIR QUOI ?
   ========================================================================= */

/**
 * Renvoie les documents Mongo des administrateurs (profil "Admin") de la même
 * filiale que $collab, puis filtre par department / agency si nécessaire.
 */
/* Renvoie les docs Mongo des admins de la même filiale
 * puis filtre par department / agency si nécessaire.       */
 function getAdminsDocs($collab, Client $mongo): array
 {

    if (is_object($collab)) {
        $collab = json_decode(json_encode($collab), true);
    }

     $users  = $mongo->academy->users;
 
     $admins = iterator_to_array($users->find([
         'profile'    => 'Admin',
         'subsidiary' => $collab['subsidiary'],
     ]));
 
     // Affinage department
     if (count($admins) > 1 && !empty($collab['department'])) {
         $admins = array_filter($admins, function ($u) use ($collab) {
             return strcasecmp($u['department'] ?? '', $collab['department']) === 0;
         });
     }
 
     // Affinage agency
     if (count($admins) > 1 && !empty($collab['agency'])) {
         $admins = array_filter($admins, function ($u) use ($collab) {
             return strcasecmp($u['agency'] ?? '', $collab['agency']) === 0;
         });
     }
     return array_values($admins);   // index propres
 }

/**
 * Renvoie la liste d’adresses RH ("Ressource Humaine") et DPS d’une filiale.
 */
function getRhAndDpsEmails(string $subsidiary, Client $mongo): array
{
    $users = $mongo->academy->users;
    $cursor = $users->find([
        'profile'    => ['$in' => ['Ressource Humaine', 'Directeur Pièce et Service']],
        'subsidiary' => $subsidiary,
    ]);
    return array_unique(array_column(iterator_to_array($cursor), 'email'));
}

/**
 * Salutation adaptée :
 *  – « Bonjour à tous, » si plusieurs admins
 *  – « Bonjour Mme/M. Dupont, » si un seul (déduit via gender)
 *  – « Bonjour, » fallback.
 *  – English equivalents for certain subsidiaries
 */
function buildAdminSalutation(array $adminDocs): string
{
    $nb = count($adminDocs);
    if ($nb === 0) {
        return 'Bonjour,';
    }
    
    // Check if the subsidiary requires English
    $isEnglish = false;
    if (isset($adminDocs[0]['subsidiary'])) {
        $subsidiary = $adminDocs[0]['subsidiary'];
        $isEnglish = ($subsidiary === 'CFAO MOTORS RWANDA');
    }
    
    if ($nb > 1) {
        return $isEnglish ? 'Hello everyone,' : 'Bonjour à tous,';
    }
    
    $admin  = $adminDocs[0];
    $gender = strtolower($admin['gender'] ?? '');
    
    if ($isEnglish) {
        // English salutation
        $prefix = $gender === 'feminin' ? 'Ms.' : ($gender === 'masculin' ? 'Mr.' : 'Mr.');
        return 'Hello ' . $prefix . ' ' . htmlspecialchars($admin['lastName']) . ',';
    } else {
        // French salutation
        $prefix = $gender === 'feminin' ? 'Mme' : ($gender === 'masculin' ? 'M.' : 'M.');
        return 'Bonjour ' . $prefix . ' ' . htmlspecialchars($admin['lastName']) . ',';
    }
}

/* =========================================================================
   ENVOIS “SIMPLES” (inchangés)
   ========================================================================= */
function sendMailRegisterUser(string $to, string $subject, string $message): bool
{
    return mail($to, $subject, $message, buildHeaders());
}

function sendMailRegisterTraining(
    string $to,
    string $subject,
    string $message,
    string $trainerEmail
): bool {
    return mail(
        $to,
        $subject,
        $message . SIGNATURE_HTML,
        buildHeaders([$trainerEmail, 'ceyinga-ext@cfao.com'])
    );
}

function sendMailSelectDone(string $subject, string $message): bool
{
    return mail(
        'jakaa-ext@cfao.com',
        $subject,
        $message . SIGNATURE_HTML,
        buildHeaders()
    );
}

/* =========================================================================
   QCM FINALISÉ – Destinataires & salutation dynamiques
   =========================================================================
   $adminDocs    = docs Mongo des admins (obtenus via getAdminsDocs)
   $managerEmail = e-mail du manager (copie)
   $rhEmails     = RH de la filiale   (copie)
   $dpsEmails    = DPS de la filiale  (copie)
*/
function sendMailQcmFinalized(
    array  $adminDocs,
    string $subject,
    string $bodyWithoutSignature,   // déjà salutation + contenu
    string $managerEmail,
    array  $rhEmails = [],
    array  $dpsEmails = []
): bool {
    if (empty($adminDocs)) {
        return false;               // sécurité : aucun admin, on n’envoie pas
    }

    $to = implode(', ', array_column($adminDocs, 'email'));
    $cc = array_merge([$managerEmail], $rhEmails, $dpsEmails);

    return mail(
        $to,
        $subject,
        $bodyWithoutSignature . SIGNATURE_HTML,
        buildHeaders($cc)
    );
}
/* =========================================================================
   ENVOIS CANDIDATS
   ========================================================================= */

/**
 * Notify administrators about a newly created candidate.
 *
 * @param array  $adminDocs      Admin documents from MongoDB  
 * @param array  $candidate      Candidate document from MongoDB
 * @param string $visiblePassword Plain text password for the candidate
 * @param array  $managerEmails   Emails of managers to put in CC
 */
function sendCandidateCreationMail(array $adminDocs, array $candidate, string $visiblePassword, array $managerEmails = []): bool
{
    if (empty($adminDocs)) {
        return false;
    }

    $salutation = buildAdminSalutation($adminDocs);
    $subject = 'Nouveau compte candidat créé';
    $body    = sprintf(
        '%s<br><br>Un nouveau compte candidat a été créé sur la plateforme MEDACAP:<br><br>' .
        'Nom : %s %s<br>' .
        'Email : %s<br>' .
        'Identifiant : %s<br>' .
        'Mot de passe : %s',
        $salutation,
        htmlspecialchars($candidate['firstName']),
        htmlspecialchars($candidate['lastName']),
        htmlspecialchars($candidate['email']),
        htmlspecialchars($candidate['username']),
        htmlspecialchars($visiblePassword)
    );

    $to = implode(', ', array_column($adminDocs, 'email'));

    return mail(
        $to,
        $subject,
        $body . SIGNATURE_HTML,
        buildHeaders($managerEmails)
    );
}

/**
 * Inform administrators that a candidate has finished all factual tests.
 *
 * @param array $adminDocs Admin documents from MongoDB
 * @param array $candidate Candidate document
 * @param array $managerEmails Emails of managers to put in CC
 */
function sendCandidateFinishedMail(array $adminDocs, array $candidate, array $managerEmails = []): bool
{
    if (empty($adminDocs)) {
        return false;
    }

    $salutation = buildAdminSalutation($adminDocs);
    $subject = 'Tests candidat terminés';
    $body    = sprintf(
        '%s<br><br>Le candidat %s %s a terminé ses tests factuels.',
        $salutation,
        htmlspecialchars($candidate['firstName']),
        htmlspecialchars($candidate['lastName'])
    );

    $to = implode(', ', array_column($adminDocs, 'email'));

    return mail(
        $to,
        $subject,
        $body . SIGNATURE_HTML,
        buildHeaders($managerEmails)
    );
}

/* =========================================================================
   FONCTIONS POUR LES RAPPELS DE TESTS
   ========================================================================= */

/**
 * Envoie un email de confirmation de planification de test
 *
 * @param array $adminDocs Documents MongoDB des administrateurs destinataires
 * @param array $technicianDocs Documents MongoDB des techniciens concernés
 * @param array $testEvent Données de l'événement de test
 * @return bool Succès ou échec de l'envoi
 */
function sendTestScheduledMail(array $adminDocs, array $technicianDocs, array $testEvent): bool
{
    if (empty($adminDocs)) {
        return false;
    }

    $salutation = buildAdminSalutation($adminDocs);
    
    // Liste des techniciens concernés
    $techList = '';
    foreach ($technicianDocs as $tech) {
        $techList .= sprintf(
            '- %s %s (%s)<br>',
            htmlspecialchars($tech['firstName']),
            htmlspecialchars($tech['lastName']),
            htmlspecialchars($tech['level'])
        );
    }
    
    // Get managers' email addresses (not technicians)
    $managerEmails = [];
    foreach ($technicianDocs as $tech) {
        if (isset($tech['manager_email']) && !empty($tech['manager_email'])) {
            $managerEmails[] = $tech['manager_email'];
        }
    }
    $managerEmails = array_unique($managerEmails);
    
    // Use English or French subject based on admin language preference
    $isEnglish = isset($adminDocs[0]['language']) && strtolower($adminDocs[0]['language']) === 'en';
    
    $subject = '';
    $body = '';
    
    if ($isEnglish) {
        $subject = 'Test Scheduling: ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>A new test has been scheduled in the calendar:<br><br>' .
            '<strong>Test name:</strong> %s<br>' .
            '<strong>Description:</strong> %s<br>' .
            '<strong>Location:</strong> %s<br>' .
            '<strong>Start date:</strong> %s at %s<br>' .
            '<strong>End date:</strong> %s at %s<br><br>' .
            '<strong>Technicians involved:</strong><br>%s<br>' .
            'You will receive automatic reminders before the test.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startDate']),
            htmlspecialchars($testEvent['startTime']),
            htmlspecialchars($testEvent['endDate']),
            htmlspecialchars($testEvent['endTime']),
            $techList
        );
    } else {
        $subject = 'Planification de test : ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>Un nouveau test a été planifié dans le calendrier :<br><br>' .
            '<strong>Nom du test :</strong> %s<br>' .
            '<strong>Description :</strong> %s<br>' .
            '<strong>Lieu :</strong> %s<br>' .
            '<strong>Date de début :</strong> %s à %s<br>' .
            '<strong>Date de fin :</strong> %s à %s<br><br>' .
            '<strong>Techniciens concernés :</strong><br>%s<br>' .
            'Vous recevrez des rappels automatiques avant le test.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startDate']),
            htmlspecialchars($testEvent['startTime']),
            htmlspecialchars($testEvent['endDate']),
            htmlspecialchars($testEvent['endTime']),
            $techList
        );
    }
    
    $to = implode(', ', array_column($adminDocs, 'email'));
    
    return mail(
        $to,
        $subject,
        $body . SIGNATURE_HTML,
        buildHeaders($managerEmails)
    );
}

/**
 * Envoie un rappel la veille du test
 *
 * @param array $adminDocs Documents MongoDB des administrateurs destinataires
 * @param array $technicianDocs Documents MongoDB des techniciens concernés
 * @param array $testEvent Données de l'événement de test
 * @return bool Succès ou échec de l'envoi
 */
function sendTestDayBeforeMail(array $adminDocs, array $technicianDocs, array $testEvent): bool
{
    if (empty($adminDocs)) {
        return false;
    }

    $salutation = buildAdminSalutation($adminDocs);
    
    // Liste des techniciens concernés
    $techList = '';
    foreach ($technicianDocs as $tech) {
        $techList .= sprintf(
            '- %s %s (%s)<br>',
            htmlspecialchars($tech['firstName']),
            htmlspecialchars($tech['lastName']),
            htmlspecialchars($tech['level'])
        );
    }
    
    // Get managers' email addresses (not technicians)
    $managerEmails = [];
    foreach ($technicianDocs as $tech) {
        if (isset($tech['manager_email']) && !empty($tech['manager_email'])) {
            $managerEmails[] = $tech['manager_email'];
        }
    }
    $managerEmails = array_unique($managerEmails);
    
    // Use English if the subsidiary is CFAO MOTORS RWANDA
    $isEnglish = false;
    if (isset($adminDocs[0]['subsidiary'])) {
        $subsidiary = $adminDocs[0]['subsidiary'];
        $isEnglish = ($subsidiary === 'CFAO MOTORS RWANDA');
    }
    
    $subject = '';
    $body = '';
    
    if ($isEnglish) {
        $subject = 'REMINDER D-1: Test scheduled tomorrow - ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>Reminder: A test is scheduled for <strong>tomorrow</strong>:<br><br>' .
            '<strong>Test name:</strong> %s<br>' .
            '<strong>Description:</strong> %s<br>' .
            '<strong>Location:</strong> %s<br>' .
            '<strong>Date:</strong> %s<br>' .
            '<strong>Time:</strong> %s<br><br>' .
            '<strong>Technicians involved:</strong><br>%s<br>' .
            'Please ensure that everything is ready for the test.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startDate']),
            htmlspecialchars($testEvent['startTime']),
            $techList
        );
    } else {
        $subject = 'RAPPEL J-1 : Test prévu demain - ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>Rappel : Un test est prévu pour <strong>demain</strong> :<br><br>' .
            '<strong>Nom du test :</strong> %s<br>' .
            '<strong>Description :</strong> %s<br>' .
            '<strong>Lieu :</strong> %s<br>' .
            '<strong>Date :</strong> %s<br>' .
            '<strong>Heure :</strong> %s<br><br>' .
            '<strong>Techniciens concernés :</strong><br>%s<br>' .
            'Veuillez vous assurer que tout est prêt pour le test.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startDate']),
            htmlspecialchars($testEvent['startTime']),
            $techList
        );
    }
    
    $to = implode(', ', array_column($adminDocs, 'email'));
    
    return mail(
        $to,
        $subject,
        $body . SIGNATURE_HTML,
        buildHeaders($managerEmails)
    );
}

/**
 * Envoie un rappel le jour du test
 *
 * @param array $adminDocs Documents MongoDB des administrateurs destinataires
 * @param array $technicianDocs Documents MongoDB des techniciens concernés
 * @param array $testEvent Données de l'événement de test
 * @return bool Succès ou échec de l'envoi
 */
function sendTestDayOfMail(array $adminDocs, array $technicianDocs, array $testEvent): bool
{
    if (empty($adminDocs)) {
        return false;
    }

    $salutation = buildAdminSalutation($adminDocs);
    
    // Liste des techniciens concernés
    $techList = '';
    foreach ($technicianDocs as $tech) {
        $techList .= sprintf(
            '- %s %s (%s)<br>',
            htmlspecialchars($tech['firstName']),
            htmlspecialchars($tech['lastName']),
            htmlspecialchars($tech['level'])
        );
    }
    
    // Get managers' email addresses (not technicians)
    $managerEmails = [];
    foreach ($technicianDocs as $tech) {
        if (isset($tech['manager_email']) && !empty($tech['manager_email'])) {
            $managerEmails[] = $tech['manager_email'];
        }
    }
    $managerEmails = array_unique($managerEmails);
    
    // Use English or French based on admin language preference
    $isEnglish = isset($adminDocs[0]['language']) && strtolower($adminDocs[0]['language']) === 'en';
    
    if ($isEnglish) {
        $subject = 'TODAY: Test to complete - ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>A test is scheduled for <strong>today</strong>:<br><br>' .
            '<strong>Test name:</strong> %s<br>' .
            '<strong>Description:</strong> %s<br>' .
            '<strong>Location:</strong> %s<br>' .
            '<strong>Time:</strong> %s<br><br>' .
            '<strong>Technicians involved:</strong><br>%s<br>' .
            'Please ensure that the test is properly completed today.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startTime']),
            $techList
        );
    } else {
        $subject = 'AUJOURD\'HUI : Test à réaliser - ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>Un test est prévu <strong>aujourd\'hui</strong> :<br><br>' .
            '<strong>Nom du test :</strong> %s<br>' .
            '<strong>Description :</strong> %s<br>' .
            '<strong>Lieu :</strong> %s<br>' .
            '<strong>Heure :</strong> %s<br><br>' .
            '<strong>Techniciens concernés :</strong><br>%s<br>' .
            'Merci de vous assurer que le test est bien réalisé aujourd\'hui.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startTime']),
            $techList
        );
    }
    
    $to = implode(', ', array_column($adminDocs, 'email'));
    
    return mail(
        $to,
        $subject,
        $body . SIGNATURE_HTML,
        buildHeaders($managerEmails)
    );
}

/**
 * Envoie un rappel de retard si le test n'a pas été complété
 *
 * @param array $adminDocs Documents MongoDB des administrateurs destinataires
 * @param array $technicianDocs Documents MongoDB des techniciens concernés
 * @param array $testEvent Données de l'événement de test
 * @return bool Succès ou échec de l'envoi
 */
function sendTestDelayedMail(array $adminDocs, array $technicianDocs, array $testEvent): bool
{
    if (empty($adminDocs)) {
        return false;
    }

    $salutation = buildAdminSalutation($adminDocs);
    
    // Liste des techniciens concernés
    $techList = '';
    foreach ($technicianDocs as $tech) {
        $techList .= sprintf(
            '- %s %s (%s)<br>',
            htmlspecialchars($tech['firstName']),
            htmlspecialchars($tech['lastName']),
            htmlspecialchars($tech['level'])
        );
    }
    
    // Get managers' email addresses (not technicians)
    $managerEmails = [];
    foreach ($technicianDocs as $tech) {
        if (isset($tech['manager_email']) && !empty($tech['manager_email'])) {
            $managerEmails[] = $tech['manager_email'];
        }
    }
    $managerEmails = array_unique($managerEmails);
    
    // Use English or French based on admin language preference
    $isEnglish = isset($adminDocs[0]['language']) && strtolower($adminDocs[0]['language']) === 'en';
    
    if ($isEnglish) {
        $subject = 'DELAYED: Test not completed - ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>A test scheduled for <strong>yesterday</strong> has not yet been marked as completed:<br><br>' .
            '<strong>Test name:</strong> %s<br>' .
            '<strong>Description:</strong> %s<br>' .
            '<strong>Location:</strong> %s<br>' .
            '<strong>Scheduled date:</strong> %s<br>' .
            '<strong>Scheduled time:</strong> %s<br><br>' .
            '<strong>Technicians involved:</strong><br>%s<br>' .
            'Please check the status of the test and complete it as soon as possible.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startDate']),
            htmlspecialchars($testEvent['startTime']),
            $techList
        );
    } else {
        $subject = 'RETARD : Test non réalisé - ' . htmlspecialchars($testEvent['name']);
        
        $body = sprintf(
            '%s<br><br>Un test planifié <strong>hier</strong> n\'a pas encore été marqué comme terminé :<br><br>' .
            '<strong>Nom du test :</strong> %s<br>' .
            '<strong>Description :</strong> %s<br>' .
            '<strong>Lieu :</strong> %s<br>' .
            '<strong>Date prévue :</strong> %s<br>' .
            '<strong>Heure prévue :</strong> %s<br><br>' .
            '<strong>Techniciens concernés :</strong><br>%s<br>' .
            'Merci de vérifier le statut du test et de le compléter dès que possible.',
            $salutation,
            htmlspecialchars($testEvent['name']),
            htmlspecialchars($testEvent['description']),
            htmlspecialchars($testEvent['location']),
            htmlspecialchars($testEvent['startDate']),
            htmlspecialchars($testEvent['startTime']),
            $techList
        );
    }
    
    $to = implode(', ', array_column($adminDocs, 'email'));
    
    return mail(
        $to,
        $subject,
        $body . SIGNATURE_HTML,
        buildHeaders($managerEmails)
    );
}

/**
 * Récupère les documents des techniciens à partir de leurs IDs
 *
 * @param array $technicianIds Tableau d'IDs de techniciens
 * @param Client $mongo Instance de connexion MongoDB
 * @return array Documents des techniciens
 */
function getTechnicianDocs(array $technicianIds, Client $mongo): array
{
    $users = $mongo->academy->users;
    $techDocs = [];
    
    foreach ($technicianIds as $id) {
        $tech = $users->findOne([
            '_id' => new MongoDB\BSON\ObjectId($id),
            'active' => true
        ]);
        
        if ($tech) {
            $techDocs[] = $tech;
        }
    }
    
    return $techDocs;
}
