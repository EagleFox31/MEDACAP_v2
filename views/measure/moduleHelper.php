<?php
// includes/ModuleHelper.php

class ModuleHelper {
    /**
     * Détermine les modules actifs basés sur l'URL actuelle.
     *
     * @return array Les modules actifs trouvés dans l'URL.
     */
    public static function getCurrentModule() {
        // Liste des modules à vérifier
        $modulesToCheck = ['measure', 'explore', 'define'];
        
        // Obtenir l'URL actuelle sans les paramètres GET
        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Debugging : Log l'URL actuelle
        error_log("ModuleHelper::getCurrentModule - Current URL: " . $currentUrl);
        
        $activeModules = [];
        
        // Vérifier si l'un des modules est présent dans l'URL
        foreach ($modulesToCheck as $module) {
            if (stripos($currentUrl, '/' . $module . '/') !== false) {
                $activeModules[] = $module;
            }
        }
        
        // Éliminer les doublons
        $activeModules = array_unique($activeModules);
        
        // Debugging : Log les modules actifs trouvés
        if (!empty($activeModules)) {
            error_log("ModuleHelper::getCurrentModule - Active Modules: " . implode(', ', $activeModules));
        } else {
            error_log("ModuleHelper::getCurrentModule - No active modules found in URL.");
        }
        
        return $activeModules;
    }
}
?>
