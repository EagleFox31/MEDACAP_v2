<?php
use MongoDB\BSON\ObjectId;
/**
 * Contrôleur de gestion des filtres pour les dashboards
 * Implémente le système de filtrage avancé avec les dépendances hiérarchiques
 */
class FilterController {
    private $academy;
    
    /**
     * Constructeur
     * 
     * @param MongoDB\Database $academy Instance de la base de données MongoDB
     */
    public function __construct($academy = null) {
        $this->academy = $academy;
    }
    
    /**  Sélectionne :
     *   – tous les Techniciens
     *   – les Managers ayant test = true
     */
    private function getTechOrTestManagerClause(): array
    {
        return [
            '$or' => [
                ['profile' => 'Technicien'],
                ['profile' => 'Manager', 'test' => true],
            ],
        ];
    }

    /**
     * Récupère et traite les filtres depuis les paramètres GET
     * 
     * @return array Tableau associatif contenant les filtres appliqués
     */
    public function getFilters() {
        // Récupération des valeurs de filtre depuis l'URL
        $filters = [
            'subsidiary' => $_GET['subsidiary'] ?? $_SESSION['subsidiary'] ?? 'all',
            'agency' => $_GET['agency'] ?? 'all',
            'managerId' => $_GET['managerId'] ?? 'all',
            'level' => $_GET['level'] ?? 'all',
            'brand' => $_GET['brand'] ?? 'all',
            'technicianId' => $_GET['technicianId'] ?? 'all'
        ];
        
        // Logique supplémentaire pour le filtrage selon le profil utilisateur
        if ($_SESSION['profile'] === 'Manager') {
            // Un manager ne peut voir que ses propres techniciens
            $filters['managerId'] = $_SESSION['id'];
        } else if (!in_array($_SESSION['profile'], ['Super Admin', 'Group Director'])) {
            // Ces profils sont limités à leur filiale (sauf Super Admin et Group Director)
            $filters['subsidiary'] = $_SESSION['subsidiary'];
        }
        
        // Si un technicien est sélectionné, pré-remplir tous les autres filtres
        if ($filters['technicianId'] !== 'all') {
            $this->hydrateFiltersFromTechnician($filters);
        }
        // Sinon, si une marque est sélectionnée, ajuster les niveaux et techniciens
        else if ($filters['brand'] !== 'all') {
            $this->adjustFiltersByBrand($filters);
        }
        // Sinon, si un niveau est sélectionné, ajuster les marques et techniciens
        else if ($filters['level'] !== 'all') {
            $this->adjustFiltersByLevel($filters);
        }
        
        return $filters;
    }
    
    /**
     * Remplit tous les filtres en fonction d'un technicien sélectionné
     * 
     * @param array &$filters Tableau de filtres à modifier
     */
    private function hydrateFiltersFromTechnician(&$filters) {
        if (!$this->academy || $filters['technicianId'] === 'all') {
            return;
        }
        
        try {
            // Convertir l'ID du technicien en ObjectId
            $technicianObjId = $filters['technicianId']; // Use string ID directly
            
            // Récupérer les données du technicien
            $technician = $this->academy->users->findOne(
                ['_id' => $technicianObjId],
                [
                    'projection' => [
                        'subsidiary' => 1,
                        'agency' => 1,
                        'manager' => 1,
                        'level' => 1,
                        'brandJunior' => 1,
                        'brandSenior' => 1,
                        'brandExpert' => 1
                    ]
                ]
            );
            
            if ($technician) {
                // Mettre à jour les filtres avec les données du technicien
                $filters['subsidiary'] = $technician['subsidiary'] ?? $filters['subsidiary'];
                $filters['agency'] = $technician['agency'] ?? $filters['agency'];
                
                // Récupérer le manager du technicien si disponible
                if (isset($technician['manager']) && !empty($technician['manager'])) {
                    $filters['managerId'] = (string)$technician['manager'];
                }
                
                // Déterminer le niveau du technicien
                $filters['level'] = $technician['level'] ?? $filters['level'];
                
                // Déterminer les marques associées au technicien en fonction de son niveau
                // Pour l'instant on prend juste la première marque pour simplifier
                $brandField = 'brandJunior';
                if ($filters['level'] === 'Expert' && isset($technician['brandExpert'])) {
                    $brandField = 'brandExpert';
                } else if ($filters['level'] === 'Senior' && isset($technician['brandSenior'])) {
                    $brandField = 'brandSenior';
                }
                
                if (isset($technician[$brandField]) && !empty($technician[$brandField])) {
                    $filters['brand'] = $technician[$brandField];
                }
            }
        } catch (Exception $e) {
            error_log("Erreur lors de l'hydratation des filtres depuis le technicien: " . $e->getMessage());
        }
    }
    
