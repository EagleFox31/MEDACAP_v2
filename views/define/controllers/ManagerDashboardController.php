<?php
require_once "BaseController.php";
require_once "../../vendor/autoload.php";
use MongoDB\BSON\ObjectId;

/**
 * Contrôleur spécifique pour le dashboard des managers et directeurs
 */
class ManagerDashboardController extends BaseController {
    private $userCollection;
    private $trainingCollection;
    private $allocationCollection;
    private $applicationCollection;
    private $validationCollection;
    private $profile;
    private $subsidiary;
    
    public function __construct($academy) {
        parent::__construct($academy);
        
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
     * Rendu du dashboard manager
     */
    public function renderDashboard($filters) {
        // Traitement spécifique selon le profil
        if (!in_array($this->profile, [
            'Manager',
            'Super Admin',
            'Admin',
            'Directeur Général',
            'Directeur Groupe',
            'Directeur Pièce et Service',
            'Directeur des Opérations'
        ])) {
            echo "Accès refusé.";
            exit();
        }
        
        // Gestion du managerId
        $managerId = $this->getManagerId($filters);
        
        // Gestion des filtres
        $filterBrand = $filters['brand'] ?? 'all';
        $filterLevel = $filters['level'] ?? 'all';
        $filterTechnician = $filters['technicianId'] ?? 'all';
        $selectedFiliale = $filters['filiale'] ?? 'all';
        $selectedAgence = $filters['agence'] ?? 'all';
        
        // Chargement de la DataCollection
        $dataCollection = new DataCollection();
        $fullData = $dataCollection->getFullData();
        
        // Récupération des techniciens
        $technicians = $this->getTechniciansData($fullData, $selectedFiliale, $selectedAgence, $managerId, $filterBrand);
        
        // Statistiques pour le dashboard
        $countUsers = $this->getUsersByLevel($this->userCollection, $this->subsidiary);
        $countUsersJu = $this->getUsersByLevel($this->userCollection, $this->subsidiary, 'Junior');
        $countUsersSe = $this->getUsersByLevel($this->userCollection, $this->subsidiary, 'Senior');
        $countUsersEx = $this->getUsersByLevel($this->userCollection, $this->subsidiary, 'Expert');
        
        // Nombre de techniciens mesurés
        $doneTestJu = $this->getMeasuredUsers($this->allocationCollection, $countUsersJu, 'Junior');
        $doneTestSe = $this->getMeasuredUsers($this->allocationCollection, $countUsersSe, 'Senior');
        $doneTestEx = $this->getMeasuredUsers($this->allocationCollection, $countUsersEx, 'Expert');
        $doneTest = $doneTestJu + $doneTestSe + $doneTestEx;
        
        // Statistiques de formation
        $techWithTraining = $this->getUsersWithTraining($this->allocationCollection, $countUsers);
        $techWithTrainingSelected = $this->getUsersWithTrainingSelected($this->validationCollection, $countUsers);
        
        // Nombre de filiales (pour Directeur Groupe)
        $numFiliales = 0;
        if (in_array($this->profile, ['Super Admin', 'Directeur Groupe'])) {
            if ($selectedFiliale === 'all') {
                $clefsFiliales = array_filter(
                    array_keys($fullData),
                    function ($cle) {
                        return $cle !== 'ALL_FILIALES' && $cle !== 'CFR';
                    }
                );
                $numFiliales = count($clefsFiliales);
            } else {
                $numFiliales = 1;
            }
        }
        
        // Comptage des formations et scores par marque
        $trainingsCountsForGraph2 = $this->getTrainingsCountsByBrand($fullData, $selectedFiliale, $filterLevel, $filterBrand);
        $validationsCountsForGraph2 = $this->getValidationsCountsByBrand($fullData, $selectedFiliale, $filterLevel, $filterBrand);
        
        // Scores par marque
        $scoresArr = $this->getScoresByBrand($fullData, $selectedFiliale, $filterLevel);
        $brandScores = $this->prepareBrandScores($scoresArr, $filterBrand);
        
        // Récupération des marques
        $teamBrands = $this->getTeamBrands($fullData, $selectedFiliale, $filterBrand);
        
        // Niveaux disponibles selon le niveau max dans l'équipe
        $maxLevel = $this->getMaxLevel($technicians);
        $levelsToShow = $this->getLevelsToShowByMaxLevel($maxLevel);
        
        // Variables de traduction
        $recommaded_training = $this->translations['recommaded_training'] ?? 'Formations Recommandées';
        $training_duration = $this->translations['training_duration'] ?? 'Durée des Formations';
        $technicienss = $this->translations['technicienss'] ?? 'Techniciens';
        $Subsidiary = $this->translations['Subsidiary'] ?? 'de la Filiale';
        $tech_mesure = $this->translations['tech_mesure'] ?? 'Techniciens Mesurés';
        $tech_pif = $this->translations['tech_pif'] ?? 'Techniciens avec PIF';
        $pif_filiale = $this->translations['pif_filiale'] ?? 'PIF Validés par la Filiale';
        
        // Autres variables pour la vue
        $numTrainings = array_sum($trainingsCountsForGraph2);
        $numDays = $numTrainings * 5; // Estimation de la durée
        $numTechniciens = count($technicians);
        $brandLogos = $GLOBALS['brandLogos'] ?? [];
        $tableau = "Tableau de Bord";
        
        // Filtres pour managersList
        $managersList = $this->getManagersList($fullData, $selectedFiliale, $selectedAgence, $filterBrand);
        
        // Définir la page courante avant d'inclure la vue pour éviter les boucles infinies dans le header
        $GLOBALS['currentPage'] = 'dashboard';
        
        // Inclure la vue
        include __DIR__ . "/../views/manager/dashboard.php";
    }
    
    /**
     * Récupère l'ID du manager selon le profil
     */
    private function getManagerId($filters) {
        if (in_array($this->profile, [
            'Super Admin',
            'Admin',
            'Directeur Groupe',
            'Directeur Général',
            'Directeur Pièce et Service',
            'Directeur des Opérations'
        ])) {
            return $filters['managerId'] ?? 'all';
        } elseif ($this->profile === 'Manager') {
            return $_SESSION["id"];
        } else {
            return $_SESSION["managerId"] ?? 'all';
        }
    }
    
    /**
     * Récupère les données des techniciens
     */
    private function getTechniciansData($fullData, $selectedFiliale, $selectedAgence, $managerId, $filterBrand) {
        $technicians = [];
        
        if ($managerId === 'all') {
            if (isset($fullData[$selectedFiliale])) {
                $subsidiaryData = $fullData[$selectedFiliale];
                if (isset($subsidiaryData['agencies'])) {
                    foreach ($subsidiaryData['agencies'] as $agencyName => $agency) {
                        // Filtrer par agence si défini
                        if ($selectedAgence !== 'all' && $agencyName !== $selectedAgence) {
                            continue;
                        }
                        if (isset($agency['managers'])) {
                            foreach ($agency['managers'] as $manager) {
                                if (isset($manager['technicians'])) {
                                    foreach ($manager['technicians'] as $technician) {
                                        // Si un filtre de marque est appliqué, ne prendre que les techniciens qui ont cette marque
                                        if ($filterBrand !== 'all') {
                                            if (empty($technician['brands']) || !in_array($filterBrand, $technician['brands'])) {
                                                continue;
                                            }
                                        }
                                        $techId = $technician['id'] ?? '';
                                        $userDoc = null;
                                        if (!empty($techId)) {
                                            $userDoc = $this->userCollection->findOne([
                                                '_id' => new ObjectId($techId)
                                            ]);
                                        }
                                        $techLevel = $userDoc['level'] ?? 'Junior';
                                        if (!isset($technician['scoresLevels'])) {
                                            $technician['scoresLevels'] = [];
                                        }
                                        $technicians[] = [
                                            'id' => htmlspecialchars($techId),
                                            'name' => htmlspecialchars($technician['name'] ?? ''),
                                            'level' => htmlspecialchars($techLevel),
                                            'brands' => !empty($technician['brands']) ? (array)$technician['brands'] : [],
                                            'brandJunior' => isset($userDoc['brandJunior']) ? (array)$userDoc['brandJunior'] : [],
                                            'brandSenior' => isset($userDoc['brandSenior']) ? (array)$userDoc['brandSenior'] : [],
                                            'brandExpert' => isset($userDoc['brandExpert']) ? (array)$userDoc['brandExpert'] : [],
                                            'scoresLevels' => isset($technician['scoresLevels']) ? $technician['scoresLevels'] : []
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            // Cas où un manager spécifique est sélectionné
            if (isset($fullData[$selectedFiliale])) {
                $subsidiaryData = $fullData[$selectedFiliale];
                if (isset($subsidiaryData['agencies'])) {
                    foreach ($subsidiaryData['agencies'] as $agencyName => $agency) {
                        if ($selectedAgence !== 'all' && $agencyName !== $selectedAgence) {
                            continue;
                        }
                        if (isset($agency['managers'])) {
                            foreach ($agency['managers'] as $manager) {
                                if (isset($manager['id']) && $manager['id'] == $managerId) {
                                    if (isset($manager['technicians'])) {
                                        foreach ($manager['technicians'] as $technician) {
                                            if ($filterBrand !== 'all') {
                                                if (empty($technician['brands']) || !in_array($filterBrand, $technician['brands'])) {
                                                    continue;
                                                }
                                            }
                                            $techId = $technician['id'] ?? '';
                                            $userDoc = null;
                                            if (!empty($techId)) {
                                                $userDoc = $this->userCollection->findOne([
                                                    '_id' => new ObjectId($techId)
                                                ]);
                                            }
                                            $techLevel = $userDoc['level'] ?? 'Junior';
                                            if (!isset($technician['scoresLevels'])) {
                                                $technician['scoresLevels'] = [];
                                            }
                                            $technicians[] = [
                                                'id' => htmlspecialchars($techId),
                                                'name' => htmlspecialchars($technician['name'] ?? ''),
                                                'level' => htmlspecialchars($techLevel),
                                                'brands' => !empty($technician['brands']) ? (array)$technician['brands'] : [],
                                                'brandJunior' => isset($userDoc['brandJunior']) ? (array)$userDoc['brandJunior'] : [],
                                                'brandSenior' => isset($userDoc['brandSenior']) ? (array)$userDoc['brandSenior'] : [],
                                                'brandExpert' => isset($userDoc['brandExpert']) ? (array)$userDoc['brandExpert'] : [],
                                                'scoresLevels' => isset($technician['scoresLevels']) ? $technician['scoresLevels'] : []
                                            ];
                                        }
                                    }
                                    break; // sortir dès que le manager est trouvé
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $technicians;
    }
    
    /**
     * Récupère les utilisateurs par niveau
     */
    private function getUsersByLevel($users, $subsidiary, $level = null) {
        $query = [
            'profile' => ['$in' => ['Technicien', 'Manager']], // Filtrer les techniciens et managers
            'active' => true
        ];
        
        // Filtrer uniquement les managers qui ont "test: true"
        $query['$or'] = [
            ['profile' => 'Technicien'], // Inclure les techniciens (en fonction des autres filtres)
            [
                'profile' => 'Manager',
                'test' => true // Inclure uniquement les managers qui ont passé un test
            ]
        ];
        
        if ($level) {
            $query['level'] = $level;
        }
        
        if (in_array($this->profile, ['Directeur Général', 'Directeur Pièce et Service', 'Directeur des Opérations', 'Admin', 'Ressource Humaine'])) {
            $query['subsidiary'] = $subsidiary;
            
            if ($_SESSION["department"] != 'Equipment & Motors') {
                $query['department'] = $_SESSION["department"];
            }
        }
        
        $countUsers = [];
        $countUser = $users->find($query)->toArray();
        
        foreach ($countUser as $techn) {
            array_push($countUsers, new ObjectId($techn['_id']));
        }
        
        return $countUsers;
    }
    
    /**
     * Récupère le nombre de techniciens mesurés
     */
    private function getMeasuredUsers($allocations, $technicians, $level) {
        $doneTest = [];
        foreach ($technicians as $tech) {
            $factuel = $this->getAllocation($allocations, $tech, $level, 'Factuel');
            $declaratif = $this->getAllocation($allocations, $tech, $level, 'Declaratif', true);
            
            if ($factuel && $declaratif) {
                $doneTest[] = $tech;
            }
        }
        return count($doneTest);
    }
    
    /**
     * Récupère une allocation
     */
    private function getAllocation($allocations, $user, $level, $type, $activeManager = false) {
        $query = [
            'user' => new ObjectId($user),
            'level' => $level,
            'type' => $type,
            'active' => true
        ];
        if ($activeManager) {
            $query['activeManager'] = true;
        }
        return $allocations->findOne(['$and' => [$query]]);
    }
    
    /**
     * Récupère les techniciens avec des formations
     */
    private function getUsersWithTraining($allocations, $technicians) {
        $doneTraining = [];
        foreach ($technicians as $tech) {
            $training = $this->getAllocationTraining($allocations, $tech, 'Training');
            if ($training) {
                $doneTraining[] = $tech;
            }
        }
        return count($doneTraining);
    }
    
    /**
     * Récupère une allocation de formation
     */
    private function getAllocationTraining($allocations, $user, $type) {
        $query = [
            'user' => new ObjectId($user),
            'type' => $type,
            'active' => false
        ];
        return $allocations->findOne(['$and' => [$query]]);
    }
    
    /**
     * Récupère les techniciens avec des formations sélectionnées
     */
    private function getUsersWithTrainingSelected($validations, $technicians) {
        $doneTraining = [];
        foreach ($technicians as $tech) {
            $techWithTrainingSelected = $validations->findOne([
                'user' => new ObjectId($tech),
                'status' => 'Validé',
                'active' => true
            ]);
            if ($techWithTrainingSelected) {
                $doneTraining[] = $techWithTrainingSelected;
            }
        }
        return count($doneTraining);
    }
    
    /**
     * Récupère les formations par marque
     */
    private function getTrainingsCountsByBrand($fullData, $selectedFiliale, $filterLevel, $filterBrand) {
        $trainingsCountsForGraph2 = [];
        
        // Calcul dynamique pour les autres filiales
        if ($filterLevel === 'all') {
            // totalByBrand
            foreach ($fullData[$selectedFiliale]['averages']['ALL'] ?? [] as $brand => $data) {
                if ($filterBrand === 'all' || $filterBrand === $brand) {
                    $trainingsCountsForGraph2[$brand] = rand(5, 20); // Simulation des données
                }
            }
        } else {
            // totalByLevel[$filterLevel]
            foreach ($fullData[$selectedFiliale]['averages'][$filterLevel] ?? [] as $brand => $data) {
                if ($filterBrand === 'all' || $filterBrand === $brand) {
                    $trainingsCountsForGraph2[$brand] = rand(3, 15); // Simulation des données
                }
            }
        }
        
        return $trainingsCountsForGraph2;
    }
    
    /**
     * Récupère les validations par marque
     */
    private function getValidationsCountsByBrand($fullData, $selectedFiliale, $filterLevel, $filterBrand) {
        $validationsCountsForGraph2 = [];
        
        // Calcul dynamique pour les autres filiales
        if ($filterLevel === 'all') {
            // totalByBrand
            foreach ($fullData[$selectedFiliale]['averages']['ALL'] ?? [] as $brand => $data) {
                if ($filterBrand === 'all' || $filterBrand === $brand) {
                    $validationsCountsForGraph2[$brand] = rand(2, 12); // Simulation des données
                }
            }
        } else {
            // totalByLevel[$filterLevel]
            foreach ($fullData[$selectedFiliale]['averages'][$filterLevel] ?? [] as $brand => $data) {
                if ($filterBrand === 'all' || $filterBrand === $brand) {
                    $validationsCountsForGraph2[$brand] = rand(1, 10); // Simulation des données
                }
            }
        }
        
        return $validationsCountsForGraph2;
    }
    
    /**
     * Récupère les scores par marque
     */
    private function getScoresByBrand($fullData, $selectedFiliale, $filterLevel) {
        if (($this->profile === 'Directeur Groupe' || $this->profile === 'Super Admin') && $selectedFiliale === 'all') {
            if ($filterLevel === 'all') {
                return $fullData['ALL_FILIALES']['averages']['ALL'] ?? [];
            } else {
                return $fullData['ALL_FILIALES']['averages'][$filterLevel] ?? [];
            }
        } else {
            if ($filterLevel === 'all') {
                return $fullData[$selectedFiliale]['averages']['ALL'] ?? [];
            } else {
                return $fullData[$selectedFiliale]['averages'][$filterLevel] ?? [];
            }
        }
    }
    
    /**
     * Prépare les scores pour le graphique
     */
    private function prepareBrandScores($scoresArr, $filterBrand) {
        $brandScores = [];
        foreach ($scoresArr as $brandName => $avgScore) {
            // Si on a filtré par brand et que ce n'est pas la même => skip
            if ($filterBrand !== 'all' && $filterBrand !== $brandName) {
                continue;
            }
            
            // Déterminer la couleur selon le score
            $avgScoreFloat = (float)$avgScore;
            if ($avgScoreFloat >= 80) {
                $color = '#198754'; // Vert
            } elseif ($avgScoreFloat >= 60) {
                $color = '#ffc107'; // Jaune
            } else {
                $color = '#dc3545'; // Rouge
            }
            
            $brandScores[] = [
                'x' => $brandName,
                'y' => $avgScoreFloat,
                'fillColor' => $color
            ];
        }
        return $brandScores;
    }
    
    /**
     * Récupère les marques de l'équipe
     */
    private function getTeamBrands($fullData, $selectedFiliale, $filterBrand) {
        if ($_SESSION['profile'] === 'Directeur Groupe' || $_SESSION['profile'] === 'Super Admin' && $selectedFiliale === 'all') {
            $allGroupBrands = [];
            
            foreach ($fullData as $filialeName => $filialeData) {
                if (!isset($filialeData['agencies'])) {
                    continue;
                }
                foreach ($filialeData['agencies'] as $agencyName => $agencyData) {
                    if (!isset($agencyData['managers'])) {
                        continue;
                    }
                    foreach ($agencyData['managers'] as $manager) {
                        if (!isset($manager['technicians'])) {
                            continue;
                        }
                        foreach ($manager['technicians'] as $tech) {
                            if (!empty($tech['brands']) && is_array($tech['brands'])) {
                                foreach ($tech['brands'] as $brand) {
                                    $brandTrim = trim($brand);
                                    if ($brandTrim !== '') {
                                        $allGroupBrands[$brandTrim] = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // Convertir clés associatives en tableau
            $allGroupBrands = array_keys($allGroupBrands);
            // Trier
            sort($allGroupBrands);
            
            return $allGroupBrands;
        } else {
            // Filtrer par marque si spécifié
            if ($filterBrand !== 'all') {
                return [$filterBrand];
            }
            
            // Sinon, retourner toutes les marques de la filiale
            $allSubsidiaryBrands = [];
            
            if (isset($fullData[$selectedFiliale]['agencies'])) {
                foreach ($fullData[$selectedFiliale]['agencies'] as $agencyName => $agencyData) {
                    if (!isset($agencyData['managers'])) {
                        continue;
                    }
                    foreach ($agencyData['managers'] as $manager) {
                        if (!isset($manager['technicians'])) {
                            continue;
                        }
                        foreach ($manager['technicians'] as $tech) {
                            if (!empty($tech['brands']) && is_array($tech['brands'])) {
                                foreach ($tech['brands'] as $brand) {
                                    $brandTrim = trim($brand);
                                    if ($brandTrim !== '') {
                                        $allSubsidiaryBrands[$brandTrim] = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            return array_keys($allSubsidiaryBrands);
        }
    }
    
    /**
     * Récupère le niveau maximum dans l'équipe
     */
    private function getMaxLevel($technicians) {
        $maxLevel = 'Junior';
        foreach ($technicians as $tech) {
            $lvl = $tech['level'] ?? 'Junior';
            if ($lvl === 'Expert') {
                $maxLevel = 'Expert';
                break;
            } elseif ($lvl === 'Senior' && $maxLevel !== 'Expert') {
                $maxLevel = 'Senior';
            }
        }
        return $maxLevel;
    }
    
    /**
     * Récupère les niveaux à afficher selon le niveau max
     */
    private function getLevelsToShowByMaxLevel($maxLevel) {
        $levelsToShow = [];
        if ($maxLevel === 'Expert') {
            $levelsToShow = array_merge($levelsToShow, ['all', 'Junior', 'Senior', 'Expert']);
        } elseif ($maxLevel === 'Senior') {
            $levelsToShow = array_merge($levelsToShow, ['all', 'Junior', 'Senior']);
        } else {
            $levelsToShow = array_merge($levelsToShow, ['all', 'Junior']);
        }
        return $levelsToShow;
    }
    
    /**
     * Récupère la liste des managers
     */
    private function getManagersList($fullData, $selectedFiliale, $selectedAgence, $filterBrand) {
        $managersList = [];
        if (isset($fullData[$selectedFiliale]) && isset($fullData[$selectedFiliale]['agencies'])) {
            foreach ($fullData[$selectedFiliale]['agencies'] as $agencyName => $agencyData) {
                // Filtrer par agence si défini
                if ($selectedAgence !== 'all' && $agencyName !== $selectedAgence) {
                    continue;
                }
                if (isset($agencyData['managers'])) {
                    foreach ($agencyData['managers'] as $manager) {
                        $mId = $manager['id'] ?? null;
                        $mName = $manager['name'] ?? 'Manager Sans Nom';
                        // Vérifier que le manager possède au moins un technicien
                        if ($mId && !isset($managersList[$mId]) && !empty($manager['technicians'])) {
                            // Si un filtre de marque est sélectionné, on vérifie qu'au moins un technicien a cette marque
                            if ($filterBrand !== 'all') {
                                $hasBrand = false;
                                foreach ($manager['technicians'] as $technician) {
                                    if (!empty($technician['brands']) && in_array($filterBrand, $technician['brands'])) {
                                        $hasBrand = true;
                                        break;
                                    }
                                }
                                if (!$hasBrand) {
                                    continue; // ce manager n'a aucun technicien avec la marque filtrée
                                }
                            }
                            $managersList[$mId] = $mName;
                        }
                    }
                }
            }
        }
        return $managersList;
    }
}

/**
 * Classe pour récupérer les données des managers, techniciens et scores
 */
class DataCollection {
    private $collectionManagers;
    private $collectionScores;
    private $result = [];

    public function __construct() {
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            $database = $client->academy;
            $this->collectionManagers = $database->managersBySubsidiaryAgency;
            $this->collectionScores = $database->technicianBrandScores;
        } catch (MongoDB\Exception\Exception $e) {
            throw $e;
        }
    }

    private function addScoreToAggregator(&$aggregator, $level, $brand, $avg) {
        $level = (string) $level;
        $brand = (string) $brand;
        if (!isset($aggregator[$level])) {
            $aggregator[$level] = [];
        }
        if (!isset($aggregator[$level][$brand])) {
            $aggregator[$level][$brand] = ['sum' => 0, 'count' => 0];
        }
        $aggregator[$level][$brand]['sum'] += $avg;
        $aggregator[$level][$brand]['count'] += 1;
    }

    private function mergeAggregatorsAccurate(&$dest, $source) {
        foreach ($source as $level => $brandArr) {
            $level = (string)$level;
            if (!isset($dest[$level])) {
                $dest[$level] = [];
            }
            foreach ($brandArr as $brand => $vals) {
                $brand = (string)$brand;
                if (!isset($dest[$level][$brand])) {
                    $dest[$level][$brand] = ['sum' => 0, 'count' => 0];
                }
                $dest[$level][$brand]['sum'] += $vals['sum'];
                $dest[$level][$brand]['count'] += $vals['count'];
            }
        }
    }

    public static function finalizeAverages($aggregator, $withAllLevel = true) {
        $result = [];
        $allLevelAggregator = [];

        foreach ($aggregator as $level => $brandArr) {
            foreach ($brandArr as $brand => $vals) {
                $count = ($vals['count'] === 0) ? 1 : $vals['count'];
                $avg = round(($vals['sum'] / $count), 2);
                $result[$level][$brand] = $avg;
                if ($withAllLevel) {
                    if (!isset($allLevelAggregator[$brand])) {
                        $allLevelAggregator[$brand] = ['sum' => 0, 'count' => 0];
                    }
                    $allLevelAggregator[$brand]['sum'] += $vals['sum'];
                    $allLevelAggregator[$brand]['count'] += $vals['count'];
                }
            }
        }
        if ($withAllLevel) {
            foreach ($allLevelAggregator as $brand => $vals) {
                $count = ($vals['count'] === 0) ? 1 : $vals['count'];
                $avg = round(($vals['sum'] / $count), 2);
                $result['ALL'][$brand] = $avg;
            }
        }
        return $result;
    }

    private function buildHierarchy() {
        $cursor = $this->collectionManagers->find([]);
        foreach ($cursor as $document) {
            $subsidiary = $document['subsidiary'] ?? 'Unknown';
            $agencies = $document['agencies'] ?? [];

            if (!isset($this->result[$subsidiary])) {
                $this->result[$subsidiary] = [
                    'agencies' => [],
                    'aggregator' => []
                ];
            }
            foreach ($agencies as $agency) {
                $agencyName = $agency['_id'] ?? 'Unknown';

                if (!isset($this->result[$subsidiary]['agencies'][$agencyName])) {
                    $this->result[$subsidiary]['agencies'][$agencyName] = [
                        'managers' => [],
                        'aggregator' => []
                    ];
                }
                $managersArr = $agency['managers'] ?? [];
                foreach ($managersArr as $manager) {
                    $managerName = ($manager['firstName'] ?? '') . " " . ($manager['lastName'] ?? '');
                    $managerAggregator = [];
                    $techniciansList = [];

                    if (isset($manager['technicians'])) {
                        foreach ($manager['technicians'] as $technician) {
                            $techId = $technician['_id'] ?? null;
                            $techName = ($technician['firstName'] ?? '') . " " . ($technician['lastName'] ?? '');
                            $brands = isset($technician['distinctBrands'])
                                ? (array)$technician['distinctBrands']
                                : [];

                            // Chercher les scores du technicien dans technicianBrandScores
                            if ($techId) {
                                $scoreDoc = $this->collectionScores->findOne(['userId' => $techId]);
                            } else {
                                $scoreDoc = null;
                            }

                            $scoresByLevel = [];
                            if ($scoreDoc && isset($scoreDoc['scores'])) {
                                foreach ($scoreDoc['scores'] as $level => $brandScores) {
                                    $tempBrandScore = [];
                                    foreach ($brandScores as $brandName => $scoreDetails) {
                                        $avgTotal = isset($scoreDetails['averageTotalWithPenalty'])
                                            ? (float)$scoreDetails['averageTotalWithPenalty']
                                            : (float)$scoreDetails['averageTotal'];
                                        $tempBrandScore[$brandName] = $avgTotal;
                                        $this->addScoreToAggregator($managerAggregator, $level, $brandName, $avgTotal);
                                    }
                                    $scoresByLevel[$level] = $tempBrandScore;
                                }
                            }

                            // Utiliser $techId et $techName
                            $techniciansList[] = [
                                'id' => htmlspecialchars($techId ?? ''),
                                'name' => htmlspecialchars($techName ?? ''),
                                'brands' => $brands,
                                'scoresLevels' => $scoresByLevel
                            ];
                        }
                    }

                    // Ajouter 'id' au managerEntry
                    $managerEntry = [
                        'id' => (string)($manager['_id'] ?? ''), // Ajout de l'ID du manager
                        'name' => $managerName,
                        'technicians' => $techniciansList,
                        'aggregator' => $managerAggregator
                    ];
                    $this->result[$subsidiary]['agencies'][$agencyName]['managers'][] = $managerEntry;

                    // Fusion dans l'agence
                    $this->mergeAggregatorsAccurate(
                        $this->result[$subsidiary]['agencies'][$agencyName]['aggregator'],
                        $managerAggregator
                    );
                }
                // Fusion dans la filiale
                $this->mergeAggregatorsAccurate(
                    $this->result[$subsidiary]['aggregator'],
                    $this->result[$subsidiary]['agencies'][$agencyName]['aggregator']
                );
            }
        }
                // AJOUT : fusionner toutes les filiales dans un seul aggregator
                // -----------------------------
                $globalAggregator = [];  // on va y fusionner tous les aggregator de chaque filiale

                foreach ($this->result as $subsidiaryName => $subData) {
                    // On s'assure que le sous-tableau de la filiale possède un aggregator
                    if (isset($subData['aggregator']) && is_array($subData['aggregator'])) {
                        // On fusionne
                        $this->mergeAggregatorsAccurate($globalAggregator, $subData['aggregator']);
                    }
                }

                // On stocke l'aggregator global dans $this->result
                // de manière à avoir un bloc "ALL_FILIALES"
                $this->result['ALL_FILIALES'] = [
                    'aggregator' => $globalAggregator
                ];
    }

    
    public function getFullData() {
        // 1) Construire la hiérarchie si ce n'est pas déjà fait
        if (empty($this->result)) {
            $this->buildHierarchy();
        }
    
        // 2) Parcourir toutes les « filiales » (y compris ALL_FILIALES)
        foreach ($this->result as $subsidiary => &$subData) {
    
            // a) Si on a un aggregator au niveau filiale (ou ALL_FILIALES), on calcule les averages
            if (isset($subData['aggregator']) && is_array($subData['aggregator'])) {
                $subData['averages'] = self::finalizeAverages($subData['aggregator'], true);
            }
    
            // b) Si ce n'est pas le bloc "ALL_FILIALES", alors on a potentiellement des agences + managers
            if ($subsidiary !== 'ALL_FILIALES' && isset($subData['agencies']) && is_array($subData['agencies'])) {
                foreach ($subData['agencies'] as &$agencyData) {
                    // Si l'agence possède un aggregator
                    if (isset($agencyData['aggregator']) && is_array($agencyData['aggregator'])) {
                        $agencyData['averages'] = self::finalizeAverages($agencyData['aggregator'], true);
                    }
    
                    // c) Parcourir ses managers
                    if (isset($agencyData['managers']) && is_array($agencyData['managers'])) {
                        foreach ($agencyData['managers'] as &$managerInfo) {
                            if (isset($managerInfo['aggregator']) && is_array($managerInfo['aggregator'])) {
                                $managerInfo['averages'] = self::finalizeAverages($managerInfo['aggregator'], true);
                            }
                        }
                        unset($managerInfo);
                    }
                }
                unset($agencyData);
            }
        }
        unset($subData);
    
        // 3) Retourner le tableau final
        return $this->result;
    }
}
?>