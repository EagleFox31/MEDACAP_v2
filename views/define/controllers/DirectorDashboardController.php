<?php
require_once "BaseController.php";
require_once "../../vendor/autoload.php";

/**
 * Contrôleur spécifique pour le dashboard des directeurs (Directeur Général, Groupe, etc.)
 */
class DirectorDashboardController extends BaseController {
    private $userCollection;
    private $trainingCollection;
    private $allocationCollection;
    private $applicationCollection;
    private $validationCollection;
    private $profile;
    private $subsidiary;
    
    /**
     * Constructeur
     * 
     * @param MongoDB\Database $academy Instance de la base de données MongoDB
     */
    public function __construct($academy) {
        parent::__construct($academy);
        
        // Vérification que l'utilisateur a un profil de directeur
        $this->checkAuthorization([
            'Directeur Général',
            'Directeur Groupe',
            'Directeur Pièce et Service',
            'Directeur des Opérations',
            'Super Admin',
            'Admin'
        ]);
        
        // Initialisation des collections MongoDB
        $this->userCollection = $academy->users;
        $this->trainingCollection = $academy->trainings;
        $this->allocationCollection = $academy->allocations;
        $this->applicationCollection = $academy->applications;
        $this->validationCollection = $academy->validations;
        
        // Récupération du profil et de la filiale
        $this->profile = $_SESSION['profile'] ?? '';
        $this->subsidiary = $_SESSION['subsidiary'] ?? '';
    }
    
    /**
     * Rendu du dashboard directeur
     * 
     * @param array $filters Filtres à appliquer
     */
    public function renderDashboard($filters) {
        // Récupération des données pour la vue
        $data = $this->prepareViewData($filters);
        
        // Définir la page courante avant le rendu pour éviter les boucles infinies dans le header
        $GLOBALS['currentPage'] = 'dashboard';
        
        // Rendu de la vue
        $this->renderView('director/dashboard.php', $data);
    }
    
    /**
     * Prépare les données pour la vue
     * 
     * @param array $filters Filtres à appliquer
     * @return array Données pour la vue
     */
    private function prepareViewData($filters) {
        // Initialiser le FilterController pour la vue
        require_once __DIR__ . "/FilterController.php";
        $filterController = new FilterController($this->academy);
        
        // Gestion des filtres
        $filterSubsidiary = $filters['subsidiary'] ?? 'all';
        $filterManager = $filters['managerId'] ?? 'all';
        $filterBrand = $filters['brand'] ?? 'all';
        $filterLevel = $filters['level'] ?? 'all';
        
        // Statistiques globales
        $globalStats = $this->getGlobalStatistics($filterSubsidiary);
        
        // Statistiques par filiale
        $subsidiaryStats = $this->getSubsidiaryStatistics($filterSubsidiary);
        
        // Statistiques par marque
        $brandStats = $this->getBrandStatistics($filterSubsidiary, $filterBrand);
        
        // Statistiques des formations
        $trainingStats = $this->getTrainingStatistics($filterSubsidiary, $filterBrand);
        
        // Liste des filiales pour le filtre
        $subsidiaries = $this->getSubsidiaries();
        
        // Liste des marques pour le filtre
        $brands = $this->getBrands($filterSubsidiary);
        
        // Récupération des agences si une filiale est sélectionnée
        $agencies = ($filterSubsidiary !== 'all')
            ? $filterController->getAgencies($filterSubsidiary)
            : [];
            
        // Récupération des managers si une filiale est sélectionnée
        $managers = ($filterSubsidiary !== 'all')
            ? $this->getManagers($filterSubsidiary)
            : [];
            
        // Récupération des techniciens pour le filtre
        $technicians = $filterController->getTechnicians($filters);
        
        // Tableau récapitulatif des techniciens par niveau et filiale
        $technicianSummary = $this->getTechnicianSummary($filterSubsidiary);
        
        // Données pour le graphique des scores par marque
        $brandScores = $this->prepareBrandScoresForChart($brandStats);
        
        // Récupération des traductions
        $translations = $this->translations;
        
        // Création du tableau de données pour la vue
        return [
            'tableau' => "Tableau de Bord Directeur",
            'globalStats' => $globalStats,
            'subsidiaryStats' => $subsidiaryStats,
            'brandStats' => $brandStats,
            'trainingStats' => $trainingStats,
            'subsidiaries' => $subsidiaries,
            'brands' => $brands,
            'agencies' => $agencies,
            'managers' => $managers,
            'technicians' => $technicians,
            'technicianSummary' => $technicianSummary,
            'brandScores' => $brandScores,
            'filters' => $filters,
            'translations' => $translations,
            'brandLogos' => $GLOBALS['brandLogos'] ?? [],
            'filterController' => $filterController
        ];
    }
    
