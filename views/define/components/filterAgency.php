<?php
// components/filterAgency.php

/**
 * Retourne la liste des agences pour un pays donné.
 * Les mappings proviennent du header (views/define/partials/header.php).
 */
function getAgenciesFromConfig(string $country): array
{
    $mapping = [
        'Burkina Faso'   => ['Ouaga'],
        'Cameroun'       => ['Bafoussam', 'Bertoua', 'Douala', 'Garoua', 'Ngaoundere', 'Yaoundé'],
        "Cote d'Ivoire" => ['Vridi - Equip'],
        'Gabon'          => ['Libreville'],
        'Madagascar'     => ['Ankorondrano', 'Anosizato', 'Diego', 'Moramanga', 'Tamatave'],
        'Mali'           => ['Bamako'],
        'RCA'            => ['Bangui'],
        'RDC'            => ['Kinshasa', 'Kolwezi', 'Lubumbashi'],
        'Senegal'        => ['Dakar'],
        'Congo'          => ['Brazzaville', 'Pointe-Noire'],
    ];

    return $mapping[$country] ?? [];
}

function renderFilterAgency($selectedAgency = 'all', $agencies = [], $isEnabled = false)
{
    $disabled = $isEnabled ? '' : 'disabled';

    // Si aucun tableau d'agences n'est fourni, on tente de charger celui
    // défini pour la filiale sélectionnée dans agencyData.php
    if ($isEnabled && empty($agencies)) {
        $subsidiary = $_GET['subsidiary'] ?? $_SESSION['subsidiary'] ?? '';
        $agencyMap = include __DIR__ . '/agencyData.php';
        if ($subsidiary && isset($agencyMap[$subsidiary])) {
            $agencies = $agencyMap[$subsidiary];
        }
    }

    echo '<label for="filterAgency" class="form-label">Filtrer par Agence</label>';
    echo '<select id="filterAgency" class="form-select" name="agency" aria-label="Filtrer par Agence" ' . $disabled . '>';
    echo '<option value="all">Toutes les Agences</option>';
    if ($isEnabled && !empty($agencies)) {
        foreach ($agencies as $agencyName) {
            $selected = ($selectedAgency === $agencyName) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($agencyName) . '" ' . $selected . '>' . htmlspecialchars($agencyName) . '</option>';
        }
    }
    echo '</select>';
}
?>
