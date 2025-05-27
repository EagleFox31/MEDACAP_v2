<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use MongoDB\Client as MongoClient;
use MongoDB\Exception\Exception as MongoException;

// ----------------------------------------------------------
// 1) Vérifier session / profil, puis connexion MongoDB
// ----------------------------------------------------------
if (!isset($_SESSION["profile"])) {
    header("Location: /");
    exit();
} else {
    // Autoriser l'accès si l'utilisateur est Manager, Super Admin ou Directeur Filiale
    if ($_SESSION["profile"] !== 'Manager' && $_SESSION["profile"] !== 'Super Admin' && $_SESSION["profile"] !== 'Directeur Filiale') {
        echo "Accès refusé.";
        exit();
    }

    // Déterminer le managerId
    if ($_SESSION["profile"] === 'Super Admin' || $_SESSION["profile"] === 'Directeur Filiale') {
        // Super Admin ou Directeur Filiale peut spécifier managerId via GET, par défaut à 'all' si non spécifié
        $managerId = isset($_GET['managerId']) ? $_GET['managerId'] : 'all';
    } else {
        // Manager peut uniquement voir son propre tableau de bord
        $managerId = $_SESSION["id"];
    }

    require_once "../../vendor/autoload.php";

    // Connexion
    try {
        $mongo = new MongoDB\Client("mongodb://localhost:27017");
        $academy = $mongo->academy;  // base "academy"

        // Collections
        $usersColl     = $academy->users;
        $trainingsColl = $academy->trainings;
    } catch (MongoDB\Exception\Exception $e) {
        echo "Erreur de connexion à MongoDB : " . htmlspecialchars($e->getMessage());
        exit();
    }

    class DataCollection
    {
        private $collectionManagers;
        private $collectionScores;
        private $result = [];

        public function __construct()
        {
            try {
                $client = new MongoClient("mongodb://localhost:27017");
                $database = $client->academy;
                $this->collectionManagers = $database->managersBySubsidiaryAgency;
                $this->collectionScores   = $database->technicianBrandScores;
            } catch (MongoException $e) {
                throw $e;
            }
        }

        private function addScoreToAggregator(&$aggregator, $level, $brand, $avg)
        {
            $level = (string) $level;
            $brand = (string) $brand;
            if (!isset($aggregator[$level])) {
                $aggregator[$level] = [];
            }
            if (!isset($aggregator[$level][$brand])) {
                $aggregator[$level][$brand] = ['sum' => 0, 'count' => 0];
            }
            $aggregator[$level][$brand]['sum']   += $avg;
            $aggregator[$level][$brand]['count'] += 1;
        }

        private function mergeAggregatorsAccurate(&$dest, $source)
        {
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
                    $dest[$level][$brand]['sum']   += $vals['sum'];
                    $dest[$level][$brand]['count'] += $vals['count'];
                }
            }
        }

        private function finalizeAverages($aggregator, $withAllLevel = true)
        {
            $result = [];
            $allLevelAggregator = [];

            foreach ($aggregator as $level => $brandArr) {
                foreach ($brandArr as $brand => $vals) {
                    $count = ($vals['count'] === 0) ? 1 : $vals['count'];
                    $avg   = round(($vals['sum'] / $count), 2);
                    $result[$level][$brand] = $avg;
                    if ($withAllLevel) {
                        if (!isset($allLevelAggregator[$brand])) {
                            $allLevelAggregator[$brand] = ['sum' => 0, 'count' => 0];
                        }
                        $allLevelAggregator[$brand]['sum']   += $vals['sum'];
                        $allLevelAggregator[$brand]['count'] += $vals['count'];
                    }
                }
            }
            if ($withAllLevel) {
                foreach ($allLevelAggregator as $brand => $vals) {
                    $count = ($vals['count'] === 0) ? 1 : $vals['count'];
                    $avg   = round(($vals['sum'] / $count), 2);
                    $result['ALL'][$brand] = $avg;
                }
            }
            return $result;
        }

        private function buildHierarchy()
        {
            $cursor = $this->collectionManagers->find([]);
            foreach ($cursor as $document) {
                $subsidiary = $document['subsidiary'] ?? 'Unknown';
                $agencies   = $document['agencies']   ?? [];

                if (!isset($this->result[$subsidiary])) {
                    $this->result[$subsidiary] = [
                        'agencies'   => [],
                        'aggregator' => []
                    ];
                }
                foreach ($agencies as $agency) {
                    $agencyName = $agency['_id'] ?? 'Unknown';
                    if (!isset($this->result[$subsidiary]['agencies'][$agencyName])) {
                        $this->result[$subsidiary]['agencies'][$agencyName] = [
                            'managers'   => [],
                            'aggregator' => []
                        ];
                    }
                    $managersArr = $agency['managers'] ?? [];
                    foreach ($managersArr as $manager) {
                        $managerName        = ($manager['firstName'] ?? '') . " " . ($manager['lastName'] ?? '');
                        $managerAggregator  = [];
                        $techniciansList    = [];

                        if (isset($manager['technicians'])) {
                            foreach ($manager['technicians'] as $technician) {
                                $techId       = $technician['_id'] ?? null;
                                $techName     = ($technician['firstName'] ?? '') . " " . ($technician['lastName'] ?? '');
                                $brands       = isset($technician['distinctBrands'])
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
                                            $avgTotal = isset($scoreDetails['averageTotal'])
                                                ? (float)$scoreDetails['averageTotal']
                                                : 0;
                                            $tempBrandScore[$brandName] = $avgTotal;
                                            $this->addScoreToAggregator($managerAggregator, $level, $brandName, $avgTotal);
                                        }
                                        $scoresByLevel[$level] = $tempBrandScore;
                                    }
                                }

                                // Utiliser $techId et $techName
                                $techniciansList[] = [
                                    'id'           => htmlspecialchars($techId ?? ''),
                                    'name'         => htmlspecialchars($techName ?? ''),
                                    'brands'       => $brands,
                                    'scoresLevels' => $scoresByLevel
                                ];
                            }
                        }

                        // Ajouter 'id' au managerEntry
                        $managerEntry = [
                            'id'          => (string)($manager['_id'] ?? ''), // Ajout de l'ID du manager
                            'name'        => $managerName,
                            'technicians' => $techniciansList,
                            'aggregator'  => $managerAggregator
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
        }

        public function getFullData()
        {
            if (empty($this->result)) {
                $this->buildHierarchy();
            }
            foreach ($this->result as $subsidiary => &$subData) {
                // Calculer 'averages' au niveau filiale
                $subData['averages'] = $this->finalizeAverages($subData['aggregator'], true);

                // Parcourir les agences
                foreach ($subData['agencies'] as $agencyName => &$agencyData) {
                    $agencyData['averages'] = $this->finalizeAverages($agencyData['aggregator'], true);

                    // Parcourir les managers
                    foreach ($agencyData['managers'] as &$managerInfo) {
                        $managerInfo['averages'] = $this->finalizeAverages($managerInfo['aggregator'], true);
                    }
                    unset($managerInfo);
                }
                unset($agencyData);
            }
            unset($subData);
            return $this->result;
        }
    }

    // ----------------------------------------------------------
    // 3) Récupération du "FullData" via DataCollection
    // ----------------------------------------------------------
    $dataCollection = new DataCollection();
    $fullData       = $dataCollection->getFullData();

    // ----------------------------------------------------------
    // 4) Récupérer la filiale depuis la session
    // ----------------------------------------------------------
    $subsidiary = $_SESSION["subsidiary"] ?? null;
    if (!$subsidiary) {
        echo "Informations de filiale manquantes (subsidiary).";
        exit();
    }
    // ------------------ BLOC MANAGERS ------------------
    $managersList = [];
    if (isset($fullData[$subsidiary]) && isset($fullData[$subsidiary]['agencies'])) {
        foreach ($fullData[$subsidiary]['agencies'] as $agencyName => $agencyData) {
            if (isset($agencyData['managers'])) {
                foreach ($agencyData['managers'] as $manager) {
                    $mId   = $manager['id']   ?? null;
                    $mName = $manager['name'] ?? 'Manager Sans Nom';
                    if ($mId && !isset($managersList[$mId])) {
                        $managersList[$mId] = $mName;
                    }
                }
            }
        }
    }

    // **Nouvelle section : Initialiser $technicians**
    $technicians = []; // Initialisation par défaut

    // Vérifier que la filiale existe dans $fullData
    if ($managerId === 'all') {
        // Cas où managerId est "all" : on récupère tous les techniciens pour la subsidiary
        if (isset($fullData[$subsidiary])) {
            $subsidiaryData = $fullData[$subsidiary];

            // Parcourir les agences de la filiale
            if (isset($subsidiaryData['agencies'])) {
                foreach ($subsidiaryData['agencies'] as $agency) {
                    // Parcourir les managers de chaque agence
                    if (isset($agency['managers'])) {
                        foreach ($agency['managers'] as $manager) {
                            // Ajouter les techniciens de chaque manager au tableau $technicians
                            if (isset($manager['technicians'])) {
                                foreach ($manager['technicians'] as $technician) {
                                    $technicians[] = [
                                        'id'   => htmlspecialchars($technician['id'] ?? ''),
                                        'name' => htmlspecialchars($technician['name'] ?? '')
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        // Cas où un manager spécifique est sélectionné (logique existante)
        if (isset($fullData[$subsidiary])) {
            $subsidiaryData = $fullData[$subsidiary];

            // Parcourir les agences de la filiale
            if (isset($subsidiaryData['agencies'])) {
                foreach ($subsidiaryData['agencies'] as $agency) {
                    // Parcourir les managers de chaque agence
                    if (isset($agency['managers'])) {
                        foreach ($agency['managers'] as $manager) {
                            if (isset($manager['id']) && $manager['id'] == $managerId) {
                                // Ajouter les techniciens de ce manager
                                if (isset($manager['technicians'])) {
                                    foreach ($manager['technicians'] as $technician) {
                                        $technicians[] = [
                                            'id'   => htmlspecialchars($technician['id'] ?? ''),
                                            'name' => htmlspecialchars($technician['name'] ?? '')
                                        ];
                                    }
                                }
                                break; // Sortir de la boucle des managers si trouvé
                            }
                        }
                    }
                }
            }
        }
    }

    // Optionnel : Gérer le cas où aucun technicien n'est trouvé
    if (empty($technicians)) {
        // Vous pouvez choisir d'afficher un message, de désactiver le filtre, etc.
        // Par exemple :
        // echo "<p>Aucun technicien trouvé pour ce manager.</p>";
    }

    // ----------------------------------------------------------
    // 5) Construire le tableau $brandScores à partir de $fullData
    //    pour le 1er graphique (scores par marque)
    // ----------------------------------------------------------

    // Votre logique existante pour construire $brandScores

    // ----------------------------------------------------------
    // 6) Logique pour le 2ème graphique : Plans de Formations
    //    (via votre pipeline existant pour $managerId)
    // ----------------------------------------------------------
    $filterLevel      = isset($_GET['level'])        ? trim($_GET['level']) : 'all';
    $filterBrand      = isset($_GET['brand'])        ? trim($_GET['brand']) : 'all';
    $filterTechnician = isset($_GET['technicianId']) ? trim($_GET['technicianId']) : 'all';

    // Construire le pipeline conditionnellement
    $pipeline2 = [];

    // Ajouter le match sur manager si managerId n'est pas 'all'
    if ($managerId === 'all') {
        // Cas TOUS LES MANAGERS de LA FILIALE
        $pipeline2[] = [
            '$match' => [
                'profile'    => 'Manager',
                // On suppose que le doc "users" a un champ "subsidiary"
                'subsidiary' => $subsidiary
            ]
        ];
    } else {
        // Cas MANAGER SPÉCIFIQUE
        try {
            $managerObjectId = new MongoDB\BSON\ObjectId($managerId);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            echo "managerId invalide: " . htmlspecialchars($e->getMessage());
            exit();
        }

        $pipeline2[] = [
            '$match' => [
                '_id'     => $managerObjectId,
                'profile' => 'Manager',
                'subsidiary' => $subsidiary
                // On peut aussi matcher 'subsidiary' => $subsidiary si on veut être sûr
            ]
        ];
    }

    // Ajouter le lookup sur les subordinates
    $pipeline2[] = [
        '$lookup' => [
            'from' => 'users',
            'localField' => 'users',
            'foreignField' => '_id',
            'as' => 'subordinates'
        ]
    ];

    // Unwind les subordinates
    $pipeline2[] = [
        '$unwind' => '$subordinates'
    ];

    // Match seulement les techniciens
    $pipeline2[] = [
        '$match' => [
            'subordinates.profile' => 'Technicien'
        ]
    ];

    // Ajouter un match sur technicianId si nécessaire
    if ($filterTechnician !== 'all') {
        try {
            $filterTechnicianId = new MongoDB\BSON\ObjectId($filterTechnician);
            $pipeline2[] = [
                '$match' => [
                    'subordinates._id' => $filterTechnicianId
                ]
            ];
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            echo "technicianId invalide: " . htmlspecialchars($e->getMessage());
            exit();
        }
    }

    // Lookup sur les trainings
    $pipeline2[] = [
        '$lookup' => [
            'from' => 'trainings',
            'localField' => 'subordinates._id',
            'foreignField' => 'users',
            'as' => 'trainings'
        ]
    ];

    // Unwind les trainings
    $pipeline2[] = [
        '$unwind' => '$trainings'
    ];

    if ($filterBrand !== 'all') {
        $pipeline2[] = [
            '$match' => [
                'trainings.brand' => $filterBrand
            ]
        ];
    }
    // Filtrer par level si nécessaire
    if ($filterLevel !== 'all') {
        $pipeline2[] = [
            '$match' => [
                'trainings.level' => $filterLevel
            ]
        ];
    }

    // Grouper par brand et level
    $pipeline2[] = [
        '$group' => [
            '_id' => [
                'brand' => '$trainings.brand',
                'level' => '$trainings.level'
            ],
            'count' => ['$sum' => 1]
        ]
    ];

    // Re-group par brand
    $pipeline2[] = [
        '$group' => [
            '_id' => '$_id.brand',
            'totalByBrand' => ['$sum' => '$count'],
            'totalByLevel' => [
                '$push' => [
                    'k' => '$_id.level',
                    'v' => '$count'
                ]
            ]
        ]
    ];

    // Convertir 'totalByLevel' en objet
    $pipeline2[] = [
        '$addFields' => [
            'totalByLevel' => ['$arrayToObject' => '$totalByLevel']
        ]
    ];

    // Re-group final en objet
    $pipeline2[] = [
        '$group' => [
            '_id' => null,
            'totalTrainingsByBrandAndLevel' => [
                '$push' => [
                    'k' => '$_id',
                    'v' => [
                        'totalByBrand' => '$totalByBrand',
                        'totalByLevel' => '$totalByLevel'
                    ]
                ]
            ]
        ]
    ];

    // Convertir 'totalTrainingsByBrandAndLevel' en objet final
    $pipeline2[] = [
        '$addFields' => [
            'totalTrainingsByBrandAndLevel' => [
                '$arrayToObject' => '$totalTrainingsByBrandAndLevel'
            ]
        ]
    ];

    // Projeter le champ final
    $pipeline2[] = [
        '$project' => [
            '_id' => 0,
            'totalTrainingsByBrandAndLevel' => 1
        ]
    ];

    // Exécuter le pipeline
    try {
        $result2 = $usersColl->aggregate($pipeline2)->toArray();
        $document2 = $result2[0] ?? null;

        if ($document2 === null) {
            // Aucune donnée trouvée
            $trainingsByBrandAndLevel = [];
        } else {
            $trainingsByBrandAndLevel = $document2['totalTrainingsByBrandAndLevel'] ?? [];
        }
    } catch (MongoDB\Exception\Exception $e) {
        echo "Erreur lors du pipeline2 : " . htmlspecialchars($e->getMessage());
        exit();
    }

    // Reconstruire un simple tableau "marque" => "nombre total de formations"
    $trainingsCountsForGraph2 = [];
    $trainingsCountsForGraph2 = [];

    if ($subsidiary === "CFAO MOTORS COTE D'IVOIRE" && $managerId === "all") {
        if ($filterLevel === 'all') {
            // Valeurs codées en dur pour 'all'
            $trainingsCountsForGraph2 = [
                'FUSO'            => 45 + 7,   // 52
                'HINO'            => 10 + 55,  // 65
                'KING LONG'       => 4 + 24,   // 28
                'JCB'             => 43 + 4,   // 47
                'LOVOL'           => 30 + 6,   // 36
                'TOYOTA BT'       => 6 + 3,    // 9
                'TOYOTA FORKLIFT' => 29 + 11   // 40
            ];
        } else if ($filterLevel === 'Junior') {
            // Valeurs codées en dur pour les niveaux spécifiques
            $trainingsCountsForGraph2 = [

                'FUSO'            => 8,
                'HINO'            => 8,
                'KING LONG'       => 8,
                'JCB'             => 4,
                'LOVOL'           => 6,
                'TOYOTA BT'       => 20,
                'TOYOTA FORKLIFT' => 3 + 9 + 30 // 42

            ];
        } else if ($filterLevel === 'Senior') {
            // Valeurs codées en dur pour les niveaux spécifiques
            $trainingsCountsForGraph2 = [

                'FUSO'            => 40,
                'HINO'            => 42 + 7,    // 49
                'KING LONG'       => 14 + 2,    // 16
                'JCB'             => 20,
                'LOVOL'           => 0,
                'TOYOTA BT'       => 3 + 6,     // 9
                'TOYOTA FORKLIFT' => 6 + 18     // 24

            ];
        } else if ($filterLevel === 'Expert') {
            // Valeurs codées en dur pour les niveaux spécifiques
            $trainingsCountsForGraph2 = [

                'FUSO'            => 4,
                'HINO'            => 6 + 2,     // 8
                'KING LONG'       => 3 + 1,     // 4
                'JCB'             => 3,
                'LOVOL'           => 0,
                'TOYOTA BT'       => 0,
                'TOYOTA FORKLIFT' => 2 + 2      // 4

            ];
        }
    } else {
        // Calcul dynamique pour les autres filiales
        if ($filterLevel === 'all') {
            // totalByBrand
            foreach ($trainingsByBrandAndLevel as $brand => $data) {
                $trainingsCountsForGraph2[$brand] = (int)$data['totalByBrand'];
            }
        } else {
            // totalByLevel[$filterLevel]
            foreach ($trainingsByBrandAndLevel as $brand => $data) {
                $trainingsCountsForGraph2[$brand] = (int)($data['totalByLevel'][$filterLevel] ?? 0);
            }
        }
    }

    // Exemple d'utilisation supplémentaire si nécessaire
    // Vous pouvez accéder à $trainingsCountsForGraph2 ici pour générer vos graphiques



    // Choisir la bonne tranche de data selon $filterLevel
    if ($filterLevel === 'all') {
        $scoresArr = $fullData[$subsidiary]['averages']['ALL'] ?? [];
    } else {
        // Ex : 'Junior', 'Senior', 'Expert'
        $scoresArr = $fullData[$subsidiary]['averages'][$filterLevel] ?? [];
    }

    // ----------------------------------------------------------
    // 7) Construire $scoresArr pour le 1er graphique (scores) 
    //    En tenant compte de $filterTechnician et $filterBrand
    // ----------------------------------------------------------
    $scoresArr = [];

    // 1) Récupérer la filiale (subsidiary) dans $fullData
    if (isset($fullData[$subsidiary])) {
        // On peut décider de "fusionner" tous les techniciens du manager OU juste le "technicianFilter"
        // Option A : si $filterTechnician != 'all', ne garder que CE technicien
        // (On reconstruit un aggregator "à la main")
        $aggregator = [];

        foreach ($fullData[$subsidiary]['agencies'] as $agency) {
            foreach ($agency['managers'] as $manager) {
                if (isset($manager['id']) && ($managerId === 'all' || $manager['id'] == $managerId)) {
                    // c'est le manager ou tous les managers
                    foreach ($manager['technicians'] as $tech) {
                        // si $filterTechnician != 'all', skip si ce n'est pas le bon
                        if ($filterTechnician !== 'all') {
                            if ($filterTechnician !== $tech['id']) {
                                continue;
                            }
                        }
                        // Filtre brand => skip si brand pas dans $tech['brands']
                        // (On fera un skip plus tard, ou on peut le faire ici)
                        foreach ($tech['scoresLevels'] as $lvl => $brandsArr) {
                            // Filtre level => skip si $filterLevel != 'all' et $lvl != $filterLevel
                            if ($filterLevel !== 'all' && $lvl !== $filterLevel) {
                                continue;
                            }

                            foreach ($brandsArr as $bName => $avgVal) {
                                // Filtre brand => si $filterBrand != 'all' && $bName != $filterBrand => skip
                                if ($filterBrand !== 'all' && $bName !== $filterBrand) {
                                    continue;
                                }
                                // Sinon on ajoute
                                if (!isset($aggregator[$lvl])) {
                                    $aggregator[$lvl] = [];
                                }
                                if (!isset($aggregator[$lvl][$bName])) {
                                    $aggregator[$lvl][$bName] = ['sum' => 0, 'count' => 0];
                                }
                                $aggregator[$lvl][$bName]['sum']   += $avgVal;
                                $aggregator[$lvl][$bName]['count'] += 1;
                            }
                        }
                    }
                }
            }
        }

        // Petite fonction locale pour calculer
        function finalizeAveragesLocal($aggregator, $withAll = true)
        {
            $res = [];
            $all = [];
            foreach ($aggregator as $lvl => $brandArr) {
                foreach ($brandArr as $br => $vals) {
                    $count = max($vals['count'], 1);
                    $avg   = round($vals['sum'] / $count, 2);
                    if (!isset($res[$lvl])) {
                        $res[$lvl] = [];
                    }
                    $res[$lvl][$br] = $avg;
                    if ($withAll) {
                        if (!isset($all[$br])) {
                            $all[$br] = ['sum' => 0, 'count' => 0];
                        }
                        $all[$br]['sum']   += $vals['sum'];
                        $all[$br]['count'] += $vals['count'];
                    }
                }
            }
            if ($withAll) {
                $res['ALL'] = [];
                foreach ($all as $br => $vals) {
                    $cnt = max($vals['count'], 1);
                    $avg = round($vals['sum'] / $cnt, 2);
                    $res['ALL'][$br] = $avg;
                }
            }
            return $res;
        }

        // Finalize aggregator
        $scoresArr = [];
        $finalAverages = finalizeAveragesLocal($aggregator, true);
        // ex: $finalAverages['ALL'] = [ brand => avgScore, ... ]
        if (isset($finalAverages['ALL'])) {
            $scoresArr = $finalAverages['ALL'];
        }
    }

    // Maintenant on applique le filtre de marque
    // (si $filterBrand !== 'all', on ne garde QUE cette marque)
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
            'x'         => $brandName,
            'y'         => $avgScoreFloat,
            'fillColor' => $color
        ];
    }
    // Comptabiliser la somme totale
    $numTrainings = array_sum($trainingsCountsForGraph2);
    // Supposons 5 jours/formation
    $numDays      = $numTrainings * 5;


    // ----------------------------------------------------------
    // 7) Préparer le HTML + Graphiques
    // ----------------------------------------------------------

    // Extraire le managerName pour l'affichage (facultatif)
    $managerDoc = ($managerId !== 'all') ? $usersColl->findOne(['_id' => new MongoDB\BSON\ObjectId($managerId)]) : null;
    $managerName = ($managerDoc && $managerId !== 'all') ? ($managerDoc['firstName'] . ' ' . $managerDoc['lastName']) : 'Tous les Managers';

    // Extraire la liste des marques via $brandScores
    // (pour l'affichage des logos éventuellement)
    $teamBrands = array_map(function ($item) {
        return isset($item['x']) ? $item['x'] : null;
    }, $brandScores);

    // Extraire la liste des marques
    $teamBrands = array_map(function ($bs) {
        return $bs['x'];
    }, $brandScores);
    $teamBrands = array_filter($teamBrands); // remove null

    // Logos
    $brandLogos = [
        'RENAULT TRUCK'   => 'renaultTrucks.png',
        'HINO'            => 'Hino_logo.png',
        'TOYOTA BT'       => 'bt.png',
        'SINOTRUK'        => 'sinotruk.png',
        'JCB'             => 'jcb.png',
        'MERCEDES TRUCK'  => 'mercedestruck.png',
        'TOYOTA FORKLIFT' => 'forklift.png',
        'FUSO'            => 'fuso.png',
        'LOVOL'           => 'lovol.png',
        'KING LONG'       => 'kl2.png',
        // etc.
    ];
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Manager | CFAO Mobility Academy</title>
        <!-- Inclure Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Inclure Font Awesome pour les icônes (si nécessaire) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Inclure les CDNs des bibliothèques de graphiques -->
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Chart.js Datalabels Plugin -->
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.0"></script>
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Select2 Bootstrap Theme (Optionnel) -->
        <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />


        <style>
            /* Styles personnalisés */

            /* Style général des cartes */
            .custom-card {
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s, box-shadow 0.2s;
                cursor: pointer;
                border: none;
                background-color: #fff;
            }

            .custom-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            }

            /* Logos des marques */
            .brand-logo {
                width: 60px;
                height: 45px;
                margin-bottom: 0.5rem;
            }

            /* Conteneur des graphiques */
            .chart-dashboard-container {
                position: relative;
                padding: 20px;
                width: 100%;
                box-sizing: border-box;
                margin: 0 auto;
            }

            /* Titre des graphiques avec icône */
            .chart-title {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
                font-size: 1.25rem;
                font-weight: bold;
            }

            .chart-title i {
                margin-right: 0.5rem;
                color: #198754;
            }

            /* Responsive ajustements */
            @media (max-width: 768px) {
                .chart-title {
                    font-size: 1rem;
                }

                .brand-logo {
                    width: 40px;
                    height: 30px;
                }
            }

            /* Conteneur des logos */
            #chart-container-logo-container,
            #mesure-container-logo-container {
                z-index: 10;
            }

            /* Ajustement des logos */
            #chart-container-logo-container img,
            #mesure-container-logo-container img {
                transition: transform 0.2s;
            }

            #chart-container-logo-container img:hover,
            #mesure-container-logo-container img:hover {
                transform: scale(1.1);
            }

            /* Canvas des graphiques */
            canvas {
                /* width: 100% !important;
    height: auto !important; */
            }

            /* Scrollable Chart Container */
            .scrollable-chart-container {
                overflow-x: auto;
                white-space: nowrap;
                width: 100%;
                padding-bottom: 25px;
            }

            .scrollable-chart-container canvas {
                width: 100% !important;
                min-width: 800px;
            }

            /* Styles personnalisés pour Select2 */
            .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
                display: flex;
                align-items: center;
            }

            .select2-container--bootstrap4 .select2-results__option .badge {
                margin-left: 10px;
            }
        </style>
    </head>

    <body>
        <?php include "./partials/header.php"; ?>


        <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="toolbar" id="kt_toolbar">
                <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
                    <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                        <h1 class="text-dark fw-bold my-1 fs-2">
                            Tableau de Bord du Directeur Filiale
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
                    <div class="container-xxl">
                        <!-- Filtres -->
                        <div class="row mb-4 justify-content-center">
                            <div class="col-md-3">
                                <label class="form-label"><i class="fas fa-signal me-2 text-warning"></i>Filtrer par Niveau</label>
                                <select id="level-filter" class="form-select">
                                    <option value="all" <?php if ($filterLevel === 'all') echo 'selected'; ?>>Tous</option>
                                    <option value="Junior" <?php if ($filterLevel === 'Junior') echo 'selected'; ?>>Junior</option>
                                    <option value="Senior" <?php if ($filterLevel === 'Senior') echo 'selected'; ?>>Senior</option>
                                    <option value="Expert" <?php if ($filterLevel === 'Expert') echo 'selected'; ?>>Expert</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><i class="fas fa-car me-2 text-warning"></i>Filtrer par Marque</label>
                                <select id="brand-filter" class="form-select">
                                    <option value="all" <?php if ($filterBrand === 'all') echo 'selected'; ?>>Toutes</option>
                                    <?php
                                    // On pourrait lister plus précisément les marques par l'équipe,
                                    // mais ici on se contente des X qu'on a déjà en brandScores
                                    foreach ($teamBrands as $b) {
                                        $sel = (strcasecmp($filterBrand, $b) === 0) ? 'selected' : '';
                                        echo "<option value=\"" . htmlspecialchars($b) . "\" $sel>" . htmlspecialchars($b) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- APRES : Liste de managers dynamique -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    <i class="fas fa-user-tie me-2 text-warning"></i>
                                    Filtrer par Chef d'Équipes
                                </label>
                                <select id="manager-filter" class="form-select"
                                    <?= ($_SESSION["profile"] === 'Manager') ? 'disabled' : ''; ?>>

                                    <?php
                                    // Proposer "Tous" si l'utilisateur est Directeur Filiale ou Super Admin
                                    if ($_SESSION["profile"] === 'Directeur Filiale' || $_SESSION["profile"] === 'Super Admin') {
                                        $selectedAll = ($managerId === 'all') ? 'selected' : '';
                                        echo '<option value="all" ' . $selectedAll . '>Tous les Managers</option>';
                                    }

                                    // On parcourt $managersList (récupéré dynamiquement)
                                    foreach ($managersList as $mId => $mName) {
                                        $isSelected = ($managerId === $mId) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($mId) . '" ' . $isSelected . '>';
                                        echo htmlspecialchars($mName);
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                            </div>


                            <!-- Filtre Technicien -->
                            <div class="col-md-3">
                                <label class="form-label"><i class="fas fa-user me-2 text-warning"></i>Filtrer par Technicien</label>
                                <select id="technician-filter" class="form-select">
                                    <option value="all" <?php if ($filterTechnician === 'all') echo 'selected'; ?>>
                                        Tous
                                    </option>
                                    <?php foreach ($technicians as $t): ?>
                                        <option value="<?php echo htmlspecialchars($t['id']); ?>"
                                            <?php if ($filterTechnician === $t['id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($t['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <script>
                            function applyFilters() {
                                const url = new URL(window.location.href);

                                // Gérer le filtre des managers
                                const selectedManagerId = document.getElementById('manager-filter').value;
                                if (selectedManagerId === 'all') {
                                    url.searchParams.delete('managerId'); // Supprimer le paramètre si "Tous" est sélectionné
                                } else {
                                    url.searchParams.set('managerId', selectedManagerId); // Mettre à jour avec l'ID du manager
                                }

                                // Gérer les autres filtres
                                const lvl = document.getElementById('level-filter').value;
                                if (lvl === 'all') {
                                    url.searchParams.delete('level');
                                } else {
                                    url.searchParams.set('level', lvl);
                                }

                                const br = document.getElementById('brand-filter').value;
                                if (br === 'all') {
                                    url.searchParams.delete('brand');
                                } else {
                                    url.searchParams.set('brand', br);
                                }

                                const tch = document.getElementById('technician-filter').value;
                                if (tch === 'all') {
                                    url.searchParams.delete('technicianId');
                                } else {
                                    url.searchParams.set('technicianId', tch);
                                }

                                window.location.href = url.toString();
                            }

                            document.getElementById('manager-filter').addEventListener('change', applyFilters);
                            document.getElementById('level-filter').addEventListener('change', applyFilters);
                            document.getElementById('brand-filter').addEventListener('change', applyFilters);
                            document.getElementById('technician-filter').addEventListener('change', applyFilters);
                        </script>


                        <br>

                        <!-- Section des Logos des Marques -->
                        <div class="text-center mb-4">

                            <?php
                            $titleManagerPart = ($managerId === 'all')
                                ? "de la Filiale"
                                : "de l'Équipe sélectionnée";

                            $titleBrandPart = ($filterBrand === 'all')
                                ? "Toutes les Marques"
                                : "Marque: " . htmlspecialchars($filterBrand);

                            $titleLevelPart = ($filterLevel === 'all')
                                ? "de Tous les Niveaux"
                                : "du Niveau: " . htmlspecialchars($filterLevel);

                            echo "<h4>$titleBrandPart  $titleLevelPart  $titleManagerPart</h4>";
                            ?>
                            <br>
                            <div class="row justify-content-center">
                                <?php
                                if (!empty($teamBrands)) {
                                    foreach ($teamBrands as $brand) {
                                        $logoSrc = isset($brandLogos[$brand]) ? "brands/" . $brandLogos[$brand] : "brands/default.png";
                                        echo "<div class='col-6 col-sm-4 col-md-3 col-lg-2 mb-4'>";
                                        echo "<div class='card custom-card h-100'>";
                                        echo "<div class='card-body d-flex flex-column justify-content-center align-items-center'>";
                                        echo "<img src='$logoSrc' alt='$brand Logo' class='img-fluid brand-logo' aria-label='Logo $brand'>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<span class='badge bg-secondary me-2 mb-2'>Aucune marque</span>";
                                }
                                ?>
                            </div>
                        </div>

                        <br>

                        <!-- Section des Graphiques -->
                        <div class="chart-dashboard-container">
                            <!-- Graphique 1 : Résultats (Scores) par Marque -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <h3 class="text-center mb-4">1. Résultats aux Tests par Marque</h3>
                                            <div class="scrollable-chart-container">
                                                <canvas id="scoreScatterCanvas" height="600"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cartes de métriques -->
                            <div class="row mb-4 justify-content-center">
                                <div class="col-md-4 mb-3">
                                    <div class="card custom-card text-center">
                                        <div class="card-body">
                                        <i class="fas fa-book-open fa-2x text-primary mb-2"></i>
                                            <h5 class="card-title">Modules de Formation</h5>
                                            <p class="fs-1 fw-bold">
                                                <?php echo (int)$numTrainings; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card custom-card text-center">
                                        <div class="card-body">
                                            <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                                            <h5 class="card-title">Jours de Formation Estimés</h5>
                                            <p class="fs-1 fw-bold">
                                                <?php echo (int)$numDays; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Graphique 2 : Plans de Formation par Marque -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <h3 class="text-center mb-4">2. Plans de Formations de l'équipe par Marque</h3>
                                            <div class="scrollable-chart-container">
                                                <canvas id="trainingsScatterCanvas" height="600"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .chart-dashboard-container -->
                    </div><!-- .container-xxl -->
                </div>
            </div>
        </div>
        <script>
            // Passer les variables PHP au JavaScript via un objet centralisé
            const variablesPHP = {
                brandScores: <?php echo json_encode($brandScores, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                trainingsCountsForGraph2: <?php echo json_encode($trainingsCountsForGraph2, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                brandLogos: <?php echo json_encode($brandLogos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                teamBrands: <?php echo json_encode($teamBrands, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                technicians: <?php echo json_encode($technicians, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                numTrainings: <?php echo json_encode($numTrainings); ?>,
                numDays: <?php echo json_encode($numDays); ?>
                // Ajoutez d'autres variables si nécessaire
            };

            // Optionnel : Vérifiez les données dans la console
            console.log("Variables PHP dans JS:", variablesPHP);

            // Fonction pour dessiner les logos sur les graphiques en utilisant les images préchargées
            function drawLogos(chart, containerId, specificLabels) {
                // Supprimer les anciens conteneurs de logos
                const oldDiv = document.getElementById(containerId + '-logo-container');
                if (oldDiv) oldDiv.remove();

                // Créer un conteneur DIV pour les logos
                const logoContainer = document.createElement('div');
                logoContainer.id = containerId + '-logo-container';
                logoContainer.style.position = 'absolute';
                logoContainer.style.top = '0';
                logoContainer.style.left = '0';
                logoContainer.style.width = '100%';
                logoContainer.style.height = '100%';
                logoContainer.style.pointerEvents = 'none'; // Permettre les événements de souris à travers

                // Obtenir les échelles du graphique
                const xScale = chart.scales.x;
                const chartArea = chart.chartArea;

                const shiftRight = 0;

                // Boucler sur les labels spécifiques pour placer les logos
                specificLabels.forEach((label, index) => {
                    const xPos = xScale.getPixelForValue(index);
                    let yPos;

                    if (containerId === 'scoreScatterCanvas') {
                        yPos = chartArea.bottom + 10; // Ajuster selon les besoins
                    } else if (containerId === 'trainingsScatterCanvas') {
                        yPos = chartArea.bottom + 10; // Ajuster selon les besoins
                    } else {
                        yPos = chartArea.bottom + 10; // Valeur par défaut
                    }

                    // Créer l'élément image
                    const img = document.createElement('img');
                    img.src = variablesPHP.brandLogos[label] ? `brands/${variablesPHP.brandLogos[label]}` : `brands/default.png`;
                    img.style.position = 'absolute';
                    img.style.left = (xPos - 30 + shiftRight) + 'px'; // Centrer l'image (ajusté pour 60px de largeur)
                    img.style.top = yPos + 'px';
                    img.style.width = '60px';
                    img.style.height = '35px';
                    img.onerror = function() {
                        console.error(`Erreur de chargement de l'image : ${img.src}`);
                        img.src = 'brands/default.png';
                    };

                    // Ajouter l'image au conteneur
                    logoContainer.appendChild(img);
                });

                // Ajouter le conteneur au parent
                const chartContainer = document.getElementById(containerId);
                chartContainer.parentElement.style.position = 'relative'; // Assurer que le parent est positionné relativement
                chartContainer.parentElement.appendChild(logoContainer);
            }

            // Plugins personnalisés pour ajouter des images après le rendu
            const imagePluginScatter = {
                id: 'imagePluginScatter',
                afterRender: (chart) => drawLogos(chart, 'scoreScatterCanvas', variablesPHP.brandScores.map(item => item.x)),
                afterResize: (chart) => {
                    const logoContainer = document.getElementById('scoreScatterCanvas-logo-container');
                    if (logoContainer) {
                        logoContainer.remove();
                    }
                    drawLogos(chart, 'scoreScatterCanvas', variablesPHP.brandScores.map(item => item.x));
                }
            };

            const imagePluginScatterTrainings = {
                id: 'imagePluginScatterTrainings',
                afterRender: (chart) => drawLogos(chart, 'trainingsScatterCanvas', variablesPHP.brandScores.map(item => item.x)),
                afterResize: (chart) => {
                    const logoContainer = document.getElementById('trainingsScatterCanvas-logo-container');
                    if (logoContainer) {
                        logoContainer.remove();
                    }
                    drawLogos(chart, 'trainingsScatterCanvas', variablesPHP.brandScores.map(item => item.x));
                }
            };

            document.addEventListener('DOMContentLoaded', function() {
                // ------------------- Données pour Graphique 1 (Scores) -------------------
                const brandScoresPHP = variablesPHP.brandScores;
                // brandScoresPHP : [ {x: "RENAULT TRUCK", y: 78.6, fillColor: "#..."}, ... ]

                console.log("Brand Scores:", brandScoresPHP);

                const brandLabelsScores = brandScoresPHP.map(item => item.x);
                const scatterScores = brandScoresPHP.map((obj, index) => {
                    return {
                        x: index,
                        y: obj.y,
                        fillColor: obj.fillColor
                    };
                });

                // ------------------- Données pour Graphique 2 (Trainings) -------------------
                const trainingsCountsForGraph2 = variablesPHP.trainingsCountsForGraph2;
                // => { "RENAULT TRUCK": 5, "HINO": 2, ... }

                console.log("Trainings Counts for Graph 2:", trainingsCountsForGraph2);

                // ------------------- Liste des Techniciens -------------------
                const techniciansList = variablesPHP.technicians;
                console.log("Technicians List:", techniciansList);

                // ------------------- Liste des Marques -------------------
                const teamBrandsList = variablesPHP.teamBrands;
                console.log("Team Brands List:", teamBrandsList);

                // ------------------- Préparation des Données pour le Deuxième Graphique -------------------
                const scatterTrainings = [];
                brandLabelsScores.forEach((b, i) => {
                    const count = trainingsCountsForGraph2[b] ?? 0;
                    // On se cale sur l'axe Y = score ; ou 0..100
                    let relevantScore = 0;
                    const found = brandScoresPHP.find(x => x.x === b);
                    if (found && typeof found.y === 'number') {
                        relevantScore = found.y;
                    }
                    scatterTrainings.push({
                        x: i,
                        y: relevantScore,
                        count: count
                    });
                });

                console.log("Scatter Trainings Data:", scatterTrainings);

                // ------------------- ChartJS : Score Scatter -------------------
                Chart.register(ChartDataLabels, ChartZoom);

                const ctx1 = document.getElementById('scoreScatterCanvas').getContext('2d');
                const chartScores = new Chart(ctx1, {
                    type: 'scatter',
                    data: {
                        datasets: [{
                            label: "Scores par Marque",
                            data: scatterScores.map(d => ({
                                x: d.x,
                                y: d.y
                            })),
                            pointRadius: 55,
                            pointHoverRadius: 60,
                            backgroundColor: '#aaaaa7',
                            borderColor: '#aaaaa7'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            datalabels: {
                                display: true,
                                formatter: (value, ctx) => {
                                    return `${Math.round(value.y)}%`; // Arrondir à l'entier le plus proche
                                },
                                color: '#000',
                                font: {
                                    size: 18,
                                    weight: 'bold'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const i = context.dataIndex;
                                        return [
                                            `Marque: ${brandLabelsScores[i]}`,
                                            `Score: ${Math.round(scatterScores[i].y)}%` // Arrondir à l'entier le plus proche
                                        ];
                                    }
                                }
                            },
                            zoom: {
                                pan: {
                                    enabled: true,
                                    mode: 'x'
                                },
                                zoom: {
                                    enabled: false
                                }
                            }
                        },
                        scales: {
                            x: {
                                type: 'linear',
                                min: -0.5,
                                max: scatterScores.length - 0.5,
                                grid: {
                                    color: '#ccc'
                                },
                                ticks: {
                                    display: false // Masquer les labels textuels
                                }
                            },
                            y: {
                                type: 'linear',
                                min: 0,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Score (%)'
                                },
                                grid: {
                                    color: '#ccc'
                                },
                                ticks: {
                                    stepSize: 10,
                                    padding: 10,
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        }
                    },
                    plugins: [imagePluginScatter, ChartDataLabels, ChartZoom]
                });

                // Plugin personnalisé pour dessiner le nombre de formations + "Module(s)"
                const customLabelPlugin = {
                    id: 'customLabelPlugin',
                    afterDraw: (chart) => {
                        const ctx = chart.ctx;

                        // Parcourir chaque dataset (même si vous n'en avez qu'un)
                        chart.data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            // Chaque point est dans meta.data
                            meta.data.forEach((element, index) => {
                                // Récupérer le nom de la marque selon l'index
                                // (brandLabelsScores doit contenir le même ordre que vos données X)
                                const brandName = brandLabelsScores[index];

                                // Récupérer le nombre de modules pour cette marque (depuis trainingsCountsForGraph2)
                                const count = trainingsCountsForGraph2[brandName] || 0;

                                // Position du point (centre)
                                const position = element.getCenterPoint();

                                // Dessiner le count (arrondir si nécessaire)
                                ctx.font = 'bold 20px Arial';
                                ctx.fillStyle = '#000';
                                ctx.textAlign = 'center';
                                ctx.fillText(Math.round(count), position.x, position.y - 15); // Arrondir 'count' si nécessaire

                                // Dessiner "Module(s)" en-dessous
                                ctx.font = '13px Arial';
                                ctx.fillStyle = '#000';
                                ctx.textAlign = 'center';
                                ctx.fillText('Module(s)', position.x, position.y + 15);
                            });
                        });
                    }
                };

                // ------------------- ChartJS : Trainings Scatter -------------------
                const ctx2 = document.getElementById('trainingsScatterCanvas').getContext('2d');
                const chartFormations = new Chart(ctx2, {
                    type: 'scatter',
                    data: {
                        datasets: [{
                            label: "Formations Recommandées",
                            data: scatterTrainings,
                            pointRadius: 55,
                            pointHoverRadius: 60,
                            backgroundColor: 'rgba(255,193,7,0.6)',
                            borderColor: 'rgba(255,193,7,1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            datalabels: {
                                display: false,
                                formatter: (value, ctx) => {
                                    const dataPoint = ctx.dataset.data[ctx.dataIndex];
                                    return `${Math.round(dataPoint.count)} modules`; // Arrondir 'count' si nécessaire
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const i = context.dataIndex;
                                        const brand = brandLabelsScores[i];
                                        const count = Math.round(scatterTrainings[i].count); // Arrondir si nécessaire
                                        return [
                                            `Marque: ${brand}`,
                                            `Formations: ${count}`
                                        ];
                                    }
                                }
                            },
                            zoom: {
                                pan: {
                                    enabled: true,
                                    mode: 'x'
                                },
                                zoom: {
                                    enabled: false
                                }
                            }
                        },
                        scales: {
                            x: {
                                type: 'linear',
                                min: -0.5,
                                max: scatterTrainings.length - 0.5,
                                grid: {
                                    color: '#ccc'
                                },
                                ticks: {
                                    display: false // Masquer les labels textuels
                                }
                            },
                            y: {
                                type: 'linear',
                                min: 0,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Modules'
                                },
                                grid: {
                                    color: '#ccc'
                                },
                                ticks: {
                                    stepSize: 10,
                                    padding: 10,
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        }
                    },
                    plugins: [imagePluginScatterTrainings, customLabelPlugin, ChartDataLabels, ChartZoom]
                });
            });
        </script>


    </body>

    </html>
<?php } ?>