    /**
     * Récupère les statistiques globales
     * 
     * @param string $subsidiary Filiale à filtrer
     * @return array Statistiques globales
     */
    private function getGlobalStatistics($subsidiary) {
        $query = [
            'profile' => 'Technicien',
            'active' => true
        ];
        
        // Filtre par filiale si définie
        if ($subsidiary !== 'all') {
            $query['subsidiary'] = $subsidiary;
        }
        
        // Compte total des techniciens
        $totalTechnicians = $this->userCollection->count($query);
        
        // Compte par niveau
        $query['level'] = 'Junior';
        $juniorCount = $this->userCollection->count($query);
        
        $query['level'] = 'Senior';
        $seniorCount = $this->userCollection->count($query);
        
        $query['level'] = 'Expert';
        $expertCount = $this->userCollection->count($query);
        
        // Techniciens mesurés (avec allocations)
        $measuredCount = $this->getMeasuredTechniciansCount($subsidiary);
        
        // Techniciens avec formations
        $withTrainingCount = $this->getTechniciansWithTrainingCount($subsidiary);
        
        // Formations validées
        $validatedTrainingCount = $this->getValidatedTrainingsCount($subsidiary);
        
        return [
            'totalTechnicians' => $totalTechnicians,
            'juniorCount' => $juniorCount,
            'seniorCount' => $seniorCount,
            'expertCount' => $expertCount,
            'measuredCount' => $measuredCount,
            'withTrainingCount' => $withTrainingCount,
            'validatedTrainingCount' => $validatedTrainingCount
        ];
    }
    
    /**
     * Récupère les techniciens mesurés
     * 
     * @param string $subsidiary Filiale à filtrer
     * @return int Nombre de techniciens mesurés
     */
    private function getMeasuredTechniciansCount($subsidiary) {
        // Critères pour les techniciens mesurés
        $query = [
            'profile' => 'Technicien',
            'active' => true,
            'measured' => true
        ];
        
        // Filtre par filiale si définie
        if ($subsidiary !== 'all') {
            $query['subsidiary'] = $subsidiary;
        }
        
        return $this->userCollection->count($query);
    }
    
    /**
     * Récupère les techniciens avec formations
     * 
     * @param string $subsidiary Filiale à filtrer
     * @return int Nombre de techniciens avec formations
     */
    private function getTechniciansWithTrainingCount($subsidiary) {
        // Récupération des techniciens avec allocations de type Training
        $pipeline = [
            ['$match' => ['type' => 'Training', 'active' => true]],
            ['$group' => ['_id' => '$user']]
        ];
        
        $result = $this->allocationCollection->aggregate($pipeline)->toArray();
        return count($result);
    }
    
    /**
     * Récupère le nombre de formations validées
     * 
     * @param string $subsidiary Filiale à filtrer
     * @return int Nombre de formations validées
     */
    private function getValidatedTrainingsCount($subsidiary) {
        $query = [
            'status' => 'Validé',
            'active' => true
        ];
        
        return $this->validationCollection->count($query);
    }
    
    /**
     * Récupère les statistiques par filiale
     * 
     * @param string $selectedSubsidiary Filiale sélectionnée
     * @return array Statistiques par filiale
     */
    private function getSubsidiaryStatistics($selectedSubsidiary) {
        $stats = [];
        
        // Si une filiale spécifique est sélectionnée, retourner uniquement ses stats
        if ($selectedSubsidiary !== 'all') {
            $stats[$selectedSubsidiary] = $this->getStatsForSubsidiary($selectedSubsidiary);
            return $stats;
        }
        
        // Sinon, récupérer les stats pour toutes les filiales
        $subsidiaries = $this->getSubsidiaries();
        foreach ($subsidiaries as $subsidiary) {
            $stats[$subsidiary] = $this->getStatsForSubsidiary($subsidiary);
        }
        
        return $stats;
    }
    