    /**
     * Ajuste les filtres lorsque l’utilisateur choisit une marque.
     */
    private function adjustFiltersByBrand(array &$filters): void
    {
        if (!$this->academy || $filters['brand'] === 'all') {
            return; // rien à faire
        }

        try {
            /* -----------------------------------------------------------------
            * 1) Requête principale : Technicien 𝐎𝐔 Manager(test=true)
            *    + actifs
            *    + possédant la marque demandée (dans l’un des 3 champs)
            * ----------------------------------------------------------------- */
            $query = array_merge(
                $this->getTechOrTestManagerClause(),      // profil Technicien élargi
                [
                    'active' => true,
                    '$or'    => [
                        ['brandJunior' => $filters['brand']],
                        ['brandSenior' => $filters['brand']],
                        ['brandExpert' => $filters['brand']],
                    ],
                ],
            );

            /* -----------------------------------------------------------------
            * 2) Filtres complémentaires (filiale, agence, manager)
            * ----------------------------------------------------------------- */
            if ($filters['subsidiary'] !== 'all') {
                $query['subsidiary'] = $filters['subsidiary'];
            }
            if ($filters['agency'] !== 'all') {
                $query['agency'] = $filters['agency'];
            }
            if ($filters['managerId'] !== 'all') {
                $query['manager'] = $filters['managerId']; // id déjà sous forme de string
            }

            /* -----------------------------------------------------------------
            * 3) Exécution et détermination du niveau max présent
            * ----------------------------------------------------------------- */
            $technicians = $this->academy->users->find(
                $query,
                ['projection' => ['_id' => 1, 'level' => 1]]
            )->toArray();

            $maxLevel             = $this->determineMaxLevel($technicians);
            $filters['maxLevel']  = $maxLevel;

            /* -----------------------------------------------------------------
            * 4) Validation d’un éventuel filtre 'level' déjà actif
            * ----------------------------------------------------------------- */
            if ($filters['level'] !== 'all') {
                $validLevels = $this->getLevelsToInclude($maxLevel);
                if (!in_array($filters['level'], $validLevels, true)) {
                    $filters['level'] = 'all'; // reset si incohérent
                }
            }

        } catch (Exception $e) {
            error_log("Erreur ajustement filtre marque : " . $e->getMessage());
        }
    }

        
    /**
     * Ajuste les filtres lorsque l’utilisateur choisit un niveau.
     *
     * @param array $filters  Référence vers le tableau des filtres courants
     */
    private function adjustFiltersByLevel(array &$filters): void
    {
        if (!$this->academy || $filters['level'] === 'all') {
            return;                                // aucun niveau choisi → rien à faire
        }

        try {
            /* -----------------------------------------------------------------
            * 1) Clause de base : Technicien (+ Manager test=true) actifs
            * ----------------------------------------------------------------- */
            $query = array_merge(
                $this->getTechOrTestManagerClause(),   // profil élargi
                ['active' => true]
            );

            /* -----------------------------------------------------------------
            * 2) Filtre « level » hiérarchique
            *    Junior  → Junior + Senior + Expert
            *    Senior  → Senior + Expert
            *    Expert  → Expert
            * ----------------------------------------------------------------- */
            switch ($filters['level']) {
                case 'Junior':
                    $query['level'] = ['$in' => ['Junior', 'Senior', 'Expert']];
                    break;
                case 'Senior':
                    $query['level'] = ['$in' => ['Senior', 'Expert']];
                    break;
                case 'Expert':
                default:
                    $query['level'] = 'Expert';
                    break;
            }

            /* -----------------------------------------------------------------
            * 3) Filtres complémentaires (filiale, agence, manager)
            * ----------------------------------------------------------------- */
            if ($filters['subsidiary'] !== 'all') {
                $query['subsidiary'] = $filters['subsidiary'];
            }
            if ($filters['agency'] !== 'all') {
                $query['agency'] = $filters['agency'];
            }
            if ($filters['managerId'] !== 'all') {
                $query['manager'] = $filters['managerId'];
            }

            /* -----------------------------------------------------------------
            * 4) Recherche des techniciens et calcul des marques communes
            * ----------------------------------------------------------------- */
            $technicians = $this->academy->users->find(
                $query,
                ['projection' => [
                    '_id'         => 1,
                    'brandJunior' => 1,
                    'brandSenior' => 1,
                    'brandExpert' => 1,
                ]]
            )->toArray();

            $commonBrands               = $this->findCommonBrands($technicians);
            $filters['availableBrands'] = $commonBrands;

            /* Si la marque déjà sélectionnée n’appartient pas au nouvel ensemble,
            on la réinitialise. */
            if ($filters['brand'] !== 'all' && !in_array($filters['brand'], $commonBrands, true)) {
                $filters['brand'] = 'all';
            }

        } catch (Exception $e) {
            error_log("Erreur ajustement filtre niveau : " . $e->getMessage());
        }
    }

    
    /**
     * Détermine le niveau maximum parmi une liste de techniciens
     * 
     * @param array $technicians Liste des techniciens
     * @return string Niveau maximum (Junior, Senior ou Expert)
     */
    private function determineMaxLevel($technicians) {
        $levelMapping = ['Junior' => 1, 'Senior' => 2, 'Expert' => 3];
        $maxLevelValue = 0;
        $maxLevel = 'Junior';
        
        foreach ($technicians as $tech) {
            $levelValue = $levelMapping[$tech['level'] ?? 'Junior'] ?? 0;
            if ($levelValue > $maxLevelValue) {
                $maxLevelValue = $levelValue;
                $maxLevel = $tech['level'];
            }
        }
        
        return $maxLevel;
    }

