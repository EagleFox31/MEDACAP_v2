<?php
// components/filterAgency.php

function renderFilterAgency($selectedAgency = 'all', $agencies = [], $isEnabled = false) {
    $disabled = $isEnabled ? '' : 'disabled';
    echo '<label for="filterAgency" class="form-label">Filtrer par Agence</label>';
    echo '<select id="filterAgency" class="form-select" aria-label="Filtrer par Agence" ' . $disabled . '>';
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
