<?php
// components/filterTechnician.php

/**
 * Rend un filtre de sélection de technicien
 * 
 * @param string $selectedTechnicianId ID du technicien actuellement sélectionné
 * @param array $technicians Liste des techniciens disponibles [id => nom]
 * @param bool $isDisabled Si le filtre doit être désactivé
 * @param bool $isLocked Si le filtre doit être verrouillé (préselectionné)
 */
function renderFilterTechnician($selectedTechnicianId = 'all', $technicians = [], $isDisabled = false, $isLocked = false) {
    // Déterminer la classe CSS basée sur l'état
    $wrapperClass = 'filter-wrapper';
    if ($isDisabled) {
        $wrapperClass .= ' filter-disabled';
    } elseif ($isLocked) {
        $wrapperClass .= ' filter-locked';
    }

    // Déterminer l'attribut disabled
    $disabledAttr = ($isDisabled || $isLocked) ? 'disabled' : '';

    echo '<div class="' . $wrapperClass . '" id="technicianFilterWrapper">';
    echo '<label for="filterTechnician" class="form-label">Technician</label>';
    echo '<select id="filterTechnician" name="technicianId" class="form-select" aria-label="Filtrer par Technicien" ' . $disabledAttr . '>';
    echo '<option value="all"' . ($selectedTechnicianId === 'all' ? ' selected' : '') . '>Tous les techniciens</option>';

    foreach ($technicians as $id => $name) {
        $selected = ($selectedTechnicianId === $id) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($id) . '" ' . $selected . '>' . htmlspecialchars($name) . '</option>';
    }
    
    echo '</select>';
    echo '</div>';
}
?>