    private function normalizeBrands($raw): array
    {
        // 1. BSONArray → array PHP
        if ($raw instanceof \MongoDB\Model\BSONArray) {
            $raw = $raw->getArrayCopy();
        }

        // 2. Pas un tableau ?  → []
        if (!is_array($raw)) {
            return [];
        }

        // 3. trim + suppression des vides + unicité
        return array_values(
            array_unique(
                array_filter(
                        array_map('trim', $raw),
                        function ($v) {
                            return $v !== '';
                        }
                    )

            )
        );
    }
    
    /**
     * Trouve les marques communes à tous les techniciens de la liste
     * 
     * @param array $technicians Liste des techniciens
     * @return array Liste des marques communes
     */
    private function findCommonBrands(array $technicians): array
    {
        $sets = [];

        foreach ($technicians as $tech) {
            $merged = array_merge(
                $this->normalizeBrands($tech['brandJunior'] ?? []),
                $this->normalizeBrands($tech['brandSenior'] ?? []),
                $this->normalizeBrands($tech['brandExpert'] ?? [])
            );
            
            // Remove duplicates after merge to avoid repeated values in
            // intersection result
            $sets[] = array_values(array_unique($merged, SORT_STRING));
        }

        // Aucun technicien → aucune marque
        if (empty($sets)) {
            return [];
        }

        // Intersection progressive
        $common = array_shift($sets);
        foreach ($sets as $set) {
            $common = array_intersect($common, $set);
            // Petit court-circuit : plus rien en commun → on peut sortir
            if (empty($common)) {
                return [];
            }
        }

        // Clean duplicate values and reindex
        return array_values(array_unique($common, SORT_STRING));
    }
    
    /**
     * Retourne les niveaux à inclure en fonction du niveau maximum
     * 
     * @param string $maxLevel Niveau maximum (Junior, Senior ou Expert)
     * @return array Liste des niveaux à inclure
     */
    public function getLevelsToInclude($maxLevel) {
        switch ($maxLevel) {
            case 'Expert':
                return ['Junior', 'Senior', 'Expert'];
            case 'Senior':
                return ['Junior', 'Senior'];
            case 'Junior':
            default:
                return ['Junior'];
        }
    }
    
    /**
     * Récupère les filiales disponibles
     * 
     * @return array Liste des filiales disponibles
     */
    public function getSubsidiaries() {
        $subsidiaries = [];
        
        if ($this->academy) {
            try {
                // Récupérer toutes les filiales distinctes depuis la collection des utilisateurs
                $cursor = $this->academy->users->distinct('subsidiary', ['active' => true]);
                $subsidiaries = $cursor;
            } catch (Exception $e) {
                error_log("Erreur lors de la récupération des filiales: " . $e->getMessage());
            }
        }
        
        return $subsidiaries;
    }
    
    /**
     * Récupère les agences d'une filiale
     * 
     * @param string $subsidiary Nom de la filiale
     * @return array Liste des agences de la filiale
     */
    public function getAgencies($subsidiary) {
        $agencies = [];
        
        if ($this->academy && $subsidiary !== 'all') {
            try {
                error_log('[FilterController] Fetching agencies for ' . $subsidiary);
                // Requête insensible à la casse pour éviter les problèmes de
                // variations d'écriture dans la base
                $query = [
                    'subsidiary' => new \MongoDB\BSON\Regex('^' . preg_quote(trim($subsidiary)) . '$', 'i'),
                    'active'     => true
                ];


                // Récupérer toutes les agences distinctes pour la filiale spécifiée
                $agencies = $this->academy->users->distinct('agency', $query);

                // Nettoyer et dédupliquer les valeurs récupérées
                $agencies = array_values(array_unique(array_map('trim', $agencies)));
                error_log('[FilterController] Agencies found: ' . json_encode($agencies));
            } catch (Exception $e) {
                error_log("Erreur lors de la récupération des agences: " . $e->getMessage());
            }
        }

        
        // Fallback: if no agencies retrieved from DB, use static configuration
        if (empty($agencies) && $subsidiary !== 'all') {
            $agencyMap = include __DIR__ . '/../components/agencyData.php';
            if (isset($agencyMap[$subsidiary])) {
                $agencies = $agencyMap[$subsidiary];
                error_log('[FilterController] Agencies from config: ' . json_encode($agencies));
            } else {
                error_log('[FilterController] No agencies found for ' . $subsidiary . ' in config');
            }
        }

        
        return $agencies;
    }
    
