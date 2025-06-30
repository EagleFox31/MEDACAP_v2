<?php
/**
 * Contrôleur de base fournissant des fonctionnalités communes
 * pour tous les contrôleurs spécifiques
 */
class BaseController {
    protected $academy;
    protected $translations;
    
    /**
     * Constructeur
     * 
     * @param MongoDB\Database $academy Instance de la base de données MongoDB
     */
    public function __construct($academy) {
        // Vérification de l'authentification
        $this->checkAuthentication();
        
        // Initialisation de la base de données
        $this->academy = $academy;
        
        // Chargement des traductions
        $this->translations = $GLOBALS['translations'] ?? [];
    }
    
    /**
     * Vérifie que l'utilisateur est authentifié
     * 
     * @throws Exception Si l'utilisateur n'est pas authentifié
     */
    protected function checkAuthentication() {
        if (!isset($_SESSION['id']) || !isset($_SESSION['profile'])) {
            header("Location: ../login.php");
            exit();
        }
    }
    
    /**
     * Vérifie que l'utilisateur a un profil autorisé
     * 
     * @param array $allowedProfiles Liste des profils autorisés
     * @throws Exception Si l'utilisateur n'a pas un profil autorisé
     */
    protected function checkAuthorization($allowedProfiles) {
        if (!in_array($_SESSION['profile'], $allowedProfiles)) {
            echo "Accès refusé. Votre profil n'est pas autorisé à accéder à cette page.";
            exit();
        }
    }
    
    /**
     * Affiche une vue avec des données
     * 
     * @param string $viewPath Chemin relatif vers la vue
     * @param array $data Données à passer à la vue
     */
    protected function renderView($viewPath, $data = []) {
        // Ajout d'un identifiant de page pour éviter les boucles infinies dans le header
        $GLOBALS['currentPage'] = 'dashboard';
        
        // Extraction des données pour les rendre disponibles dans la vue
        extract($data);
        
        // Inclusion de la vue
        include __DIR__ . "/../views/{$viewPath}";
    }
    
    /**
     * Formate un score pour l'affichage
     * 
     * @param float $score Score à formater
     * @param int $decimals Nombre de décimales
     * @return string Score formaté
     */
    protected function formatScore($score, $decimals = 1) {
        return number_format((float)$score, $decimals);
    }
    
    /**
     * Retourne la classe CSS pour un score donné
     * 
     * @param float $score Score
     * @return string Classe CSS
     */
    protected function getScoreClass($score) {
        if ($score >= SCORE_THRESHOLD_MEDIUM) {
            return 'bg-success';
        } elseif ($score >= SCORE_THRESHOLD_LOW) {
            return 'bg-warning';
        } else {
            return 'bg-danger';
        }
    }
    
    /**
     * Sécurise une chaîne pour l'affichage HTML
     * 
     * @param string $string Chaîne à sécuriser
     * @return string Chaîne sécurisée
     */
    protected function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Crée un critère MongoDB avec ID pour les requêtes
     * 
     * @param string $id ID à utiliser
     * @return array Critère MongoDB avec ID
     */
    protected function createIdCriteria($id) {
        if (empty($id)) {
            return [];
        }
        
        // Cette méthode évite l'utilisation directe de ObjectId
        // pour contourner les problèmes potentiels d'importation
        return ['_id' => $id];
    }
    
    /**
     * Calcule le pourcentage entre deux valeurs
     * 
     * @param int $value Valeur
     * @param int $total Total
     * @param int $decimals Nombre de décimales
     * @return float Pourcentage
     */
    protected function calculatePercentage($value, $total, $decimals = 1) {
        if ($total == 0) {
            return 0;
        }
        return round(($value / $total) * 100, $decimals);
    }
    
    /**
     * Retourne la traduction d'une clé
     * 
     * @param string $key Clé de traduction
     * @param string $default Valeur par défaut
     * @return string Traduction
     */
    protected function translate($key, $default = '') {
        return $this->translations[$key] ?? $default;
    }
}