<?php

/**
 * getCache: Fonction pour récupérer des données du cache
 *
 * @param string $key - La clé unique pour identifier le cache
 * @param int $cacheDuration - La durée de validité du cache en secondes (par défaut 1 heure)
 * @return mixed - Les données mises en cache ou false si le cache est expiré ou n'existe pas
 */
function getCache($key, $cacheDuration = 3600) {
    // Définir le nom du fichier cache basé sur la clé unique
    $cacheFile = __DIR__ . "/../cache/" . md5($key) . ".json";

    // Vérifier si le fichier de cache existe et est encore valide
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheDuration) {
        // Charger les résultats à partir du fichier de cache
        $cachedData = file_get_contents($cacheFile);
        return json_decode($cachedData, true);
    }

    // Sinon, retourner `false` pour indiquer qu'il faut régénérer les données
    return false;
}

/**
 * setCache: Fonction pour écrire des données dans le cache
 *
 * @param string $key - La clé unique pour identifier le cache
 * @param mixed $data - Les données à mettre en cache
 */
function setCache($key, $data) {
    // Définir le nom du fichier cache basé sur la clé unique
    $cacheFile = __DIR__ . "/../cache/" . md5($key) . ".json";

    // Stocker les résultats dans le fichier de cache
    file_put_contents($cacheFile, json_encode($data));
}
