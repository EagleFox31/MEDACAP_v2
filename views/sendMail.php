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
function getAdminsDocs(array $collab, Client $mongo): array
{
    $users  = $mongo->academy->users;

    $admins = iterator_to_array($users->find([
        'profile'    => 'Admin',
        'subsidiary' => $collab['subsidiary'],
    ]));

    // Affinage department
    if (count($admins) > 1 && !empty($collab['department'])) {
        $admins = array_filter($admins, fn ($u) =>
            strcasecmp($u['department'] ?? '', $collab['department']) === 0
        );
    }
    // Affinage agency
    if (count($admins) > 1 && !empty($collab['agency'])) {
        $admins = array_filter($admins, fn ($u) =>
            strcasecmp($u['agency'] ?? '', $collab['agency']) === 0
        );
    }
    return array_values($admins);  // index propres
}

/**
 * Renvoie la liste d’adresses RH ("Ressource Humaine") et DPS d’une filiale.
 */
function getRhAndDpsEmails(string $subsidiary, Client $mongo): array
{
    $users = $mongo->academy->users;
    $cursor = $users->find([
        'profile'    => ['$in' => ['Ressource Humaine', 'DPS']],
        'subsidiary' => $subsidiary,
    ]);
    return array_unique(array_column(iterator_to_array($cursor), 'email'));
}

/**
 * Salutation adaptée :
 *  – « Bonjour à tous, » si plusieurs admins  
 *  – « Bonjour Mme/M. Dupont, » si un seul (déduit via gender)  
 *  – « Bonjour, » fallback.
 */
function buildAdminSalutation(array $adminDocs): string
{
    $nb = count($adminDocs);
    if ($nb === 0) {
        return 'Bonjour,';
    }
    if ($nb > 1) {
        return 'Bonjour à tous,';
    }
    $admin  = $adminDocs[0];
    $gender = strtolower($admin['gender'] ?? '');
    $prefix = $gender === 'feminin'  ? 'Mme'
           : ($gender === 'masculin' ? 'M.'  : '');
    return 'Bonjour ' . ($prefix ? $prefix . ' ' : '')
         . htmlspecialchars($admin['lastName']) . ',';
}

/* =========================================================================
   ENVOIS “SIMPLES” (inchangés)
   ========================================================================= */
function sendMailRegisterUser(string $to, string $subject, string $message): bool
{
    return mail($to, $subject, $message . SIGNATURE_HTML, buildHeaders());
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