    /**
     * Récupère les managers d'une filiale/agence
     * 
     * @param string $subsidiary Nom de la filiale
     * @param string $agency Nom de l'agence (optionnel)
     * @return array Liste des managers avec leur ID et nom
     */
    public function getManagers($subsidiary, $agency = 'all') {
        $managers = [];
        
        if ($this->academy && $subsidiary !== 'all') {
            try {
                // Construire la requête
                $query = [
                    'subsidiary' => $subsidiary,
                    'profile' => 'Manager',
                    'active' => true
                ];
                
                // Ajouter le filtre d'agence si spécifié
                if ($agency !== 'all') {
                    $query['agency'] = $agency;
                }
                
                // Exécuter la requête
                $cursor = $this->academy->users->find($query, [
                    'projection' => [
                        '_id' => 1,
                        'firstName' => 1,
                        'lastName' => 1
                    ]
                ]);
                
                // Traiter les résultats
                foreach ($cursor as $manager) {
                    $managers[(string)$manager['_id']] = $manager['firstName'] . ' ' . $manager['lastName'];
                }
            } catch (Exception $e) {
                error_log("Erreur lors de la récupération des managers: " . $e->getMessage());
            }
        }
        
        return $managers;
    }
    
    /**
     * Récupère les marques disponibles, filtré par les autres critères si nécessaire
     * 
     * @param array $filters Filtres actuels
     * @return array Liste des marques
     */
    public function getBrands($filters = []) {
        // Si des marques filtrées sont déjà disponibles, les utiliser
        if (isset($filters['availableBrands']) && !empty($filters['availableBrands'])) {
            return $filters['availableBrands'];
        }
        
        $brands = [];
        
        if ($this->academy) {
            try {
                $query = ['active' => true];
                
                // Ajouter des filtres supplémentaires si nécessaire
                if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                    $query['subsidiary'] = $filters['subsidiary'];
                }
                if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                    $query['agency'] = $filters['agency'];
                }
                if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                    $query['manager'] = $filters['managerId']; // Use string ID directly
                }
                
                // Récupérer toutes les marques distinctes depuis différentes sources
                $brandJunior = $this->academy->users->distinct('brandJunior', $query);
                $brandSenior = $this->academy->users->distinct('brandSenior', $query);
                $brandExpert = $this->academy->users->distinct('brandExpert', $query);
                
               // Combiner puis dédupliquer en ignorant la casse et les espaces
                $allBrands = array_merge($brandJunior, $brandSenior, $brandExpert);
                $normalized = [];
                foreach ($allBrands as $brandName) {
                    $trimmed = trim((string)$brandName);
                    if ($trimmed === '') {
                        continue;
                    }
                    $key = mb_strtolower($trimmed);
                    if (!isset($normalized[$key])) {
                        $normalized[$key] = $trimmed;
                    }
                }

                $brands = array_values($normalized);