    /**
     * Récupère les statistiques pour une filiale spécifique
     * 
     * @param string $subsidiary Nom de la filiale
     * @return array Statistiques de la filiale
     */
    private function getStatsForSubsidiary($subsidiary) {
        $query = [
            'profile' => 'Technicien',
            'active' => true,
            'subsidiary' => $subsidiary
        ];
        
        // Compte total des techniciens
        $totalTechnicians = $this->userCollection->count($query);
        
        // Compte par niveau
        $query['level'] = 'Junior';
        $juniorCount = $this->userCollection->count($query);
        
        $query['level'] = 'Senior';
        $seniorCount = $this->userCollection->count($query);
        
        $query['level'] = 'Expert';
        $expertCount = $this->userCollection->count($query);
        
        return [
            'totalTechnicians' => $totalTechnicians,
            'juniorCount' => $juniorCount,
            'seniorCount' => $seniorCount,
            'expertCount' => $expertCount
        ];
    }
    
    /**
     * Récupère les statistiques par marque
     * 
     * @param string $subsidiary Filiale à filtrer
     * @param string $brand Marque à filtrer
     * @return array Statistiques par marque
     */
    private function getBrandStatistics($subsidiary, $brand) {
        // Si une marque spécifique est sélectionnée
        if ($brand !== 'all') {
            return [$brand => $this->getStatsForBrand($brand, $subsidiary)];
        }
        
        // Sinon, récupérer pour toutes les marques
        $stats = [];
        $brands = $this->getBrands($subsidiary);
        
        foreach ($brands as $brandName) {
            $stats[$brandName] = $this->getStatsForBrand($brandName, $subsidiary);
        }
        
        return $stats;
    }
    
    /**
     * Récupère les statistiques pour une marque spécifique
     * 
     * @param string $brand Nom de la marque
     * @param string $subsidiary Filiale à filtrer
     * @return array Statistiques de la marque
     */
    private function getStatsForBrand($brand, $subsidiary) {
        // Pipeline pour compter les techniciens par niveau pour la marque
        $pipeline = [
            ['$match' => [
                'profile' => 'Technicien',
                'active' => true,
                '$or' => [
                    ['brandJunior' => $brand],
                    ['brandSenior' => $brand],
                    ['brandExpert' => $brand]
                ]
            ]]
        ];
        
        // Ajouter le filtre par filiale si nécessaire
        if ($subsidiary !== 'all') {
            $pipeline[0]['$match']['subsidiary'] = $subsidiary;
        }
        
        // Grouper par niveau
        $pipeline[] = ['$group' => [
            '_id' => '$level',
            'count' => ['$sum' => 1]
        ]];
        
        // Exécuter l'agrégation
        $result = $this->userCollection->aggregate($pipeline)->toArray();
        
        // Initialiser les compteurs
        $juniorCount = 0;
        $seniorCount = 0;
        $expertCount = 0;
        
        // Traiter les résultats
        foreach ($result as $item) {
            switch ($item['_id']) {
                case 'Junior':
                    $juniorCount = $item['count'];
                    break;
                case 'Senior':
                    $seniorCount = $item['count'];
                    break;
                case 'Expert':
                    $expertCount = $item['count'];
                    break;
            }
        }
        
        // Calculer le score moyen
        $averageScore = $this->getAverageScoreForBrand($brand, $subsidiary);
        
        return [
            'juniorCount' => $juniorCount,
            'seniorCount' => $seniorCount,
            'expertCount' => $expertCount,
            'totalCount' => $juniorCount + $seniorCount + $expertCount,
            'averageScore' => $averageScore
        ];
    }
    
    /**
     * Calcule le score moyen pour une marque
     * 
     * @param string $brand Nom de la marque
     * @param string $subsidiary Filiale à filtrer
     * @return float Score moyen
     */
    private function getAverageScoreForBrand($brand, $subsidiary) {
        // Cette méthode serait implémentée pour calculer le score moyen
        // En l'absence de données réelles, nous retournons une valeur simulée
        return rand(60, 95);
    }
    
