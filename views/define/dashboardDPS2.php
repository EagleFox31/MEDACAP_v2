<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use MongoDB\Client as MongoClient;
use MongoDB\Exception\Exception as MongoException;

// Vérifier que l'utilisateur est connecté et a un profil autorisé
if (!isset($_SESSION["profile"])) {
    header("Location: /");
    exit();
} else {
    // Pour cet exemple, on autorise Manager, Super Admin, Directeur Filiale, Directeur Groupe et Technicien
    $profile = $_SESSION["profile"];
    if (!in_array($profile, ['Manager', 'Super Admin', 'Directeur Filiale', 'Directeur Groupe', 'Technicien'])) {
        echo "Accès refusé.";
        exit();
    }

    // Déterminer le managerId :
    // - Pour Super Admin, Directeur Filiale et Directeur Groupe, on peut le passer en GET (ou 'all')
    // - Pour Manager, on prend son ID depuis la session
    // - Pour Technicien, on peut par exemple récupérer son manager via $_SESSION['managerId'] (si existant)
    if (in_array($profile, ['Super Admin', 'Directeur Filiale', 'Directeur Groupe'])) {
        $managerId = isset($_GET['managerId']) ? $_GET['managerId'] : 'all';
    } elseif ($profile === 'Manager') {
        $managerId = $_SESSION["id"];
    } else { // Technicien
        $managerId = $_SESSION["managerId"] ?? 'all';
    }

    if ($profile === 'Technicien') {
        if ($profile === 'Super Admin' && isset($_GET['id'])) {
            $technicianId = $_GET['id'];
        } else {
            $technicianId = $_SESSION["id"];
        }
    }

    require_once "../../vendor/autoload.php";

    // Connexion à MongoDB
    try {
        $mongo   = new MongoClient("mongodb://localhost:27017");
        $academy = $mongo->academy;
        $usersColl     = $academy->users;
        $trainingsColl = $academy->trainings;
    } catch (MongoDB\Exception\Exception $e) {
        echo "Erreur de connexion à MongoDB : " . htmlspecialchars($e->getMessage());
        exit();
    }

    // Classe DataCollection (identique à votre version)
    class DataCollection
    {
        private $collectionManagers;
        private $collectionScores;
        private $result = [];

        public function __construct()
        {
            try {
                $client   = new MongoClient("mongodb://localhost:27017");
                $database = $client->academy;
                $this->collectionManagers = $database->managersBySubsidiaryAgency;
                $this->collectionScores   = $database->technicianBrandScores;
            } catch (MongoException $e) {
                throw $e;
            }
        }

        private function addScoreToAggregator(&$aggregator, $level, $brand, $avg)
        {
            $level = (string)$level;
            $brand = (string)$brand;
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
                    $avg = round(($vals['sum'] / $count), 2);
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
                    $avg = round(($vals['sum'] / $count), 2);
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
                $agencies = $document['agencies'] ?? [];
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
                        $managerName = ($manager['firstName'] ?? '') . " " . ($manager['lastName'] ?? '');
                        $managerAggregator = [];
                        $techniciansList = [];
                        if (isset($manager['technicians'])) {
                            foreach ($manager['technicians'] as $technician) {
                                $techId = $technician['_id'] ?? null;
                                $techName = ($technician['firstName'] ?? '') . " " . ($technician['lastName'] ?? '');
                                $brands = isset($technician['distinctBrands']) ? (array)$technician['distinctBrands'] : [];
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
                                            $avgTotal = isset($scoreDetails['averageTotal']) ? (float)$scoreDetails['averageTotal'] : 0;
                                            $tempBrandScore[$brandName] = $avgTotal;
                                            $this->addScoreToAggregator($managerAggregator, $level, $brandName, $avgTotal);
                                        }
                                        $scoresByLevel[$level] = $tempBrandScore;
                                    }
                                }
                                $techniciansList[] = [
                                    'id' => htmlspecialchars($techId ?? ''),
                                    'name' => htmlspecialchars($techName ?? ''),
                                    'brands' => $brands,
                                    'scoresLevels' => $scoresByLevel
                                ];
                            }
                        }
                        $managerEntry = [
                            'id' => (string)($manager['_id'] ?? ''),
                            'name' => $managerName,
                            'technicians' => $techniciansList,
                            'aggregator' => $managerAggregator
                        ];
                        $this->result[$subsidiary]['agencies'][$agencyName]['managers'][] = $managerEntry;
                        $this->mergeAggregatorsAccurate($this->result[$subsidiary]['agencies'][$agencyName]['aggregator'], $managerAggregator);
                    }
                    $this->mergeAggregatorsAccurate($this->result[$subsidiary]['aggregator'], $this->result[$subsidiary]['agencies'][$agencyName]['aggregator']);
                }
            }
        }

        public function getFullData()
        {
            if (empty($this->result)) {
                $this->buildHierarchy();
            }
            foreach ($this->result as $subsidiary => &$subData) {
                $subData['averages'] = $this->finalizeAverages($subData['aggregator'], true);
                foreach ($subData['agencies'] as $agencyName => &$agencyData) {
                    $agencyData['averages'] = $this->finalizeAverages($agencyData['aggregator'], true);
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
    // Fin de DataCollection

    $dataCollection = new DataCollection();
    $fullData = $dataCollection->getFullData();

    // Récupérer la filiale depuis la session
    $subsidiary = $_SESSION["subsidiary"] ?? null;
    if (!$subsidiary) {
        echo "Informations de filiale manquantes (subsidiary).";
        exit();
    }

    // Récupérer les filtres passés en GET
    $filterBrand = isset($_GET['brand']) ? trim($_GET['brand']) : 'all';
    $filterLevel = isset($_GET['level']) ? trim($_GET['level']) : 'all';
    $filterTechnician = isset($_GET['technicianId']) ? trim($_GET['technicianId']) : 'all';

    // Construction des listes de managers et techniciens (même logique que votre code existant)
    $managersList = [];
    if (isset($fullData[$subsidiary]['agencies'])) {
        foreach ($fullData[$subsidiary]['agencies'] as $agencyData) {
            if (isset($agencyData['managers'])) {
                foreach ($agencyData['managers'] as $manager) {
                    $mId = $manager['id'] ?? null;
                    $mName = $manager['name'] ?? 'Manager Sans Nom';
                    if ($mId && !isset($managersList[$mId]) && !empty($manager['technicians'])) {
                        if ($filterBrand !== 'all') {
                            $hasBrand = false;
                            foreach ($manager['technicians'] as $technician) {
                                if (!empty($technician['brands']) && in_array($filterBrand, $technician['brands'])) {
                                    $hasBrand = true;
                                    break;
                                }
                            }
                            if (!$hasBrand) continue;
                        }
                        $managersList[$mId] = $mName;
                    }
                }
            }
        }
    }

    $technicians = [];
    if ($managerId === 'all') {
        if (isset($fullData[$subsidiary]['agencies'])) {
            foreach ($fullData[$subsidiary]['agencies'] as $agency) {
                if (isset($agency['managers'])) {
                    foreach ($agency['managers'] as $manager) {
                        if (isset($manager['technicians'])) {
                            foreach ($manager['technicians'] as $technician) {
                                if ($filterBrand !== 'all') {
                                    if (empty($technician['brands']) || !in_array($filterBrand, $technician['brands'])) {
                                        continue;
                                    }
                                }
                                $techId = $technician['id'] ?? '';
                                $userDoc = (!empty($techId)) ? $usersColl->findOne(['_id' => new MongoDB\BSON\ObjectId($techId)]) : null;
                                $techLevel = $userDoc['level'] ?? 'Junior';
                                $technicians[] = [
                                    'id' => htmlspecialchars($techId),
                                    'name' => htmlspecialchars($technician['name'] ?? ''),
                                    'level' => htmlspecialchars($techLevel)
                                ];
                            }
                        }
                    }
                }
            }
        }
    } else {
        if (isset($fullData[$subsidiary]['agencies'])) {
            foreach ($fullData[$subsidiary]['agencies'] as $agency) {
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
                                    $userDoc = (!empty($techId)) ? $usersColl->findOne(['_id' => new MongoDB\BSON\ObjectId($techId)]) : null;
                                    $techLevel = $userDoc['level'] ?? 'Junior';
                                    $technicians[] = [
                                        'id' => htmlspecialchars($techId),
                                        'name' => htmlspecialchars($technician['name'] ?? ''),
                                        'level' => htmlspecialchars($techLevel)
                                    ];
                                }
                            }
                            break;
                        }
                    }
                }
            }
        }
    }
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

       // Juste avant le $facet :
       $brandAndLevelPipeline = [];

       if ($managerId === 'all' || $filterTechnician === 'all') {
           // CAS DISTINCT : group par trainingId
           $brandAndLevelPipeline = [
               // 1er group => group par brand/level/trainingId
               [
                   '$group' => [
                       '_id' => [
                           'brand'      => '$trainings.brand',
                           'level'      => '$trainings.level',
                           'trainingId' => '$trainings._id'
                       ],
                       'count' => ['$sum' => 1]
                   ]
               ],
               // 2e group => brand + level => on additionne
               [
                   '$group' => [
                       '_id' => [
                           'brand' => '$_id.brand',
                           'level' => '$_id.level'
                       ],
                       'count' => ['$sum' => 1]
                   ]
               ],
               // 3e group final
               [
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
               ],
               [
                   '$addFields' => [
                       'totalByLevel' => ['$arrayToObject' => '$totalByLevel']
                   ]
               ],
               [
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
               ],
               [
                   '$addFields' => [
                       'totalTrainingsByBrandAndLevel' => [
                           '$arrayToObject' => '$totalTrainingsByBrandAndLevel'
                       ]
                   ]
               ],
               [
                   '$project' => [
                       '_id' => 0,
                       'totalTrainingsByBrandAndLevel' => 1
                   ]
               ]
           ];
       } else {
           // CAS NON-DISTINCT : group par brand/level, on compte TOUT
           $brandAndLevelPipeline = [
               // group par brand + level SANS trainingId
               [
                   '$group' => [
                       '_id' => [
                           'brand' => '$trainings.brand',
                           'level' => '$trainings.level'
                       ],
                       'count' => ['$sum' => 1]
                   ]
               ],
               // on n'a plus besoin du 1er regroupement distinct
               [
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
               ],
               [
                   '$addFields' => [
                       'totalByLevel' => ['$arrayToObject' => '$totalByLevel']
                   ]
               ],
               [
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
               ],
               [
                   '$addFields' => [
                       'totalTrainingsByBrandAndLevel' => [
                           '$arrayToObject' => '$totalTrainingsByBrandAndLevel'
                       ]
                   ]
               ],
               [
                   '$project' => [
                       '_id' => 0,
                       'totalTrainingsByBrandAndLevel' => 1
                   ]
               ]
           ];
       }


       $pipeline2[] = [
           '$facet' => [
               'brandAndLevel' => $brandAndLevelPipeline,
               'totalOccurrences' => [
                   [
                       '$group' => [
                           '_id' => null,
                           'totalOccurrences' => ['$sum' => 1]
                       ]
                   ],
                   [
                       '$project' => [
                           '_id' => 0,
                           'totalOccurrences' => 1
                       ]
                   ]
               ]
           ]
       ];



       // Exécuter le pipeline
       try {
           $result2 = $usersColl->aggregate($pipeline2)->toArray();
           $document2 = $result2[0] ?? null;

           if ($document2) {
               // Aucune donnée trouvée
               $brandAndLevelArr = $document2['brandAndLevel'][0] ?? null;
               if ($brandAndLevelArr) {
                   $trainingsByBrandAndLevel = $brandAndLevelArr['totalTrainingsByBrandAndLevel'] ?? [];
               } else {
                   $trainingsByBrandAndLevel = [];
               }
               // Récupération de la partie totalOccurrences
               $occurrencesArr = $document2['totalOccurrences'][0] ?? [];
               $totalOccurrences = $occurrencesArr['totalOccurrences'] ?? 0;
           } else {
               // Pas de résultat => vides
               $trainingsByBrandAndLevel = [];
               $totalOccurrences = 0;
           }
           //     $trainingsByBrandAndLevel = [];
           // } else {
           //     $trainingsByBrandAndLevel = $document2['totalTrainingsByBrandAndLevel'] ?? [];
           // }
       } catch (MongoDB\Exception\Exception $e) {
           echo "Erreur lors du pipeline2 : " . htmlspecialchars($e->getMessage());
           exit();
       }

       $trainingsCountsForGraph2 = [];
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
        // Choisir la bonne tranche de data selon $filterLevel
        if ($filterLevel === 'all') {
            $scoresArr = $fullData[$subsidiary]['averages']['ALL'] ?? [];
        } else {
            // Ex : 'Junior', 'Senior', 'Expert'
            $scoresArr = $fullData[$subsidiary]['averages'][$filterLevel] ?? [];
        }
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
    // (Les pipelines pour les graphiques et le calcul des métriques restent inchangés dans votre code.)
    // Pour cet exemple, nous définissons des valeurs fictives :
    $numTrainings = array_sum($trainingsCountsForGraph2);
    $numDays = $totalOccurrences * 5;

    // Récupérer le managerName
    $managerDoc = ($managerId !== 'all') ? $usersColl->findOne(['_id' => new MongoDB\BSON\ObjectId($managerId)]) : null;
    $managerName = ($managerDoc && $managerId !== 'all') ? ($managerDoc['firstName'] . ' ' . $managerDoc['lastName']) : 'Tous les Managers';
    $allSubsidiaryBrands = []; // contiendra toutes les marques

    // Parcourt de la filiale pour récupérer toutes les marques
    if (isset($fullData[$subsidiary]['agencies'])) {
        foreach ($fullData[$subsidiary]['agencies'] as $agency) {
            if (isset($agency['managers'])) {
                foreach ($agency['managers'] as $manager) {
                    if (isset($manager['technicians'])) {
                        foreach ($manager['technicians'] as $technician) {
                            // $technician['brands'] est censé lister les "distinctBrands" 
                            // faites un merge dans $allSubsidiaryBrands
                            if (!empty($technician['brands'])) {
                                foreach ($technician['brands'] as $br) {
                                    $allSubsidiaryBrands[$br] = true;
                                    // (un simple array associatif pour éviter les doublons)
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // On extrait la liste finale :
    $allSubsidiaryBrands = array_keys($allSubsidiaryBrands);
    // Construire la liste des marques à afficher (à partir des scores, par exemple)
    $teamBrands = array_map(function ($item) {
        return isset($item['x']) ? $item['x'] : null;
    }, $brandScores);

    // Extraire la liste des marques
    $teamBrands = array_map(function ($bs) {
        return $bs['x'];
    }, $brandScores);
    $teamBrands = array_filter($teamBrands); // remove null

    // Logos (définition fixe)
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
    ];
    $brandsToShow = [];
if ($profile === 'Technicien') {
    // Récupérer le document du technicien (s'il n'est pas déjà chargé)
    $technicianDoc = $usersColl->findOne([
        '_id' => new MongoDB\BSON\ObjectId($technicianId),
        'profile' => 'Technicien'
    ]);
    if ($technicianDoc) {
        $brandFieldJunior = $technicianDoc['brandJunior'] ?? [];
        $brandFieldSenior = $technicianDoc['brandSenior'] ?? [];
        $brandFieldExpert = $technicianDoc['brandExpert'] ?? [];
        $allBrandsInDoc = [];
        // Dépendant du niveau filtré : 
        if (in_array('Junior', ($filterLevel === 'all' ? ['Junior','Senior','Expert'] : [$filterLevel]))) {
            foreach ($brandFieldJunior as $b) {
                $bTrimmed = trim((string)$b);
                if ($bTrimmed !== '') { $allBrandsInDoc[] = $bTrimmed; }
            }
        }
        if (in_array('Senior', ($filterLevel === 'all' ? ['Junior','Senior','Expert'] : [$filterLevel]))) {
            foreach ($brandFieldSenior as $b) {
                $bTrimmed = trim((string)$b);
                if ($bTrimmed !== '') { $allBrandsInDoc[] = $bTrimmed; }
            }
        }
        if (in_array('Expert', ($filterLevel === 'all' ? ['Junior','Senior','Expert'] : [$filterLevel]))) {
            foreach ($brandFieldExpert as $b) {
                $bTrimmed = trim((string)$b);
                if ($bTrimmed !== '') { $allBrandsInDoc[] = $bTrimmed; }
            }
        }
        $brandsToShow = array_unique($allBrandsInDoc);
    }
} else {
    // Pour les autres profils, on affichera toutes les marques de la filiale
    $brandsToShow = $allSubsidiaryBrands;
}


    // On suppose que $brandScores est construit ailleurs (ici non reproduit pour la concision)
    $brandScores = []; // Par exemple, vous pourriez avoir un tableau de scores par marque

    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Dashboard DOP | CFAO Mobility Academy</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.0"></script>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet">
      <style>
         /* Styles pour le bloc de filtres et le défilement horizontal des marques */
         .brand-scroll-wrapper {
            position: relative;
            padding: 0 3rem; /* Laisser de la place pour les flèches */
            overflow: hidden;
         }
         .horizontal-scroll-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            scroll-behavior: smooth;
            gap: 1rem;
            padding: 0.5rem;
            justify-content: center;
         }
         .horizontal-scroll-container::-webkit-scrollbar {
            display: none;
         }
         .horizontal-scroll-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
         }
         .brand-card {
            flex: 0 0 auto;
            width: 18rem;
         }
         .arrow-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.8);
            border: none;
            font-size: 2rem;
            cursor: pointer;
            z-index: 2;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
         }
         #scrollLeft {
            left: 0;
         }
         #scrollRight {
            right: 0;
         }
         /* Autres styles généraux */
         .custom-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            border: none;
            background-color: #fff;
         }
         .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.2);
         }
         .brand-logo {
            width: 60px;
            height: 45px;
            margin-bottom: 0.5rem;
         }
         .chart-dashboard-container {
            position: relative;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            margin: 0 auto;
         }
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
         @media (max-width: 768px) {
            .chart-title { font-size: 1rem; }
            .brand-logo { width: 40px; height: 30px; }
         }
      </style>
    </head>
    <body>
      <?php include "./partials/header.php"; ?>
      <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
         <div class="toolbar" id="kt_toolbar">
            <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
               <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                  <h1 class="text-dark fw-bold my-1 fs-2">Tableau de Bord</h1>
               </div>
            </div>
         </div>
         <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
               <div class="container-xxl">
                  <!-- Section des filtres -->
                  <div class="row mb-4 justify-content-center">
                     <!-- Filtre Niveau -->
                     <?php if ($profile === 'Technicien'): ?>
                        <div class="col-md-3">
                           <label class="form-label"><i class="fas fa-signal me-2 text-warning"></i>Mon Niveau</label>
                           <select id="level-filter" class="form-select">
                              <?php
                                 $userLevel = $_SESSION['level'] ?? 'Junior';
                                 if ($userLevel === 'Expert') {
                                     echo '<option value="Junior">Junior</option>';
                                     echo '<option value="Senior">Senior</option>';
                                     echo '<option value="Expert" selected>Expert</option>';
                                 } elseif ($userLevel === 'Senior') {
                                     echo '<option value="Junior">Junior</option>';
                                     echo '<option value="Senior" selected>Senior</option>';
                                 } else {
                                     echo '<option value="Junior" selected>Junior</option>';
                                 }
                              ?>
                           </select>
                        </div>
                     <?php else: ?>
                        <div class="col-md-3">
                           <label class="form-label"><i class="fas fa-signal me-2 text-warning"></i>Filtrer par Niveau</label>
                           <select id="level-filter" class="form-select">
                              <option value="all" <?= ($filterLevel === 'all') ? 'selected' : ''; ?>>Tous</option>
                              <option value="Junior" <?= ($filterLevel === 'Junior') ? 'selected' : ''; ?>>Junior</option>
                              <option value="Senior" <?= ($filterLevel === 'Senior') ? 'selected' : ''; ?>>Senior</option>
                              <option value="Expert" <?= ($filterLevel === 'Expert') ? 'selected' : ''; ?>>Expert</option>
                           </select>
                        </div>
                     <?php endif; ?>

                     <!-- Filtre Marque -->
                     <?php if ($profile === 'Technicien'): ?>
                        <div class="col-md-3">
                           <label class="form-label"><i class="fas fa-car me-2 text-warning"></i>Mes Marques</label>
                           <select id="brand-filter" class="form-select">
                              <?php
                                 $myBrands = $_SESSION['brands'] ?? [];
                                 foreach ($myBrands as $brand) {
                                     $selected = ($filterBrand === $brand) ? 'selected' : '';
                                     echo "<option value=\"" . htmlspecialchars($brand) . "\" $selected>" . htmlspecialchars($brand) . "</option>";
                                 }
                              ?>
                           </select>
                        </div>
                     <?php elseif ($profile === 'Manager'): ?>
                        <div class="col-md-3">
                           <label class="form-label"><i class="fas fa-car me-2 text-warning"></i>Mes Marques d'Équipe</label>
                           <select id="brand-filter" class="form-select">
                              <option value="all" <?= ($filterBrand === 'all') ? 'selected' : ''; ?>>Toutes</option>
                              <?php
                                 // Vous devez calculer $managerBrands en fonction de votre logique
                                 $managerBrands = []; // Exemple : $managerBrands = ['RENAULT TRUCK','FUSO'];
                                 foreach ($managerBrands as $brand) {
                                     $selected = ($filterBrand === $brand) ? 'selected' : '';
                                     echo "<option value=\"" . htmlspecialchars($brand) . "\" $selected>" . htmlspecialchars($brand) . "</option>";
                                 }
                              ?>
                           </select>
                        </div>
                     <?php else: ?>
                        <div class="col-md-3">
                           <label class="form-label"><i class="fas fa-car me-2 text-warning"></i>Filtrer par Marque</label>
                           <select id="brand-filter" class="form-select">
                              <option value="all" <?= ($filterBrand === 'all') ? 'selected' : ''; ?>>Toutes</option>
                              <?php
                                 foreach ($allSubsidiaryBrands as $b) {
                                     $selected = (strcasecmp($filterBrand, $b) === 0) ? 'selected' : '';
                                     echo "<option value=\"" . htmlspecialchars($b) . "\" $selected>" . htmlspecialchars($b) . "</option>";
                                 }
                              ?>
                           </select>
                        </div>
                     <?php endif; ?>

                     <!-- Filtre Manager : visible uniquement pour Directeur Filiale, Directeur Groupe et Super Admin -->
                     <?php if (in_array($profile, ['Directeur Filiale', 'Directeur Groupe', 'Super Admin'])): ?>
                        <div class="col-md-3">
                           <label class="form-label"><i class="fas fa-user-tie me-2 text-warning"></i>Filtrer par Manager</label>
                           <select id="manager-filter" class="form-select">
                              <option value="all" <?= ($managerId === 'all') ? 'selected' : ''; ?>>Tous les Managers</option>
                              <?php
                                 foreach ($managersList as $mId => $mName) {
                                     $selected = ($managerId === $mId) ? 'selected' : '';
                                     echo '<option value="' . htmlspecialchars($mId) . '" ' . $selected . '>' . htmlspecialchars($mName) . '</option>';
                                 }
                              ?>
                           </select>
                        </div>
                     <?php endif; ?>

                     <!-- Filtre Technicien : visible uniquement pour Directeur Filiale, Directeur Groupe et Super Admin -->
                     <?php if (in_array($profile, ['Directeur Filiale', 'Directeur Groupe', 'Super Admin'])): ?>
                        <div class="col-md-3">
                           <label class="form-label"><i class="fas fa-user me-2 text-warning"></i>Filtrer par Technicien</label>
                           <select id="technician-filter" class="form-select">
                              <option value="all" <?= ($filterTechnician === 'all') ? 'selected' : ''; ?>>Tous</option>
                              <?php
                                 foreach ($technicians as $t) {
                                     $selected = ($filterTechnician === $t['id']) ? 'selected' : '';
                                     echo '<option value="' . htmlspecialchars($t['id']) . '" ' . $selected . '>' . htmlspecialchars($t['name']) . '</option>';
                                 }
                              ?>
                           </select>
                        </div>
                     <?php endif; ?>
                  </div>

                  <script>
                     function applyFilters() {
                        const url = new URL(window.location.href);
                        const managerFilter = document.getElementById('manager-filter');
                        if(managerFilter) {
                           const selectedManagerId = managerFilter.value;
                           if (selectedManagerId === 'all') {
                              url.searchParams.delete('managerId');
                           } else {
                              url.searchParams.set('managerId', selectedManagerId);
                           }
                        }
                        const brandFilter = document.getElementById('brand-filter').value;
                        if (brandFilter === 'all') {
                           url.searchParams.delete('brand');
                        } else {
                           url.searchParams.set('brand', brandFilter);
                        }
                        const technicianFilter = document.getElementById('technician-filter');
                        if(technicianFilter) {
                           if (technicianFilter.value === 'all') {
                              url.searchParams.delete('technicianId');
                           } else {
                              url.searchParams.set('technicianId', technicianFilter.value);
                           }
                        }
                        const levelFilter = document.getElementById('level-filter').value;
                        if (levelFilter === 'all') {
                           url.searchParams.delete('level');
                        } else {
                           url.searchParams.set('level', levelFilter);
                        }
                        window.location.href = url.toString();
                     }
                     document.getElementById('manager-filter')?.addEventListener('change', applyFilters);
                     document.getElementById('brand-filter').addEventListener('change', applyFilters);
                     document.getElementById('technician-filter')?.addEventListener('change', applyFilters);
                     document.getElementById('level-filter').addEventListener('change', applyFilters);
                  </script>

                  <!-- Section des logos des marques sous forme de bloc scrollable horizontal -->
                  <div class="text-center mb-4">
                     <?php
                        $titleManagerPart = ($managerId === 'all') ? "de la Filiale" : "de l'Équipe sélectionnée";
                        $titleBrandPart = ($filterBrand === 'all') ? "Toutes les Marques" : "Marque: " . htmlspecialchars($filterBrand);
                        $titleLevelPart = ($filterLevel === 'all') ? "de Tous les Niveaux" : "du Niveau: " . htmlspecialchars($filterLevel);
                        echo "<h4>$titleBrandPart  $titleLevelPart  $titleManagerPart</h4>";
                     ?>
                     <br>
                     <div class="brand-scroll-wrapper">
                        <button class="arrow-button" id="scrollLeft">&lsaquo;</button>
                        <div id="brandContainer" class="horizontal-scroll-container">
                           <?php
                              foreach ($teamBrands as $brand) {
                                  $logoSrc = isset($brandLogos[$brand]) ? "brands/" . $brandLogos[$brand] : "brands/default.png";
                                  ?>
                           <div class="card custom-card brand-card">
                              <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                 <img src="<?php echo $logoSrc; ?>" alt="<?php echo htmlspecialchars($brand); ?> Logo" class="img-fluid brand-logo">
                              </div>
                           </div>
                           <?php
                              }
                           ?>
                        </div>
                        <button class="arrow-button" id="scrollRight">&rsaquo;</button>
                     </div>
                     <script>
                        document.getElementById('scrollLeft').addEventListener('click', function() {
                           document.getElementById('brandContainer').scrollBy({ left: -200, behavior: 'smooth' });
                        });
                        document.getElementById('scrollRight').addEventListener('click', function() {
                           document.getElementById('brandContainer').scrollBy({ left: 200, behavior: 'smooth' });
                        });
                     </script>
                  </div>

                  <!-- Section des graphiques et des métriques -->
                  <div class="chart-dashboard-container">
                     <div class="row mb-6 justify-content-center">
                        <div class="col-md-5 mb-5">
                           <div class="card custom-card text-center">
                              <div class="card-body">
                                 <i class="fas fa-book-open fa-2x text-primary mb-2"></i>
                                 <h5 class="card-title"><?php echo htmlspecialchars("Modules de Formation pour " . $titleBrandPart . " " . $titleLevelPart . " " . $titleManagerPart); ?></h5>
                                 <p class="fs-1 fw-bold"><?php echo (int)$numTrainings; ?></p>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-5 mb-5">
                           <div class="card custom-card text-center">
                              <div class="card-body">
                                 <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                                 <h5 class="card-title"><?php echo htmlspecialchars("Jours de Formation Estimés pour " . $titleBrandPart . " " . $titleLevelPart . " " . $titleManagerPart); ?></h5>
                                 <p class="fs-1 fw-bold"><?php echo (int)$numDays; ?></p>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- Zone pour le graphique (exemple avec Chart.js) -->
                     <div class="row mb-4">
                        <div class="col-12">
                           <div class="card custom-card">
                              <div class="card-body">
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
         // Passage des variables PHP au JavaScript
         const variablesPHP = {
            brandScores: <?php echo json_encode($brandScores, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            trainingsCountsForGraph2: <?php echo json_encode($trainingsCountsForGraph2, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            brandLogos: <?php echo json_encode($brandLogos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            teamBrands: <?php echo json_encode($teamBrands, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            technicians: <?php echo json_encode($technicians, JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            numTrainings: <?php echo json_encode($numTrainings); ?>,
            numDays: <?php echo json_encode($numDays); ?>
         };
         console.log("Variables PHP dans JS:", variablesPHP);

         function drawLogos(chart, containerId, specificLabels) {
            const oldDiv = document.getElementById(containerId + '-logo-container');
            if (oldDiv) oldDiv.remove();
            const logoContainer = document.createElement('div');
            logoContainer.id = containerId + '-logo-container';
            logoContainer.style.position = 'absolute';
            logoContainer.style.top = '0';
            logoContainer.style.left = '0';
            logoContainer.style.width = '100%';
            logoContainer.style.height = '100%';
            logoContainer.style.pointerEvents = 'none';
            const xScale = chart.scales.x;
            const chartArea = chart.chartArea;
            const shiftRight = 0;
            specificLabels.forEach((label, index) => {
               const xPos = xScale.getPixelForValue(index);
               let yPos = chartArea.bottom + 10;
               const img = document.createElement('img');
               img.src = variablesPHP.brandLogos[label] ? `brands/${variablesPHP.brandLogos[label]}` : `brands/default.png`;
               img.style.position = 'absolute';
               img.style.left = (xPos - 30 + shiftRight) + 'px';
               img.style.top = yPos + 'px';
               img.style.width = '60px';
               img.style.height = '35px';
               img.onerror = function() {
                  console.error(`Erreur de chargement de l'image : ${img.src}`);
                  img.src = 'brands/default.png';
               };
               logoContainer.appendChild(img);
            });
            const chartContainer = document.getElementById(containerId);
            chartContainer.parentElement.style.position = 'relative';
            chartContainer.parentElement.appendChild(logoContainer);
         }

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
            const brandScoresPHP = variablesPHP.brandScores;
            console.log("Brand Scores:", brandScoresPHP);
            const brandLabelsScores = brandScoresPHP.map(item => item.x);
            const scatterScores = brandScoresPHP.map((obj, index) => ({ x: index, y: obj.y, fillColor: obj.fillColor }));
            const trainingsCountsForGraph2 = variablesPHP.trainingsCountsForGraph2;
            console.log("Trainings Counts for Graph 2:", trainingsCountsForGraph2);
            const techniciansList = variablesPHP.technicians;
            console.log("Technicians List:", techniciansList);
            const teamBrandsList = variablesPHP.teamBrands;
            console.log("Team Brands List:", teamBrandsList);
            const scatterTrainings = [];
            brandLabelsScores.forEach((b, i) => {
               const count = trainingsCountsForGraph2[b] ?? 0;
               let relevantScore = 0;
               const found = brandScoresPHP.find(x => x.x === b);
               if (found && typeof found.y === 'number') {
                  relevantScore = found.y;
               }
               scatterTrainings.push({ x: i, y: relevantScore, count: count });
            });
            console.log("Scatter Trainings Data:", scatterTrainings);
            Chart.register(ChartDataLabels, ChartZoom);
            const customLabelPlugin = {
               id: 'customLabelPlugin',
               afterDraw: (chart) => {
                  const ctx = chart.ctx;
                  chart.data.datasets.forEach((dataset) => {
                     const meta = chart.getDatasetMeta(0);
                     meta.data.forEach((element, index) => {
                        const dataPoint = dataset.data[index];
                        const count = dataPoint.count;
                        const score = dataPoint.y;
                        const position = element.getCenterPoint();
                        ctx.font = 'bold 23px Arial';
                        ctx.fillStyle = '#000';
                        ctx.textAlign = 'center';
                        ctx.fillText(Math.round(count), position.x, position.y - 15);
                        ctx.font = 'bold 13px Arial';
                        ctx.fillStyle = '#000';
                        ctx.textAlign = 'center';
                        ctx.fillText('Module(s)', position.x, position.y + 15);
                        ctx.fillText(`${Math.round(score)}%`, position.x, position.y + 30);
                     });
                  });
               }
            };
            const ctx2 = document.getElementById('trainingsScatterCanvas').getContext('2d');
            const dataForChart2 = scatterTrainings.map(pt => {
               let color;
               if (pt.y <= 50) {
                  color = 'rgb(241, 85, 100)';
               } else if (pt.y < 80) {
                  color = '#ffc107';
               } else {
                  color = '#1bea04';
               }
               return {
                  x: pt.x,
                  y: pt.y,
                  count: pt.count,
                  backgroundColor: color,
                  borderColor: color,
                  pointRadius: 55,
                  pointHoverRadius: 60
               };
            });
            const chartFormations = new Chart(ctx2, {
               type: 'scatter',
               data: {
                  datasets: [{
                     label: "Formations Recommandées",
                     data: dataForChart2,
                     showLine: false,
                     parsing: false,
                     pointRadius: (ctx) => ctx.raw.pointRadius ?? 55,
                     pointHoverRadius: (ctx) => ctx.raw.pointHoverRadius ?? 60,
                     backgroundColor: (ctx) => ctx.raw.backgroundColor ?? '#999',
                     borderColor: (ctx) => ctx.raw.borderColor ?? '#999',
                     borderWidth: 2
                  }]
               },
               options: {
                  responsive: true,
                  plugins: {
                     legend: { display: false },
                     datalabels: {
                        display: false,
                        formatter: (value, ctx) => {
                           const dataPoint = ctx.dataset.data[ctx.dataIndex];
                           return `${Math.round(dataPoint.count)} modules`;
                        },
                     },
                     tooltip: {
                        callbacks: {
                           label: (context) => {
                              const i = context.dataIndex;
                              const brand = brandLabelsScores[i];
                              const count = Math.round(scatterTrainings[i].count);
                              return [`Marque: ${brand}`, `Formations: ${count}`];
                           }
                        }
                     },
                     zoom: {
                        pan: { enabled: true, mode: 'x' },
                        zoom: { enabled: false }
                     }
                  },
                  scales: {
                     x: {
                        type: 'linear',
                        min: -0.5,
                        max: scatterTrainings.length - 0.5,
                        grid: { color: '#ccc' },
                        ticks: { display: false }
                     },
                     y: {
                        type: 'linear',
                        min: 0,
                        max: 100,
                        title: { display: true, text: 'Modules' },
                        grid: { color: '#ccc' },
                        ticks: { stepSize: 10, padding: 10, font: { size: 12 } }
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
