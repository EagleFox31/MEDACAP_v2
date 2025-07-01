<?php
// components/filterAgency.php

function renderFilterAgency($selectedAgency = 'all', $agencies = [], $isEnabled = false) {
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
