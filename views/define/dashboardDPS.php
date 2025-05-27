
<?php

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    use MongoDB\Client as MongoClient;
    use MongoDB\Exception\Exception as MongoException;

    // ----------------------------------------------------------
    // 1) Vérifier session / profil, puis connexion MongoDB
    // ----------------------------------------------------------
    $profile = $_SESSION['profile'] ?? '';

    if (!isset($_SESSION["profile"])) {
        header("Location: /");
        exit();
    } else {
        // Pour un manager, s'il n'y a pas de managerId dans l'URL, rediriger avec l'ID de session
        // if ($_SESSION["profile"] === 'Manager' && !isset($_GET['managerId'])) {
        //     header("Location: " . $_SERVER['PHP_SELF'] . "?managerId=" . $_SESSION['id']);
        //     exit();
        // }
        //     // Autoriser l'accès si l'utilisateur est Manager, Super Admin ou Directeur Filiale

        if (!in_array($profile, [
            'Manager', 
            'Super Admin', 
            'Directeur Filiale', 
            'Directeur Groupe',
            'Directeur Pièce et Service', 
            'Directeur Opérationnel',
            'Technicien'
        ])) {
            echo "Accès refusé.";
            exit();
        }
        

        if (in_array($profile, [
            'Super Admin', 
            'Directeur Groupe',
            'Directeur Filiale',
            'Directeur Pièce et Service',
            'Directeur Opérationnel'
        ])) {
            // Eux aussi ont un scope "filiale" complet
            $managerId = isset($_GET['managerId']) ? $_GET['managerId'] : 'all';
        } elseif ($profile === 'Manager') {
            $managerId = $_SESSION["id"];
        } else {
            // Technicien
            $managerId = $_SESSION["managerId"] ?? 'all';
        }
        

        if ($profile === 'Technicien') {
            // Si le paramètre "technicianId" n'existe pas dans l'URL,
            // rediriger vers la même page en l'ajoutant.
            if (!isset($_GET['technicianId'])) {
                // On récupère l'ID du technicien depuis la session
                $techId = (string) $_SESSION["id"];
                // On reconstruit l'URL actuelle sans les autres paramètres (vous pouvez adapter pour conserver d'autres GET)
                $currentUri = $_SERVER['PHP_SELF'];
                header("Location: {$currentUri}?technicianId={$techId}");
                exit();
            }
            // Sinon, on récupère l'ID du technicien depuis l'URL
            $technicianId = (string) $_GET['technicianId'];
        }

        $technicianId = isset($_GET['id']) ? (string)$_GET['id'] : (string)$_SESSION["id"];

        try {
            $techObjId = new MongoDB\BSON\ObjectId($technicianId);
        } catch (\Exception $e) {
            echo "Identifiant technicien invalide.";
            exit();
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
        function getTechLevelFromUserId($userId, $usersColl)
        {
            // On utilise ici la variable globale $usersColl
            // si vous préférez, vous pouvez passer $usersColl en paramètre
            if (empty($userId)) {
                return 'Junior'; // fallback
            }
            try {
                // Convertir la chaîne en ObjectId
                $objId = new MongoDB\BSON\ObjectId($userId);
            } catch (\Exception $e) {
                // Si le cast en ObjectId échoue
                return 'Junior'; // fallback ou "Unknown"
            }

            // Trouver le doc correspondant dans la collection users
            $doc = $usersColl->findOne(['_id' => $objId]);
            if (!$doc) {
                // Pas de document trouvé
                return 'Junior';
            }
            if (!isset($doc['profile']) || $doc['profile'] !== 'Technicien') {
                // Dans votre cas, si vous ne cherchez que les techniciens
                // vous pouvez aussi renvoyer un autre fallback
                return 'Junior';
            }

            // Renvoyer le champ 'level' s'il existe
            return $doc['level'] ?? 'Junior';
        }
        if ($_SESSION["profile"] === 'Technicien') {
            $technicianDoc = $usersColl->findOne([
                '_id' => $techObjId,
                'profile' => 'Technicien'
            ]);

            if (!$technicianDoc) {
                echo "Technicien introuvable.";
                exit();
            }
            // Récupérer le niveau du technicien
            $tLevel = isset($technicianDoc['level']) ? $technicianDoc['level'] : 'Junior';
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

        if ($profile === 'Technicien') {
            // 1) On récupère l'ID du technicien depuis la session
            $technicianId = $_SESSION["id"];
            $subsidiary   = $_SESSION["subsidiary"] ?? null;

            if (!$subsidiary) {
                echo "Informations de filiale manquantes (subsidiary).";
                exit();
            }

            // 2) On lit les filtres GET brand/level s'ils existent
            $filterBrand = isset($_GET['brand']) ? trim($_GET['brand']) : 'all';
            $filterLevel = isset($_GET['level']) ? trim($_GET['level']) : 'all';

            // === A) Récupérer et traiter les SCORES directement dans "technicianBrandScores" ===
            try {
                $scoreDoc = $academy->technicianBrandScores->findOne([
                    'userId' => new MongoDB\BSON\ObjectId($technicianId),
                ]);
            } catch (MongoDB\Exception\Exception $e) {
                echo "Erreur récupération des scores : " . htmlspecialchars($e->getMessage());
                exit();
            }

            // Construire un agrégateur local
            $aggregator = [];
            if ($scoreDoc && isset($scoreDoc['scores'])) {
                foreach ($scoreDoc['scores'] as $lvl => $brandsData) {
                    // Filtre par level
                    if ($filterLevel !== 'all' && $lvl !== $filterLevel) {
                        continue;
                    }
                    foreach ($brandsData as $br => $scoreDetails) {
                        // Filtre par brand
                        if ($filterBrand !== 'all' && $br !== $filterBrand) {
                            continue;
                        }
                        $avgTotal = isset($scoreDetails['averageTotal'])
                            ? (float)$scoreDetails['averageTotal']
                            : 0.0;
                        if (!isset($aggregator[$lvl])) {
                            $aggregator[$lvl] = [];
                        }
                        if (!isset($aggregator[$lvl][$br])) {
                            $aggregator[$lvl][$br] = ['sum' => 0, 'count' => 0];
                        }
                        $aggregator[$lvl][$br]['sum']   += $avgTotal;
                        $aggregator[$lvl][$br]['count'] += 1;
                    }
                }
            }

            // Petite fonction pour finaliser la moyenne
            function finalizeAveragesTech($aggregator, $withAll = true)
            {
                $res = [];
                $global = [];
                foreach ($aggregator as $lvl => $brandArr) {
                    foreach ($brandArr as $br => $vals) {
                        $c   = max($vals['count'], 1);
                        $avg = round($vals['sum'] / $c, 2);
                        if (!isset($res[$lvl])) {
                            $res[$lvl] = [];
                        }
                        $res[$lvl][$br] = $avg;

                        if ($withAll) {
                            if (!isset($global[$br])) {
                                $global[$br] = ['sum' => 0, 'count' => 0];
                            }
                            $global[$br]['sum']   += $vals['sum'];
                            $global[$br]['count'] += $vals['count'];
                        }
                    }
                }
                if ($withAll) {
                    $res['ALL'] = [];
                    foreach ($global as $br => $vals) {
                        $c   = max($vals['count'], 1);
                        $avg = round($vals['sum'] / $c, 2);
                        $res['ALL'][$br] = $avg;
                    }
                }
                return $res;
            }
            $finalScores  = finalizeAveragesTech($aggregator, true);
            $scoresArr    = $finalScores['ALL'] ?? []; // On récupère la partie "ALL"
            // On construit $brandScores pour Chart.js
            $brandScores = [];
            foreach ($scoresArr as $brandName => $avgVal) {
                if ($avgVal >= 80) {
                    $color = '#198754'; // Vert
                } elseif ($avgVal >= 60) {
                    $color = '#ffc107'; // Jaune
                } else {
                    $color = '#dc3545'; // Rouge
                }
                $brandScores[] = [
                    'x' => $brandName,
                    'y' => (float)$avgVal,
                    'fillColor' => $color
                ];
            }

            // === B) Récupérer les FORMATIONS du technicien depuis "trainings" ===
            $pipelineTech = [
                // On match UNIQUEMENT le Technicien (et la filiale)
                ['$match' => [
                    '_id' => new MongoDB\BSON\ObjectId($technicianId),
                    'profile' => 'Technicien',
                    'subsidiary' => $subsidiary
                ]],
                // lookup vers trainings
                ['$lookup' => [
                    'from'         => 'trainings',
                    'localField'   => '_id',
                    'foreignField' => 'users',
                    'as'           => 'trainings'
                ]],
                ['$unwind' => '$trainings'],
            ];
            // Filtre brand
            if ($filterBrand !== 'all') {
                $pipelineTech[] = [
                    '$match' => ['trainings.brand' => $filterBrand]
                ];
            }
            // Filtre level
            if ($filterLevel !== 'all') {
                $pipelineTech[] = [
                    '$match' => ['trainings.level' => $filterLevel]
                ];
            }
            // group distinct trainings
            $pipelineTech[] = [
                '$group' => [
                    '_id' => [
                        'brand' => '$trainings.brand',
                        'level' => '$trainings.level',
                        'trainingId' => '$trainings._id'
                    ],
                    'count' => ['$sum' => 1]
                ]
            ];
            // group final par brand
            $pipelineTech[] = [
                '$group' => [
                    '_id'         => '$_id.brand',
                    'totalByBrand' => ['$sum' => 1],
                ]
            ];

            try {
                $cursorTrainings = $usersColl->aggregate($pipelineTech)->toArray();
            } catch (MongoDB\Exception\Exception $e) {
                echo "Erreur pipeline formations: " . htmlspecialchars($e->getMessage());
                exit();
            }

            $trainingsCountsForGraph2 = [];
            foreach ($cursorTrainings as $doc) {
                $br = $doc->_id;
                $trainingsCountsForGraph2[$br] = (int)$doc->totalByBrand;
            }
            $numTrainings = array_sum($trainingsCountsForGraph2);
            $numDays      = $numTrainings * 5; // 5 jours par formation ?

            // === C) Préparer l’affichage HTML ===
            // On n’utilise PAS DataCollection ni tout le code managerId/technicians
            // On peut faire un include de la vue (ou continuer ci-dessous).
            // On mettra juste ce qu’il faut pour Chart.js et brandScores.

            // ===========================================================
            // ICI, collez votre code HTML existant, mais en version "Technicien".
            // Par exemple :
            // echo "<!DOCTYPE html>...";
            // echo <html> ... <body> etc.

            // Au besoin, vous pouvez réutiliser vos variables => $brandScores, $trainingsCountsForGraph2, $numTrainings, $numDays ...
            // SANS la partie manager/technicians.

            // Quand tout est fait, on termine l’exécution :

        }
        // 1) Récup filtres
        $selectedFiliale   = $_GET['filiale']     ?? 'all';
        $selectedAgence    = $_GET['agence']      ?? 'all';
        $selectedManagerId = $_GET['managerId']   ?? 'all';
        $selectedTechId    = $_GET['technicianId'] ?? 'all';
        $selectedLevel     = $_GET['level']       ?? 'all';
        $selectedBrand     = $_GET['brand']       ?? 'all';

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
        // Si un filtre sur la filiale est envoyé en GET, on l'utilise (pour forcer l'affichage d'une autre filiale)
        if (isset($_GET['filiale']) && $_GET['filiale'] !== 'all') {
            $subsidiary = trim($_GET['filiale']);
        }
        // Pour le filtre Agence, on récupère la valeur de GET s'il existe
        $filterAgency = (isset($_GET['agence']) && $_GET['agence'] !== 'all') ? trim($_GET['agence']) : 'all';
        // On prépare la liste de toutes les filiales en extrayant les clés de $fullData
        $allFiliales = array_keys($fullData);
        $filterBrand      = isset($_GET['brand'])        ? trim($_GET['brand']) : 'all';
        // ------------------ BLOC MANAGERS ------------------
        $managersList = [];
        if (isset($fullData[$subsidiary]) && isset($fullData[$subsidiary]['agencies'])) {
            foreach ($fullData[$subsidiary]['agencies'] as $agencyName => $agencyData) {
                if (isset($agencyData['managers'])) {
                    foreach ($agencyData['managers'] as $manager) {
                        $mId   = $manager['id']   ?? null;
                        $mName = $manager['name'] ?? 'Manager Sans Nom';
                        // Vérifier que le manager possède au moins un technicien
                        if ($mId && !isset($managersList[$mId]) && !empty($manager['technicians'])) {
                            // Si un filtre de marque est sélectionné, on vérifie qu'au moins un technicien a cette marque
                            if ($filterBrand !== 'all') {
                                $hasBrand = false;
                                foreach ($manager['technicians'] as $technician) {
                                    // $technician['brands'] correspond au tableau issu de "distinctBrands"
                                    if (!empty($technician['brands']) && in_array($filterBrand, $technician['brands'])) {
                                        $hasBrand = true;
                                        break;
                                    }
                                }
                                if (!$hasBrand) {
                                    continue; // ce manager n’a aucun technicien avec la marque filtrée
                                }
                            }
                            $managersList[$mId] = $mName;
                        }
                    }
                }
            }
        }



        // **Nouvelle section : Initialiser $technicians**
        $technicians = []; // Initialisation par défaut

        if ($managerId === 'all') {
            if (isset($fullData[$subsidiary])) {
                $subsidiaryData = $fullData[$subsidiary];
                if (isset($subsidiaryData['agencies'])) {
                    foreach ($subsidiaryData['agencies'] as $agency) {
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
                                            $userDoc = $usersColl->findOne([
                                                '_id' => new MongoDB\BSON\ObjectId($techId)
                                            ]);
                                        }
                                        $techLevel = $userDoc['level'] ?? 'Junior';
                                        $technicians[] = [
                                            'id'   => htmlspecialchars($techId),
                                            'name' => htmlspecialchars($technician['name'] ?? ''),
                                            'level' => htmlspecialchars($techLevel),
                                            'brands'     => !empty($technician['brands'])
                                            ? (array)$technician['brands']
                                            : [],
                                            'brandJunior' => $technician['brandJunior'] ?? [],
                                            'brandSenior' => $technician['brandSenior'] ?? [],
                                            'brandExpert' => $technician['brandExpert'] ?? [],  
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
            if (isset($fullData[$subsidiary])) {
                $subsidiaryData = $fullData[$subsidiary];
                if (isset($subsidiaryData['agencies'])) {
                    foreach ($subsidiaryData['agencies'] as $agency) {
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
                                                $userDoc = $usersColl->findOne([
                                                    '_id' => new MongoDB\BSON\ObjectId($techId)
                                                ]);
                                            }
                                            $techLevel = $userDoc['level'] ?? 'Junior';
                                            $technicians[] = [
                                                'id'   => htmlspecialchars($techId),
                                                'name' => htmlspecialchars($technician['name'] ?? ''),
                                                'level' => htmlspecialchars($techLevel),
                                                'brands'     => !empty($technician['brands'])
                                                ? (array)$technician['brands']
                                                : [],
                                                'brandJunior' => $technician['brandJunior'] ?? [],
                                                'brandSenior' => $technician['brandSenior'] ?? [],
                                                'brandExpert' => $technician['brandExpert'] ?? [],  
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
        // Seulement pour Super Admin et Directeur Filiale, autoriser le GET pour managerId
        if ($_SESSION["profile"] === 'Super Admin' || $_SESSION["profile"] === 'Directeur Filiale') {
            $managerId = isset($_GET['managerId']) ? $_GET['managerId'] : 'all';
        }
        // Pour les managers, on conserve l'ID de la session (déjà défini en amont)

        $filterLevel      = isset($_GET['level'])        ? trim($_GET['level']) : 'all';

        $filterTechnician = isset($_GET['technicianId']) ? trim($_GET['technicianId']) : 'all';
        // On initialise un tableau associatif pour collecter les niveaux détectés
        $foundLevels = [];

        // Déterminer le niveau maximum dans l'équipe (logique existante)
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

        // Construire la liste des niveaux pour le filtre pour Manager et Directeurs
        $levelsToShow = [];
        if ($maxLevel === 'Expert') {
            $levelsToShow = array_merge($levelsToShow, ['all','Junior', 'Senior', 'Expert']);
        } elseif ($maxLevel === 'Senior') {
            // Même si l’équipe affiche uniquement "Senior", on force l’inclusion de "Junior"
            $levelsToShow = array_merge($levelsToShow, ['all','Junior', 'Senior']);
        } else {
            $levelsToShow = array_merge($levelsToShow, ['all','Junior']);
        }

// $levelsToShow ressemble maintenant à :
// ["all", "Junior"]             si tous sont Juniors
// ["all", "Junior", "Senior"]   s’il y a au moins un Senior
// ["all", "Junior", "Senior", "Expert"] s’il y a un Expert, etc.

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

        // Reconstruire un simple tableau "marque" => "nombre total de formations"

        $trainingsCountsForGraph2 = [];


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

        //     $pipelineDays = [];

        // // 1) Le match sur le manager
        // if ($managerId === 'all') {
        //     $pipelineDays[] = [
        //         '$match' => [
        //             'profile'    => 'Manager',
        //             'subsidiary' => $subsidiary
        //         ]
        //     ];
        // } else {
        //     $pipelineDays[] = [
        //         '$match' => [
        //             '_id'     => new MongoDB\BSON\ObjectId($managerId),
        //             'profile' => 'Manager',
        //             'subsidiary' => $subsidiary
        //         ]
        //     ];
        // }

        // // 2) Lookup, unwind subordinates
        // $pipelineDays[] = [
        //     '$lookup' => [
        //         'from'         => 'users',
        //         'localField'   => 'users',
        //         'foreignField' => '_id',
        //         'as'           => 'subordinates'
        //     ]
        // ];
        // $pipelineDays[] = [
        //     '$unwind' => '$subordinates'
        // ];
        // $pipelineDays[] = [
        //     '$match' => [
        //         'subordinates.profile' => 'Technicien'
        //     ]
        // ];

        // // 3) Si filterTechnician != 'all'
        // if ($filterTechnician !== 'all') {
        //     $pipelineDays[] = [
        //         '$match' => [
        //             'subordinates._id' => new MongoDB\BSON\ObjectId($filterTechnician)
        //         ]
        //     ];
        // }

        // // 4) Lookup trainings
        // $pipelineDays[] = [
        //     '$lookup' => [
        //         'from'         => 'trainings',
        //         'localField'   => 'subordinates._id',
        //         'foreignField' => 'users',
        //         'as'           => 'trainings'
        //     ]
        // ];
        // $pipelineDays[] = ['$unwind' => '$trainings'];

        // // 5) Filtrer brand, level si besoin
        // if ($filterBrand !== 'all') {
        //     $pipelineDays[] = [
        //         '$match' => ['trainings.brand' => $filterBrand]
        //     ];
        // }
        // if ($filterLevel !== 'all') {
        //     $pipelineDays[] = [
        //         '$match' => ['trainings.level' => $filterLevel]
        //     ];
        // }

        // // 6) Grouper pour compter TOUTES les occurrences
        // //    => 1 occurrence par (technicien, training) sans distinct
        // $pipelineDays[] = [
        //     '$group' => [
        //         '_id' => null,
        //         'totalOccurrences' => ['$sum' => 1]
        //     ]
        // ];

        // // 7) Projection
        // $pipelineDays[] = [
        //     '$project' => [
        //         '_id' => 0,
        //         'totalOccurrences' => 1
        //     ]
        // ];

        // // Exécuter
        // $resultDays = $usersColl->aggregate($pipelineDays)->toArray();

        // if (empty($resultDays)) {
        //     $totalOccurrences = 0;
        // } else {
        //     $totalOccurrences = $resultDays[0]['totalOccurrences'] ?? 0;
        // }

        // => numDays = totalOccurrences * 5


        // Comptabiliser la somme totale
        $numTrainings = array_sum($trainingsCountsForGraph2);
        $numDays = $totalOccurrences * 5;


        // Supposons 5 jours/formation



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
        if ($profile === 'Technicien') {
            // Pour un technicien, on récupère les marques depuis $technicianDoc
            $allBrands = [];
            if (isset($technicianDoc['brandJunior'])) {
                foreach ($technicianDoc['brandJunior'] as $b) {
                    $bTrimmed = trim((string)$b);
                    if ($bTrimmed !== '') {
                        $allBrands[$bTrimmed] = true;
                    }
                }
            }
            if (isset($technicianDoc['brandSenior'])) {
                foreach ($technicianDoc['brandSenior'] as $b) {
                    $bTrimmed = trim((string)$b);
                    if ($bTrimmed !== '') {
                        $allBrands[$bTrimmed] = true;
                    }
                }
            }
            if (isset($technicianDoc['brandExpert'])) {
                foreach ($technicianDoc['brandExpert'] as $b) {
                    $bTrimmed = trim((string)$b);
                    if ($bTrimmed !== '') {
                        $allBrands[$bTrimmed] = true;
                    }
                }
            }
            $allBrands = array_keys($allBrands);
            sort($allBrands);
        } else {
            $allBrands = [];  // Initialise $allBrands pour les non-techniciens
            $filteredTechnicians = [];
        
            if ($profile === 'Manager' || $profile === 'Directeur Filiale' || $profile === 'Directeur Groupe' || $profile === 'Directeur Pièce et Service' || $profile === 'Directeur Opérationnel' || $profile === 'Super Admin') {
                // Créez un tableau temporaire pour stocker les techniciens filtrés
                $filteredTechnicians = [];
        
                foreach ($technicians as $technician) {
                    $techLevel = $technician['level'] ?? 'Junior';
                    // Filtrer les techniciens en fonction du niveau sélectionné
                    if ($filterLevel === 'all' || $techLevel === $filterLevel) {
                        $filteredTechnicians[] = $technician;
                    }
                }
        
                // Collecter les marques de la liste filtrée des techniciens
                $levelSpecificBrands = [];
                foreach ($filteredTechnicians as $technician) {
                    if (!empty($technician['brands'])) {
                        foreach ($technician['brands'] as $brand) {
                            $levelSpecificBrands[$brand] = true;
                        }
                    }
                }
                $allBrands = array_keys($levelSpecificBrands);
                sort($allBrands);
            }
        }
        
        
        
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

        // Maintenant, $allSubsidiaryBrands contient toutes les marques potentiellement rencontrées.

        // Récupération du document du technicien
        $technicianDoc = $usersColl->findOne([
            '_id' => new MongoDB\BSON\ObjectId($technicianId),
            'profile' => 'Technicien'
        ]);
        $techLevel = $technicianDoc['level'] ?? 'Junior';

        if (!$technicianDoc) {
            // Si le technicien n'existe pas, on met en place des valeurs par défaut
            $brandsToShow = [];
            $techLevel = 'Junior';
        } else {
            // Récupération du niveau du technicien
            $techLevel = $technicianDoc['level'] ?? 'Junior';

            // Définir quels niveaux doivent être inclus dans la liste des marques
            // Par exemple, on considère qu'un technicien Senior a accès aux marques Junior et Senior.
            if ($techLevel === 'Junior') {
                $levels = ['Junior'];
            } elseif ($techLevel === 'Senior') {
                $levels = ['Junior', 'Senior'];
            } elseif ($techLevel === 'Expert') {
                $levels = ['Junior', 'Senior', 'Expert'];
            } else {
                $levels = ['Junior']; // cas par défaut
            }

            // Récupération des champs de marques dans le document
            $brandFieldJunior = $technicianDoc['brandJunior'] ?? [];
            $brandFieldSenior = $technicianDoc['brandSenior'] ?? [];
            $brandFieldExpert = $technicianDoc['brandExpert'] ?? [];

            if ($brandFieldJunior instanceof MongoDB\Model\BSONArray) {
                $brandFieldJunior = $brandFieldJunior->getArrayCopy();
            }
            if ($brandFieldSenior instanceof MongoDB\Model\BSONArray) {
                $brandFieldSenior = $brandFieldSenior->getArrayCopy();
            }
            if ($brandFieldExpert instanceof MongoDB\Model\BSONArray) {
                $brandFieldExpert = $brandFieldExpert->getArrayCopy();
            }

            // Construire la liste des marques en fonction des niveaux auxquels le technicien a accès
            $allBrandsInDoc = [];
            if ($filterLevel === 'all') {
                // On inclut tout
                $allBrandsInDoc = array_merge($allBrandsInDoc, $brandFieldJunior, $brandFieldSenior, $brandFieldExpert);
            }
            elseif ($filterLevel === 'Junior') {
                $allBrandsInDoc = array_merge($allBrandsInDoc, $brandFieldJunior);
            }
            elseif ($filterLevel === 'Senior') {
                // Par exemple, si Senior inclut Junior + Senior
                $allBrandsInDoc = array_merge($allBrandsInDoc, $brandFieldSenior);
            }
            elseif ($filterLevel === 'Expert') {
                // Par exemple, si Expert inclut Junior + Senior + Expert
                $allBrandsInDoc = array_merge($allBrandsInDoc, $brandFieldExpert);
            }
            
            // Maintenant on enlève les valeurs vides + on supprime les doublons
            $cleaned = [];
            foreach ($allBrandsInDoc as $b) {
                $bTrim = trim((string)$b);
                if ($bTrim !== '') {
                    // On l’ajoute
                    $cleaned[$bTrim] = true;  
                }
            }
            // Convertir en array
            $allBrands = array_keys($cleaned);
            // On peut trier alphabétiquement
            sort($allBrands);
        }

        $maxLevel = 'Junior';

        // Selon le profil
        if ($profile === 'Technicien') {
            // On prend directement le niveau du technicien (déjà récupéré en BDD dans $tLevel)
            // $tLevel pourrait être "Junior", "Senior" ou "Expert".
            $maxLevel = $tLevel;
        } elseif ($profile === 'Manager') {
            // 1) On a déjà la liste des techniciens du manager dans $technicians (ou via $fullData).
            //    Pour rappel, $technicians[] = [ 'id' => ..., 'name' => ..., 'level' => ... ].

            $maxLevel = 'Junior'; // par défaut
            foreach ($technicians as $tech) {
                $levelTech = $tech['level'] ?? 'Junior';
                if ($levelTech === 'Expert') {
                    $maxLevel = 'Expert';
                    break; // on a trouvé Expert, on s’arrête
                } elseif ($levelTech === 'Senior' && $maxLevel !== 'Expert') {
                    // On met à jour $maxLevel à Senior
                    $maxLevel = 'Senior';
                    // on ne break pas, au cas où on trouve plus tard un Expert
                }
            }
        } elseif (in_array($profile, ['Directeur Filiale', 'Directeur Groupe', 'Super Admin'])) {
            // Même logique, mais sur tous les techniciens de la filiale (ou du groupe, etc.)
            // Par exemple, vous avez $fullData, il contient l’ensemble des managers/agences/techniciens de la filiale.
            // Vous parcourez tous les "technicians" et vous trouvez le plus haut niveau.
            // Ci-dessous un exemple simple :

            $maxLevel = 'Junior';
            if (isset($fullData[$subsidiary]['agencies'])) {
                foreach ($fullData[$subsidiary]['agencies'] as $agencyData) {
                    if (!isset($agencyData['managers'])) continue;
                    foreach ($agencyData['managers'] as $mgr) {
                        if (!isset($mgr['technicians'])) continue;
                        foreach ($mgr['technicians'] as $tech) {
                            $lvl = getTechLevelFromUserId($tech['id'], $usersColl);
                            // (Une fonction qui récupère le level du technicien; ou vous l’avez déjà dans $tech['level'] si c’est stocké)

                            if ($lvl === 'Expert') {
                                $maxLevel = 'Expert';
                                break 3; // on sort des 3 boucles
                            } elseif ($lvl === 'Senior' && $maxLevel !== 'Expert') {
                                $maxLevel = 'Senior';
                                // pas de break, on continue pour voir s'il y a un Expert
                            }
                        }
                    }
                }
            }
        }
        if ($profile === 'Manager') {
            $fullTeamBrands = [];
            foreach ($technicians as $t) {
                if (isset($t['brands']) && is_array($t['brands'])) {
                    foreach ($t['brands'] as $brandName) {
                        $trimB = trim($brandName);
                        if ($trimB !== '') {
                            $fullTeamBrands[$trimB] = true;
                        }
                    }
                }
        }
        
            $allBrands = array_keys($fullTeamBrands);
            sort($allBrands);
        }
        
        // -------------------------------------------
        // Maintenant, on construit le tableau $levelsToShow
        // -------------------------------------------
        // if ($profile === 'Manager') {
        //     $availableLevels = [];
        //     foreach ($technicians as $tech) {
        //         $availableLevels[] = $tech['level'] ?? 'Junior';
        //     }
        //     $availableLevels = array_unique($availableLevels);
        //     // Définir l'ordre souhaité
        //     $orderedLevels = ['Junior', 'Senior', 'Expert'];
        //     $levelsToShow = [];
        //     $levelsToShow[] = 'all'; // Option "Tous"
        //     foreach ($orderedLevels as $level) {
        //         if (in_array($level, $availableLevels)) {
        //             $levelsToShow[] = $level;
        //         }
        //     }
        // } else {
        //     // Pour les autres profils, vous pouvez utiliser la logique existante
        //     $levelsToShow = [];
        //     $levelsToShow[] = 'all';
        //     $levelsToShow[] = 'Junior';
        //     if ($maxLevel === 'Senior') {
        //         $levelsToShow[] = 'Senior';
        //     }
        //     if ($maxLevel === 'Expert') {
        //         $levelsToShow[] = 'Expert';
        //     }
        // }
        

    ?>

        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard DOP | CFAO Mobility Academy</title>
            <!-- Inclure Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                /* Wrapper général pour le bloc de marques */
                .brand-scroll-wrapper {
                    position: relative;
                    /* Ajoute un padding pour laisser de la place aux flèches */
                    padding: 0 3rem;
                    overflow: hidden;
                    /* Cache tout débordement */
                }

                /* Conteneur horizontal scrollable pour les cartes */
                .horizontal-scroll-container {
                    display: flex;
                    flex-wrap: nowrap;
                    /* Forcer une seule ligne */
                    overflow-x: auto;
                    /* Activer le défilement horizontal */
                    scroll-behavior: smooth;
                    /* Défilement fluide */
                    gap: 1rem;
                    justify-content: center;
                    /* Espacement entre les cartes */
                    /* Pas besoin de padding ici, car il est géré par le wrapper */
                }

                /* Pour que la scrollbar ne soit pas visible */
                .horizontal-scroll-container::-webkit-scrollbar {
                    display: none;
                }

                .horizontal-scroll-container {
                    -ms-overflow-style: none;
                    /* IE et Edge */
                    scrollbar-width: none;
                    /* Firefox */
                }

                /* Les cartes de marque : empêcher leur rétrécissement */
                .brand-card {
                    flex: 0 0 auto;
                    width: 18rem;
                    /* Ajustez cette largeur selon vos besoins */
                }

                /* Boutons fléchés positionnés absolument par rapport au wrapper */
                .arrow-button {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(255, 255, 255, 0.8);
                    /* Optionnel : fond semi-transparent */
                    border: none;
                    font-size: 2rem;
                    cursor: pointer;
                    z-index: 2;
                    width: 2.5rem;
                    /* Ajustez la taille */
                    height: 2.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                /* Bouton gauche en haut à gauche */
                #scrollLeft {
                    left: 0;
                }

                /* Bouton droit en haut à droite */
                #scrollRight {
                    right: 0;
                }


                /* Pour améliorer l’expérience sur un seul élément, vous pouvez ajouter un style
   pour centrer visuellement le conteneur si le scroll n’est pas nécessaire.
   Par exemple, si le contenu est plus petit que le wrapper, le justify-content: center 
   déjà appliqué au conteneur se charge de centrer le contenu. */

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

                /* Conteneur horizontal scrollable */
                .horizontal-scroll-container {
                    display: flex;
                    flex-wrap: nowrap;
                    /* Pour forcer une seule ligne */
                    overflow-x: auto;
                    /* Activer le défilement horizontal */
                    scroll-behavior: smooth;
                    /* Pour un défilement fluide */
                    gap: 1rem;
                    /* Espacement entre les éléments */
                    padding: 0.5rem;
                }

                /* Optionnel : masquer la scrollbar (varie selon navigateurs) */
                .horizontal-scroll-container::-webkit-scrollbar {
                    display: none;
                }

                .horizontal-scroll-container {
                    -ms-overflow-style: none;
                    /* IE et Edge */
                    scrollbar-width: none;
                    /* Firefox */
                }

                /* Pour les cartes, on s'assure qu'elles ne se réduisent pas */
                .brand-card {
                    flex: 0 0 auto;
                    width: 18rem;
                    /* Ajustez la largeur souhaitée */
                }

                /* Style pour les flèches de navigation */
                .arrow-button {
                    background: none;
                    border: none;
                    font-size: 2rem;
                    cursor: pointer;
                    color: #198754;
                    /* Par exemple, couleur verte */
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
                                Tableau de Bord
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
                        <div class="container-xxl">
                            <?php if ($_SESSION["profile"] === 'Manager'): ?>
                                <input type="hidden" id="manager-hidden" value="<?= htmlspecialchars($_SESSION["id"]) ?>">
                            <?php endif; ?>
                            <!-- SECTION DES FILTRES -->
                            <div class="row mb-4 justify-content-center">
                                <?php if ($_SESSION["profile"] === 'Directeur Groupe'): ?>
                                    <!-- Filtre Filiale -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="mb-0">Filtres Globaux</h5>
                                            <small class="text-muted">Sélectionnez une filiale et une agence pour filtrer l'ensemble des équipes.</small>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Filtre Filiale -->
                                                <div class="col-md-6">
                                                    <label for="filiale-filter" class="form-label">
                                                        <i class="fas fa-building me-2 text-warning"></i>Filtrer par Filiale
                                                    </label>
                                                    <select id="filiale-filter" class="form-select">
                                                        <option value="all">Toutes les Filiales</option>
                                                        <?php
                                                        foreach ($allFiliales as $filiale) {
                                                            $selected = (isset($_GET['filiale']) && $_GET['filiale'] === $filiale) ? 'selected' : '';
                                                            echo "<option value=\"" . htmlspecialchars($filiale) . "\" $selected>" . htmlspecialchars($filiale) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- Filtre Agence -->
                                                <div class="col-md-6">
                                                    <label for="agence-filter" class="form-label">
                                                        <i class="fas fa-city me-2 text-warning"></i>Filtrer par Agence
                                                    </label>
                                                    <select id="agence-filter" class="form-select">
                                                        <option value="all">Toutes les Agences</option>
                                                        <?php
                                                        // Si une filiale est sélectionnée, afficher ses agences :
                                                        if (isset($_GET['filiale']) && $_GET['filiale'] !== 'all' && isset($fullData[$_GET['filiale']])) {
                                                            $agences = $fullData[$_GET['filiale']]['agencies'] ?? [];
                                                            foreach ($agences as $agenceName => $agenceData) {
                                                                $selected = (isset($_GET['agence']) && $_GET['agence'] === $agenceName) ? 'selected' : '';
                                                                echo "<option value=\"" . htmlspecialchars($agenceName) . "\" $selected>" . htmlspecialchars($agenceName) . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endif; ?>
                                <!-- Dans la partie HTML du filtre par niveau -->
                                <div class="col-md-3">
                                    <label class="form-label">
                                        <i class="fas fa-signal me-2 text-warning"></i>Filtrer par Niveau
                                    </label>

                                    <?php if ($_SESSION['profile'] === "Technicien"): ?>
                                        <select id="level-filter" class="form-select" onchange="redirectWithFilter(this.value)">
                                            <option value="all" <?= ($filterLevel === 'all') ? 'selected' : '' ?>>Tous</option>
                                            <?php
                                            // Définir la liste des niveaux disponibles en fonction du niveau du technicien
                                            if ($tLevel === 'Expert') {
                                                $lvlsAvailable = ['Junior', 'Senior', 'Expert'];
                                            } elseif ($tLevel === 'Senior') {
                                                $lvlsAvailable = ['Junior', 'Senior'];
                                            } else {
                                                $lvlsAvailable = ['Junior'];
                                            }
                                            foreach ($lvlsAvailable as $lvl): ?>
                                                <option value="<?= htmlspecialchars($lvl) ?>" <?= ($lvl === $filterLevel) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($lvl) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <?php elseif (in_array($_SESSION['profile'], [
                                            "Manager", 
                                            "Directeur Filiale", 
                                            "Directeur Pièce et Service", 
                                            "Directeur Opérationnel", 
                                            "Directeur Groupe"
                                        ])): ?>
                                            <!-- Pour ces profils, on utilise la variable $levelsToShow (pré-calculée sur la base des niveaux des techniciens de la filiale) -->
                                            <select id="level-filter" class="form-select" onchange="applyFilters()">
                                                <?php foreach ($levelsToShow as $lvlOption): ?>
                                                    <option value="<?= htmlspecialchars($lvlOption) ?>" <?= ($filterLevel === $lvlOption) ? 'selected' : '' ?>>
                                                        <?= ($lvlOption === 'all') ? 'Tous' : htmlspecialchars($lvlOption) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                </div>


                                <div class="col-md-3">


                                    <label class="form-label"><i class="fas fa-car me-2 text-warning"></i>Filtrer par Marque</label>
                                    <select id="brand-filter" class="form-select">
                                        <option value="all" <?php if ($filterBrand === 'all') echo 'selected'; ?>>Toutes</option>
                                        <?php

                                        foreach ($allBrands as $b) {
                                            $sel = (strcasecmp($filterBrand, $b) === 0) ? 'selected' : '';
                                            echo "<option value=\"" . htmlspecialchars($b) . "\" $sel>" . htmlspecialchars($b) . "</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                                <!-- APRES : Liste de managers dynamique -->


                                <?php if ($_SESSION["profile"] === 'Directeur Pièce et Service' || $_SESSION["profile"] === 'Directeur Groupe') : ?>

                                    <div class="col-md-3">
                                        <label class="form-label">
                                            <i class="fas fa-user-tie me-2 text-warning"></i>
                                            Filtrer par Manager
                                        </label>
                                        <select id="manager-filter" class="form-select">

                                            <?php
                                            // Proposer "Tous" si l'utilisateur est Directeur Filiale ou Super Admin
                                            if ($_SESSION["profile"] === 'Directeur Pièce et Service' || $_SESSION["profile"] === 'Super Admin' || $_SESSION["profile"] === 'Directeur Groupe') {
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
                                <?php endif; ?>


                                <!-- Filtre Technicien -->
                                <div class="col-md-3">
                                    <?php if ($_SESSION["profile"] !== 'Technicien' && $_SESSION["profile"] !== 'Directeur Groupe') : ?>

                                        <label class="form-label"><i class="fas fa-user me-2 text-warning"></i>Filtrer par Technicien</label>
                                        <select id="technician-filter" class="form-select">
                                            <option value="all" <?php if ($filterTechnician === 'all') echo 'selected'; ?>>
                                                Tous
                                            </option>
                                            <?php foreach ($technicians as $t): ?>
                                                <?php
                                                // Déterminer la couleur Bootstrap en fonction du level
                                                $badgeColor = 'bg-secondary'; // par défaut
                                                if ($t['level'] === 'Junior') $badgeColor = 'bg-info';
                                                if ($t['level'] === 'Senior') $badgeColor = 'bg-warning';
                                                if ($t['level'] === 'Expert') $badgeColor = 'bg-danger';
                                                ?>
                                                <option value="<?php echo htmlspecialchars($t['id']); ?>"
                                                    <?php if ($filterTechnician === $t['id']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($t['name']); ?>

                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                    <?php endif; ?>
                                </div>
                            </div>



                            <script>
                                // On passe la variable PHP $maxLevel en JavaScript :
                                const maxLevelInTeam = <?php echo json_encode($maxLevel); ?>;
                                console.log("Niveau maximum de l'équipe (calcul PHP) :", maxLevelInTeam);

                                // Idem si vous voulez voir la liste $levelsToShow :
                                const levelsToShowPHP = <?php echo json_encode($levelsToShow); ?>;
                                console.log("Liste des niveaux à afficher (levelsToShow) :", levelsToShowPHP);
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Récupération des paramètres d'URL
                                    const urlParams = new URLSearchParams(window.location.search);

                                    // Fonction de mise à jour des options du filtre de niveau
                                    function updateLevelOptions(selectedTechId) {
                                        console.log("Technicien sélectionné :", selectedTechId);
                                        const levelFilter = document.getElementById('level-filter');
                                        if (!levelFilter) return; // On quitte si l'élément n'existe pas

                                        // Vider le sélecteur
                                        levelFilter.innerHTML = '';

                                        // Récupérer la valeur "level" depuis l'URL (ou 'all' par défaut)
                                        const chosenLevel = urlParams.get('level') || 'all';

                                        <?php if ($profile === 'Technicien'): ?>
                                            // Pour un technicien, utiliser son niveau passé depuis PHP
                                            const technicianLevel = <?php echo json_encode($techLevel); ?>;
                                            console.log('Niveau du technicien :', technicianLevel);
                                            // Ajouter l'option "Tous"
                                            levelFilter.add(new Option('Tous', 'all'));
                                            if (technicianLevel === 'Junior') {
                                                levelFilter.add(new Option('Junior', 'Junior'));
                                            } else if (technicianLevel === 'Senior') {
                                                levelFilter.add(new Option('Junior', 'Junior'));
                                                levelFilter.add(new Option('Senior', 'Senior'));
                                            } else if (technicianLevel === 'Expert') {
                                                levelFilter.add(new Option('Junior', 'Junior'));
                                                levelFilter.add(new Option('Senior', 'Senior'));
                                                levelFilter.add(new Option('Expert', 'Expert'));
                                            }
                                            levelFilter.value = chosenLevel;
                                            return; // On s'arrête ici pour un technicien
                                            <?php else: ?>
                                                const levelsToShowPHP = <?php echo json_encode($levelsToShow); ?>;
                                                // Dans la fonction updateLevelOptions() pour Manager/Directeurs :
                                                levelFilter.add(new Option('Tous', 'all'));
                                                levelsToShowPHP.forEach(level => {
                                                    if (level === 'all') return; 
                                                    // On ajoute l'option
                                                    levelFilter.add(new Option(level, level));
                                                });
                                                // Affecter la valeur choisie pour que le sélecteur affiche le bon élément
                                                levelFilter.value = chosenLevel;
                                            <?php endif; ?>

                                    }

                                    // Fonction applyFilters : lit la valeur de chaque filtre (si présent) et met à jour l'URL
                                    
                                    function applyFilters() {
                                        const url = new URL(window.location.href);

                                        // Filtre Filiale
                                        const filialeElement = document.getElementById('filiale-filter');
                                        if (filialeElement) {
                                            const filiale = filialeElement.value;
                                            if (filiale === 'all') {
                                                url.searchParams.delete('filiale');
                                            } else {
                                                url.searchParams.set('filiale', filiale);
                                            }
                                        }

                                        // Filtre Agence
                                        const agenceElement = document.getElementById('agence-filter');
                                        if (agenceElement) {
                                            const agence = agenceElement.value;
                                            if (agence === 'all') {
                                                url.searchParams.delete('agence');
                                            } else {
                                                url.searchParams.set('agence', agence);
                                            }
                                        }

                                        // Filtre Manager
                                        const managerHidden = document.getElementById('manager-hidden');
                                        if (managerHidden) {
                                            url.searchParams.set('managerId', managerHidden.value);
                                        } else {
                                            const managerFilter = document.getElementById('manager-filter');
                                            if (managerFilter) {
                                                const selectedManagerId = managerFilter.value;
                                                if (selectedManagerId === 'all') {
                                                    url.searchParams.delete('managerId');
                                                } else {
                                                    url.searchParams.set('managerId', selectedManagerId);
                                                }
                                            }
                                        }

                                        // Filtre Marque
                                        const brandElement = document.getElementById('brand-filter');
                                        if (brandElement) {
                                            const br = brandElement.value;
                                            if (br === 'all') {
                                                url.searchParams.delete('brand');
                                            } else {
                                                url.searchParams.set('brand', br);
                                            }
                                        }

                                        // Filtre Technicien
                                        const technicianElement = document.getElementById('technician-filter');
                                        if (technicianElement) {
                                            const tch = technicianElement.value;
                                            if (tch === 'all') {
                                                url.searchParams.delete('technicianId');
                                            } else {
                                                url.searchParams.set('technicianId', tch);
                                            }
                                        }

                                        // ----- Mise à jour du filtre Niveau -----
                                        // Ici nous utilisons la proposition qui met à jour le filtre "Niveau" selon la sélection
                                        const levelFilter = document.getElementById('level-filter');
                                        if (levelFilter) {
                                            const selectedLevel = levelFilter.value;
                                            if (selectedLevel === 'all') {
                                                url.searchParams.delete('level');
                                            } else {
                                                url.searchParams.set('level', selectedLevel);
                                            }
                                        }

                                        // Si l'URL a changé, rediriger vers la nouvelle URL
                                        const newUrl = url.toString();
                                        if (newUrl !== window.location.href) {
                                            window.location.href = newUrl;
                                        }
                                    }



                                    // Ajout des écouteurs d'événements aux filtres (si les éléments existent)
                                    const brandFilter = document.getElementById('brand-filter');
                                    if (brandFilter) {
                                        brandFilter.addEventListener('change', applyFilters);
                                    }

                                    const levelFilter = document.getElementById('level-filter');
                                    if (levelFilter) {
                                        levelFilter.addEventListener('change', applyFilters);
                                    }

                                    const managerSelect = document.getElementById('manager-filter');
                                    if (managerSelect) {
                                        managerSelect.addEventListener('change', applyFilters);
                                    }

                                    const technicianSelect = document.getElementById('technician-filter');
                                    if (technicianSelect) {
                                        technicianSelect.addEventListener('change', applyFilters);
                                        // Mettre à jour le filtre Niveau en fonction du technicien sélectionné
                                        updateLevelOptions(technicianSelect.value);
                                        // Vous pouvez commenter ou retirer l'appel automatique suivant s'il provoque un rechargement infini
                                        // if (!urlParams.has('managerId') && !urlParams.has('brand') && !urlParams.has('level') && !urlParams.has('technicianId')) {
                                        //   applyFilters();
                                        // }
                                    }

                                    const filialeFilter = document.getElementById('filiale-filter');
                                    if (filialeFilter) {
                                        filialeFilter.addEventListener('change', applyFilters);
                                    }

                                    const agenceFilter = document.getElementById('agence-filter');
                                    if (agenceFilter) {
                                        agenceFilter.addEventListener('change', applyFilters);
                                    }

                                    // Au chargement de la page, si un technicien est déjà sélectionné, on met à jour le filtre niveau
                                    const techFilterElement = document.getElementById('technician-filter');
                                    if (techFilterElement) {
                                        updateLevelOptions(techFilterElement.value);
                                    }
                                });
                            </script>



                            <!-- <script>
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
                            </script> -->


                            <br>

                            <!-- Section des Logos des Marques -->
                            <div class="text-center mb-4">

                                <?php
                                $titleManagerPart = ($managerId === 'all')
                                    ? "de la Filiale"
                                    : "de l'Équipe sélectionnée";
                                if ($_SESSION["profile"] === 'Directeur Groupe') {
                                    $titleManagerPart = "du Groupe";
                                }

                                $titleBrandPart = ($filterBrand === 'all')
                                    ? "Toutes les Marques"
                                    : "Marque: " . htmlspecialchars($filterBrand);

                                $titleLevelPart = ($filterLevel === 'all')
                                    ? "de Tous les Niveaux"
                                    : "du Niveau: " . htmlspecialchars($filterLevel);

                                echo "<h4>$titleBrandPart  $titleLevelPart  $titleManagerPart</h4>";
                                ?>
                                <br>
                                <div class="brand-scroll-wrapper">
                                    <!-- Bouton flèche gauche -->
                                    <button class="arrow-button" id="scrollLeft">‹</button>

                                    <!-- Conteneur scrollable pour les cartes de marques -->
                                    <div id="brandContainer" class="horizontal-scroll-container">
                                        <?php

                                        // Boucle sur le tableau des marques à afficher (par exemple, $teamBrands)
                                        foreach ($teamBrands as $brand) {
                                            // Récupération du logo de la marque, avec une valeur par défaut
                                            $logoSrc = isset($brandLogos[$brand]) ? "brands/" . $brandLogos[$brand] : "brands/default.png";
                                        ?>
                                            <div class="card custom-card brand-card">
                                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                                    <img src="<?php echo $logoSrc; ?>" alt="<?php echo htmlspecialchars($brand); ?> Logo" class="img-fluid brand-logo">
                                                    <!-- Vous pouvez ajouter ici le nom de la marque si nécessaire -->

                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <!-- Bouton flèche droite -->
                                    <button class="arrow-button" id="scrollRight">›</button>
                                </div>


                                <script>
                                    document.getElementById('scrollLeft').addEventListener('click', function() {
                                        document.getElementById('brandContainer').scrollBy({
                                            left: -200,
                                            behavior: 'smooth'
                                        });
                                    });

                                    document.getElementById('scrollRight').addEventListener('click', function() {
                                        document.getElementById('brandContainer').scrollBy({
                                            left: 200,
                                            behavior: 'smooth'
                                        });
                                    });
                                </script>


                            </div>

                            <br>
                            <?php


                            // Construire une description complète des filtres
                            $filterDescriptionParts = [];

                            // Filtrer par marque
                            if ($filterBrand !== 'all') {
                                $filterDescriptionParts[] = "la Marque: " . htmlspecialchars($filterBrand);
                            } else {
                                $filterDescriptionParts[] = "toutes les Marques";
                            }

                            // Filtrer par niveau
                            if ($filterLevel !== 'all') {
                                $filterDescriptionParts[] = "Niveau: " . htmlspecialchars($filterLevel);
                            } else {
                                $filterDescriptionParts[] = "de tous les Niveaux";
                            }

                            // Filtrer par manager ou technicien
                            if ($filterTechnician !== 'all') {
                                // Récupérer le nom du technicien
                                $technicianName = '';
                                foreach ($technicians as $tech) {
                                    if ($tech['id'] === $filterTechnician) {
                                        $technicianName = htmlspecialchars($tech['name']);
                                        break;
                                    }
                                }
                                if ($technicianName) {
                                    $filterDescriptionParts[] = "du technicien: " . $technicianName;
                                }
                            } elseif ($managerId !== 'all') {
                                // Utiliser le nom du manager déjà récupéré
                                $filterDescriptionParts[] = "de l'équipe de " . htmlspecialchars($managerName);
                            } else {
                                // Filiale
                                $filterDescriptionParts[] = "de la filiale";
                            }

                            // Joindre les parties de la description
                            $filterDescription = implode("  ", $filterDescriptionParts);

                            // Définir les titres dynamiques
                            $titleModulesFormation = "Modules de Formation pour $filterDescription";
                            $titleTotalDaysFormation = "Jours de Formation Estimés pour $filterDescription";
                            ?>

                            <!-- Section des Graphiques -->
                            <div class="chart-dashboard-container">
                                <!-- Graphique 1 : Résultats (Scores) par Marque -->
                                <!-- <div class="row mb-4">
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
                                </div> -->


                                <!-- Graphique 2 : Plans de Formation par Marque -->
                                <h3 class="text-center mb-4">Plans de Formations Collectifs de la Filiale par Marque</h3><br>
                                <!-- Cartes de métriques -->
                                <div class="row mb-6 justify-content-center">
                                    <div class="col-md-5 mb-5">
                                        <div class="card custom-card text-center">
                                            <div class="card-body">
                                                <i class="fas fa-book-open fa-2x text-primary mb-2"></i>
                                                <h5 class="card-title"><?php echo htmlspecialchars($titleModulesFormation); ?></h5>
                                                <p class="fs-1 fw-bold">
                                                    <?php echo (int)$numTrainings; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 mb-5">
                                        <div class="card custom-card text-center">
                                            <div class="card-body">
                                                <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                                                <h5 class="card-title"><?php echo htmlspecialchars($titleTotalDaysFormation); ?></h5>
                                                <p class="fs-1 fw-bold">
                                                    <?php echo (int)$numDays; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            <?php include "./partials/footer.php"; ?>
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
                // const imagePluginScatter = {
                //     id: 'imagePluginScatter',
                //     afterRender: (chart) => drawLogos(chart, 'scoreScatterCanvas', variablesPHP.brandScores.map(item => item.x)),
                //     afterResize: (chart) => {
                //         const logoContainer = document.getElementById('scoreScatterCanvas-logo-container');
                //         if (logoContainer) {
                //             logoContainer.remove();
                //         }
                //         drawLogos(chart, 'scoreScatterCanvas', variablesPHP.brandScores.map(item => item.x));
                //     }
                // };

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

                    // const ctx1 = document.getElementById('scoreScatterCanvas').getContext('2d');
                    // const chartScores = new Chart(ctx1, {
                    //     type: 'scatter',
                    //     data: {
                    //         datasets: [{
                    //             label: "Scores par Marque",
                    //             data: scatterScores.map(d => ({
                    //                 x: d.x,
                    //                 y: d.y
                    //             })),
                    //             pointRadius: 55,
                    //             pointHoverRadius: 60,
                    //             backgroundColor: '#aaaaa7',
                    //             borderColor: '#aaaaa7'
                    //         }]
                    //     },
                    //     options: {
                    //         responsive: true,
                    //         plugins: {
                    //             legend: {
                    //                 display: false
                    //             },
                    //             datalabels: {
                    //                 display: true,
                    //                 formatter: (value, ctx) => {
                    //                     return `${Math.round(value.y)}%`; // Arrondir à l'entier le plus proche
                    //                 },
                    //                 color: '#000',
                    //                 font: {
                    //                     size: 20,
                    //                     weight: 'bold'
                    //                 }
                    //             },
                    //             tooltip: {
                    //                 callbacks: {
                    //                     label: (context) => {
                    //                         const i = context.dataIndex;
                    //                         return [
                    //                             `Marque: ${brandLabelsScores[i]}`,
                    //                             `Score: ${Math.round(scatterScores[i].y)}%` // Arrondir à l'entier le plus proche
                    //                         ];
                    //                     }
                    //                 }
                    //             },
                    //             zoom: {
                    //                 pan: {
                    //                     enabled: true,
                    //                     mode: 'x'
                    //                 },
                    //                 zoom: {
                    //                     enabled: false
                    //                 }
                    //             }
                    //         },
                    //         scales: {
                    //             x: {
                    //                 type: 'linear',
                    //                 min: -0.5,
                    //                 max: scatterScores.length - 0.5,
                    //                 grid: {
                    //                     color: '#ccc'
                    //                 },
                    //                 ticks: {
                    //                     display: false // Masquer les labels textuels
                    //                 }
                    //             },
                    //             y: {
                    //                 type: 'linear',
                    //                 min: 0,
                    //                 max: 100,
                    //                 title: {
                    //                     display: true,
                    //                     text: 'Score (%)'
                    //                 },
                    //                 grid: {
                    //                     color: '#ccc'
                    //                 },
                    //                 ticks: {
                    //                     stepSize: 10,
                    //                     padding: 10,
                    //                     font: {
                    //                         size: 12
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     },
                    //     plugins: [imagePluginScatter, ChartDataLabels, ChartZoom]
                    // });

                    // Plugin personnalisé pour dessiner le nombre de formations + "Module(s)"
                    const customLabelPlugin = {
                        id: 'customLabelPlugin',
                        afterDraw: (chart) => {
                            const ctx = chart.ctx;
                            chart.data.datasets.forEach((dataset) => {
                                const meta = chart.getDatasetMeta(0);
                                meta.data.forEach((element, index) => {
                                    const dataPoint = dataset.data[index];
                                    // dataPoint.y => le score 
                                    // dataPoint.count => le nb de modules 

                                    const count = dataPoint.count;
                                    const score = dataPoint.y;

                                    // Position du point
                                    const position = element.getCenterPoint();

                                    // Dessiner le count
                                    ctx.font = 'bold 23px Arial';
                                    ctx.fillStyle = '#000';
                                    ctx.textAlign = 'center';
                                    ctx.fillText(Math.round(count), position.x, position.y - 15);

                                    // Dessiner "Module(s)" + le score
                                    ctx.font = 'bold 13px Arial';
                                    ctx.fillStyle = '#000';
                                    ctx.textAlign = 'center';
                                    // 1) "Module(s)"
                                    ctx.fillText('Module(s)', position.x, position.y + 15);

                                    // 2) Score sur la ligne suivante => position.y + 30
                                    ctx.fillText(`${Math.round(score)}%`, position.x, position.y + 30);
                                });
                            });
                        }
                    };


                    // ------------------- ChartJS : Trainings Scatter -------------------
                    const ctx2 = document.getElementById('trainingsScatterCanvas').getContext('2d');
                    const dataForChart2 = scatterTrainings.map(pt => {
                        // pt = { x: ..., y: scoreMoyen, count: nombreDeModules }
                        let color;
                        if (pt.y <= 50) {
                            // 0..50% => warning
                            color = 'rgb(241, 85, 100)';
                        } else if (pt.y < 80) {
                            // 51..79% => "couleur actuelle" (par exemple un orange un peu plus foncé, ou rouge)
                            // Ex : un orange plus foncé #fd7e14, ou un rouge #dc3545, au choix :
                            color = '#ffc107';
                            // ou color = '#dc3545'; // si vous préférez "danger"
                        } else {
                            // 80..100 => success
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
                                showLine: false, // pas de ligne, juste des points
                                parsing: false, // important pour accéder directement à ctx.raw
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