    /**
     * Récupère les statistiques des formations
     * 
     * @param string $subsidiary Filiale à filtrer
     * @param string $brand Marque à filtrer
     * @return array Statistiques des formations
     */
    private function getTrainingStatistics($subsidiary, $brand) {
        // Cette méthode serait implémentée pour récupérer les statistiques des formations
        // En l'absence de données réelles, nous retournons des valeurs simulées
        
        $stats = [
            'totalTrainings' => rand(20, 50),
            'recommendedTrainings' => rand(10, 30),
            'validatedTrainings' => rand(5, 15),
            'trainingDays' => rand(40, 100)
        ];
        
        // Si une marque est spécifiée, simuler des données par marque
        if ($brand !== 'all') {
            $stats['brandTrainings'] = [
                $brand => [
                    'recommended' => rand(5, 15),
                    'validated' => rand(2, 10)
                ]
            ];
        } else {
            // Sinon, simuler des données pour plusieurs marques
            $brands = $this->getBrands($subsidiary);
            $stats['brandTrainings'] = [];
            
            foreach ($brands as $brandName) {
                $stats['brandTrainings'][$brandName] = [
                    'recommended' => rand(5, 15),
                    'validated' => rand(2, 10)
                ];
            }
        }
        
        return $stats;
    }
    
    /**
     * Récupère la liste des filiales
     * 
     * @return array Liste des filiales
     */
    private function getSubsidiaries() {
        // Récupérer toutes les filiales distinctes
        return $this->userCollection->distinct('subsidiary', ['active' => true]);
    }
    
    /**
     * Récupère la liste des marques
     * 
     * @param string $subsidiary Filiale à filtrer
     * @return array Liste des marques
     */
    private function getBrands($subsidiary) {
        $query = ['active' => true];
        
        // Filtre par filiale si définie
        if ($subsidiary !== 'all') {
            $query['subsidiary'] = $subsidiary;
        }
        
        // Récupérer les marques depuis différents champs
        $brandJunior = $this->userCollection->distinct('brandJunior', $query);
        $brandSenior = $this->userCollection->distinct('brandSenior', $query);
        $brandExpert = $this->userCollection->distinct('brandExpert', $query);
        
        // Fusionner et dédupliquer
        $allBrands = array_merge($brandJunior, $brandSenior, $brandExpert);
        $uniqueBrands = array_unique($allBrands);
        
        // Filtrer les valeurs vides
        $brands = array_filter($uniqueBrands, function($brand) {
            return !empty(trim($brand));
        });
        
        // Trier par ordre alphabétique
        sort($brands);
        
        return $brands;
    }
    
    /**
     * Récupère la liste des managers
     * 
     * @param string $subsidiary Filiale à filtrer
     * @return array Liste des managers
     */
    private function getManagers($subsidiary) {
        $query = [
            'profile' => 'Manager',
            'active' => true
        ];
        
        // Filtre par filiale si définie
        if ($subsidiary !== 'all') {
            $query['subsidiary'] = $subsidiary;
        }
        
        // Récupérer les managers
        $managers = [];
        $cursor = $this->userCollection->find($query);
        
        foreach ($cursor as $manager) {
            // Convertir l'ID MongoDB en chaîne pour l'utiliser comme clé
            $managers[(string)$manager['_id']] = $manager['firstName'] . ' ' . $manager['lastName'];
        }
        
        return $managers;
    }
    
    /**
     * Récupère le résumé des techniciens par niveau et filiale
     * 
     * @param string $selectedSubsidiary Filiale sélectionnée
     * @return array Résumé des techniciens
     */
    private function getTechnicianSummary($selectedSubsidiary) {
        $summary = [];
        
        // Si une filiale spécifique est sélectionnée
        if ($selectedSubsidiary !== 'all') {
            $summary[$selectedSubsidiary] = $this->getStatsForSubsidiary($selectedSubsidiary);
            return $summary;
        }
        
        // Sinon, récupérer pour toutes les filiales
        $subsidiaries = $this->getSubsidiaries();
        foreach ($subsidiaries as $subsidiary) {
            $summary[$subsidiary] = $this->getStatsForSubsidiary($subsidiary);
        }
        
        return $summary;
    }
    
    /**
     * Prépare les données de score par marque pour le graphique
     * 
     * @param array $brandStats Statistiques par marque
     * @return array Données formatées pour le graphique
     */
    private function prepareBrandScoresForChart($brandStats) {
        $chartData = [];
        
        foreach ($brandStats as $brand => $stats) {
            $avgScore = $stats['averageScore'];
            
            // Déterminer la couleur selon le score
            if ($avgScore >= 80) {
                $color = COLOR_SUCCESS;
            } elseif ($avgScore >= 60) {
                $color = COLOR_WARNING;
            } else {
                $color = COLOR_DANGER;
            }
            
            $chartData[] = [
                'x' => $brand,
                'y' => $avgScore,
                'fillColor' => $color
            ];
        }
        
        return $chartData;
    }
}