                // Trier les marques alphabétiquement (sans tenir compte de la casse)
                sort($brands, SORT_FLAG_CASE | SORT_STRING);
            } catch (Exception $e) {
                error_log("Erreur lors de la récupération des marques: " . $e->getMessage());
            }
        }
        
        return $brands;
    }
    
    /**
     * Récupère les techniciens disponibles selon les filtres actuels
     * 
     * @param array $filters Filtres actuels
     * @return array Liste des techniciens avec leur ID et nom
     */
    public function getTechnicians($filters = []) {
        $technicians = [];
        
        if ($this->academy) {
            try {
                // Construire la requête de base
                $query = array_merge(
                $this->getTechOrTestManagerClause(),
                ['active'=>true]
            );

                
                // Ajouter des filtres supplémentaires
                if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                    $query['subsidiary'] = $filters['subsidiary'];
                }
                if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                    $query['agency'] = $filters['agency'];
                }
                if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                    $query['manager'] = $filters['managerId']; // Use string ID directly
                }
                
                // Filtre par niveau
                if (isset($filters['level']) && $filters['level'] !== 'all') {
                    // Logique pour inclure les niveaux correspondants
                    if ($filters['level'] === 'Junior') {
                        $query['level'] = ['$in' => ['Junior', 'Senior', 'Expert']];
                    } else if ($filters['level'] === 'Senior') {
                        $query['level'] = ['$in' => ['Senior', 'Expert']];
                    } else if ($filters['level'] === 'Expert') {
                        $query['level'] = 'Expert';
                    }
                }
                
                // Filtre par marque
                if (isset($filters['brand']) && $filters['brand'] !== 'all') {
                    $query['$or'] = [
                        ['brandJunior' => $filters['brand']],
                        ['brandSenior' => $filters['brand']],
                        ['brandExpert' => $filters['brand']]
                    ];
                }
                
                // Exécuter la requête
                $cursor = $this->academy->users->find($query, [
                    'projection' => [
                        '_id' => 1,
                        'firstName' => 1,
                        'lastName' => 1
                    ]
                ]);
                
                // Traiter les résultats
                foreach ($cursor as $technician) {
                    $technicians[(string)$technician['_id']] = $technician['firstName'] . ' ' . $technician['lastName'];
                }
            } catch (Exception $e) {
                error_log("Erreur lors de la récupération des techniciens: " . $e->getMessage());
            }
        }
        
        return $technicians;
    }
    
    /**
     * Détermine si l'utilisateur actuel est un Super Admin ou Group Director
     * 
     * @return bool True si l'utilisateur peut voir toutes les filiales
     */
    public function canSelectSubsidiary() {
        return in_array($_SESSION['profile'] ?? '', ['Super Admin', 'Group Director']);
    }
    
    /**
     * Récupère les statistiques globales des niveaux pour l'UI adaptative
     * 
     * @param array $filters Filtres actuels à appliquer
     * @return array Statistiques des niveaux (juniorCount, seniorCount, expertCount)
     */
    public function getLevelStats($filters = []) {
        $stats = [
            'juniorCount' => 0,
            'seniorCount' => 0,
            'expertCount' => 0
        ];
        
        if ($this->academy) {
            try {
                // Construire la requête de base
                $query = array_merge(
                    $this->getTechOrTestManagerClause(),   // ← remplace $this->getTechOrTestManagerClause()
                    ['active' => true]
                );
                
                // Ajouter des filtres supplémentaires
                if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                    $query['subsidiary'] = $filters['subsidiary'];
                }
                if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                    $query['agency'] = $filters['agency'];
                }
                if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                    $query['manager'] = $filters['managerId']; // Use string ID directly
                }
                if (isset($filters['brand']) && $filters['brand'] !== 'all') {
                    $query['$or'] = [
                        ['brandJunior' => $filters['brand']],
                        ['brandSenior' => $filters['brand']],
                        ['brandExpert' => $filters['brand']]
                    ];
                }
                
                // Compter par niveau
                $stats['juniorCount'] = $this->academy->users->count(array_merge($query, ['level' => 'Junior']));
                $stats['seniorCount'] = $this->academy->users->count(array_merge($query, ['level' => 'Senior']));
                $stats['expertCount'] = $this->academy->users->count(array_merge($query, ['level' => 'Expert']));
            } catch (Exception $e) {
                error_log("Erreur lors du calcul des statistiques de niveau: " . $e->getMessage());
            }
        }
        
        return $stats;
    }
    
    /**
     * Récupère les statistiques globales pour le tableau de bord
     *
     * @param array $filters Filtres actuels à appliquer
     * @return array Statistiques globales
     */
    public function getGlobalStats($filters = []) {
        // Initialisation des statistiques de base
        $stats = [
            'totalTechnicians' => 0,
            'juniorCount' => 0,
            'seniorCount' => 0,
            'expertCount' => 0,
            'measuredCount' => 0,
            'withTrainingCount' => 0,
            'validatedTrainingCount' => 0
        ];
        
        if ($this->academy) {
            try {
                // Obtenir les statistiques de niveau
                $levelStats = $this->getLevelStats($filters);
                $stats['juniorCount'] = $levelStats['juniorCount'];
                $stats['seniorCount'] = $levelStats['seniorCount'];
                $stats['expertCount'] = $levelStats['expertCount'];
                $stats['totalTechnicians'] = $levelStats['juniorCount'] + $levelStats['seniorCount'] + $levelStats['expertCount'];
                
                // Construire la requête de base pour les comptages
                $query = array_merge(
                    $this->getTechOrTestManagerClause(),
                    ['active' => true]
                );

                
                // Ajouter des filtres supplémentaires
                if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                    $query['subsidiary'] = $filters['subsidiary'];
                }
                if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                    $query['agency'] = $filters['agency'];
                }
                if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                    $query['manager'] = $filters['managerId']; // Use string ID directly
                }
                if (isset($filters['level']) && $filters['level'] !== 'all') {
                    if ($filters['level'] === 'Junior') {
                        $query['level'] = ['$in' => ['Junior', 'Senior', 'Expert']];
                    } else if ($filters['level'] === 'Senior') {
                        $query['level'] = ['$in' => ['Senior', 'Expert']];
                    } else if ($filters['level'] === 'Expert') {
                        $query['level'] = 'Expert';
                    }
                }
                if (isset($filters['brand']) && $filters['brand'] !== 'all') {
                    $query['$or'] = [
                        ['brandJunior' => $filters['brand']],
                        ['brandSenior' => $filters['brand']],
                        ['brandExpert' => $filters['brand']]
                    ];
                }
                
                // Compter les techniciens mesurés (possédant des résultats d'évaluation)
                $measuredQuery = array_merge($query, ['evaluationResults' => ['$exists' => true, '$ne' => []]]);
                $stats['measuredCount'] = $this->academy->users->count($measuredQuery);
                
                // Compter les techniciens avec PIF (ayant des formations recommandées)
                $withTrainingQuery = array_merge($query, ['recommendedTrainings' => ['$exists' => true, '$ne' => []]]);
                $stats['withTrainingCount'] = $this->academy->users->count($withTrainingQuery);
                
                // Compter les formations validées
                $validatedTrainingCount = 0;
                $cursor = $this->academy->users->find($withTrainingQuery, [
                    'projection' => ['recommendedTrainings' => 1]
                ]);
                
                foreach ($cursor as $user) {
                    if (isset($user['recommendedTrainings']) && is_array($user['recommendedTrainings'])) {
                        foreach ($user['recommendedTrainings'] as $training) {
                            if (isset($training['status']) && $training['status'] === 'validated') {
                                $validatedTrainingCount++;
                            }
                        }
                    }
                }
                $stats['validatedTrainingCount'] = $validatedTrainingCount;
                
            } catch (Exception $e) {
                error_log("Erreur lors du calcul des statistiques globales: " . $e->getMessage());
            }
        }
        
        return $stats;
    }
    
    /**
     * Récupère les statistiques par marque
     *
     * @param array $filters Filtres actuels à appliquer
     * @return array Statistiques par marque
     */
    public function getBrandStats($filters = []) {
        $stats = [];
        
        if ($this->academy) {
            try {
                // Récupérer les marques disponibles
                $brands = $this->getBrands($filters);
                
                // Initialiser les statistiques pour chaque marque
                foreach ($brands as $brand) {
                    $stats[$brand] = [
                        'technicianCount' => 0,
                        'averageScore' => 0,
                        'recommendedTrainings' => 0,
                        'validatedTrainings' => 0
                    ];
                }
                
                // Construire la requête de base pour les techniciens
                $baseQuery = array_merge(
                    $this->getTechOrTestManagerClause(),
                    ['active'=>true]
                );
                
                // Ajouter les filtres supplémentaires
                if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                    $baseQuery['subsidiary'] = $filters['subsidiary'];
                }
                if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                    $baseQuery['agency'] = $filters['agency'];
                }
                if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                    $baseQuery['manager'] = $filters['managerId'];
                }
                if (isset($filters['level']) && $filters['level'] !== 'all') {
                    if ($filters['level'] === 'Junior') {
                        $baseQuery['level'] = ['$in' => ['Junior', 'Senior', 'Expert']];
                    } else if ($filters['level'] === 'Senior') {
                        $baseQuery['level'] = ['$in' => ['Senior', 'Expert']];
                    } else if ($filters['level'] === 'Expert') {
                        $baseQuery['level'] = 'Expert';
                    }
                }
                
                // Calculer les statistiques pour chaque marque
                foreach ($brands as $brand) {
                    // Requête pour les techniciens de cette marque
                    $query = array_merge($baseQuery, [
                        '$or' => [
                            ['brandJunior' => $brand],
                            ['brandSenior' => $brand],
                            ['brandExpert' => $brand]
                        ]
                    ]);
                    
                    // Compter les techniciens pour cette marque
                    $stats[$brand]['technicianCount'] = $this->academy->users->count($query);
                    
                    // Calculer le score moyen pour cette marque
                    $scoreQuery = array_merge($query, ['evaluationResults' => ['$exists' => true, '$ne' => []]]);
                    $technicians = $this->academy->users->find($scoreQuery, [
                        'projection' => ['evaluationResults' => 1]
                    ])->toArray();
                    
                    $totalScore = 0;
                    $count = 0;
                    
                    foreach ($technicians as $tech) {
                        if (isset($tech['evaluationResults']) && is_array($tech['evaluationResults'])) {
                            foreach ($tech['evaluationResults'] as $eval) {
                                if (isset($eval['brand']) && $eval['brand'] === $brand && isset($eval['score'])) {
                                    $totalScore += $eval['score'];
                                    $count++;
                                }
                            }
                        }
                    }
                    
                    $stats[$brand]['averageScore'] = ($count > 0) ? round($totalScore / $count) : 0;
                    
                    // Calculer les statistiques de formation pour cette marque
                    $trainingQuery = array_merge($query, ['recommendedTrainings' => ['$exists' => true, '$ne' => []]]);
                    $technicians = $this->academy->users->find($trainingQuery, [
                        'projection' => ['recommendedTrainings' => 1]
                    ])->toArray();
                    
                    foreach ($technicians as $tech) {
                        if (isset($tech['recommendedTrainings']) && is_array($tech['recommendedTrainings'])) {
                            foreach ($tech['recommendedTrainings'] as $training) {
                                if (isset($training['brand']) && $training['brand'] === $brand) {
                                    $stats[$brand]['recommendedTrainings']++;
                                    
                                    if (isset($training['status']) && $training['status'] === 'validated') {
                                        $stats[$brand]['validatedTrainings']++;
                                    }
                                }
                            }
                        }
                    }
                }
                
            } catch (Exception $e) {
                error_log("Erreur lors du calcul des statistiques par marque: " . $e->getMessage());
            }
        }
        
        return $stats;
    }
    
    /**
     * Récupère les scores par marque pour les graphiques
     *
     * @param array $filters Filtres actuels à appliquer
     * @return array Scores par marque
     */
    public function getBrandScores($filters = []) {
        $scores = [];
        
        if ($this->academy) {
            try {
                // Récupérer les marques disponibles
                $brands = $this->getBrands($filters);
                
                // Pour chaque marque, calculer un score moyen basé sur les évaluations réelles
                foreach ($brands as $brand) {
                    // Construire la requête pour trouver les techniciens de cette marque
                    $query = array_merge(
                        $this->getTechOrTestManagerClause(),   // Technicien ou Manager(test)
                        [
                            'active'            => true,
                            '$or'               => [
                                ['brandJunior' => $brand],
                                ['brandSenior' => $brand],
                                ['brandExpert' => $brand],
                            ],
                            'evaluationResults' => ['$exists' => true, '$ne' => []],
                        ],
                    );
                    
                    // Ajouter les filtres supplémentaires
                    if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                        $query['subsidiary'] = $filters['subsidiary'];
                    }
                    if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                        $query['agency'] = $filters['agency'];
                    }
                    if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                        $query['manager'] = $filters['managerId'];
                    }
                    
                    // Récupérer les scores d'évaluation pour cette marque
                    $technicians = $this->academy->users->find($query, [
                        'projection' => ['evaluationResults' => 1]
                    ])->toArray();
                    
                    // Calculer le score moyen
                    $totalScore = 0;
                    $count = 0;
                    
                    foreach ($technicians as $tech) {
                        if (isset($tech['evaluationResults']) && is_array($tech['evaluationResults'])) {
                            foreach ($tech['evaluationResults'] as $eval) {
                                if (isset($eval['brand']) && $eval['brand'] === $brand && isset($eval['score'])) {
                                    $totalScore += $eval['score'];
                                    $count++;
                                }
                            }
                        }
                    }
                    
                    $averageScore = ($count > 0) ? round($totalScore / $count) : 0;
                    
                    // Déterminer la couleur en fonction du score
                    $fillColor = '#dc3545'; // Rouge par défaut (score faible)
                    if ($averageScore >= 80) {
                        $fillColor = '#198754'; // Vert (score élevé)
                    } elseif ($averageScore >= 60) {
                        $fillColor = '#ffc107'; // Jaune (score moyen)
                    }
                    
                    // Créer une donnée formatée pour Chart.js
                    $scores[] = [
                        'x' => $brand,
                        'y' => $averageScore,
                        'fillColor' => $fillColor
                    ];
                }
                
                // Trier par score décroissant
                usort($scores, function($a, $b) {
                    return $b['y'] - $a['y'];
                });
                
            } catch (Exception $e) {
                error_log("Erreur lors du calcul des scores par marque: " . $e->getMessage());
            }
        }
        
        return $scores;
    }
    
    /**
     * Récupère les statistiques des formations
     *
     * @param array $filters Filtres actuels à appliquer
     * @return array Statistiques des formations
     */
    public function getTrainingStats($filters = []) {
        $stats = [
            'totalTrainings' => 0,
            'recommendedTrainings' => 0,
            'validatedTrainings' => 0,
            'trainingDays' => 0,
            'brandTrainings' => []
        ];
        
        if ($this->academy) {
            try {
                // Construire la requête de base pour les techniciens
                $query = array_merge(
                    $this->getTechOrTestManagerClause(),   // profil Technicien + Manager(test)
                    [
                        'active'               => true,
                        'recommendedTrainings' => ['$exists' => true, '$ne' => []],
                    ]
                );

                
                // Ajouter les filtres supplémentaires
                if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                    $query['subsidiary'] = $filters['subsidiary'];
                }
                if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                    $query['agency'] = $filters['agency'];
                }
                if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                    $query['manager'] = $filters['managerId'];
                }
                if (isset($filters['level']) && $filters['level'] !== 'all') {
                    if ($filters['level'] === 'Junior') {
                        $query['level'] = ['$in' => ['Junior', 'Senior', 'Expert']];
                    } else if ($filters['level'] === 'Senior') {
                        $query['level'] = ['$in' => ['Senior', 'Expert']];
                    } else if ($filters['level'] === 'Expert') {
                        $query['level'] = 'Expert';
                    }
                }
                
                // Initialiser les statistiques par marque
                $brands = $this->getBrands($filters);
                foreach ($brands as $brand) {
                    $stats['brandTrainings'][$brand] = [
                        'recommended' => 0,
                        'validated' => 0
                    ];
                }
                
                // Récupérer les techniciens avec leurs formations recommandées
                $technicians = $this->academy->users->find($query, [
                    'projection' => ['recommendedTrainings' => 1]
                ])->toArray();
                
                // Calculer les statistiques de formation
                $totalDays = 0;
                
                foreach ($technicians as $tech) {
                    if (isset($tech['recommendedTrainings']) && is_array($tech['recommendedTrainings'])) {
                        foreach ($tech['recommendedTrainings'] as $training) {
                            // Incrémenter le compteur total de formations recommandées
                            $stats['recommendedTrainings']++;
                            
                            // Si la formation a une marque associée, mettre à jour les stats par marque
                            if (isset($training['brand']) && isset($stats['brandTrainings'][$training['brand']])) {
                                $stats['brandTrainings'][$training['brand']]['recommended']++;
                                
                                // Si la formation est validée, mettre à jour les compteurs
                                if (isset($training['status']) && $training['status'] === 'validated') {
                                    $stats['validatedTrainings']++;
                                    $stats['brandTrainings'][$training['brand']]['validated']++;
                                }
                            } else {
                                // Si la formation est validée mais sans marque spécifique
                                if (isset($training['status']) && $training['status'] === 'validated') {
                                    $stats['validatedTrainings']++;
                                }
                            }
                            
                            // Ajouter les jours de formation au total
                            if (isset($training['duration'])) {
                                $totalDays += $training['duration'];
                            } else {
                                // Si la durée n'est pas spécifiée, estimer à 1 jour
                                $totalDays += 1;
                            }
                        }
                    }
                }
                
                $stats['totalTrainings'] = $stats['recommendedTrainings'];
                $stats['trainingDays'] = $totalDays;
                
            } catch (Exception $e) {
                error_log("Erreur lors du calcul des statistiques de formation: " . $e->getMessage());
            }
        }
        
        return $stats;
    }
    
    /**
     * Récupère un résumé des techniciens par filiale
     *
     * @param array $filters Filtres actuels à appliquer
     * @return array Résumé par filiale
     */
    public function getTechnicianSummary($filters = []) {
        $summary = [];
        
        if ($this->academy) {
            try {
                // Récupérer les filiales
                $subsidiaries = $this->getSubsidiaries();
                
                // Pour chaque filiale, calculer les statistiques
                foreach ($subsidiaries as $subsidiary) {
                    // Créer des filtres spécifiques à cette filiale
                    $subsidiaryFilters = array_merge($filters, ['subsidiary' => $subsidiary]);
                    
                    // Récupérer les statistiques de niveau pour cette filiale
                    $levelStats = $this->getLevelStats($subsidiaryFilters);
                    
                    // Calculer le total
                    $totalTechnicians = $levelStats['juniorCount'] + $levelStats['seniorCount'] + $levelStats['expertCount'];
                    
                    // Ne pas inclure les filiales sans techniciens
                    if ($totalTechnicians > 0) {
                        $summary[$subsidiary] = [
                            'totalTechnicians' => $totalTechnicians,
                            'juniorCount' => $levelStats['juniorCount'],
                            'seniorCount' => $levelStats['seniorCount'],
                            'expertCount' => $levelStats['expertCount']
                        ];
                    }
                }
                
            } catch (Exception $e) {
                error_log("Erreur lors du calcul du résumé des techniciens: " . $e->getMessage());
            }
        }
        
        return $summary;
    }

    /**
     * Calcule le nombre de formations proposées et validées par marque
     * en appliquant les filtres spécifiés.
     *
     * @param array $filters Filtres du dashboard
     * @return array [ 'trainingsCounts' => [], 'validationsCounts' => [] ]
     */
    public function getTrainingValidationStats($filters = []) {
        $result = [
            'trainingsCounts'   => [],
            'validationsCounts' => []
        ];

        if (!$this->academy) {
            return $result;
        }

        try {
            $techQuery = array_merge(
                $this->getTechOrTestManagerClause(),
                ['active' => true]
            );

            if (isset($filters['subsidiary']) && $filters['subsidiary'] !== 'all') {
                $techQuery['subsidiary'] = $filters['subsidiary'];
            }
            if (isset($filters['agency']) && $filters['agency'] !== 'all') {
                $techQuery['agency'] = $filters['agency'];
            }
            if (isset($filters['managerId']) && $filters['managerId'] !== 'all') {
                $techQuery['manager'] = $filters['managerId'];
            }
            if (isset($filters['technicianId']) && $filters['technicianId'] !== 'all') {
                $techQuery['_id'] = new ObjectId($filters['technicianId']);
            }
            if (isset($filters['level']) && $filters['level'] !== 'all') {
                $techQuery['level'] = $filters['level'];
            }

            $techCursor = $this->academy->users->find($techQuery, ['projection' => ['_id' => 1]]);
            $techIds   = [];
            foreach ($techCursor as $doc) {
                $techIds[] = $doc['_id'];
            }

            if (empty($techIds)) {
                return $result;
            }

            $trainMatch = [
                'active' => true,
                'users'  => ['$in' => $techIds]
            ];
            if (isset($filters['brand']) && $filters['brand'] !== 'all') {
                $trainMatch['brand'] = $filters['brand'];
            }
            if (isset($filters['level']) && $filters['level'] !== 'all') {
                $trainMatch['level'] = $filters['level'];
            }

            $pipelineTrain = [
                ['$match' => $trainMatch],
                ['$group' => [ '_id' => '$brand', 'count' => ['$sum' => 1]]]
            ];

            foreach ($this->academy->trainings->aggregate($pipelineTrain) as $doc) {
                $brand = (string)$doc->_id;
                $result['trainingsCounts'][$brand] = (int)$doc->count;
            }

            $validPipeline = [
                ['$match' => ['status' => 'Validé', 'user' => ['$in' => $techIds]]],
                ['$lookup' => [
                    'from'         => 'trainings',
                    'localField'   => 'training',
                    'foreignField' => '_id',
                    'as'           => 'training'
                ]],
                ['$unwind' => '$training']
            ];

            if (isset($filters['brand']) && $filters['brand'] !== 'all') {
                $validPipeline[] = ['$match' => ['training.brand' => $filters['brand']]];
            }
            if (isset($filters['level']) && $filters['level'] !== 'all') {
                $validPipeline[] = ['$match' => ['training.level' => $filters['level']]];
            }

            $validPipeline[] = ['$group' => [ '_id' => '$training.brand', 'count' => ['$sum' => 1]]];

            foreach ($this->academy->validations->aggregate($validPipeline) as $doc) {
                $brand = (string)$doc->_id;
                $result['validationsCounts'][$brand] = (int)$doc->count;
            }

        } catch (Exception $e) {
            error_log('Erreur getTrainingValidationStats: ' . $e->getMessage());
        }

        return $result;
    }
}
