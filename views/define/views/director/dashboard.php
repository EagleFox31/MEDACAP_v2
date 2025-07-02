        <?php
        // Ensure the currentPage is set before including the header
        $GLOBALS['currentPage'] = 'dashboard';
        $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
        $academy     = $mongoClient->academy;

        // Initialize the FilterController
        require_once __DIR__ . '/../../controllers/FilterController.php';
        require_once __DIR__ . '/../../services/PIFService.php';


        $pifService = new \views\define\services\PIFService();

        $filterController = new FilterController($academy);
        // Get filters from request
        $filters = $filterController->getFilters();

        /* ─────────────────  injection pour stats_tests.php  ─────────────────────── */
        $GLOBALS['filtersForStats'] = $filters;           // ← new
        $statsTests = require __DIR__ . '/../../services/stats_tests.php';

        // Include filter components
        require_once __DIR__ . '/../../components/filterCountry.php';
        require_once __DIR__ . '/../../components/filterAgency.php';
        require_once __DIR__ . '/../../components/filterLevel.php';
        require_once __DIR__ . '/../../components/filterBrand.php';
        require_once __DIR__ . '/../../components/filterTechnician.php';

        /* ----------  infos droits utilisateur  ---------- */
        // Get user permissions
        $isAdmin = in_array($_SESSION['profile'] ?? '', ['Super Admin', 'Directeur Groupe','Admin']);
        $canSelectSubsidiary = $filterController->canSelectSubsidiary();

        // Get data based on current filters
        $globalStats = $filterController->getGlobalStats($filters);
        $levelStats = $filterController->getLevelStats($filters);
        $brandScores = $filterController->getBrandScores($filters);
        if ($filters['technicianId'] !== 'all') {
            unset($filters['availableBrands']);   // on force la fresh-list
        }

        $brandStats = $filterController->getBrandStats($filters);
        $trainingStats = $filterController->getTrainingStats($filters);
        $technicianSummary = $filterController->getTechnicianSummary($filters);
        // Nouveaux calculs pour l'histogramme Formations proposées / validées
        $trainingValidationStats = $filterController->getTrainingValidationStats($filters);
        $trainingsCountsForGraph2 = $trainingValidationStats['trainingsCounts'];
        $validationsCountsForGraph2 = $trainingValidationStats['validationsCounts'];
        foreach (array_keys($brandStats) as $brand) {
            if (!isset($trainingsCountsForGraph2[$brand])) {
                $trainingsCountsForGraph2[$brand] = 0;
            }
            if (!isset($validationsCountsForGraph2[$brand])) {
                $validationsCountsForGraph2[$brand] = 0;
            }
        }
        // $globalStats = $filterController->getGlobalStats($filters);
        // $levelStats = $filterController->getLevelStats($filters);
        // $brandScores = $filterController->getBrandScores($filters);
        // $brandStats = $filterController->getBrandStats($filters);
        // $trainingStats = $filterController->getTrainingStats($filters);
        // $technicianSummary = $filterController->getTechnicianSummary($filters);
        /* ----------  PIF  ---------- */
        $nbPifJunior  = $pifService->countPIFByExactLevel('Junior', $filters);
        $nbPifSenior  = $pifService->countPIFByExactLevel('Senior', $filters);
        $nbPifExpert  = $pifService->countPIFByExactLevel('Expert', $filters);

        $nbValidJunior = $pifService->countUsersWithValidation('Junior', $filters);
        $nbValidSenior = $pifService->countUsersWithValidation('Senior', $filters);
        $nbValidExpert = $pifService->countUsersWithValidation('Expert', $filters);


        /* ----------  options de filtres dynamiques  ---------- */
        // Get filter options based on current selection
        $subsidiaries = $filterController->getSubsidiaries();
        $currentSubsidiary = $filters['subsidiary'] ?? 'all';
        // Fetch agencies for the current subsidiary before the header overrides the variable
        $agencyOptions = $filterController->getAgencies($currentSubsidiary);
        error_log('[dashboard.php] Subsidiary ' . $currentSubsidiary . ' agencies: ' . json_encode($agencyOptions));

        // Ensure $agencyOptions is an array of strings
        if (is_object($agencyOptions) || (is_array($agencyOptions) && !empty($agencyOptions) && isset($agencyOptions[$currentSubsidiary]))) {
            $agencyOptions = $agencyOptions[$currentSubsidiary] ?? [];
        }
        $brands = $filterController->getBrands($filters);
        $technicians = $filterController->getTechnicians($filters);

        // Mapping des logos de marques (uniquement depuis le dossier "brands")
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
        'MERCEDES'        => 'mercedestruck.png',
        'TOYOTA'          => 'toyota-logo.png',
        'SUZUKI'          => 'suzuki-logo.png',
        'MITSUBISHI'      => 'mitsubishi-logo.png',
        'BYD'             => 'byd-logo.png',
        'CITROEN'         => 'citroen-logo.png',
        'PEUGEOT'         => 'peugeot-logo.png',
    ];

        /* ----------  disponibilité niveaux pour le sélecteur  ---------- */
        // Determine maximum level for adaptive display
        $maxLevel = 'Junior';
        if ($levelStats['expertCount'] > 0) {
            $maxLevel = 'Expert';
        } elseif ($levelStats['seniorCount'] > 0) {
            $maxLevel = 'Senior';
        }
        $availableLevels = $filterController->getLevelsToInclude($maxLevel);

        // Check if filters should be locked
        $technicianSelected = ($filters['technicianId'] ?? 'all') !== 'all';
        $countrySelected = ($filters['subsidiary'] ?? 'all') !== 'all';

        // Page title
        $tableau = 'Etat des Propositions des Plans de Formation Individuels (PIF) pour les techniciens validé par la filiale';

        // Helper functions
        function sanitizeOutput($data) {
            if (is_array($data)) {
                return array_map('sanitizeOutput', $data);
            }
            return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
        }

        function calculatePercentage($numerator, $denominator) {
            if ($denominator <= 0) return 0;
            return min(100, max(0, round(($numerator / $denominator) * 100)));
        }

        function getProgressClass($percentage) {
            if ($percentage >= 80) return 'bg-success';
            if ($percentage >= 50) return 'bg-warning';
            return 'bg-danger';
        }

        // Calculate percentages
        $measuredPercentage = calculatePercentage($globalStats['measuredCount'], $globalStats['totalTechnicians']);
        $withTrainingPercentage = calculatePercentage($globalStats['withTrainingCount'], $globalStats['totalTechnicians']);
        $validatedPercentage = calculatePercentage($globalStats['validatedTrainingCount'], $globalStats['withTrainingCount']);
        ?>

        <style>
            :root {
                /* Couleurs de l'entreprise */
                --primary-black: #1a1a1a;
                --primary-red: #dc2626;
                --primary-navy: #1e3a8a;
                --secondary-navy: #3b82f6;
                --light-gray: #f8fafc;
                --medium-gray: #64748b;
                --dark-gray: #334155;
                --white: #ffffff;
                --border-color: #e2e8f0;
                --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                --border-radius: 12px;
                --border-radius-lg: 16px;
                --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Effet de glassmorphisme pour les cartes */
            .glass-effect {
                background: rgba(255, 255, 255, 0.7) !important;
                backdrop-filter: blur(10px) !important;
                -webkit-backdrop-filter: blur(10px) !important;
                border: 1px solid rgba(255, 255, 255, 0.18) !important;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15) !important;
                border-radius: 15px !important;
                transition: all 0.3s ease !important;
            }
            
            /* Effet hover sur les titres */
            .glass-effect:hover {
                background: rgba(255, 255, 255, 0.8) !important;
                box-shadow: 0 10px 40px 0 rgba(31, 38, 135, 0.2) !important;
                transform: translateY(-3px) !important;
            }
            
            /* Style pour les titres dans les cartes glassmorphisme */
            .glass-effect h1, .glass-effect h2 {
                color: var(--primary-black) !important;
                font-weight: 700;
                letter-spacing: -0.025em;
                text-shadow: 0px 1px 1px rgba(255, 255, 255, 0.5);
            }
            
            /* Effet de profondeur pour les cartes */
            .depth-effect {
                transform: translateZ(0);
                box-shadow:
                    0 4px 6px -1px rgba(0, 0, 0, 0.1),
                    0 2px 4px -1px rgba(0, 0, 0, 0.06),
                    0 12px 20px -2px rgba(0, 0, 0, 0.05) !important;
                transition: all 0.3s ease-in-out !important;
            }
            
            .depth-effect:hover {
                transform: translateY(-5px) translateZ(0);
                box-shadow:
                    0 10px 15px -3px rgba(0, 0, 0, 0.1),
                    0 4px 6px -2px rgba(0, 0, 0, 0.05),
                    0 20px 25px -5px rgba(0, 0, 0, 0.03) !important;
            }

            /* Reset et base */
            * {
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                color: var(--primary-black);
                line-height: 1.6;
            }

            /* Conteneur principal */
            .content {
                min-height: 100vh;
                position: relative;
                z-index: 1;
            }

            .container-fluid {
                    max-width: 90%; /* Changed from 1400px to use full width */
                    margin: 0 auto;
                    padding: 2rem 0; /* Removed horizontal padding completely */
                }

            /* Titre principal */
            h1 {
                color: var(--primary-black);
                font-weight: 700;
                font-size: 3.2rem;
                margin-bottom: 2rem;
                letter-spacing: -0.025em;
            }

            /* Cards principales */
            .card {
                background: rgba(255, 255, 255, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: var(--border-radius);
                box-shadow:
                    0 4px 6px -1px rgba(0, 0, 0, 0.1),
                    0 2px 4px -1px rgba(0, 0, 0, 0.06),
                    0 0 0 1px rgba(255, 255, 255, 0.3) inset;
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                overflow: hidden;
                margin-bottom: 1.5rem;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .card:hover {
                box-shadow:
                    0 10px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -5px rgba(0, 0, 0, 0.04),
                    0 0 0 1px rgba(255, 255, 255, 0.5) inset;
                transform: translateY(-8px);
            }

            /* Stats cards spécifiques - Style compact horizontal */
            .stats-card {
                border-radius: var(--border-radius);
                background: rgba(255, 255, 255, 0.7);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow:
                    0 4px 20px 0 rgba(0, 0, 0, 0.1),
                    0 1px 5px 0 rgba(0, 0, 0, 0.05),
                    0 0 0 1px rgba(255, 255, 255, 0.2) inset;
                transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
                overflow: hidden;
                min-height: 120px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .stats-card:hover {
                box-shadow:
                    0 15px 30px 0 rgba(0, 0, 0, 0.1),
                    0 5px 15px 0 rgba(0, 0, 0, 0.05),
                    0 0 0 1px rgba(30, 58, 138, 0.2) inset;
                transform: translateY(-8px) scale(1.02);
                border-color: rgba(30, 58, 138, 0.3);
            }

            .stats-card .card-body {
                padding: 1.5rem;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
            }

            .stats-card .big-number {
                font-size: 3rem;
                font-weight: 900;
                color: var(--primary-black);
                line-height: 1;
                margin-bottom: 0.5rem;
                font-family: 'Inter', sans-serif;
                letter-spacing: -0.03em;
            }

            .stats-card .card-title-text {
                font-size: 0.875rem;
                font-weight: 600;
                color: var(--primary-navy);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin: 0;
                line-height: 1.2;
            }

            /* En-têtes de cards - Supprimées pour les stats cards */
            .card-header-bg {
                background: linear-gradient(135deg, var(--primary-black) 0%, var(--primary-navy) 100%);
                color: var(--white);
                padding: 1.25rem 1.5rem;
                border: none;
                margin: 0;
            }

            .card-header-bg h5 {
                color: var(--white);
                font-weight: 600;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin: 0;
            }
            
            /* Style pastel pour les en-têtes de filtre et formations */
            .filter-header, .training-header {
                background: linear-gradient(135deg,rgb(248, 250, 251) 0%,rgb(250, 251, 251) 100%) !important;
                color: #333 !important;
                text-align: center !important;
                padding: 1rem !important;
            }
            
            .filter-header h5, .training-header h5 {
                color: #333 !important;
                font-weight: 700 !important;
                display: inline-block !important;
                margin: 0 auto !important;
            }

            /* Headers cachés pour les stats cards */
            .stats-card .card-header-bg {
                display: none;
            }

            /* Corps des cards */
            .card-body {
                padding: 1.5rem;
            }

            /* Grands chiffres */
            .big-number {
                font-size: 2.5rem;
                font-weight: 800;
                color: var(--primary-black);
                line-height: 1;
                margin-bottom: 0.5rem;
                font-family: 'Inter', sans-serif;
                letter-spacing: -0.02em;
            }

            /* Badges et indicateurs */
            .badge {
                padding: 0.5rem 0.75rem;
                font-weight: 600;
                border-radius: 8px;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.025em;
            }

            .badge.bg-info {
                background-color: var(--secondary-navy) !important;
                color: var(--white);
            }

            .badge.bg-secondary {
                background-color: var(--medium-gray) !important;
                color: var(--white);
            }

            .badge.bg-dark {
                background-color: var(--primary-black) !important;
                color: var(--white);
            }
            
            .bg-navy {
                background-color: var(--primary-navy) !important;
                color: var(--white);
            }

            .badge.bg-success {
                background-color: var(--primary-red) !important;
                color: var(--white);
            }

            /* Barres de progression */
            .progress {
                height: 16px; /* Increased height from 8px to 16px */
                background-color: #333333; /* Darker gray background */
                border-radius: 8px;
                overflow: hidden;
                margin-bottom: 0.75rem;
            }

            .progress-bar {
                background: linear-gradient(90deg, var(--primary-red) 0%, var(--primary-navy) 100%);
                transition: width 1s ease-in-out;
                border-radius: 8px;
                font-size: 14px; /* Larger percentage text */
                font-weight: bold;
                line-height: 16px; /* Match the new height */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .progress-bar.bg-success {
                background: linear-gradient(90deg, var(--primary-red) 0%, var(--primary-navy) 100%);
            }

            .progress-bar.bg-warning {
                background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
            }

            .progress-bar.bg-danger {
                background: linear-gradient(90deg, var(--primary-red) 0%, #b91c1c 100%);
            }
            
            .progress-bar.bg-secondary {
                background:rgb(16, 16, 16) !important;
                color: var(--white);
            }

            /* Texte secondaire */
            .text-muted {
                color: var(--primary-navy) !important;
                font-size: 0.875rem;
                font-weight: 500;
            }

            /* Boutons */
            .btn {
                border-radius: 10px;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.025em;
                transition: var(--transition);
                border: none;
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
                color: var(--white);
                box-shadow: var(--shadow-medium);
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, var(--secondary-navy) 0%, var(--primary-navy) 100%);
                transform: translateY(-2px);
                box-shadow: var(--shadow-large);
                color: var(--white);
            }

            .btn-outline-secondary {
                border: 2px solid var(--border-color);
                color: var(--dark-gray);
                background: var(--white);
            }

            .btn-outline-secondary:hover {
                background: var(--primary-black);
                color: var(--white);
                border-color: var(--primary-black);
            }
            
            /* Style personnalisé pour le bouton "Appliquer les filtres" */
            #applyFiltersButton,
            #applyFiltersButton.btn,
            #applyFiltersButton.btn-primary {
                background: white !important;
                background-color: white !important;
                background-image: none !important;
                color: black !important;
                border: 1px solid #ddd !important;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
            }
            
            #applyFiltersButton:hover,
            #applyFiltersButton.btn:hover,
            #applyFiltersButton.btn-primary:hover {
                background-color: #f8f9fa !important;
                background-image: none !important;
                box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
                color: black !important;
            }
            
            #applyFiltersButton .ki-duotone,
            #applyFiltersButton .ki-duotone .path1,
            #applyFiltersButton .ki-duotone .path2 {
                color: black !important;
            }

            .btn-outline-primary {
                border: 2px solid var(--primary-navy);
                color: var(--primary-navy);
                background: var(--white);
            }

            .btn-outline-primary:hover {
                background: var(--primary-navy);
                color: var(--white);
            }

            /* Formulaires */
            .form-control,
            .form-select {
                border: 2px solid var(--border-color);
                border-radius: 10px;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                color: var(--primary-black);
                background-color: var(--white);
                transition: var(--transition);
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--primary-navy);
                box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
                outline: none;
            }

            /* Tableaux */
            .table {
                background: var(--white);
                border-radius: var(--border-radius);
                overflow: hidden;
                box-shadow: var(--shadow-light);
            }

            .table th {
                background-color: var(--light-gray);
                color: var(--primary-black);
                font-weight: 600;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.025em;
                padding: 1rem;
                border: none;
            }

            .table td {
                padding: 1rem;
                color: var(--primary-black);
                font-weight: 500;
                border-color: var(--border-color);
            }

            .table-striped > tbody > tr:nth-of-type(odd) > td {
                background-color: rgba(248, 250, 252, 0.5);
            }

            .table-hover > tbody > tr:hover > td {
                background-color: rgba(30, 58, 138, 0.05);
            }

            /* Indicateurs de niveau */
            .level-indicator {
                display: inline-block;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin-right: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            /* Conteneurs de graphiques */
            .chart-container {
                position: relative;
                height: 300px;
                width: 100%;
                padding: 1rem;
            }

            .double-doughnut-container {
                position: relative;
                width: 160px;
                height: 160px;
                margin: 0 auto;
            }

            /* Panel de filtres */
            .filter-panel {
                background: linear-gradient(135deg, var(--white) 0%, var(--light-gray) 100%);
                border: 1px solid var(--border-color);
                border-radius: var(--border-radius-lg);
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                box-shadow: var(--shadow-light);
            }

            /* États des filtres */
            .filter-disabled {
                opacity: 0.5;
                pointer-events: none;
            }

            .filter-locked {
                opacity: 0.6;
                pointer-events: none;
                position: relative;
            }

            .filter-locked::after {
                content: "🔒";
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 1rem;
            }

            /* Loading spinner */
            .loading-spinner {
                display: none;
                text-align: center;
                padding: 2rem;
            }

            .spinner-border {
                color: var(--primary-navy);
            }

            .spinner-grow {
                color: var(--primary-navy);
            }

            /* Overlay de chargement */
            #loadingOverlay {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(4px);
            }

            /* Animations */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in {
                animation: fadeIn 0.6s ease-out forwards;
            }

            @keyframes highlight-success {
                0% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.3);
                }
                50% {
                    box-shadow: 0 0 0 20px rgba(220, 38, 38, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
                }
            }

            .highlight-update {
                animation: highlight-success 1.5s ease;
            }

            /* Couleurs spécifiques pour les graphiques */
            .chart-primary {
                color: var(--primary-navy);
            }

            .chart-secondary {
                color: var(--primary-red);
            }

            .chart-tertiary {
                color: var(--primary-black);
            }

            /* Responsive design */
            @media (max-width: 768px) {
                .container-fluid {
                    padding: 1rem 0;
                }

                h1 {
                    font-size: 2rem;
                }

                .big-number {
                    font-size: 2rem;
                }

                .card-body {
                    padding: 1rem;
                }

                .btn {
                    padding: 0.625rem 1.25rem;
                }
            }

            /* Améliorations pour l'accessibilité */
            .card:focus-within {
                outline: 2px solid var(--primary-navy);
                outline-offset: 2px;
            }

            /* Styles pour les états de succès/erreur */
            .border-success {
                border-color: var(--primary-red) !important;
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
            }

            /* Typographie améliorée */
            strong {
                color: var(--primary-black);
                font-weight: 600;
            }

            /* Espacements cohérents */
            .mb-4 {
                margin-bottom: 2rem !important;
            }

            .mb-3 {
                margin-bottom: 1.5rem !important;
            }

            .mb-2 {
                margin-bottom: 1rem !important;
            }

            /* Style pour les icônes spécifiquement dans les cartes du dashboard */
            .card .ki-duotone,
            .card .fas,
            .card .far {
                color: black !important;
            }
            
            /* Assurez-vous que tous les chemins de l'icône sont aussi noirs */
            .card .ki-duotone .path1,
            .card .ki-duotone .path2,
            .card .ki-duotone .path3,
            .card .ki-duotone .path4 {
                color: black !important;
            }
            
            /* Style spécifique pour les icônes dans les titres de carte */
            .card-title .ki-duotone,
            .chart-title .ki-duotone,
            h1 .ki-duotone,
            h2 .ki-duotone {
                color: black !important;
            }

            /* Conteneur de stats en ligne */
            .stats-row {
                display: flex;
                gap: 1rem;
                margin-bottom: 2rem;
                flex-wrap: wrap;
            }

            .stats-row .stats-card {
                flex: 1;
                min-width: 200px;
                max-width: 300px;
            }

            @media (max-width: 768px) {
                .stats-row {
                    flex-direction: column;
                }
                
                .stats-row .stats-card {
                    max-width: none;
                }
            }
            
            /* Styles pour histogrammes */
            /* Style pour les histogrammes ApexCharts */
            .chart-container {
                position: relative;
                height: 300px;
                width: 100%;
            }
            
            .test-levels-chart {
                position: relative;
                height: 450px;
                width: 110%;
                margin-left: -5%;
                background-color: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                padding: 10px;
                border-radius: 8px;
                box-shadow:
                    0 4px 20px 0 rgba(0, 0, 0, 0.05),
                    0 0 0 1px rgba(255, 255, 255, 0.4) inset;
                transition: all 0.3s ease;
            }
            
            .test-levels-chart:hover {
                box-shadow:
                    0 8px 30px 0 rgba(0, 0, 0, 0.08),
                    0 0 0 1px rgba(255, 255, 255, 0.5) inset;
                transform: translateY(-3px);
            }
            
            .chart-title {
                text-align: center;
                background: white;
                color: #333;
                padding: 8px;
                border-radius: 6px;
                margin-bottom: 15px;
                font-weight: 600;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            /* Shadow custom pour les éléments importants */
            .elevated {
                box-shadow: var(--shadow-large);
            }

            /* Améliorations pour le contraste */
            .text-contrast {
                color: var(--primary-black);
                font-weight: 600;
            }
            /* 1.  Crée (ou sur-écrit) une teinte plus dark */
            .bg-charcoal {
                --bs-progress-bar-bg: #374151;   /* slate-700 style */
                color: #fff;                     /* texte toujours lisible */
            }
            
            /* Style pour le tooltip personnalisé */
            .custom-tooltip {
                background: rgba(0, 0, 0, 0.85);
                color: white;
                padding: 10px 15px;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 600;
                box-shadow:
                    0 4px 20px rgba(0, 0, 0, 0.3),
                    0 0 0 1px rgba(255, 255, 255, 0.1) inset;
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                margin-top: 5px;
                margin-bottom: 30px; /* Plus d'espace en bas pour le tooltip */
                position: relative;
                z-index: 999;
                pointer-events: none; /* Empêche les interactions avec le tooltip */
            }
            
            /* Ajout d'une flèche au tooltip */
            .custom-tooltip::after {
                content: '';
                position: absolute;
                bottom: -8px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 0;
                border-left: 8px solid transparent;
                border-right: 8px solid transparent;
                border-top: 8px solid rgba(0, 0, 0, 0.85);
            }
            
            /* Assurez-vous que le tooltip est toujours au-dessus des autres éléments */
            .apexcharts-tooltip {
                z-index: 1000 !important;
                top: -70px !important; /* Force le tooltip à s'afficher au-dessus */
                overflow: visible !important;
            }
            
            .apexcharts-tooltip-title {
                display: none !important;
            }
            
            /* Masquer les tooltips par défaut d'ApexCharts */
            .apexcharts-tooltip-text-y-value,
            .apexcharts-tooltip-text-y-label {
                display: none !important;
            }
        </style>
        <?php require_once __DIR__ . '/../../partials/header.php'; ?>
        
        <style>
            /* Style for technician-specific indicators */
            .technician-specific-notice {
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                z-index: 10;
                font-weight: 600;
                border: 1px solid rgba(0,0,0,0.1);
                transition: all 0.3s ease;
            }
            
            .technician-specific-notice:hover {
                background: rgba(255,255,255,0.95);
                box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            }
        </style>

        <!-- CountUp.js for number animations (using browser version, not module) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.8/countUp.umd.min.js"></script>
        <!-- ApexCharts for histogram visualization -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

        <!--begin::Content-->
        <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content" style="position: relative; background: none;">
            <!-- Image en arrière-plan avec bords arrondis -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
                        background-size: cover;
                        background-position: center;
                        border-radius: 20px;
                        overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                            filter: blur(10px);
                            transform: scale(1.05);
                            background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.1)), url('/MEDACAP/public/images/welcome_tech.png');
                            background-size: cover;
                            background-position: center;">
                </div>
                <img src="/MEDACAP/views/define/views/director/tof_dashboard_tech.png" alt="Background"
                    style="width: 100%; height: 100%; object-fit: cover; display: block; opacity: 0;">
                <img src="/MEDACAP/views/define/views/director/tof_dashboard_tech.png" alt="Background"
                    style="width: 100%; height: 100%; object-fit: cover; display: block; opacity: 0;">
            </div>
            
            <!-- Full Page Loading Overlay -->
            <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background-color: rgba(27, 24, 24, 0.85); z-index: 9999;">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-grow text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid mt-4 px-0"> <!-- Removed side padding completely -->
                <div class="row">
                    <div class="col-12">
                        <!-- Titre principal dans une carte glassmorphisme -->
                        <div class="card depth-effect mb-5" style="background-color: white;">
                            <div class="card-body text-center py-4">
                                <h1 class="card-title mb-0"><i class="ki-duotone ki-document fs-2 me-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>Etat des Propositions des Plans de Formation Individuels (PIF) pour les techniciens validé par la filiale</h1>
                            </div>
                        </div>
                        
                        <!-- Sous-titre dans une carte glassmorphisme -->
                        <div class="card glass-effect depth-effect mb-5">
                            <div class="card-body text-center py-3">
                                <h1 class="text-contrast mb-0"><i class="ki-duotone ki-people fs-2 me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>Effectifs par Niveau</h1>
                            </div>
                        </div>
                        <br>
                        <!-- Loading indicator -->
                        <div class="loading-spinner" id="loadingSpinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="d-flex flex-nowrap align-items-center justify-content-between mb-5 overflow-auto" id="statsContainer" style="gap: 5px; padding: 10px 0;">
                            <!-- Junior Technicians -->
                            <div class="flex-grow-1" style="min-width: 350px;">
                                <div class="card stats-card glass-effect depth-effect h-100">
                                    <div class="card-body text-center p-2">
                                        <div class="big-number mb-2" id="juniorTechnicians" data-value="<?php echo $globalStats['juniorCount']; ?>"><?php echo number_format($globalStats['juniorCount']); ?></div>
                                        <div class="card-title-text">Techniciens Junior</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plus Sign -->
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="card" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    <div style="color: black; font-size: 32px; font-weight: 900;">+</div>
                                </div>
                            </div>

                            <!-- Senior Technicians -->
                            <div class="flex-grow-1" style="min-width: 350px;">
                                <div class="card stats-card glass-effect depth-effect h-100">
                                    <div class="card-body text-center p-2">
                                        <div class="big-number mb-2" id="seniorTechnicians" data-value="<?php echo $globalStats['seniorCount']; ?>"><?php echo number_format($globalStats['seniorCount']); ?></div>
                                        <div class="card-title-text">Techniciens Senior</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plus Sign -->
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="card" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    <div style="color: black; font-size: 32px; font-weight: 900;">+</div>
                                </div>
                            </div>

                            <!-- Expert Technicians -->
                            <div class="flex-grow-1" style="min-width: 350px;">
                                <div class="card stats-card glass-effect depth-effect h-100">
                                    <div class="card-body text-center p-2">
                                        <div class="big-number mb-2" id="expertTechnicians" data-value="<?php echo $globalStats['expertCount']; ?>"><?php echo number_format($globalStats['expertCount']); ?></div>
                                        <div class="card-title-text">Techniciens Expert</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Equals Sign -->
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="card" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    <div style="color: black; font-size: 32px; font-weight: 900;">=</div>
                                </div>
                            </div>

                            <!-- All Levels Technicians -->
                            <div class="flex-grow-1" style="min-width: 350px; max-width: 220px;">
                                <div class="card stats-card glass-effect depth-effect h-100">
                                    <div class="card-body text-center p-2">
                                        <div class="big-number mb-2" id="allLevelsTechnicians" data-value="<?php echo $globalStats['totalTechnicians']; ?>"><?php echo number_format($globalStats['totalTechnicians']); ?></div>
                                        <div class="card-title-text">Techniciens Tous Niveaux Filiales</div>
                                    </div>
                                </div>
                            </div>
                        </div>
        <br>
        
                        <!-- Sous-titre dans une carte glassmorphisme -->
                        <div class="card glass-effect depth-effect mb-5">
                            <div class="card-body text-center py-3">
                                <h1 class="text-contrast mb-0"><i class="ki-duotone ki-check-circle fs-2 me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>État d'avancement des validations des PIFs proposés par l'Academy par Niveau</h1>
                            </div>
                        </div>

                        <div class="d-flex flex-nowrap align-items-center justify-content-between mb-5 mt-5 fade-in overflow-auto" style="animation-delay:.2s; gap: 10px; padding: 10px 0;">
                        <?php
                        $levelsTests = [
                            ['key' => 'Junior', 'label' => 'JUNIOR', 'badge' => '#8b5cf6', 'pif' => $nbPifJunior, 'valid' => $nbValidJunior, 'total' => $globalStats['juniorCount']],
                            ['key' => 'Senior', 'label' => 'SENIOR', 'badge' => '#8b5cf6', 'pif' => $nbPifSenior, 'valid' => $nbValidSenior, 'total' => $globalStats['seniorCount']],
                            ['key' => 'Expert', 'label' => 'EXPERT', 'badge' => '#8b5cf6', 'pif' => $nbPifExpert, 'valid' => $nbValidExpert, 'total' => $globalStats['expertCount']],
                        ];

                        foreach ($levelsTests as $index => $lvl):
                            $testsCompleted = $statsTests[$lvl['key']]['done'];
                            $totalTests = $statsTests[$lvl['key']]['total'];
                            
                            $proposedPIF = $lvl['pif'];
                            $totalTechs = $lvl['total'];
                            
                            $validatedPIF = $lvl['valid'];
                            
                            // Generate unique chart ID for this level
                            $chartId = "levelChart" . $index;

                            // Afficher le signe "+" après chaque carte sauf la dernière
                            if ($index > 0) {
                                echo '<div class="d-flex align-items-center justify-content-center">';
                                echo '<div class="card" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);"><div style="color: black; font-size: 32px; font-weight: 900;">+</div></div>';
                                echo '</div>';
                            }
                        ?>
                            <div class="flex-grow-1" style="min-width: 350px;">
                                <div class="card glass-effect depth-effect h-100">
                                    <div class="card-body">
                                        <div class="chart-title" style="background-color: white !important;">
                                            <i class="ki-duotone ki-chart fs-2 me-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i><?= $lvl['label'] ?>
                                        </div>
                                        
                                        <div id="<?= $chartId ?>" class="test-levels-chart" style="height: 350px; width: 110%; margin-left: -5%;">
                                            <?php if ($filters['technicianId'] !== 'all'): ?>
                                            <div class="technician-specific-notice" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; font-size: 11px; color: #333;">
                                                <i class="fas fa-user-check"></i> Données spécifiques au technicien sélectionné
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="text-center mt-2">
                                       </div>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var options = {
                                        series: [{
                                            name: 'Réalisé',
                                            data: [
                                                <?= $testsCompleted ?>,
                                                <?= $proposedPIF ?>,
                                                <?= $validatedPIF ?>
                                            ]
                                        },
                                        {
                                            name: 'Non réalisé',
                                            data: [
                                                <?= $totalTechs - $testsCompleted ?>,
                                                <?= $totalTechs - $proposedPIF ?>,
                                                <?= $totalTechs - $validatedPIF ?>
                                            ]
                                        }],
                                        chart: {
                                            type: 'bar',
                                            height: 400,
                                            stacked: true,
                                            toolbar: {
                                                show: false
                                            },
                                            fontFamily: 'Inter, sans-serif',
                                            background: 'transparent'
                                        },
                                        plotOptions: {
                                            bar: {
                                                horizontal: false,
                                                columnWidth: '90%',
                                                endingShape: 'flat',
                                                borderRadius: 4,
                                                distributed: false
                                            },
                                        },
                                        colors: ['#8b5cf6', '#e2e8f0'],
                                        dataLabels: {
                                            enabled: true,
                                            formatter: function(val, opt) {
                                                // Afficher le label uniquement sur la première série
                                                if (opt.seriesIndex === 0) {
                                                    var total;
                                                    if (opt.dataPointIndex === 0) total = <?= $totalTechs ?>;
                                                    else if (opt.dataPointIndex === 1) total = <?= $totalTechs ?>;
                                                    else total = <?= $totalTechs ?>;
                                                    var percent = total > 0 ? Math.round((val / total) * 100) : 0;
                                                    return percent + '%';
                                                } else {
                                                    return '';
                                                }
                                            },
                                            style: {
                                                fontSize: '14px',
                                                fontWeight: 'bold',
                                                colors: ['#FFFFFF']
                                            }
                                        },
                                        grid: {
                                            show: true,
                                            borderColor: '#e7e7e7',
                                            strokeDashArray: 5,
                                            position: 'back',
                                            xaxis: {
                                                lines: {
                                                    show: true
                                                }
                                            },
                                            yaxis: {
                                                lines: {
                                                    show: true
                                                }
                                            },
                                            row: {
                                                colors: ['#f8f8f8', 'transparent'],
                                                opacity: 0.5
                                            },
                                            padding: {
                                                top: 10,
                                                right: 10,
                                                bottom: 0,
                                                left: 10
                                            }
                                        },
                                        stroke: {
                                            width: 0
                                        },
                                        xaxis: {
                                            categories: ['Tests complétés', 'PIF Proposés', 'PIF Validés'],
                                            labels: {
                                                style: {
                                                    fontSize: '12px',
                                                    fontWeight: 600
                                                },
                                                offsetY: 5
                                            },
                                            axisBorder: {
                                                show: true,
                                                color: '#e0e0e0'
                                            },
                                            axisTicks: {
                                                show: true,
                                                color: '#e0e0e0'
                                            },
                                            crosshairs: {
                                                show: true,
                                                stroke: {
                                                    color: '#b6b6b6',
                                                    width: 1,
                                                    dashArray: 3
                                                }
                                            }
                                        },
                                        yaxis: {
                                            min: 0,
                                            max: Math.max(<?= $totalTests ?>, <?= $totalTechs ?>, <?= $proposedPIF ?>) * 1.2,
                                            labels: {
                                                style: {
                                                    fontSize: '12px'
                                                },
                                                offsetX: -8,
                                                formatter: function(val) {
                                                    return Math.round(val);
                                                }
                                            },
                                            tickAmount: 5,
                                            forceNiceScale: true
                                        },
                                        tooltip: {
                                            enabled: true,
                                            shared: true,
                                            intersect: false,
                                            followCursor: false,
                                            fixed: {
                                                enabled: true,
                                                position: 'topCenter',
                                                offsetY: -80
                                            },
                                            onDatasetHover: {
                                                highlightDataSeries: false,
                                            },
                                            y: {
                                                formatter: function(val, opts) {
                                                    return val;
                                                }
                                            },
                                            marker: {
                                                show: false
                                            },
                                            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                                                var total;
                                                if (dataPointIndex === 0) total = <?= $totalTests ?>;
                                                else if (dataPointIndex === 1) total = <?= $totalTechs ?>;
                                                else total = <?= $proposedPIF ?>;
                                                
                                                var done = series[0][dataPointIndex];
                                                
                                                return '<div class="custom-tooltip">' +
                                                    '<span style="font-size: 14px; font-weight: bold;">Etat d\'avancement : ' + done + '/' + total + '</span>' +
                                                    '</div>';
                                            }
                                        },
                                        legend: {
                                            show: false
                                        },
                                        fill: {
                                            opacity: 1,
                                            type: 'solid'
                                        },
                                        states: {
                                            hover: {
                                                filter: {
                                                    type: 'darken',
                                                    value: 0.9
                                                }
                                            }
                                        }
                                    };
                                
                                    var chart = new ApexCharts(document.querySelector("#<?= $chartId ?>"), options);
                                    chart.render();
                                });
                            </script>
                        <?php endforeach; ?>

                        <!-- Histogramme TOTAL -->
                        <?php
                            $totalTestsCompleted = $statsTests['Total']['done'];
                            $totalTestsAll = $statsTests['Total']['total'];
                            
                            $totalProposed = $nbPifJunior + $nbPifSenior + $nbPifExpert;
                            $totalTechnicians = $globalStats['totalTechnicians'];
                            
                            $totalValidated = $nbValidJunior + $nbValidSenior + $nbValidExpert;
                        ?>
                        <!-- Signe égal avant la carte "Tous Niveaux" -->
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="card" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <div style="color: black; font-size: 32px; font-weight: 900;">=</div>
                            </div>
                        </div>

                        <div class="flex-grow-1" style="min-width: 350px;">
                            <div class="card glass-effect depth-effect h-100">
                                <div class="card-body">
                                    <div class="chart-title" style="background-color: white !important;">
                                        <i class="ki-duotone ki-chart fs-2 me-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>TOUS LES NIVEAUX
                                    </div>
                                    
                                    <div id="totalLevelChart" class="test-levels-chart" style="height: 350px; width: 110%; margin-left: -5%;">
                                        <?php if ($filters['technicianId'] !== 'all'): ?>
                                        <div class="technician-specific-notice" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; font-size: 11px; color: #333;">
                                            <i class="fas fa-user-check"></i> Données spécifiques au technicien sélectionné
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-center mt-2">
                                   </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var options = {
                                    series: [{
                                        name: 'Réalisé',
                                        data: [
                                            <?= $totalTestsCompleted ?>,
                                            <?= $totalProposed ?>,
                                            <?= $totalValidated ?>
                                        ]
                                    },
                                    {
                                        name: 'Non réalisé',
                                        data: [
                                            <?= $totalTechnicians - $totalTestsCompleted ?>,
                                            <?= $totalTechnicians - $totalProposed ?>,
                                            <?= $totalTechnicians - $totalValidated ?>
                                        ]
                                    }],
                                    chart: {
                                        type: 'bar',
                                        height: 400,
                                        stacked: true,
                                        toolbar: {
                                            show: false
                                        },
                                        fontFamily: 'Inter, sans-serif',
                                        background: 'transparent'
                                    },
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: '90%',
                                            endingShape: 'flat',
                                            borderRadius: 4,
                                            barHeight: '70%',
                                            distributed: false
                                        },
                                    },
                                    colors: ['#8b5cf6', '#e2e8f0'],
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function(val, opt) {
                                            // Afficher le label uniquement sur la première série
                                            if (opt.seriesIndex === 0) {
                                                var total;
                                                if (opt.dataPointIndex === 0) total = <?= $totalTechnicians ?>;
                                                else if (opt.dataPointIndex === 1) total = <?= $totalTechnicians ?>;
                                                else total = <?= $totalTechnicians ?>;
                                                var percent = total > 0 ? Math.round((val / total) * 100) : 0;
                                                return percent + '%';
                                            } else {
                                                return '';
                                            }
                                        },
                                        style: {
                                            fontSize: '14px',
                                            fontWeight: 'bold',
                                            colors: ['#FFFFFF']
                                        }
                                    },
                                    grid: {
                                        show: true,
                                        borderColor: 'rgba(136, 136, 136, 0.1)',
                                        strokeDashArray: 5,
                                        position: 'back',
                                        xaxis: {
                                            lines: {
                                                show: true,
                                                opacity: 0.3
                                            }
                                        },
                                        yaxis: {
                                            lines: {
                                                show: true,
                                                opacity: 0.5
                                            }
                                        },
                                        row: {
                                            colors: ['#f9f9f9', 'transparent'],
                                            opacity: 0.2
                                        },
                                        column: {
                                            colors: ['#f9f9f9', 'transparent'],
                                            opacity: 0.2
                                        },
                                        padding: {
                                            top: 15,
                                            right: 15,
                                            bottom: 10,
                                            left: 15
                                        }
                                    },
                                    stroke: {
                                        width: 0
                                    },
                                    xaxis: {
                                        categories: ['Tests complétés', 'PIF Proposés', 'PIF Validés'],
                                        labels: {
                                            style: {
                                                fontSize: '12px',
                                                fontWeight: 600
                                            },
                                            offsetY: 5
                                        },
                                        axisBorder: {
                                            show: true,
                                            color: '#e0e0e0'
                                        },
                                        axisTicks: {
                                            show: true,
                                            color: '#e0e0e0',
                                            height: 6
                                        },
                                        crosshairs: {
                                            show: true,
                                            stroke: {
                                                color: '#b6b6b6',
                                                width: 1,
                                                dashArray: 3
                                            }
                                        }
                                    },
                                    yaxis: {
                                        max: function() {
                                            return Math.max(<?= $totalTestsAll ?>, <?= $totalTechnicians ?>, <?= $totalProposed ?>) * 1.2;
                                        },
                                        labels: {
                                            style: {
                                                fontSize: '10px'
                                            },
                                            offsetX: -8,
                                            formatter: function(val) {
                                                return Math.round(val);
                                            }
                                        },
                                        tickAmount: 5,
                                        forceNiceScale: true
                                    },
                                    tooltip: {
                                        enabled: true,
                                        shared: true,
                                        intersect: false,
                                        followCursor: false,
                                        fixed: {
                                            enabled: true,
                                            position: 'topCenter',
                                            offsetY: -80
                                        },
                                        onDatasetHover: {
                                            highlightDataSeries: false,
                                        },
                                        y: {
                                            formatter: function(val, opts) {
                                                return val;
                                            }
                                        },
                                        marker: {
                                            show: false
                                        },
                                        custom: function({ series, seriesIndex, dataPointIndex, w }) {
                                            var total;
                                            if (dataPointIndex === 0) total = <?= $totalTestsAll ?>;
                                            else if (dataPointIndex === 1) total = <?= $totalTechnicians ?>;
                                            else total = <?= $totalProposed ?>;
                                            
                                            var done = series[0][dataPointIndex];
                                            
                                            return '<div class="custom-tooltip">' +
                                                '<span style="font-size: 14px; font-weight: bold;">Etat d\'avancement : ' + done + '/' + total + '</span>' +
                                                '</div>';
                                        }
                                    },
                                    legend: {
                                        show: false
                                    },
                                    fill: {
                                        opacity: 1,
                                        type: 'solid'
                                    },
                                    states: {
                                        hover: {
                                            filter: {
                                                type: 'darken',
                                                value: 0.9
                                            }
                                        }
                                    }
                                };
                            
                                var chart = new ApexCharts(document.querySelector("#totalLevelChart"), options);
                                chart.render();
                            });
                        </script>
                        </div>

                        <!-- Titre de section dans une carte blanche -->
                        <div class="card depth-effect mb-5" style="background-color: white;">
                            <div class="card-body text-center py-4">
                                <h1 class="text-contrast mb-0"><i class="ki-duotone ki-document fs-2 me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>Proposition des Plans de Formations par l'Academy</h1>
                            </div>
                        </div>
                        <!-- KPI Cards and Training Chart Row -->
                        <div class="row mb-5 mt-3">
                            <div class="col-md-9">
                                <!-- KPI Cards -->
                                <div class="row mb-4">
                                    <!-- Formations proposées KPI -->
                                    <div class="col-md-6">
                                        <div class="card stats-card glass-effect depth-effect h-100 mb-3">
                                            <div class="card-header-bg">
                                                <h5 class="card-title mb-0">FORMATIONS PROPOSÉES</h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-alt fs-2 text-primary mb-2"></i>
                                                <div class="big-number mb-3" id="totalTrainings" data-value="<?php echo $trainingStats['totalTrainings']; ?>"><?php echo number_format($trainingStats['totalTrainings']); ?></div>
                                                <div style="color: var(--primary-navy) !important; font-weight: 500; font-size: 0.875rem;">Formations Proposées</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Volume journalier KPI -->
                                    <div class="col-md-6">
                                        <div class="card stats-card glass-effect depth-effect h-100 mb-3">
                                            <div class="card-header-bg">
                                                <h5 class="card-title mb-0">VOLUME JOURNALIER</h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <i class="fas fa-calendar-alt fs-2 text-warning mb-2"></i>
                                                <div class="big-number mb-3" id="trainingDays" data-value="<?php echo $trainingStats['trainingDays']; ?>"><?php echo number_format($trainingStats['trainingDays']); ?></div>
                                                <div style="color: var(--primary-navy) !important; font-weight: 500; font-size: 0.875rem;">Jours de Formation</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Training Chart -->
                                <div class="card glass-effect depth-effect mb-4">
                                    <div class="card-header-bg training-header">
                                        <h5 class="card-title mb-0">FORMATIONS PAR MARQUE</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container" style="height: 500px; position: relative;">
                                            <canvas id="trainingChart"></canvas>
                                            <!-- Container for brand logos that will be positioned below the chart -->
                                            <div id="brandLogosContainer" style="position: absolute; bottom: -50px; left: 0; width: 100%; height: 40px; text-align: center;"></div>
                                            <?php if ($filters['technicianId'] !== 'all'): ?>
                                            <div class="technician-specific-notice" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; font-size: 11px; color: #333;">
                                                <i class="fas fa-user-check"></i> Données spécifiques au technicien sélectionné
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Filters Panel -->
                            <div class="col-md-3">
                                <div class="card glass-effect depth-effect h-100">
                                    <div class="card-header-bg filter-header">
                                        <i class="ki-duotone ki-filter fs-6 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <h5 class="card-title mb-0 d-inline">FILTRE DES DONNÉES</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="dynamicFilters">
                                            <!-- Country Filter -->
                                            <div class="mb-3">
                                                <?php if (function_exists('renderFilterCountry')): ?>
                                                    <?php renderFilterCountry(
                                                        $filters['subsidiary'] ?? 'all',
                                                        $subsidiaries,
                                                        !$canSelectSubsidiary
                                                    ); ?>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Agency Filter -->
                                            <div class="mb-3">
                                                <div id="agencyFilterWrapper" class="<?php echo !$countrySelected ? 'filter-disabled' : ''; ?>">
                                                    <?php if (function_exists('renderFilterAgency')): ?>
                                                        <?php renderFilterAgency(
                                                            $filters['agency'] ?? 'all',
                                                            $agencyOptions,
                                                            $countrySelected
                                                        ); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Level Filter -->
                                            <div class="mb-3">
                                                <div id="levelFilterWrapper" class="<?php echo $technicianSelected ? 'filter-locked' : ''; ?>">
                                                    <?php if (function_exists('renderFilterLevel')): ?>
                                                        <?php renderFilterLevel(
                                                            $filters['level'] ?? 'all',
                                                            $availableLevels,
                                                            false,
                                                            $technicianSelected
                                                        ); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Brand Filter -->
                                            <div class="mb-3">
                                                <div id="brandFilterWrapper" class="<?php echo $technicianSelected ? 'filter-locked' : ''; ?>">
                                                    <?php if (function_exists('renderFilterBrand')): ?>
                                                        <?php renderFilterBrand(
                                                            $filters['brand'] ?? 'all',
                                                            $brands,
                                                            false,
                                                            $technicianSelected
                                                        ); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Manager Filter emptied-->

                                            <!-- Technician Filter -->
                                            <div class="mb-3">
                                                <?php if (function_exists('renderFilterTechnician')): ?>
                                                    <?php renderFilterTechnician(
                                                        $filters['technicianId'] ?? 'all',
                                                        $technicians,
                                                        false,
                                                        false
                                                    ); ?>
                                                <?php endif; ?>
                                            </div>
    <br><br>
                                            <!-- Filter Buttons removed -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Table by Subsidiary -->
                        <?php if (($filters['subsidiary'] ?? 'all') === 'all' && !empty($technicianSummary)): ?>
                        <div class="card glass-effect depth-effect mb-5 mt-5">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Récapitulatif par filiale</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover summary-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Filiale</th>
                                                <th>Total Techniciens</th>
                                                <th>Junior</th>
                                                <th>Senior</th>
                                                <th>Expert</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($technicianSummary as $subsidiary => $stats): ?>
                                            <tr>
                                                <td><strong><?php echo sanitizeOutput($subsidiary); ?></strong></td>
                                                <td><?php echo number_format($stats['totalTechnicians'] ?? 0); ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo number_format($stats['juniorCount'] ?? 0); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo number_format($stats['seniorCount'] ?? 0); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark"><?php echo number_format($stats['expertCount'] ?? 0); ?></span>
                                                </td>
                                                <td>
                                                    <a href="?subsidiary=<?php echo urlencode($subsidiary); ?>" 
                                                    class="btn btn-sm btn-outline-primary">
                                                        Détails
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content-->
        <!-- Serialized chart data for AJAX refresh -->
        <script id="chart-data" type="application/json">
            <?php echo json_encode([
                'brandScores' => $brandScores,
                'brandStats' => array_keys($brandStats),
                'trainingStats' => $trainingStats,
                'trainingsCountsForGraph2' => $trainingsCountsForGraph2,
                'validationsCountsForGraph2' => $validationsCountsForGraph2,
                'brandHours' => $globalStats['trainingDays'] ?? 0,
                'brandLogos' => [
                    'RENAULT TRUCK' => 'renaultTrucks.png',
                    'HINO' => 'Hino_logo.png',
                    'TOYOTA BT' => 'bt.png',
                    'SINOTRUK' => 'sinotruk.png',
                    'JCB' => 'jcb.png',
                    'MERCEDES TRUCK' => 'mercedestruck.png',
                    'TOYOTA FORKLIFT' => 'forklift.png',
                    'FUSO' => 'fuso.png',
                    'LOVOL' => 'lovol.png',
                    'KING LONG' => 'kl2.png',
                    'MERCEDES' => 'mercedestruck.png',
                    'TOYOTA' => 'toyota-logo.png',
                    'SUZUKI' => 'suzuki-logo.png',
                    'MITSUBISHI' => 'mitsubishi-logo.png',
                    'BYD' => 'byd-logo.png',
                    'CITROEN' => 'citroen-logo.png',
                    'PEUGEOT' => 'peugeot-logo.png'
                ]
            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
        </script>                                       

        <script>
        // Register Chart.js plugin
        Chart.register(ChartDataLabels);

        // Initialize CountUp instances
        let countUpInstances = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Chart variables
            let brandScoresChart = null;
            let trainingChart = null;
            
            // Loading state management
            let isLoading = false;
            
            // DOM elements
            const loadingSpinner = document.getElementById('loadingSpinner');
            const statsContainer = document.getElementById('statsContainer');
            const filterForm = document.getElementById('dashboardFilters');

            // We'll initialize CountUp after DOM is fully loaded
            setTimeout(() => {
                // Initialize CountUp for all numbers
                initCountUps();
            }, 500);
            
            // Filter elements
            const filters = {
                country: document.getElementById('filterCountry'),
                agency: document.getElementById('filterAgency'),
                level: document.getElementById('filterLevel'),
                brand: document.getElementById('filterBrand'),
                
                technicianId: document.getElementById('filterTechnician')
            };
            console.log('Agency options from PHP:', <?php echo json_encode($agencyOptions); ?>);
            if (filters.agency) {
                const optsInit = Array.from(filters.agency.options).map(o => o.value);
                console.log('Initial agency options:', optsInit);
            }
            
            // Helper function to clean URL by removing duplicate parameters
            function cleanURL(url) {
                const urlObj = new URL(url);
                // If both technician and technicianId exist, keep only technicianId
                if (urlObj.searchParams.has('technician') && urlObj.searchParams.has('technicianId')) {
                    urlObj.searchParams.delete('technician');
                }
                return urlObj;
            }
            function applyFilters() {
                if (isLoading) return;

                const filterData = {};
                Object.entries(filters).forEach(([key, element]) => {
                    if (element) {
                        filterData[key] = element.value;
                    }
                });
                
                // Ensure parameter names match the PHP expectations
                console.log("Filter data:", filterData);

                const url = new URL(window.location.href);
                
                // Clear ALL parameters from the URL (except pagination if needed)
                Array.from(url.searchParams.keys()).forEach(key => {
                    // Preserve pagination parameters if needed
                    if (!['page', 'per_page', '_t'].includes(key)) {
                        url.searchParams.delete(key);
                    }
                });
                
                // Add only the current filter values
                Object.entries(filterData).forEach(([key, value]) => {
                    url.searchParams.set(key, value);
                });

                // Log what we're about to do with technicianId
                if (filterData.technicianId && filterData.technicianId !== 'all') {
                    console.log("Filtering for specific technician ID:", filterData.technicianId);
                    console.log("Current filter values being applied:");
                    console.log("- Subsidiary:", filterData.country || 'all');
                    console.log("- Agency:", filterData.agency || 'all');
                    console.log("- Level:", filterData.level || 'all');
                    console.log("- Brand:", filterData.brand || 'all');
                }

                window.history.pushState({}, '', url);

                fetchDashboardData(url);
            }



            function bindFilterEvents() {
                if (filters.country) {
                    filters.country.addEventListener('change', function() {
                        if (filters.agency) filters.agency.value = 'all';
                        
                        updateFilterStates();
                        applyFilters();
                    });
                }

                ['agency', 'level', 'brand'].forEach(key => {
                    if (filters[key]) {
                        filters[key].addEventListener('change', function() {
                            updateFilterStates();
                            applyFilters();
                        });
                    }
                });
                
                // Special handler for technician filter to log specific info
                if (filters.technicianId) {
                    filters.technicianId.addEventListener('change', function() {
                        const techId = this.value;
                        console.log("🔍 TECHNICIAN FILTER CHANGED to:", techId);
                        
                        if (techId !== 'all') {
                            console.log("📊 Before update - Current filter values:");
                            console.log("- Level:", filters.level ? filters.level.value : 'none');
                            console.log("- Brand:", filters.brand ? filters.brand.value : 'none');
                        }
                        
                        updateFilterStates();
                        applyFilters();
                        
                        // Log after update is triggered
                        if (techId !== 'all') {
                            console.log("🔄 Update triggered for technician:", techId);
                        } else {
                            console.log("🔄 Technician filter cleared - showing all technicians");
                        }
                    });
                }

                // Filter button event listeners removed as buttons no longer exist
                // Filters are applied automatically on change via the individual filter change handlers
            }



            // Loading management
            function toggleLoading(show = true) {
                isLoading = show;
                
                // Handle overlay
                const loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay) {
                    loadingOverlay.classList.toggle('d-none', !show);
                }
                
                if (loadingSpinner) {
                    loadingSpinner.style.display = show ? 'block' : 'none';
                }
                
                if (statsContainer) {
                    statsContainer.style.opacity = show ? '0.5' : '1';
                }
                
                // Button references removed as they no longer exist
                
                // Disable all select elements while loading
                document.querySelectorAll('select').forEach(select => {
                    select.disabled = show;
                });
            }

            // Data from PHP (safely encoded)
            const chartData = {
                brandScores: <?php echo json_encode($brandScores, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                brandStats: <?php echo json_encode(array_keys($brandStats), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                trainingStats: <?php echo json_encode($trainingStats, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                trainingsCountsForGraph2: <?php echo json_encode($trainingsCountsForGraph2, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                validationsCountsForGraph2: <?php echo json_encode($validationsCountsForGraph2, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                brandHours: <?php echo json_encode($globalStats['trainingDays'] ?? 0, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                brandLogos: <?php echo json_encode($brandLogos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
            };

            // Initialize charts
            function initializeCharts() {
                // Destroy existing charts
                if (brandScoresChart) {
                    brandScoresChart.destroy();
                    brandScoresChart = null;
                }
                if (trainingChart) {
                    trainingChart.destroy();
                    trainingChart = null;
                }

                // Training chart
                const trainingCanvas = document.getElementById('trainingChart');
                if (trainingCanvas && chartData.brandStats && chartData.brandStats.length > 0) {
                    // Prepare data for the chart - ensure we have values for each brand
                    const recommendedData = [];
                    const validatedData = [];
                    
                    // Check if a specific technician is selected
                    const technicianSelected = filters.technicianId && filters.technicianId.value !== 'all';
                    console.log("📊 Chart preparation - Technician selected:", technicianSelected, "ID:", filters.technicianId ? filters.technicianId.value : 'none');
                    
                    // Filter brands only relevant to this technician when a technician is selected
                    console.log("🔍 Filtering brands for chart display...");
                    const relevantBrands = chartData.brandStats.filter(brand => {
                        if (!technicianSelected) return true; // Show all brands if no technician selected
                        
                        // Only include brands that have data for this technician
                        const hasBrandData = (chartData.trainingsCountsForGraph2[brand] > 0 || chartData.validationsCountsForGraph2[brand] > 0);
                        if (technicianSelected && hasBrandData) {
                            console.log(`  ✅ Including brand for technician: ${brand} (has data)`);
                        } else if (technicianSelected) {
                            console.log(`  ❌ Excluding brand for technician: ${brand} (no data)`);
                        }
                        return hasBrandData;
                    });
                    
                    if (technicianSelected) {
                        console.log("📊 Technician-specific chart data:");
                        console.log(`  - Showing ${relevantBrands.length} brands out of ${chartData.brandStats.length} total brands`);
                        console.log("  - Relevant brands:", relevantBrands);
                        
                        // Create detailed log of what data is being used for the chart
                        console.log("📈 Data being displayed in chart:");
                        relevantBrands.forEach(brand => {
                            const proposed = chartData.trainingsCountsForGraph2[brand] || 0;
                            const validated = chartData.validationsCountsForGraph2[brand] || 0;
                            console.log(`  - ${brand}: ${proposed} proposed, ${validated} validated`);
                        });
                    }
                    
                    relevantBrands.forEach(brand => {
                        recommendedData.push(chartData.trainingsCountsForGraph2[brand] ?? 0);
                        validatedData.push(chartData.validationsCountsForGraph2[brand] ?? 0);
                    });
                    
                    console.log("Chart data prepared:", {
                        technicianFiltered: technicianSelected,
                        brands: relevantBrands,
                        recommended: recommendedData,
                        validated: validatedData
                    });

                    const ctx = trainingCanvas.getContext('2d');
                    // Créer des options modifiées qui utilisent l'événement afterDatasetsDraw pour dessiner manuellement les valeurs
                    trainingChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: relevantBrands,
                            datasets: [
                                {
                                    label: 'Formations Proposées',
                                    data: recommendedData,
                                    backgroundColor: '#FFC107',
                                    borderColor: '#FFC107',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Formations Validées',
                                    data: validatedData,
                                    backgroundColor: '#0275D8',
                                    borderColor: '#0275D8',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        boxWidth: 12,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                // Nous n'utilisons plus le plugin datalabels car nous allons dessiner les labels manuellement
                                datalabels: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: false // Tooltips désactivés car les valeurs sont déjà affichées sur les barres
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        display: false // Hide X axis labels (brand names) since we'll use logos instead
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                        },
                                    title: {
                                        display: true,
                                        text: 'Nombre de Modules de Formations proposés'
                                        }
                                }
                            },
                            tooltip: {
                                enabled: false // Désactivation des tooltips car les valeurs sont directement affichées sur les barres
                            },
                            // Dessiner manuellement les valeurs au-dessus des barres
                            plugins: {
                                customLabels: {
                                    afterDatasetsDraw: function(chart) {
                                        var ctx = chart.ctx;
                                        ctx.font = 'bold 16px Arial';
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';
                                        
                                        chart.data.datasets.forEach(function(dataset, datasetIndex) {
                                            var meta = chart.getDatasetMeta(datasetIndex);
                                            if (!meta.hidden) {
                                                meta.data.forEach(function(element, index) {
                                                    // Position de dessin
                                                    var position = element.tooltipPosition();
                                                    
                                                    // Valeur à afficher
                                                    var value = dataset.data[index];
                                                    
                                                    // N'afficher que si la valeur est supérieure à zéro
                                                    if (value > 0) {
                                                        // Dessiner un fond blanc
                                                        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                                                        var textWidth = ctx.measureText(value).width;
                                                        ctx.fillRect(position.x - textWidth/2 - 5, position.y - 15, textWidth + 10, 30);
                                                        
                                                        // Dessiner le texte
                                                        ctx.fillStyle = '#000';
                                                        ctx.fillText(value, position.x, position.y);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                }
                            },
                            // Add padding at the bottom to make room for brand logos
                            layout: {
                                padding: {
                                    bottom: 50,
                                    top: 20 // Ajouter un padding en haut pour éviter que les étiquettes ne soient coupées
                                }
                            }
                        }
                    });
                    
                    // Add brand logos after chart is rendered
                    positionBrandLogos(trainingChart, relevantBrands, -55);
                    window.addEventListener('resize', () => {
                        positionBrandLogos(trainingChart, relevantBrands, -55);
                });
                }
            }

            // Update filter states based on selections
            function updateFilterStates() {
                const technicianSelected = filters.technicianId && filters.technicianId.value !== 'all';
                const countrySelected = filters.country && filters.country.value !== 'all';
                
                // Agency filter depends on country
                const agencyWrapper = document.getElementById('agencyFilterWrapper');
                if (agencyWrapper) {
                    agencyWrapper.classList.toggle('filter-disabled', !countrySelected);
                    if (filters.agency) {
                        filters.agency.disabled = !countrySelected;
                    }
                }
                
                // Manager filter depends on country and technician
                
                
                // Level and brand filters are locked when technician is selected
                ['level', 'brand'].forEach(filterName => {
                    const wrapper = document.getElementById(filterName + 'FilterWrapper');
                    if (wrapper) {
                        wrapper.classList.toggle('filter-locked', technicianSelected);
                        if (filters[filterName]) {
                            filters[filterName].disabled = technicianSelected;
                            
                            // Log current values when locking/unlocking
                            if (technicianSelected) {
                                console.log(`LOCKING ${filterName} filter - current value:`, filters[filterName].value);
                            }
                        }
                    }
                });
                
                // Log the current filter state
                console.log("Current filter state:", {
                    technicianSelected: technicianSelected,
                    countrySelected: countrySelected,
                    technicianValue: filters.technicianId ? filters.technicianId.value : 'all'
                });
                
                if (technicianSelected) {
                    console.log("TECHNICIAN FILTER SELECTED - Tracking loaded data");
                    console.log("Selected technician ID:", filters.technicianId.value);
                    
                    // Log filter values that should be updated for this technician
                    const currentLevel = filters.level ? filters.level.value : 'all';
                    const currentBrand = filters.brand ? filters.brand.value : 'all';
                    
                    console.log("Current level filter value:", currentLevel);
                    console.log("Current brand filter value:", currentBrand);
                }
            }

        bindFilterEvents();
            
            // Function to fetch dashboard data without page reload
            function fetchDashboardData(url) {
                toggleLoading(true);
                
                // Clean the URL to remove any duplicate parameters
                url = cleanURL(url);
                
                // Add timestamp to prevent caching
                url.searchParams.set('_t', Date.now());
                console.log('Fetching dashboard data from:', url.toString());
                
                // Check if this is a technician-specific request
                if (url.searchParams.get('technicianId') && url.searchParams.get('technicianId') !== 'all') {
                    console.log("FETCHING DATA FOR TECHNICIAN:", url.searchParams.get('technicianId'));
                }
                // Use fetch to get updated data via AJAX
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        
                        const dynamicFiltersNew = doc.getElementById('dynamicFilters');
                        const dynamicFilters = document.getElementById('dynamicFilters');
                        if (dynamicFilters && dynamicFiltersNew) {
                            dynamicFilters.innerHTML = dynamicFiltersNew.innerHTML;

                            filters.country = document.getElementById('filterCountry');
                            filters.agency = document.getElementById('filterAgency');
                            filters.level = document.getElementById('filterLevel');
                            filters.brand = document.getElementById('filterBrand');
                            
                            filters.technicianId = document.getElementById('filterTechnician');

                            bindFilterEvents();
                            updateFilterStates();
                            if (filters.agency) {
                                const opts = Array.from(filters.agency.options).map(o => o.value);
                                console.log('Updated agency options:', opts);
                            } else {
                                console.log('Agency select not found after update');
                            }
                            
                            // After updating filters, check if we're filtering for a specific technician
                            if (filters.technicianId && filters.technicianId.value !== 'all') {
                                console.log("AFTER UPDATE - Technician-specific data loaded:");
                                console.log("- Technician ID:", filters.technicianId.value);
                                console.log("- Level set to:", filters.level ? filters.level.value : 'none');
                                console.log("- Brand set to:", filters.brand ? filters.brand.value : 'none');
                                
                                // Also check if these fields are properly disabled/locked
                                ['level', 'brand'].forEach(filterName => {
                                    const el = filters[filterName];
                                    if (el) {
                                        console.log(`- ${filterName} filter disabled:`, el.disabled);
                                        console.log(`- ${filterName} filter value:`, el.value);
                                    }
                                });
                            }
                        }

                        // Update stats container
                        const newStatsContainer = doc.getElementById('statsContainer');
                        if (newStatsContainer && statsContainer) {
                            statsContainer.innerHTML = newStatsContainer.innerHTML;
                        }
                        
                        // Update each section with animation
                        
                        // Update stats container with fade-in effect
                        const statsContainerNew = doc.getElementById('statsContainer');
                        if (statsContainerNew && statsContainer) {
                            // Apply fade-out
                            statsContainer.style.opacity = 0;
                            statsContainer.style.transform = 'translateY(-10px)';
                            statsContainer.style.transition = 'all 0.3s ease';
                            
                            // After short delay, update content and fade in
                            setTimeout(() => {
                                statsContainer.innerHTML = statsContainerNew.innerHTML;
                                statsContainer.style.opacity = 1;
                                statsContainer.style.transform = 'translateY(0)';
                            }, 300);
                        }
                        
                        // Update progress bars container with staggered animation
                        const chartsRow = document.querySelector('.row.mb-5.mt-5.fade-in');
                        const newChartsRow = doc.querySelector('.row.mb-5.mt-5.fade-in');
                        if (chartsRow && newChartsRow) {
                            chartsRow.innerHTML = newChartsRow.innerHTML;
                            // Add staggered animation to each card
                            chartsRow.querySelectorAll('.card').forEach((card, index) => {
                                card.style.opacity = 0;
                                card.style.transform = 'translateY(20px)';
                                setTimeout(() => {
                                    card.style.opacity = 1;
                                    card.style.transform = 'translateY(0)';
                                    card.style.transition = 'all 0.4s ease';
                                }, 100 * index);
                            });
                        }
                        
                        // Update KPI cards and chart row with slide-in animation
                        const kpiRow = document.querySelector('.row.mb-4:nth-child(4)');
                        const newKpiRow = doc.querySelector('.row.mb-4:nth-child(4)');
                        if (kpiRow && newKpiRow) {
                            kpiRow.style.opacity = 0;
                            kpiRow.style.transform = 'translateX(-20px)';
                            kpiRow.style.transition = 'all 0.5s ease';
                            
                            setTimeout(() => {
                                kpiRow.innerHTML = newKpiRow.innerHTML;
                                kpiRow.style.opacity = 1;
                                kpiRow.style.transform = 'translateX(0)';
                            }, 300);
                        }
                        
                        // Update summary table if present
                        const summaryTable = document.querySelector('.card.mb-4:last-of-type');
                        const newSummaryTable = doc.querySelector('.card.mb-4:last-of-type');
                        if (summaryTable && newSummaryTable) {
                            summaryTable.innerHTML = newSummaryTable.innerHTML;
                        }
                        
                        
                        const chartDataScript = doc.getElementById('chart-data');
                        if (chartDataScript) {
                            try {
                                const newChartData = JSON.parse(chartDataScript.textContent);
                                Object.assign(chartData, newChartData);
                            } catch (e) {
                                console.error('Failed to parse chart data', e);
                            }
                        }
                        
                        // Re-initialize all charts
                        initializeCharts();
                        initializeCamembertCharts();
                        
                        // Ajouter le plugin personnalisé après l'initialisation des charts
                        Chart.register({
                            id: 'customLabels',
                            afterDatasetsDraw: function(chart) {
                                var ctx = chart.ctx;
                                ctx.font = 'bold 16px Arial';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                
                                chart.data.datasets.forEach(function(dataset, datasetIndex) {
                                    var meta = chart.getDatasetMeta(datasetIndex);
                                    if (!meta.hidden) {
                                        meta.data.forEach(function(element, index) {
                                            // Position de dessin
                                            var position = element.tooltipPosition();
                                            
                                            // Valeur à afficher
                                            var value = dataset.data[index];
                                            
                                            // N'afficher que si la valeur est supérieure à zéro
                                            if (value > 0) {
                                                // Dessiner un fond blanc
                                                ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                                                var textWidth = ctx.measureText(value).width;
                                                ctx.fillRect(position.x - textWidth/2 - 5, position.y - 15, textWidth + 10, 30);
                                                
                                                // Dessiner le texte
                                                ctx.fillStyle = '#000';
                                                ctx.fillText(value, position.x, position.y);
                                            }
                                        });
                                    }
                                });
                            }
                        });
                        
                        // Add animation effect to indicate data refresh
                        document.querySelectorAll('.card').forEach(card => {
                            card.classList.add('border-success');
                            card.classList.add('highlight-update');
                            setTimeout(() => {
                                card.classList.remove('border-success');
                                card.classList.remove('highlight-update');
                            }, 1500);
                        });

                        // Update percentage labels in doughnut chart wrappers
                        const doughnutWrappers = document.querySelectorAll('.doughnut-wrapper');
                        const newDoughnutWrappers = doc.querySelectorAll('.doughnut-wrapper');
                        
                        if (doughnutWrappers.length === newDoughnutWrappers.length) {
                            for (let i = 0; i < doughnutWrappers.length; i++) {
                                doughnutWrappers[i].setAttribute('data-label',
                                    newDoughnutWrappers[i].getAttribute('data-label'));
                            }
                        }
                        
                        toggleLoading(false);
                    })
                    .catch(error => {
                        console.error('Error updating dashboard:', error);
                        toggleLoading(false);
                        
                        // Show error message to user
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        alertDiv.innerHTML = `
                            <strong>Erreur:</strong> Impossible de mettre à jour les données du tableau de bord.
                            <br><small>${error.message || 'Veuillez réessayer ou recharger la page.'}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.querySelector('.container-fluid').prepend(alertDiv);
                        
                        // Auto-dismiss after 5 seconds
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                alertDiv.parentNode.removeChild(alertDiv);
                            }
                        }, 5000);
                    });
            }

            // Initialize everything
            initializeCharts();
            updateFilterStates();
            
            // Initialize camembert charts
            initializeCamembertCharts();
            
            // Hide loading after initialization - make sure DOM is fully loaded
            setTimeout(() => {
                toggleLoading(false);
                
                // Ensure CountUp is available before starting animations
                if (typeof countUp !== 'undefined') {
                    // Start CountUp animations with staggered timing
                    startCountUpAnimations();
                } else {
                    console.error('CountUp library not loaded properly');
                }
            }, 800);

            // Global error handler
            window.addEventListener('error', function(e) {
                console.error('JavaScript Error:', e.error);
                toggleLoading(false);
            });
            
            // Function to initialize the charts
            function initializeCamembertCharts() {
                // This function is kept for backward compatibility
                console.log("ApexCharts initialized");
                
                // Clear any existing charts that might still be referenced
                const chartIds = ['juniorDonut', 'seniorDonut', 'expertDonut', 'totalDonut'];
                chartIds.forEach(id => {
                    if (window[id + 'Chart']) {
                        window[id + 'Chart'].destroy();
                        window[id + 'Chart'] = null;
                    }
                });
                
                // Progress bar animations are handled separately
                initProgressBarAnimations();
            }
            
            // Function to animate progress bars with CountUp effect
            function initProgressBarAnimations() {
                // Get all progress bar elements
                const progressBars = document.querySelectorAll('.progress-bar');
                
                // Reset all progress bars to 0% width first
                progressBars.forEach(bar => {
                    // Store the original width percentage
                    const finalWidth = bar.style.width;
                    const finalPercentage = bar.getAttribute('aria-valuenow');
                    
                    // Set initial width to 0%
                    bar.style.width = '0%';
                    
                    // Store final width value as data attribute
                    bar.setAttribute('data-final-width', finalWidth);
                    bar.setAttribute('data-final-percentage', finalPercentage);
                    
                    // Hide percentage text initially
                    bar.textContent = '0%';
                });
                
                // Animate progress bars after a short delay
                setTimeout(() => {
                    progressBars.forEach((bar, index) => {
                        // Stagger animations
                        setTimeout(() => {
                            const finalWidth = bar.getAttribute('data-final-width');
                            const finalPercentage = bar.getAttribute('data-final-percentage');
                            
                            // Animate width
                            bar.style.transition = 'width 1.5s ease-in-out';
                            bar.style.width = finalWidth || finalPercentage + '%';
                            
                            // Animate percentage text using CountUp
                            if (finalPercentage) {
                                const percentageValue = parseInt(finalPercentage, 10);
                                // Create temporary span for CountUp if needed
                                const tempId = 'progress-count-' + index;
                                bar.id = tempId;
                                
                                // Use CountUp for the text
                                const countUpInstance = new countUp.CountUp(tempId, percentageValue, {
                                    duration: 1.5,
                                    useEasing: true,
                                    suffix: '%',
                                    enableScrollSpy: false
                                });
                                
                                countUpInstance.start();
                            }
                        }, 150 * index);
                    });
                }, 300);
            }
            
            // Function to initialize CountUp instances for all elements with data-value
            function initCountUps() {
                // Clear any existing instances
                countUpInstances = [];
                
                // Find all elements with data-value attribute
                document.querySelectorAll('[data-value]').forEach(el => {
                    if (!el || !el.id) return; // Skip if element or id is missing
                    
                    const value = parseFloat(el.getAttribute('data-value'));
                    if (!isNaN(value)) {
                        try {
                            // Create CountUp instance with formatting
                            const countUpInstance = new countUp.CountUp(el.id, value, {
                                duration: 2.5,
                                useEasing: true,
                                useGrouping: true,
                                separator: ' ',
                                decimal: ',',
                                enableScrollSpy: false
                            });
                            
                            // Store the instance for later use
                            countUpInstances.push({
                                element: el,
                                countUp: countUpInstance
                            });
                            
                            // Show the element (was hidden with opacity 0)
                            el.style.opacity = 1;
                        } catch (error) {
                            console.error('Error creating CountUp for', el.id, error);
                        }
                    }
                });
            }
            
            // Function to start all CountUp animations with staggered timing
            function startCountUpAnimations() {
                countUpInstances.forEach((instance, index) => {
                    setTimeout(() => {
                        try {
                            if (instance && instance.countUp && typeof instance.countUp.start === 'function') {
                                instance.countUp.start();
                            }
                        } catch (error) {
                            console.error('Error starting CountUp animation', error);
                        }
                    }, 100 * index); // Stagger animations by 100ms
                });
            }
            
            // Update animations when filters change
            function resetAndStartCountUps() {
                initCountUps();
                startCountUpAnimations();
                initProgressBarAnimations(); // Also restart progress bar animations
                initHistogramAnimations(); // Also restart histogram animations
            }
            
            // Add additional event listener for filter button to handle animations
            if (applyFiltersButton) {
                applyFiltersButton.addEventListener('click', function() {
                    // Log filter values when applying filters
                    const techId = filters.technicianId ? filters.technicianId.value : 'all';
                    if (techId !== 'all') {
                        console.log("🔍 APPLY FILTERS clicked for technician:", techId);
                        console.log("  - Level:", filters.level ? filters.level.value : 'none');
                        console.log("  - Brand:", filters.brand ? filters.brand.value : 'none');
                        console.log("  - Data will be fetched for this technician's specific brands and levels");
                    }
                    
                    // Reset animations when new data is loaded
                    setTimeout(() => {
                        resetAndStartCountUps();
                    }, 1000);
                });
            }
            
            // We no longer need the histogram animation function as ApexCharts handles animations
            
        function positionBrandLogos(chart, brands, offsetY = -15) {   // ← décalage vertical
            const logoContainer = document.getElementById('brandLogosContainer');
            if (!logoContainer) return;
            logoContainer.innerHTML = '';

            const xScale = chart.scales.x;
            const meta0  = chart.getDatasetMeta(0);
            const meta1  = chart.getDatasetMeta(1);

            brands.forEach((brand, index) => {
                let xPos = xScale.getPixelForValue(index);
                if (meta0 && meta1) {
                const bar0 = meta0.data[index];
                const bar1 = meta1.data[index];
                if (bar0 && bar1) xPos = (bar0.x + bar1.x) / 2;
                }

                const logoDiv = document.createElement('div');
                logoDiv.style.position = 'absolute';
                logoDiv.style.left      = `${xPos}px`;
                logoDiv.style.top       = `${offsetY}px`;         // ← on remonte de 15 px
                logoDiv.style.transform = 'translate(-50%, 0)';   // X centré, Y sans décalage
                logoDiv.style.width     = '40px';
                logoDiv.style.height    = '40px';
                logoDiv.style.display   = 'flex';
                logoDiv.style.justifyContent = 'center';
                logoDiv.style.alignItems     = 'center';

                const img = document.createElement('img');
                const logoFilename = chartData.brandLogos[brand] || 'default-logo.png';
                // Les logos sont stockés dans le dossier 'brands'
                img.src = `/MEDACAP/views/define/brands/${logoFilename}`;
                img.alt   = img.title = brand;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'contain';

                img.onerror = () => {
                const txt = document.createElement('span');
                txt.textContent = brand.slice(0, 3);
                txt.style.cssText = 'font: 10px/1 sans-serif; font-weight:700; color:#333';
                logoDiv.appendChild(txt);
                img.remove();
                };

                logoDiv.appendChild(img);
                logoContainer.appendChild(logoDiv);
            });
        }

        });
        </script>
        <script>
        // Debug: log agencies and filters when the page loads
        console.log('Agencies available on load:', <?php echo json_encode($agencyOptions); ?>);
        console.log('Filters on load:', <?php echo json_encode($filters); ?>);
        
        // Debug: log if technician filter is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            const techFilter = document.getElementById('filterTechnician');
            if (techFilter && techFilter.value !== 'all') {
                console.log('🔍 PAGE LOADED with technician selected:', techFilter.value);
                console.log('  - Current level:', document.getElementById('filterLevel')?.value);
                console.log('  - Current brand:', document.getElementById('filterBrand')?.value);
                
                // Log the existing chart data for this technician
                setTimeout(() => {
                    // Get chart data from initial load
                    try {
                        const chartDataScript = document.getElementById('chart-data');
                        if (chartDataScript) {
                            const chartData = JSON.parse(chartDataScript.textContent);
                            console.log('📊 Initial chart data for technician:');
                            console.log('  - Training counts:', chartData.trainingsCountsForGraph2);
                            console.log('  - Validation counts:', chartData.validationsCountsForGraph2);
                            
                            // Log brands with data
                            const techBrands = Object.keys(chartData.trainingsCountsForGraph2).filter(
                                brand => chartData.trainingsCountsForGraph2[brand] > 0 ||
                                        chartData.validationsCountsForGraph2[brand] > 0
                            );
                            
                            console.log('  - Brands with data:', techBrands);
                        }
                    } catch (e) {
                        console.error('Error parsing initial chart data:', e);
                    }
                }, 500);
            }
        });
        </script>

        <?php require_once __DIR__ . '/../../partials/footer.php'; ?>

